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
		<!-- Register button for mobiles -->
        <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1">{{ trans('admin.register_publish_free') }}</a>
        <!-- Register button for mobiles -->

		<h2>{{ trans('frontend.search_listings') }}</h2>
	    
	    <!-- Search & order buttons -->
    	<div class="uk-flex">
	    	<a href="#order_modal" class="uk-button uk-button-large uk-width-1-2" data-uk-modal="{center:true}"><i class="uk-icon-sort-amount-desc"></i> {{ trans('frontend.order') }}</a>
	    	<a href="#search_modal" class="uk-button uk-button-large uk-button-success uk-width-1-2" data-uk-modal="{center:true}"><i class="uk-icon-search"></i> {{ trans('frontend.search_button') }}</a>
	    </div>
	    <!-- Search & order buttons -->

	    <hr>

	    <!-- Listings -->
	    <div>
	    	@if(count($listings) > 0)
		    	@foreach($listings as $listing)
		    		<!-- Listing list view -->
		    		@include('listings.mobile.mosaic')
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
	    <!-- Listings -->

	    <hr>

	    <!-- Register button for mobiles -->
        <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1">{{ trans('admin.register_publish_free') }}</a>
        <!-- Register button for mobiles -->
	</div>
</div>

@include('listings.mobile.search_modal')
@include('listings.mobile.order_modal')

@endsection

@section('js')
	@parent
@endsection