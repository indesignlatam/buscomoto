@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.my_listings') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">

	<div class="uk-panel">		
		@if(Auth::user()->isAdmin())
			<h1 class="uk-display-inline">{{ trans('admin.listings') }}</h1>

		    <div class="uk-align-right">
		        <a class="uk-button uk-button-large" href="{{ url('/admin/listings/create') }}">{{ trans('admin.new') }}</a>
				<button class="uk-button uk-button-large uk-button-danger" onclick="deleteObjects()"><i class="uk-icon-trash"></i></button>    
			</div>

			<hr>

			@if(count($listings) > 0)
			<div class="uk-panel">
				<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
			        <input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-form-width-small" value="{{ Request::get('search') }}">
					<select name="take" onchange="this.form.submit()">
				    	<option value="">{{ trans('admin.elements_amount') }}</option>
				    	@if(Request::get('take') == 50)
				    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
				    	@else
				    		<option value="50">{{ trans('admin.elements_50') }}</option>
				    	@endif

				    	@if(Request::get('take') == 30)
				    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
				    	@else
				    		<option value="30">{{ trans('admin.elements_30') }}</option>
				    	@endif

				    	@if(Request::get('take') == 10)
				    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
				    	@else
				    		<option value="10">{{ trans('admin.elements_10') }}</option>
				    	@endif
				    </select>

				    <select name="order_by" onchange="this.form.submit()">
				    	<option value="">{{ trans('admin.order_by') }}</option>
				    	
				    	@if(Request::get('order_by') == 'id_desc')
				    		<option value="id_desc" selected>{{ trans('admin.order_newer_first') }}</option>
				    	@else
				    		<option value="id_desc">{{ trans('admin.order_newer_first') }}</option>
				    	@endif

				    	@if(Request::get('order_by') == 'exp_desc')
				    		<option value="exp_desc" selected>{{ trans('admin.order_expiring_first') }}</option>
				    	@else
				    		<option value="exp_desc">{{ trans('admin.order_expiring_first') }}</option>
				    	@endif
				    </select>
				</form>
			</div>
			@endif

			<div class="uk-panel uk-margin-top">
				<table class="uk-table uk-table-hover uk-table-striped">
					<thead>
		                <tr>
		                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
		                    <th style="width:15px"></th>
		                    <th style="width:20px"></th>
		                    <th style="width:20px">{{ trans('admin.image') }}</th>
		                    <th>{{ trans('admin.title') }}</th>
		                    <th style="width:20px">{{ trans('admin.user') }}</th>
		                    <th style="width:20px">{{ trans('admin.views') }}</th>
		                    <th style="width:20px">{{ trans('admin.points') }}</th>
		                    <th style="width:80px">{{ trans('admin.expires') }}</th>
		                    <th style="width:120px">{{ trans('admin.actions_button') }}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($listings as $listing)
		                    <tr>
		                      	<td><input type="checkbox" name="checkedLine" value="{{$listing->id}}"/></td>
		                        <td>{{ $listing->id }}</td>
		                        <td class="uk-text-center">@if($listing->published)<i class="uk-icon-check"></i>@else<i class="uk-icon-remove"></i>@endif</td>
		                        <td><img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}"></td>
		                        <td><a href="{{ url($listing->pathEdit()) }}">{{ $listing->title }}</a></td>
		                        <td>
		                        	<a href="{{ url('/admin/user/'.$listing->user->id.'/edit') }}">
		                        		<i class="uk-icon-user" data-uk-tooltip="{pos:'top'}" title="{{ $listing->user->id. ' | ' .$listing->user->name }}"></i>
		                        	</a>
		                        </td>
		                        <td>{{ $listing->views }}</td>
		                        <td>{{ $listing->points }}</td>
		                        <td>{{ $listing->expires_at->diffForHumans() }}</td>
		                        <td>
		                            <!-- This is the container enabling the JavaScript -->
		                            <div class="uk-button-dropdown" data-uk-dropdown>
		                                <!-- This is the button toggling the dropdown -->
		                                <button class="uk-button">{{ trans('admin.actions_button') }} <i class="uk-icon-caret-down"></i></button>
		                                <!-- This is the dropdown -->
		                                <div class="uk-dropdown uk-dropdown-small">
		                                    <ul class="uk-nav uk-nav-dropdown">
		                                        <li><a href="{{ url($listing->pathEdit()) }}">{{ trans('admin.edit') }}</a></li>
		                                        <li><a href="{{ url($listing->path()) }}" target="_blank">{{ trans('admin.view_listing') }}</a></li>
		                                        <li><a href="{{ url('/admin/messages/'.$listing->id) }}">{{ trans('admin.view_messages') }}</a></li>
		                                        <li><a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a></li>
		                                        <li><a href="#send_mail" onclick="setListing({{ $listing->id }})" data-uk-modal="{center:true}">{{ trans('admin.send_mail') }}</a></li>
		                                        <li><a id="{{ $listing->id }}" onclick="deleteObject(this)">{{ trans('admin.delete') }}</a></li>
		                                    </ul>
		                                </div>
		                            </div>
		                        </td>
		                    </tr>
		              	@endforeach
		            </tbody>
				</table>
				<?php echo $listings->appends(Request::all())->render(); ?>
			</div>
		@else
			<h1>{{ trans('admin.my_listings') }}</h1>

			<hr>
			@if(count($listings) > 0)
				@if(Agent::isMobile())
					<a class="uk-button uk-button-large uk-button-primary uk-width-1-1" href="{{ url('/admin/listings/create') }}">{{ trans('admin.publish_listing') }}</a>
				@else
				    <div class="">
				        <a class="uk-button uk-button-large uk-button-primary" href="{{ url('/admin/listings/create') }}">{{ trans('admin.publish_listing') }}</a>
				        <a class="uk-button uk-button-large" href="{{ url('/admin/listings/?deleted=true') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminated_listings') }}"><i class="uk-icon-trash"></i></a>

				        <form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right uk-hidden-small">
				        	<input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-form-width-small" value="{{ Request::get('search') }}">
							<select name="take" onchange="this.form.submit()">
						    	<option value="">{{ trans('admin.elements_amount') }}</option>
						    	@if(Request::get('take') == 50)
						    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
						    	@else
						    		<option value="50">{{ trans('admin.elements_50') }}</option>
						    	@endif

						    	@if(Request::get('take') == 30)
						    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
						    	@else
						    		<option value="30">{{ trans('admin.elements_30') }}</option>
						    	@endif

						    	@if(Request::get('take') == 10)
						    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
						    	@else
						    		<option value="10">{{ trans('admin.elements_10') }}</option>
						    	@endif
						    </select>

						    <select name="order_by" onchange="this.form.submit()">
						    	<option value="">{{ trans('admin.order_by') }}</option>
					    		
						    	@if(Request::get('order_by') == 'id_desc')
						    		<option value="id_desc" selected>{{ trans('admin.order_newer_first') }}</option>
						    	@else
						    		<option value="id_desc">{{ trans('admin.order_newer_first') }}</option>
						    	@endif

						    	@if(Request::get('order_by') == 'exp_desc')
						    		<option value="exp_desc" selected>{{ trans('admin.order_expiring_first') }}</option>
						    	@else
						    		<option value="exp_desc">{{ trans('admin.order_expiring_first') }}</option>
						    	@endif
						    </select>
						</form>
				    </div>
				@endif
			@endif
			
			@if(!Request::get('deleted') && count($listings) > 0)
				<div class="uk-panel uk-margin-top">					
					<ul class="uk-list">
						@foreach($listings as $listing)
			                @include('admin.listings.specific.listing')
			          	@endforeach
					</ul>
					<?php echo $listings->appends(Request::all())->render(); ?>
				</div>
			@elseif(Request::get('deleted'))
				@if(count($listings))
					<table class="uk-table">
						<thead>
					        <tr>
					            <th style="width:15px">{{ trans('admin.id') }}</th>
					            <th style="width:40px">{{ trans('admin.image') }}</th>
					            <th style="width:50%">{{ trans('admin.title') }}</th>
					            <th style="width:50px">{{ trans('admin.price') }}</th>
					            <th style="width:9%">{{ trans('admin.recover') }}</th>
					        </tr>
					    </thead>
					    <tbody>
						@foreach($listings as $listing)
					        <tr id="listing-{{ $listing->id }}">
					            <td>{{ $listing->id }}</td>
					            <td><img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}" style="width:40px"></td>
					            <td class="uk-text-bold">{{ $listing->title }}</td>
					            <td>{{ money_format('$%!.0i', $listing->price) }}</td>
					            <td>
					            	<div class="uk-flex uk-flex-space-between">
					            		<a href="{{ url('/admin/listings/'.$listing->id.'/recover') }}" class="uk-button uk-button-success"><i class="uk-icon-undo"></i></a>
					            		<button class="uk-button uk-button-danger" onclick="deleteObject(this)" id="{{ $listing->id }}"><i class="uk-icon-remove"></i></button>
					            	</div>
					            </td>
					        </tr>
						@endforeach
						</tbody>
					</table>
				@else
					<div class="uk-text-center uk-margin-top">
						<h2 class="uk-text-bold uk-text-muted">{{ trans('admin.you_have_no_deleted_listings') }}</h2>

						<div class="" style="margin-top:35px">
			    			<a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('admin.publish_listing') }}</a>
			    			<br>
			    			<br>
			    			<a href="{{ url('/admin/listings') }}">{{ trans('admin.go_back_listings') }}</a>
			    		</div>
					</div>
				@endif
			@else
				<div class="uk-text-center uk-margin-top">
					<h2 class="uk-text-bold uk-text-muted">{{ trans('admin.no_listings_found') }}</h2>
					<a href="{{ url('/admin/listings/create') }}" class="uk-h3">{{ trans('admin.publish_property_4_steps') }}</a>
					<br>
					<br>
					<a href="{{ url('/admin/listings/create') }}">
						<img src="{{ asset('/images/support/listings/publica.png') }}" width="75%">
					</a>
		    		
		    		<div class="" style="margin-top:35px">
		    			<a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('admin.publish_listing') }}</a>
		    			<br>
		    			<br>
		    			<a href="{{ url('/admin/listings?deleted=true') }}">{{ trans('admin.show_deleted') }}</a>
		    		</div>
		    	</div>
			@endif
		@endif
		
	</div>
