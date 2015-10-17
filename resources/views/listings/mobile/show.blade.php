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
		loadCSS("{{ asset('/css/swiper.min.css') }}");
	</script>
	<noscript><link href="{{ asset('/css/swiper.min.css') }}" rel="stylesheet"></noscript>
@endsection

@section('content')

<div class="uk-container uk-container-center">
	<div class="uk-panel">
		<h2 class="uk-visible-small">{{ $listing->title }}</h2>

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

		<div class="uk-width-1-1 uk-margin-small-top" style="position:relative">	
			@if(count($listing->images) > 0)
				<!-- Slider main container -->
                <div class="swiper-container">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                    <!-- Slides -->
                    @foreach($listing->images->sortBy('ordering') as $image)
			    		<div class="swiper-slide">
			    			<img src="{{ asset($image->image_path) }}" alt="{{ $listing->title }}">
			    		</div>
			    	@endforeach	
                    </div>
                    
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                    
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                @if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
			    	<a onclick="like()" style="z-index:1"><i style="position:absolute; top:5px; right:5px; z-index:1000" class="uk-icon-heart uk-icon-large uk-text-danger" id="like_button_image"></i></a>
			    @else
			    	<a onclick="like()"><i style="position:absolute; top:5px; right:5px; z-index:1000" class="uk-icon-heart uk-icon-large uk-text-contrast" id="like_button_image"></i></a>
			    @endif
			@else
				<img src="{{ asset($listing->image_path()) }}" alt="{{ $listing->title }}" >
			@endif

			<button class="uk-button uk-button-large uk-margin-top uk-button-primary uk-width-1-1" data-uk-modal="{target:'#new_message_modal'}">{{ trans('frontend.contact_vendor') }}</button>
		</div>
			
		<hr>

	    <div class="uk-grid uk-margin uk-margin-bottom">
	    	<div class="uk-width-1-1">
	    		<ul class="uk-list uk-list-line">
    				<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> {{ money_format('$%!.0i', $listing->price) }}</li>

    				@if($listing->engine_size)
    					<li><i class="uk-text-muted">{{ trans('admin.engine_size') }}</i> {{ $listing->engine_size }} cc</li>
    				@endif

    				@if($listing->year)
    					<li><i class="uk-text-muted">{{ trans('admin.year') }}</i> {{ $listing->year }}</li>
    				@endif

    				@if(!is_null($listing->odometer))
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

				<button class="uk-button uk-button-large uk-button-primary uk-width-1-1" data-uk-modal="{target:'#new_message_modal'}">{{ trans('frontend.contact_vendor') }}</button>
    			<a href="{{ url($listing->user->path()) }}" class="uk-button uk-button-large uk-width-1-1 uk-margin-small-top uk-margin-bottom">{{ trans('frontend.other_user_listings') }}</a>
	    	</div>

	    	<div class="uk-width-1-1">
	    		<div class="uk-margin-bottom uk-h3">
	    			{{ $listing->description }}
	    		</div>

	    		<hr>

	    		<h3>{{ trans('admin.security') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 1)
							<div class="uk-width-1-1">
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
							<div class="uk-width-1-1">
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
							<div class="uk-width-1-1">
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
					            <th style="width:110px">{{ trans('admin.price') }}</th>
					        </tr>
					    </thead>
    				@foreach($compare as $cListing)
    					<tr>
    						<td><a href="{{ url($cListing->path()) }}">{{ $cListing->title }}</a></td>
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
	    				<div class="uk-width-1-1 uk-margin-bottom">
		    				<a href="{{ url($rlisting->path()) }}" class="uk-panel uk-panel-box">
		    					<img src="{{ asset(Image::url( $rlisting->image_path(), ['map_mini']) ) }}" alt="{{$rlisting->title}}" data-uk-scrollspy="{cls:'uk-animation-fade'}">
			    				<div class="uk-margin-small-top">
			    					<h3 class="uk-margin-remove">{{ $rlisting->title }}</h4>
								    <span class="uk-margin-top-remove">
								    	{{ money_format('$%!.0i', $rlisting->price) }} | {{ number_format($rlisting->odometer) }} kms
								    </span>
								</div>
							</a>
						</div>
					@endforeach
	    			</div>
	    			@endif
	    		</div>

	    	</div>
	    	
	    </div>

	    <hr>
    	<!-- Register button for mobiles -->
        <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-bottom">{{ trans('admin.register_publish_free') }}</a>
	    
	</div>
</div>

@include('appointments.new')

@endsection

@section('js')
	@parent
	<script src="{{ asset('/js/swiper.jquery.min.js') }}"></script>

	
	<script type="text/javascript">
		$(function (){
			var mySwiper = new Swiper ('.swiper-container', {
                // Optional parameters
                direction: 'horizontal',
                loop: true,
                
                // If we need pagination
                pagination: '.swiper-pagination',
                
                // Navigation arrows
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
            });
		});

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