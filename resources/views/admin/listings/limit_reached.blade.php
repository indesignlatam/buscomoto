@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.listings_limit_reached') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
	@if(!Agent::isMobile())
		<h1>{{ trans('admin.listings_limit_title') }}</h1>

		<img src="{{ asset('/images/support/listings/max_limit.png') }}" class="uk-align-center" style="max-width:500px">

		<div style="max-width:70%" class="uk-text-center uk-align-center">
			<h2 class="uk-text-primary">{{ trans('admin.listings_limit_explanation') }}</h2>
			<h3 class="">{{ trans('admin.listings_limit_text') }}</h3>
		</div>
	@else
		<h2>{{ trans('admin.listings_limit_title') }}</h2>

		<img src="{{ asset('/images/support/listings/max_limit.png') }}" class="uk-align-center" style="max-width:250px">

		<div class="uk-text-center">
			<h2 class="uk-text-primary">{{ trans('admin.listings_limit_explanation') }}</h2>
			<h3 class="">{{ trans('admin.listings_limit_text') }}</h3>
		</div>
	@endif
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection
