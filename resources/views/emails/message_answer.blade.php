@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.message_answer_title') }}</p>

	<div class="">
		<div class="uk-margin-top">
			<div class="uk-margin-top">
			    <p class="uk-text-bold">{{ trans('emails.message_answer') }}</p>

			    <p><i>{{ $comments }}</i></p>
			</div>

		    <hr>
		    
			<div class="uk-text-center">
				<a href="{{ url($messageToAnswer->listing->path()) }}">
					<img src="{{ $message->embed(public_path($messageToAnswer->listing->image_path())) }}" style="max-width:300px">
				</a>
			</div>

		    <p class="uk-text-bold">{{ $messageToAnswer->listing->title }}</p>
		    <p>
		    {{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $messageToAnswer->listing->price) }}</b><br>
	    	{{ trans('emails.engine_size') }} <b>{{ $messageToAnswer->listing->engine_size }} cc</b><br>
	    	{{ trans('emails.year') }} <b>{{ $messageToAnswer->listing->year }}</b><br>
	    	{{ trans('emails.odometer') }} <b>{{ $messageToAnswer->listing->odometer }} kms</b><br>
	    	{{ trans('emails.color') }} <b>{{ $messageToAnswer->listing->color }}</b><br>
	    	{{ trans('emails.code') }} <b>#{{ $messageToAnswer->listing->code }}</b><br>
	    	</p>
	    </div>
	</div>

	<div class="uk-text-center uk-margin-large-top">
		<h3 class="uk-text-primary uk-text-bold">{{ trans('emails.contact_now') }}<br>
		<b>{{ $messageToAnswer->listing->user->email }}</b></h3>
	</div>
	
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact_vendor') }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
@endsection

@section('share_unregister')
	@parent
@endsection