@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	<h3>Buen día,</h3>
@endsection

@section('content')
	@if($messageText)
		<p>{{ $messageText }}</p>
	@else
		<p>{{ trans('emails.listing_share_title', ['name' => $listing->user->name]) }}</p>
	@endif

	<div class="">
		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ $listing->title }}</p>
		    <p>
		    {{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $listing->price) }}</b><br>
	    	{{ trans('emails.engine_size') }} <b>{{ $listing->engine_size }} cc</b><br>
	    	{{ trans('emails.year') }} <b>{{ $listing->year }}</b><br>
	    	{{ trans('emails.odometer') }} <b>{{ $listing->odometer }} kms</b><br>
	    	{{ trans('emails.color') }} <b>{{ $listing->color }}</b><br>
	    	{{ trans('emails.code') }} <b>#{{ $listing->code }}</b><br>
	    	</p>
			<p>{{ $listing->description }}</p>
	    </div>
	</div>

	<div class="uk-text-center">
		@foreach($listing->images->sortBy('ordering') as $image)
			<a href="{{ url($listing->path()) }}"><img src="{{ $message->embed(public_path($image->image_path)) }}" style="max-width:300px; margin-right:10px; margin-bottom:10px"></a>
		@endforeach
	</div>
@endsection

@section('greetings')
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact_vendor') }}</p>
	<p class="uk-margin-large-top">
		Cordialmente,
		<br>
		<br>
		<br>
		<b>{{ $listing->user->name }}<b>
	</p>
@endsection

@section('footer')
	{{-- <p class="uk-text-bold">{{ trans('emails.answer_to_contact_vendor') }}</p> --}}
@endsection

@section('share_unregister')
	@parent
@endsection