</div>

@include('modals.email_listing')
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/selectize.min.css') }}" rel="stylesheet"/>

	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/selectize.min.js') }}"></script>


	<script type="text/javascript">
		$(function() {
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
		
		function setListing(id){
			$('#listingId').val(id);
			$("#emails").val('');
			$("#message").val('');
		}

		window.fbAsyncInit = function() {
        	FB.init({
         		appId      : {{ Settings::get('facebook_app_id') }},
          		xfbml      : true,
          		version    : 'v2.3'
        	});
      	};
      	(function(d, s, id){
         	var js, fjs = d.getElementsByTagName(s)[0];
         	if (d.getElementById(id)) {return;}
         	js = d.createElement(s); js.id = id;
         	js.src = "//connect.facebook.net/en_US/sdk.js";
         	fjs.parentNode.insertBefore(js, fjs);
       	}(document, 'script', 'facebook-jssdk'));

       	function share(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
				UIkit.notify('<i class="uk-icon-check-circle"></i> {{ trans("admin.listing_shared") }}', {pos:'top-right', status:'success', timeout: 15000});
				$.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+id, value: true, time:11520}, function(result){
	                
	            });
			  	// Debug response (optional)
			  	console.log(response);
			});
       	}

	    function deleteObject(sender) {
	    	UIkit.modal.confirm("{{ trans('admin.sure') }}", function(){
			    // will be executed on confirm.
			    $.post("{{ url('/admin/listings') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
			    	if(result.success){
			    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
			    		$('#listing-'+sender.id).fadeOut(500, function() { $(this).remove(); });
			    	}else if(result.error){
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 5000});
			    	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

	    function sendMail(sender) {
	    	$('#sendMail').prop('disabled', true);
	    	var message = $('#message').val();
	    	var emails = $('#emails').val().replace(/ /g,'').split(',');
	    	var validemails = [];
	    	$.each(emails, function( index, value ) {
			  	if(validateEmail(value)){
			  		validemails.push(value);
			  	}
			});

			if(validemails.length < 1){
				UIkit.notify('<i class="uk-icon-remove"></i> {{ trans('admin.no_emails') }}', {pos:'top-right', status:'danger', timeout: 5000});
				$('#sendMail').prop('disabled', false);
				return;
			}

			if(message.length < 1){
				UIkit.notify('<i class="uk-icon-remove"></i> {{ trans('admin.no_message') }}', {pos:'top-right', status:'danger', timeout: 5000});
				$('#sendMail').prop('disabled', false);
				return;
			}

	    	$.post("{{ url('/admin/listings') }}/"+ $('#listingId').val() +"/share", {_token: "{{ csrf_token() }}", email: validemails, message: message}, function(result){
		    	$('#sendMail').prop('disabled', false);
		    	if(result.success){
		    		UIkit.modal("#send_mail").hide();
		    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
		    	}else if(result.error || !result){
		    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 5000});
		    	}
	        });
	    }

	    function validateEmail(email) {
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		    return re.test(email);
		}
	</script>
@endsection