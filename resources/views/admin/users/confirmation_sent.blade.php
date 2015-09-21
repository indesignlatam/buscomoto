@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.confirmation_sent') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
	@if(!Agent::isMobile())
		<h1>{{ trans('admin.confirmation_sent_title') }}</h1>

		<img src="{{ asset('/images/support/mail/mail_sent.png') }}" class="uk-align-center" style="max-width:500px">

		<div style="max-width:70%" class="uk-text-center uk-align-center">
			<h3 class="">{{ trans('admin.confirmation_sent_text') }}</h3>
			<h3 class="uk-text-primary">{{ trans('admin.confirmation_sent_spam') }}</h3>
		</div>
	@else
		<h2>{{ trans('admin.confirmation_sent_title') }}</h2>

		<img src="{{ asset('/images/support/mail/mail_sent.png') }}" class="uk-align-center" style="max-width:250px">

		<div class="">
			<h3 class="">{{ trans('admin.confirmation_sent_text') }}</h3>
			<h3 class="uk-text-primary">{{ trans('admin.confirmation_sent_spam') }}</h3>
		</div>
	@endif
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection
