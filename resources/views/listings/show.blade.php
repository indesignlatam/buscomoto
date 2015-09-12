@extends('layouts.home')

@section('head')
    <title>{{ $listing->title }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ $listing->title }}"/>
	<meta property="og:image" content="{{ asset($listing->image_path()) }}"/>
	<meta property="og:type" content="article"/>
	@if(strlen($listing->description) > 100)
		<meta property="og:description" content="{{ $listing->description }}"/>
		<meta name="description" content="{{ $listing->description }}">
	@else
		<meta property="og:description" content="{{ $listing->description. '. ' . Settings::get('listings_description') }}" />
		<meta name="description" content="{{ $listing->description. '. ' . Settings::get('listings_description') }}">
	@endif
@endsection

@section('css')
	@parent
	<script type="text/javascript">
		loadCSS("{{ asset('/css/components/slideshow.min.css') }}");
		loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
		loadCSS("{{ asset('/css/components/tooltip.min.css') }}");
		loadCSS("{{ asset('/css/selectize.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top" id="secondContent">
	<div class="uk-panel">
		<div>
			<h2 class="uk-hidden-small" style="display:inline">{{ $listing->title }}</h2>
			<h2 class="uk-visible-small">{{ $listing->title }}</h2>
			<div style="display:inline" class="uk-hidden-small uk-float-right">
				<i class="uk-h2 uk-text-right">{{ trans('admin.price') }} </i>
				<i class="uk-h2 uk-text-primary uk-text-right"> ${{ money_format('%!.0i', $listing->price) }}</i>
			</div>
		</div>

		<div class="uk-grid uk-margin-small-top">
			<div class="uk-width-large-7-10 uk-width-medium-7-10 uk-width-small-1-1">	
				@if(count($listing->images) > 0)
					<div class="uk-slidenav-position" data-uk-slideshow="{autoplay:true, autoplayInterval:7000}">
					    <ul class="uk-slideshow">
					    	@foreach($listing->images->sortBy('ordering') as $image)
					    		<li>
					    			<img src="{{ asset($image->image_path) }}" alt="{{ $listing->title }}" style="max-width:960px; max-height:540px">
					    		</li>
					    	@endforeach		    	
					    </ul>
					    @if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
					    	<a onclick="like()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-danger" id="like_button_image"></i></a>
					    @else
					    	<a onclick="like()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-contrast" id="like_button_image"></i></a>
					    @endif

					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
					</div>
				@else
					<img src="{{ asset($listing->image_path()) }}" alt="{{ $listing->title }}" >
				@endif
			</div>

			<div class="uk-width-3-10 uk-hidden-small">
				<div class="uk-panel uk-panel-box">
					@if (Session::has('success'))
						<div class="uk-alert uk-alert-success" data-uk-alert>
			    			<a href="" class="uk-alert-close uk-close"></a>
							<ul class="uk-list">
								@foreach (Session::get('success') as $error)
									<li><i class="uk-icon-check"></i> {{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if(!Cookie::get('listing_message_'.$listing->id) || Cookie::get('listing_message_'.$listing->id) > Carbon::now())
						<h3>{{ trans('frontend.contact_vendor') }}</h3>

						@if (count($errors) > 0)
							<div class="uk-alert uk-alert-danger" data-uk-alert>
				    			<a href="" class="uk-alert-close uk-close"></a>
								<ul class="uk-list">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form id="send_message_inpage" class="uk-form uk-form-horizontal" method="POST" action="{{ url('/appointments') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
				            <input type="hidden" name="listing_id" value="{{ $listing->id }}">

				            @if(Auth::check())
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ Auth::user()->name }}">
			                @else
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">
			                @endif

			                <div class="uk-hidden">
			                	<input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
			                </div>
			                
		                	@if(Auth::check())
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ Auth::user()->phone_1 }}">
			                @else
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ old('phone') }}">
			                @endif

			                @if(Auth::check())
			            		<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ Auth::user()->email }}">
			                @else
			            		<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ old('email') }}" onchange="showCaptcha()">
			                @endif
			            
			                
				            <textarea class="uk-width-large-10-10 uk-form-large" name="comments" placeholder="{{ trans('frontend.contact_comments') }}" rows="5">@if(old('comments')){{ old('comments') }}@else{{ trans('frontend.contact_default_text') }}@endif</textarea>

				            @if(!Auth::check())
				                <!-- ReCaptcha -->
				                <div class="uk-form-row uk-width-large-10-10 uk-margin-top uk-align-center uk-hidden" id="captcha">
				                    <div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
				                    <p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
				                </div>
				                <!-- ReCaptcha -->
				            @endif

				            <button form="send_message_inpage" type="submit" class="uk-button uk-button-large uk-width-1-1 uk-button-primary uk-margin-top">{{ trans('frontend.contact_button') }}</button>
						</form>
					@else
						<h3 class="uk-text-primary">{{ trans('frontend.already_contacted_vendor') }}</h3>
					@endif
					
					<div class="uk-margin-small-top uk-flex">
						<button class="uk-button uk-button-large uk-width-1-1" data-uk-toggle="{target:'#phones'}"><i class="uk-icon-phone"></i></button>
						<button onclick="like()" class="uk-button uk-button-large uk-width-1-1 uk-margin-small-left">
					    	@if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
								<i id="like_button" class="uk-icon-heart uk-text-danger"></i>
							@else
								<i id="like_button" class="uk-icon-heart"></i>
							@endif
						</button>
					</div>

					<div id="phones" class="uk-hidden">
						@if(!$listing->user->phone_1 && !$listing->user->phone_2)
							<div class="uk-text-warning">
								El usuario no tiene ningun telefono registrado. Intenta escribirle un mensaje.
							</div>
						@else
							@if($listing->user->phone_1)
								<div class="uk-h3">
									Tel 1: <b id="phone_1">{{ $listing->user->phone_1 }}</b>
								</div>
							@endif
							@if($listing->user->phone_2)
								<div class="uk-h3">
									Tel 2: <b id="phone_2">{{ $listing->user->phone_2 }}</b>
								</div>
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
			
		<hr>

	    <div class="uk-grid uk-margin uk-margin-bottom">
	    	<div class="uk-width-large-1-4 uk-width-medium-1-4 uk-width-small-1-1">
	    		<!-- Social share links -->
	    		<div class="uk-flex uk-flex-space-between">
    				<a onclick="like('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook-square"></a> 
    				<a onclick="share('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook"></a> 
    				<a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text={{ $listing->title }}%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>
	    		</div>
				<!-- Social share links -->

	    		<ul class="uk-list uk-list-line">
    				<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> {{ money_format('$%!.0i', $listing->price) }}</li>

    				@if($listing->engine_size)
    					<li><i class="uk-text-muted">{{ trans('admin.engine_size') }}</i> {{ $listing->engine_size }} cc</li>
    				@endif

    				@if($listing->year)
    					<li><i class="uk-text-muted">{{ trans('admin.year') }}</i> {{ $listing->year }}</li>
    				@endif

    				@if($listing->odometer)
    					<li><i class="uk-text-muted">{{ trans('admin.odometer') }}</i> 0 kms</li>
    				@else
    					<li><i class="uk-text-muted">{{ trans('admin.odometer') }}</i> {{ number_format($listing->odometer) }} kms</li>
    				@endif

    				@if($listing->color)
    					<li><i class="uk-text-muted">{{ trans('admin.color') }}</i> {{ $listing->color }} </li>
    				@endif

    				@if($listing->license_number)
    					<li><i class="uk-text-muted">{{ trans('admin.license_number') }}</i> {{ $listing->license_number }}</li>
    				@endif

    				@if($listing->city)
    					<li><i class="uk-text-muted">{{ trans('admin.city') }}</i> {{ $listing->city->name }}</li>
    				@endif

    				@if($listing->district)
    					<li><i class="uk-text-muted">{{ trans('admin.district') }}</i> {{ $listing->district }}</li>
    				@endif

    				<li><i class="uk-text-muted">{{ trans('admin.code') }}</i> <b>#{{ $listing->code }}</b></li>
    			</ul>

				<button class="uk-button uk-button-large uk-button-primary uk-width-1-1" onclick="select(this)" id="{{ $listing->id }}">{{ trans('frontend.compare') }}</button>
    			<a href="{{ url($listing->user->path()) }}" class="uk-button uk-button-large uk-width-1-1 uk-margin-small-top uk-margin-bottom">{{ trans('frontend.other_user_listings') }}</a>
	    	</div>

	    	<div class="uk-width-large-3-4 uk-width-medium-3-4 uk-width-small-1-1">
	    		<div class="uk-margin-bottom uk-h3">
	    			{{ $listing->description }}
	    		</div>

	    		<hr>

	    		<h3>{{ trans('admin.security') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 1)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif
							</div>
						@endif
					@endforeach
				</div>

				<h3>{{ trans('admin.generals') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 3)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif										
							</div>
						@endif
					@endforeach
				</div>

				<h3>{{ trans('admin.accesories') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 4)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif										
							</div>
						@endif
					@endforeach
				</div>

				<hr>

				@if(count($compare) > 0)
	    		<div class="uk-width-1-1" id="compare">
	    			<h2>{{ trans('frontend.compare_prices') }}</h2>
	    			<table class="uk-table uk-table-condensed uk-table-striped" style="margin-top:-10px">
	    				<thead>
					        <tr>
					            <th>{{ trans('frontend.listing') }}</th>
					            <th style="width:50px" class="uk-hidden-small">{{ trans('admin.year') }}</th>
					            <th style="width:70px" class="uk-hidden-small">{{ trans('admin.engine_size') }}</th>
					            <th style="width:110px">{{ trans('admin.price') }}</th>
					        </tr>
					    </thead>
    				@foreach($compare as $cListing)
    					<tr>
    						<td><a href="{{ url($cListing->path()) }}">{{ $cListing->title }}</a></td>
    						<td class="uk-text-right uk-hidden-small">{{ $cListing->year }}</td>
    						<td class="uk-text-right uk-hidden-small">{{ number_format($cListing->engine_size) }}</td>
    						<td>
    							@if($cListing->price > $listing->price)
    							<i class="uk-icon-caret-up uk-text-danger uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_higher') }}"> </i>
		    					@else
		    					<i class="uk-icon-caret-down uk-text-success uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_lower') }}"> </i>
		    					@endif
		    					{{ money_format('$%!.0i', $cListing->price) }}
		    				<td>
    					</tr>
    				@endforeach
	    			</table>
	    		</div>

	    		<hr>
	    		@endif

	    		<div id="related">
	    			@if(count($related))
	    				<h2>{{ trans('frontend.similar_listings') }}</h2>
		    			<div class="uk-grid">
		    				@foreach($related as $rlisting)
		    				<div class="uk-width-large-1-4 uk-width-medium-1-4">
			    				<div class="uk-overlay uk-overlay-hover uk-margin-small">
			    					<img src="{{ asset(Image::url( $rlisting->image_path(), ['map_mini']) ) }}" alt="{{$rlisting->title}}" data-uk-scrollspy="{cls:'uk-animation-fade'}">
								    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade">
								    	<h4 class="uk-margin-remove">{{ $rlisting->title }}</h4>
								    	<h4 class="uk-margin-top-remove uk-margin-small-bottom uk-h2 uk-text-bold">{{ money_format('$%!.0i', $rlisting->price) }}</h4>
								    </div>
								    <a class="uk-position-cover" href="{{ url($rlisting->path()) }}"></a>
								</div>
							</div>
							@endforeach
		    			</div>
	    			@endif
	    		</div>

	    	</div>
	    	
	    </div>

	    <div class="uk-visible-small">
	    	<hr>
	    	<!-- Register button for mobiles -->
	        <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-bottom">{{ trans('admin.register_publish_free') }}</a>
	        <!-- Register button for mobiles -->
	    </div>
	    
	</div>
</div>

@include('modals.email_listing')

@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/components/slideshow.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/selectize.min.css') }}" rel="stylesheet"/></noscript>
	<!-- CSS -->

	<!-- JS -->
    <script src="{{ asset('/js/components/slideshow.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/selectize.min.js') }}"></script>

    @if(!Auth::check())
	<script async defer src='https://www.google.com/recaptcha/api.js'></script>
	@endif
	<!-- JS -->
	
	<script type="text/javascript">
		function phoneFormat(phone1) {
			phone = ''+phone1;
			if(phone.length == 0){
				return '';
			}
			phone = phone.replace(/\D/g,'');
			if(phone.length == 10){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 9){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{2})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 8){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{1})(\d{3})(\d{4})/, "(+$1) $2-$3");
			}else if(phone.length == 7){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{4})/, "$1-$2");
			}
			
			return phone;
		}

		function showCaptcha(){
			$('#captcha').removeClass('uk-hidden', 1000);
		}

		function select(sender){
			$.post("{{ url('/cookie/select') }}", {_token: "{{ csrf_token() }}", key: "selected_listings", value: sender.id}, function(result){
				UIkit.modal.confirm("{{ trans('frontend.listing_selected') }}", function(){
				    window.location.href = "{{ url('/comparar') }}";
				}, {labels:{Ok:'{{trans("frontend.compare_now")}}', Cancel:'{{trans("frontend.keep_looking")}}'}, center:true});
            });
		}

		$(function (){
			$('#phone_1').html(phoneFormat($('#phone_1').html()));
			$('#phone_2').html(phoneFormat($('#phone_2').html()));

			var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                  '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

			$('#emails').selectize({
			    persist: false,
			    maxItems: 5,
			    valueField: 'email',
			    labelField: 'name',
			    searchField: ['name', 'email'],
			    options: [],
			    render: {
			        item: function(item, escape) {
			            return '<div>' +
			                (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
			                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
			            '</div>';
			        },
			        option: function(item, escape) {
			            var label = item.name || item.email;
			            var caption = item.name ? item.email : null;
			            return '<div>' +
			                '<span class="label">' + escape(label) + '</span>' +
			                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
			            '</div>';
			        }
			    },
			    createFilter: function(input) {
			        var match, regex;

			        // email@address.com
			        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[0]);

			        // name <email@address.com>
			        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[2]);

			        return false;
			    },
			    create: function(input) {
			        if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
			            return {email: input};
			        }
			        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
			        if (match) {
			            return {
			                email : match[2],
			                name  : $.trim(match[1])
			            };
			        }
			        alert('Correo electrónico invalido.');
			        return false;
			    }
			});
		});

       	function like(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.likes',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response){
			  	console.log(response);
			});
       	}

       	function setListing(id){
			$('#listingId').val(id);
			$("#emails").val('');
			$("#message").val('');
		}

	    function validateEmail(email) {
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		    return re.test(email);
		}

		@if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
		var liked = true;
		@else
		var liked = false;
		@endif

		function like() {
			if(!liked){
				$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-danger');
				$('#like_button').addClass('uk-text-danger');
			}else{
				$('#like_button_image').removeClass('uk-text-danger').addClass('uk-text-contrast');
				$('#like_button').removeClass('uk-text-danger');
			}
		    

		    $.post("{{ url('/listings/'.$listing->id.'/like') }}", {_token: "{{ csrf_token() }}"}, function(result){
		    	if(result.success){
		    		if(result.like){
		    			liked = true;
		    			$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-danger');
						$('#like_button').addClass('uk-text-danger');
						UIkit.modal.alert('<h2 class="uk-text-center"><i class="uk-icon-check-circle uk-icon-large"></i><br>'+result.success+'</h2>', {center: true});

		    		}else{
		    			liked = false;
		    			$('#like_button_image').removeClass('uk-text-danger').addClass('uk-text-contrast');
						$('#like_button').removeClass('uk-text-danger');
		    		}
		    	}else if(result.error || !result){
					if(liked){
						$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-danger');
						$('#like_button').addClass('uk-text-danger');
					}else{
						$('#like_button_image').removeClass('uk-text-danger').addClass('uk-text-contrast');
						$('#like_button').removeClass('uk-text-danger');
					}
		    	}
	        });
		}
    </script>
@endsection