@extends('layouts.home')

@section('head')
	<title>{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}</title>
	<meta property="og:title" content="{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}"/>
	<meta name="description" content="{{ Settings::get('listings_description') }}">
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('listings_description') }}" />
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
	<div class="uk-panel">
		<h2>{{ trans('frontend.search_listings') }}</h2>
	    
	    <hr>

	    <!-- Search bar for pc -->
    	<div class="uk-panel uk-panel-box uk-panel-box-secondary">
    		<button class="uk-button uk-button-large uk-button-primary uk-width-1-1 search" data-uk-toggle="{target:'.search'}"><i class="uk-icon-search"></i> {{ trans('frontend.search_button') }}</button>
    		<button class="uk-button uk-button-large uk-width-1-1 uk-hidden search" data-uk-toggle="{target:'.search'}">{{ trans('frontend.hide') }}</button>

			<form id="search_form" class="uk-form uk-form-stacked uk-margin-top uk-hidden search" method="GET" action="{{ url(Request::path()) }}">
				<div class="uk-form-row">
					<label class="uk-form-label">{{ trans('frontend.search_listing_type') }}</label>
					<select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="listing_type">
                        <option value>{{ trans('frontend.search_select_option') }}</option>
                        @foreach($listingTypes as $type)
                            @if($type->id == Request::get('listing_type'))
                                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                            @else
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endif
                        @endforeach
                    </select>
		        </div>

		        <div class="uk-form-row uk-margin-remove">
					<label class="uk-form-label">{{ trans('frontend.search_manufacturer') }}</label>
					<select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="search_manufacturer" name="manufacturers[]" multiple="multiple" onchange="getListings()">
                        @foreach($manufacturers as $manufacturer)
                            @if(is_array(Request::get('manufacturers')) && in_array($manufacturer->id, Request::get('manufacturers')))
                                <option value="{{ $manufacturer->id }}" selected>{{ $manufacturer->text }}</option>
                            @else
                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->text }}</option>
                            @endif
                        @endforeach
                    </select>
		        </div>

		        <div class="uk-form-row uk-margin-small-top">
					<label class="uk-form-label">{{ trans('frontend.search_price_max') }}</label>
		            <input type="hidden" name="price_min" class="uk-width-1-1 uk-form-large" value="0">
		            <input type="number" name="price_max" class="uk-width-1-1 uk-form-large" placeholder="{{ trans('frontend.search_price_max') }}" value="{{Request::get('price_max')}}">
		        </div>

		        <div class="uk-form-row uk-margin-small-top">
				  	<label for="engine_size_range" class="uk-form-label">{{ trans('frontend.search_engine_size') }}</label>
				  	<input type="number" name="engine_size_min" class="uk-width-1-1 uk-form-large uk-margin-small-bottom" placeholder="{{ trans('frontend.search_engine_size_min') }}" value="{{Request::get('engine_size_min')}}">
		            <input type="number" name="engine_size_max" class="uk-width-1-1 uk-form-large" placeholder="{{ trans('frontend.search_engine_size_max') }}" value="{{Request::get('engine_size_max')}}">
				</div>

		        <div class="uk-form-row uk-margin-small-top">
				  	<label for="year_range" class="uk-form-label">{{ trans('frontend.search_year') }}</label>
				  	<input type="number" name="year_min" class="uk-width-1-1 uk-form-large uk-margin-small-bottom" placeholder="{{ trans('frontend.search_year_min') }}" value="{{Request::get('year_min')}}">
		            <input type="number" name="year_max" class="uk-width-1-1 uk-form-large" placeholder="{{ trans('frontend.search_year_max') }}" value="{{Request::get('year_max')}}">
				</div>

				<div class="uk-form-row uk-margin-small-top">
				  	<label for="odometer_range" class="uk-form-label">{{ trans('frontend.search_odometer') }}</label>
				  	<input type="hidden" name="odometer_min" class="uk-width-1-1 uk-form-large" value="0">
		            <input type="number" name="odometer_max" class="uk-width-1-1 uk-form-large" placeholder="{{ trans('frontend.search_odometer_max') }}" value="{{Request::get('odometer_max')}}">
				</div>

	            <select class="uk-form-large uk-width-1-1 uk-margin-small-top" name="take">
			    	<option value="">{{ trans('admin.elements_amount') }}</option>
			    	@if(Request::get('take') == 50)
			    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
			    	@elseif(session('listings_take') == 50)
			    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
			    	@else
			    		<option value="50">{{ trans('admin.elements_50') }}</option>
			    	@endif

			    	@if(Request::get('take') == 30)
			    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
			    	@elseif(session('listings_take') == 30)
			    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
			    	@else
			    		<option value="30">{{ trans('admin.elements_30') }}</option>
			    	@endif

			    	@if(Request::get('take') == 10)
			    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
			    	@elseif(session('listings_take') == 10)
			    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
			    	@else
			    		<option value="10">{{ trans('admin.elements_10') }}</option>
			    	@endif
			    </select>

			    <select class="uk-form-large uk-width-1-1 uk-margin-small-top" name="order_by">
			    	<option value="0">{{ trans('admin.order_by') }}</option>
			    	@if(Request::get('order_by') && Request::get('order_by') == 'id_desc')
			    		<option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
			    	@elseif(session('listings_order_by') == 'id_desc')
			    		<option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
			    	@else
			    		<option value="id_desc">{{ trans('admin.order_newer_first')}}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'id_asc')
			    		<option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
			    	@elseif(session('listings_order_by') == 'id_asc')
			    		<option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
			    	@else
			    		<option value="id_asc">{{ trans('admin.order_older_first')}}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'price_max')
			    		<option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
			    	@elseif(session('listings_order_by') == 'price_max')
			    		<option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
			    	@else
			    		<option value="price_max">{{ trans('admin.order_expensive_first') }}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'price_min')
			    		<option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
			    	@elseif(session('listings_order_by') == 'price_min')
			    		<option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
			    	@else
			    		<option value="price_min">{{ trans('admin.order_cheaper_first') }}</option>
			    	@endif
			    </select>

            	<button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-small-top">{{ trans('frontend.search_button') }}</button>
			</form>
    	</div>
    	<!-- End search bar -->

	    <div class="uk-margin-top">
	    	@if(count($listings) > 0)
				
		    	@foreach($listings as $listing)
		    		<!-- Listing list view -->
		    		@include('listings.mosaic')
		    		<!-- Listing list view -->
			    @endforeach
			    <div class="uk-margin-small-top">
			    	<?php echo $listings->appends(Request::all())->render(); ?>
			    </div>
		    @else
		    	<div class="uk-text-center">
		    		<h3 class="uk-text-primary">{{ trans('frontend.sorry') }}<br>{{ trans('frontend.no_listings_found') }}</h3>
		    		<h4>{{ trans('frontend.try_other_parameters') }}</h4>
		    	</div>
		    @endif
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection