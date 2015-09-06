@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.new_message_title') }}</p>

	<div class="">
		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ trans('emails.new_message_client_data') }}</p>
	    	<p>	{{ trans('emails.name') }} <b>{{ $userMessage->name }}</b><br>
	    		{{ trans('emails.phone') }} <b>{{ $userMessage->phone }}</b><br>
	    		{{ trans('emails.email') }} <b>{{ $userMessage->email }}</b>
	    	</p>

		    <p><i>{{ $userMessage->comments }}</i></p>
		</div>

		<hr>

		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ $userMessage->listing->title }}</p>
		    <p>
		    {{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $userMessage->listing->price) }}</b><br>
	    	{{ trans('emails.engine_size') }} <b>{{ $userMessage->listing->engine_size }} cc</b><br>
	    	{{ trans('emails.year') }} <b>{{ $userMessage->listing->year }}</b><br>
	    	{{ trans('emails.odometer') }} <b>{{ $userMessage->listing->odometer }} kms</b><br>
	    	{{ trans('emails.color') }} <b>{{ $userMessage->listing->color }}</b><br>
	    	{{ trans('emails.code') }} <b>#{{ $userMessage->listing->code }}</b><br>
	    	</p>
	    </div>
	</div>

	<div class="uk-text-center uk-margin-top">
		<h3 class="uk-text-primary uk-text-bold">{{ trans('emails.contact_now') }}<br>
		<b>{{ $userMessage->email }}</b></h3>
	</div>
	
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact') }}</p>
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