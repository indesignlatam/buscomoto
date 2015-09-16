@extends('layouts.home')

@section('head')
    <title>{{ $user->name }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ $user->name }}"/>
	<meta property="og:image" content="{{ $user->image_path }}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:description" content="{{ $user->name }}"/>
	<meta name="description" content="{{ $user->name }}">
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-cover-background uk-position-relative">
	@if($user->image_path)
    	<img class="" src="{{ asset($user->image_path) }}" width="100%" alt="">
	@else
    	<img class="" src="{{ asset('/images/defaults/user_front.jpg') }}" width="100%" alt="">
	@endif
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-hidden-small">
        <h1 class="uk-text-contrast uk-text-bold" style="font-size:60px; margin-left:-30%;">{{ strtoupper($user->name) }}</h1>
    </div>
</div>

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel uk-margin-bottom">
		<div class="uk-visible-small">
			<img src="{{ asset('favicon.ico') }}" style="max-width: 30%; margin-top: -60px" class="uk-align-center">
			<h2 class="uk-text-primary uk-text-bold uk-margin-top-remove uk-text-center">{{ strtoupper($user->name) }}</h2>
		</div>
		
		<h3 id="description">{{ $user->description }}</h3>

		<!-- user listings -->
        @if(count($user->listings) > 0)
            <h1 class="uk-text-bold uk-hidden-small">{{ trans('frontend.user_listings') }}</h1>
            <hr class="uk-visible-small">
            <div class="uk-grid">
                @foreach($user->listings as $listing)
                    <div class="uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-1">
                        <a href="{{ url($listing->path()) }}">
                            <img src="{{ asset(Image::url($listing->image_path(),['mini_front_2x'])) }}" class="uk-margin-small-bottom">
                        </a>
                        <br class="uk-visible-small">

                        <div class="uk-visible-small">
                        	<a href="{{ url($listing->path()) }}" class="uk-h4">{{ $listing->title }}</a>
                        	<h4 class="uk-text-muted" style="margin-top:0px; margin-bottom:0px">
                        		{{ money_format('$%!.0i', $listing->price) }} | {{ $listing->odometer }} kms
                        	</h4>
                        </div>

                        <div class="uk-hidden-small">
                        	<a href="{{ url($listing->path()) }}">{{ $listing->title }}</a>
                        	<p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ money_format('$%!.0i', $listing->price) }} | {{ $listing->odometer }} kms</p>
                        </div>
                        
                        <hr class="uk-visible-small uk-margin-bottom">
                    </div>
                @endforeach
            </div>
        @endif
		<!-- user listings end -->
		
	</div>
</div>
@endsection

@section('js')
	@parent

	<link href="{{ asset('/css/components/slideshow.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/slideshow.min.js') }}"></script>
    
	<script type="text/javascript">
		function phoneFormat(phone) {
			phone = phone.replace(/\D/g,'');
			if(phone.length == 10){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 9){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{2})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 8){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{1})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 7){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{4})/, "$1-$2");
			}
			
			return phone;
		}

		// $(function (){

		// });
	</script>
	
@endsection