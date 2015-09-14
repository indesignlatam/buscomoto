@extends('layouts.home')

@section('head')
    <title>{{ trans('frontend.compare_title') }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ trans('frontend.compare_title') }}"/>
	<meta property="og:image" content=""/>
	<meta property="og:type" content="article"/>
	<meta property="og:description" content=""/>
    <meta name="description" content="{{ Settings::get('site_description') }}">
@endsection

@section('css')
	@parent
	<script type="text/javascript">
		loadCSS("{{ asset('/css/components/sticky.min.css') }}");
	    loadCSS("{{ asset('/css/components/tooltip.min.css') }}");
   	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
	@if(!Agent::isMobile())
		<div>
			<h1 class="uk-display-inline">{{ trans('frontend.compare_title') }}</h1>
			<button class="uk-button uk-button-link" onclick="forget()">({{ trans('frontend.compare_forget_listings') }})</button>
		</div>

		<h3>{{ trans('frontend.compare_intro') }}</h3>

		<div class="uk-grid uk-grid-small uk-margin-top uk-margin-large-bottom">
			@if($listings && count($listings) > 0)
				@foreach($listings as $listing)
					<div class="uk-panel uk-panel-box uk-panel-box-primary uk-width-1-4" style="border-top:none;border-left:none;border-bottom:none;border-radius:0" id="listing_{{ $listing->id }}">
						<div style="height:60px; background-color:white;" data-uk-sticky="{boundary: true}">
							<a class="uk-modal-close uk-close uk-close-alt uk-text-danger" onclick="forgetListing({{ $listing->id }})" style="position:absolute; top:0px; right: 15px;"></a>
							<a href="{{ $listing->path() }}" style="text-decoration:none">
								<h3>{{ $listing->title }}</h3>
							</a>
						</div>
						<div class="uk-panel">
							<div class="uk-panel-badge uk-badge uk-badge-notification">{{ $listing->points }}</div>
							<a href="{{ $listing->path() }}"><img src="{{ $listing->image_path }}"></a>
						</div>

		    			<ul class="uk-list uk-list-line">
	    					<li>
	    						<i class="uk-text-muted">{{ trans('admin.price') }}</i> 
	    						<b class="uk-text-right uk-float-right">{{ money_format('$%!.0i', $listing->price) }}</b>
	    					</li>

							@if($listing->manufacturer)
								<li>
									<i class="uk-text-muted">{{ trans('admin.manufacturer') }}</i> 
									<b class="uk-text-right uk-float-right">
										{{ $listing->manufacturer->name }}
										<i class="uk-icon-info-circle" data-uk-tooltip title="{{ trans('frontend.origin') . $listing->manufacturer->country->name }}"></i>
									</b>
								</li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.manufacturer') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->engine_size)
								<li><i class="uk-text-muted">{{ trans('admin.engine_size') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->engine_size }} cc</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.engine_size') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if(!is_null($listing->odometer))
								<li><i class="uk-text-muted">{{ trans('admin.odometer') }}</i> <b class="uk-text-right uk-float-right">{{ number_format($listing->odometer) }} kms</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.odometer') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->year)
								<li><i class="uk-text-muted">{{ trans('admin.year') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->year }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.year') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->color)
								<li><i class="uk-text-muted">{{ trans('admin.color') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->color }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.color') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->listingType)
								<li><i class="uk-text-muted">{{ trans('admin.listing_type') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->listingType->name }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.listing_type') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->license_number)
								<li><i class="uk-text-muted">{{ trans('admin.license_number') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->license_number }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.license_number') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->city)
								<li><i class="uk-text-muted">{{ trans('admin.city') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->city->name }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.city') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.security') }}</i>
	    					<button class="uk-button uk-button-link interior" style="text-decoration:none;" data-uk-toggle="{target:'.interior'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden interior" style="text-decoration:none;" data-uk-toggle="{target:'.interior'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden interior">
	    					@foreach($features as $feature)
								@if($feature->category->id == 1)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.generals') }}</i>
	    					<button class="uk-button uk-button-link exterior" style="text-decoration:none;" data-uk-toggle="{target:'.exterior'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden exterior" style="text-decoration:none;" data-uk-toggle="{target:'.exterior'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden exterior">
	    					@foreach($features as $feature)
								@if($feature->category->id == 3)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.accesories') }}</i>
	    					<button class="uk-button uk-button-link sector" style="text-decoration:none;" data-uk-toggle="{target:'.sector'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden sector" style="text-decoration:none;" data-uk-toggle="{target:'.sector'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden sector">
	    					@foreach($features as $feature)
								@if($feature->category->id == 4)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<h3 id="points2-{{ $listing->id }}" class="uk-text-bold uk-text-center">{{ $listing->points . trans('frontend.points') }}</h3>
					</div>
				@endforeach
			@endif
		</div>
	@else
		<div class="uk-margin-bottom">
			<h1>{{ trans('frontend.compare_title') }}</h1>

			<hr>
		
			<h3>{{ trans('frontend.not_available_mobile') }}</h3>
		</div>

	@endif
	</div>
</div>
@endsection

@section('js')
	@parent
	<noscript><link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet"></noscript>

	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>

	<script type="text/javascript">
       	function share(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
			  	console.log(response);
			});
       	}

       	function like(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.likes',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
			  	console.log(response);
			});
       	}

       	function forget(){
       		UIkit.modal.confirm("{{ trans('frontend.listing_selected') }}", function(){
       			$.post("{{ url('/cookie/forget') }}", {_token: "{{ csrf_token() }}", key: "selected_listings"}, function(result){
				 	window.location.href = "{{ url('/comparar') }}";
            	});
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center:true});
		}

		function forgetListing(id){
       		UIkit.modal.confirm("{{ trans('frontend.compare_remove_listing') }}", function(){
       			$.post("{{ url('/cookie/forgetlisting') }}", {_token: "{{ csrf_token() }}", listing_id: id}, function(result){
       				if(result.success){
				 		$('#listing_'+id).fadeOut(500, function() { $(this).remove();});
       				}
            	});
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center:true});
		}
	</script>
@endsection