@extends('layouts.home')

@section('head')
	<title>{{ trans('frontend.liked_listings') }} - {{ Settings::get('site_name') }}</title>
	<meta property="og:title" content="{{ trans('frontend.liked_listings') }} - {{ Settings::get('site_name') }}"/>

	<meta name="description" content="{{ Settings::get('listings_description') }}">
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('listings_description') }}" />
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('frontend.liked_listings') }}</h1>
		<hr>

		<div class="uk-grid">
		@if(Auth::check() && count($likes) > 0)
			@foreach($likes as $like)
				<?php 	$listing 		= $like->listing; 
						$mosaicClass 	= "uk-width-medium-1-3 uk-width-large-1-3 uk-margin-small-bottom"; 
				?>
				@include('listings.mosaic')
			@endforeach
		@elseif(count($likes) > 0)
			@foreach($likes as $listing)
				<?php $mosaicClass = "uk-width-medium-1-3 uk-width-large-1-3 uk-margin-small-bottom"; ?>
				@include('listings.mosaic')
			@endforeach
		@else
			<div class="uk-width-1-1 uk-margin-large-bottom">
				<h3 class="uk-text-center uk-text-primary">{{ trans('frontend.no_favorites') }}</h3>
				<h3 class="uk-text-center uk-text-muted">{{ trans('frontend.no_favorites_text_1') }} <i class="uk-icon-heart"></i> {{ trans('frontend.no_favorites_text_2') }}</h3>
			</div>
		@endif
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<script type="text/javascript">
		function unlike(id) {
			$('#like_'+id).removeClass('uk-text-primary').addClass('uk-text-contrast');

		    $.post("{{ url('/listings') }}/"+ id +"/like", {_token: "{{ csrf_token() }}"}, function(result){
		    	if(result.success){
		    		if(result.like){
		    			$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
						$('#like_button').addClass('uk-text-primary');
		    		}else{
		    			$('#listing_'+id).fadeOut(500, function() { $(this).remove();});
		    		}
		    	}else if(result.error || !result){
					$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
					$('#like_button').addClass('uk-text-primary');
		    	}
	        });
		}
	</script>
@endsection