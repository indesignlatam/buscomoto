@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.user_confirmation_reminder_title') }}</p>
	
	<div class="uk-text-center">
		<h3>{{ trans('emails.click_here_to_confirm_reminder') }}</h3>
		<a class="uk-button uk-button-large uk-button-primary" href="{{ url('user/'.$user->id.'/confirm/'.$user->confirmation_code) }}">{{ trans('emails.confirm_user_reminder') }}</a>
	</div>

	<p>{{ trans('emails.confirm_user_not_working') }}<br> {{ url('user/'.$user->id.'/confirm/'.$user->confirmation_code) }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	
@endsection

@section('share_unregister')
	@parent
@endsection