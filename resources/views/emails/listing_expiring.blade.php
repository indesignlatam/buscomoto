@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.expiring_listing_title') }}</p>

	<div style="text-align:center; margin-top:20px">
		<h3><a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('emails.renovate_listing_now') }}</a></h3>
	</div>

	<h3>{{ $listing->title }}</h3>
    <p>
    	{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $listing->price) }}</b><br>
    	{{ trans('emails.engine_size') }} <b>{{ $listing->engine_size }} cc</b><br>
    	{{ trans('emails.year') }} <b>{{ $listing->year }}</b><br>
    	{{ trans('emails.odometer') }} <b>{{ $listing->odometer }} kms</b><br>
    	{{ trans('emails.color') }} <b>{{ $listing->color }}</b><br>
    	{{ trans('emails.code') }} <b>#{{ $listing->code }}</b><br>
    </p>

    <div style="text-align:center;">
		<a href="{{ url($listing->path()) }}">
			<img src="{{ $message->embed(public_path($listing->image_path())) }}" style="width:300px; max-width:300px">
		</a>
	</div>

	<div style="text-align:center; margin-top:20px">
		<h3><a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('emails.renovate_listing_now') }}</a></h3>
	</div>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	@parent
@endsection

@section('share_unregister')
	@parent
@endsection