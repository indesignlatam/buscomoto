@extends('layouts.master')

@section('head')
    <title>{{ trans('auth.register') }} - {{ Settings::get('site_name') }}</title>
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
			{{ trans('auth.register') }}
		</div>
		@if (count($errors) > 0)
			<div class="uk-alert uk-alert-danger uk-width-large-8-10 uk-align-center" data-uk-alert>
				<a href="" class="uk-alert-close uk-close"></a>
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br>
				<ul class="uk-list">
					@foreach ($errors->all() as $error)
						<li><i class="uk-icon-remove"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form class="uk-form uk-form-horizontal uk-margin-top uk-margin-bottom uk-text-center" id="myform" method="POST" action="{{ url('/auth/register') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="uk-form-row">
				<input type="text" class="uk-width-large-8-10 uk-form-large" name="name" id="name" placeholder="{{ trans('auth.name') }}" value="{{ old('name') }}" onkeyup="checkName()">
			</div>

			<div class="uk-form-row uk-hidden">
				<input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
			</div>

			<div class="uk-form-row">
				<input type="email" class="uk-width-large-8-10 uk-form-large" name="email" id="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}" onkeyup="checkEmail()">
			</div>

			<div class="uk-form-row">
				<input type="email" class="uk-width-large-8-10 uk-form-large" name="email_confirmation" id="confirm_email" placeholder="{{ trans('auth.confirm_email') }}" value="{{ old('email_confirmation') }}" onkeyup="checkConfirmEmail()">
			</div>

			<div class="uk-form-row">
				<input type="number" class="uk-width-large-8-10 uk-form-large" name="phone" id="phone" placeholder="{{ trans('auth.phone') }}" value="{{ old('phone') }}" onkeyup="checkPhone()">
			</div>

			<div class="uk-form-row">
				<input type="password" id="password" class="uk-width-large-8-10 uk-form-large" placeholder="{{ trans('auth.password') }}" name="password" style="z-index:10;" onkeyup="checkPassword()">
			</div>

			@if(!Agent::isMobile())
			<!-- ReCaptcha -->
			<div class="uk-form-row uk-width-large-8-10 uk-align-center">
				<div class="g-recaptcha" data-sitekey="6Lc9XQwTAAAAAE7GXfLVOU_g3QcsodKReurbVRUp"></div>
				<p class="uk-margin-remove uk-text-primary">{{ trans('admin.recaptcha_help') }}</p>
			</div>
			<!-- ReCaptcha -->
			@endif

			<div class="uk-form-row">
				<button type="submit" class="uk-button uk-button-success uk-width-large-8-10 uk-button-large">{{ trans('auth.register_button') }}</button>
				<a href="{{ url('/auth/login') }}" class="uk-button uk-button-large uk-button-primary uk-width-1-1 uk-margin-small-top uk-visible-small">{{ trans('frontend.already_have_account') }}</a>
			</div>

			<a class="uk-button uk-button-primary uk-button-large uk-width-large-8-10 uk-margin-small-top uk-hidden-small" href="{{ url('/social-auth/facebook') }}"><i class="uk-icon-facebook"></i> {{ trans('auth.facebook_register') }}</a>

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
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/uikit.min.js') }}"></script>

@if(!Agent::isMobile())
	<script async src='https://www.google.com/recaptcha/api.js'></script>

    <script>
		$(document).ready(function($) {
			if($('#name').val().length > 0){
				checkName();
			}

			if($('#email').val().length > 0){
				checkEmail();
			}

			if($('#phone').val().length > 0){
				checkPhone();
			}

			if($('#password').val().length > 0){
				checkPassword();
			}
		});

		function checkName(){
			str = $('#name').val();
			if($('#name').val().split(' ').length < 2 ){
				$('#name').removeClass('uk-form-success').addClass('uk-form-danger');
			}else{
				$('#name').removeClass('uk-form-danger').addClass('uk-form-success');
			}
			$('#name').val( str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) );
		}

		function checkEmail(){
			if(validateEmail($('#email').val())){
				$('#email').removeClass('uk-form-danger').addClass('uk-form-success');
			}else{
				$('#email').removeClass('uk-form-success').addClass('uk-form-danger');
			}
		}

		function checkConfirmEmail(){
			if($('#email').val() != $('#confirm_email').val()){
				$('#confirm_email').addClass('uk-form-danger');
			}else{
				$('#confirm_email').removeClass('uk-form-danger').addClass('uk-form-success');
			}
		}

		function checkPhone(){
			if($('#phone').val().length >= 7){
				$('#phone').removeClass('uk-form-danger').addClass('uk-form-success');
			}else{
				$('#phone').removeClass('uk-form-success').addClass('uk-form-danger');
			}
		}

		function checkPassword(){
			if($('#password').val().length >= 6){
				$('#password').removeClass('uk-form-danger').addClass('uk-form-success');
			}else{
				$('#password').removeClass('uk-form-success').addClass('uk-form-danger');
			}
		}

		function validateEmail(email) {
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		    return re.test(email);
		}
	</script>
@endif
@endsection
