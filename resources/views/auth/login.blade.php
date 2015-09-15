@extends('layouts.master')

@section('head')
    <title>{{ trans('auth.login') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	<link href="{{ asset('/css/uikit.flat.min.css') }}" rel="stylesheet">
	<style type="text/css">
		html{
		@if(!Agent::isMobile())
			background-color: #000;
		    background-image: url("{{ asset('images/defaults/back.jpg') }}");
		@else
			background-color: #1C7BBA;
		@endif
		    -webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
	</style>
@endsection

@section('content')

<div class="uk-container uk-container-center">
	<a href="{{url('/')}}"><img src="{{ asset('/images/logo_h_contrast.png') }}" class="uk-align-center uk-margin-top uk-width-large-4-10"></a>
	<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-width-large-4-10 uk-align-center">
		<div class="uk-h1 uk-text-center uk-text-primary">
			{{ trans('auth.login') }}
		</div>
		@if (count($errors) > 0)
			<div class="uk-alert uk-alert-danger uk-width-large-8-10 uk-align-center" data-uk-alert>
    			<a href="" class="uk-alert-close uk-close"></a>
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br><br>
				<ul class="uk-list">
					@foreach ($errors->all() as $error)
						<li><i class="uk-icon-remove"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form class="uk-form uk-form-horizontal uk-margin-top uk-text-center" role="form" method="POST" action="{{ url('/auth/login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="uk-form-row">
				<input type="text" class="uk-width-large-8-10 uk-form-large" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}">
			</div>

			<div class="uk-form-row uk-hidden">
				<input type="text" name="username" placeholder="Username" value="{{ old('username') }}">
			</div>

			<div class="uk-form-row">
				<input type="password" class="uk-width-large-8-10 uk-form-large" placeholder="{{ trans('auth.password') }}" name="password">
			</div>

			<!-- ReCaptcha -->
			<div class="uk-form-row uk-width-large-8-10 uk-align-center uk-hidden">
				<div class="g-recaptcha" data-sitekey="6Lc9XQwTAAAAAE7GXfLVOU_g3QcsodKReurbVRUp"></div>
				<p class="uk-margin-remove uk-text-contrast">{{ trans('admin.recaptcha_help') }}</p>
			</div>
			<!-- ReCaptcha -->

			<div class="uk-form-row">
				<button type="submit" class="uk-button uk-button-success uk-width-large-8-10 uk-button-large">
					{{ trans('auth.login_button') }}
				</button>
				<a class="uk-button uk-button-success uk-button-large uk-width-large-8-10 uk-margin-small-top" href="{{ url('/social-auth/facebook') }}" style="background-color:#2089cf;"><i class="uk-icon-facebook"></i> {{ trans('auth.facebook_login') }}</a>
			</div>
			<div class="uk-margin-small-top">
				<a class="uk-text-muted" href="{{ url('/password/email') }}">{{ trans('auth.forgot_password') }}</a>
				<br>
				<a class="uk-text-large uk-text-primary" href="{{ url('/auth/register') }}">{{ trans('auth.create_account') }}</a>
			</div>
		</form>
	</div>
</div>
@endsection

@section('navbar')
@endsection

@section('alerts')
@endsection

@section('footer')
@endsection

@section('js')
	<script async src='https://www.google.com/recaptcha/api.js'></script>
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/uikit.min.js') }}"></script>
@endsection
