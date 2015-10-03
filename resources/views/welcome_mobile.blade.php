@extends('layouts.home')

@section('head')
    <title>{{ Settings::get('site_name') }}</title>
    <meta name="description" content="{{ Settings::get('site_description') }}">
    <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
    <meta property="og:image" content="{{ asset('/images/defaults/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('site_description') }}"/>
@endsection

@section('css')
	@parent
   
    <script type="text/javascript">
        loadCSS("{{ asset('/css/select2.min.css') }}");
        loadCSS("{{ asset('/css/swiper.min.css') }}");
    </script>
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')

	<div class="uk-container uk-container-center uk-margin">
        <!-- Search for mobile devices -->
        <div>
            <!-- Register button for mobiles -->
            <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1">{{ trans('admin.register_publish_free') }}</a>
            <!-- Register button for mobiles -->

            <h3 class="uk-text-primary uk-text-bold uk-text-center">{{trans('frontend.search_intro')}}</h3>
            <form id="mobile_search_form" class="uk-form" method="GET" action="{{ url('/buscar') }}">
                <input type="hidden" name="engine_size_min" value id="engine_size_min">
                <input type="hidden" name="engine_size_max" value id="engine_size_max">

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="listing_type">
                    <option value>{{ trans('frontend.search_listing_type') }}</option>
                    @foreach($listingTypes as $type)
                        @if($type->id == Request::get('listing_type'))
                            <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                        @else
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="engine_size">
                    <option value>{{ trans('frontend.search_engine_size') }}</option>
                    <option value="1">0cc - 125cc</option>
                    <option value="2">125cc - 250cc</option>
                    <option value="3">250cc - 450cc</option>
                    <option value="4">450cc - 600cc</option>
                    <option value="5">600cc o más</option>
                </select>

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="price_range">
                    <option value>{{ trans('frontend.search_price') }}</option>
                    @foreach($priceRanges as $range)
                        @if($range['id'] == Request::get('price_range'))
                            <option value="{{ $range['id'] }}" selected>hasta {{ $range['name'] }}</option>
                        @elseif($range['id'] == 50000000)
                            <option value="{{ $range['id'] }}" selected>{{ $range['name'] }}</option>
                        @else
                            <option value="{{ $range['id'] }}">hasta {{ $range['name'] }}</option>
                        @endif
                    @endforeach
                </select>

                 <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="search_manufacturer_mobile" name="manufacturers[]" multiple="multiple">
                    @foreach($manufacturers as $manufacturer)
                        @if(is_array(Request::get('manufacturers')) && in_array($manufacturer->id, Request::get('manufacturers')))
                            <option value="{{ $manufacturer->id }}" selected>{{ $manufacturer->text }}</option>
                        @else
                            <option value="{{ $manufacturer->id }}">{{ $manufacturer->text }}</option>
                        @endif
                    @endforeach
                </select>

                <button form="mobile_search_form" type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-small-top">{{ trans('frontend.search_button') }}</button>
            </form>

            <div class="uk-grid uk-margin-top uk-margin-bottom-remove uk-grid-small">
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=73') }}"><img src="{{ asset('images/logos/mvagusta.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=12') }}"><img src="{{ asset('images/logos/bmw.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=51') }}"><img src="{{ asset('images/logos/ktm.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=110') }}"><img src="{{ asset('images/logos/yamaha.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=48') }}"><img src="{{ asset('images/logos/kawasaki.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
                <div class="uk-width-1-4">
                    <a href="{{ url('/buscar?manufacturers[]=73') }}"><img src="{{ asset('images/logos/honda.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                </div>
            </div>
        </div>
        <!-- Search for mobile devices end -->

        <!-- latest listings on turism-->
        @if(count($newBikes) > 0)
            <h2 class="uk-text-bold">{{ trans('frontend.latest_listings') }}</h2>

            <!-- Slider main container -->
            <div class="swiper-container">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach($newBikes as $bike)
                    <div class="swiper-slide">
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front_2x'])) }}">
                        </a>
                        <div class="uk-h3">
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted uk-h4" style="margin-top:-4px; margin-bottom:0px">
                                {{ money_format('$%!.0i', $bike->price) }} | {{ number_format($bike->odometer) }}
                            </p>
                        </div>
                    </div>
                @endforeach
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <hr>
        @endif
        <!-- latest listings on turism -->
    </div>

    <!-- Register and publish -->
    <div class="uk-block uk-block-secondary" style="background-color:#1C7BBA;">
        <div class="uk-container uk-container-center">
            <div class="uk-text-center uk-margin-bottom">
                @if(!Auth::check())
                    <a href="{{ url('/auth/register') }}" class="uk-button uk-button-link" style="text-decoration: none"><h2 class="uk-text-contrast uk-text-bold">{{ trans('admin.register_publish_free') }}</h2></a>
                @else
                    <a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-link" style="text-decoration: none"><h2 class="uk-text-contrast uk-text-bold">{{ trans('admin.publish_listing') }}</h2></a>
                @endif
            </div>

            <div class="uk-width-1-1">
                <a href="{{ url('/auth/register') }}">
                    <div class="uk-grid">
                        <div class="uk-width-2-5">
                            <img src="{{ asset('images/fp/icon_1.png') }}" class="uk-border-circle">
                        </div>
                        <div class="uk-width-3-5">
                            <h2 class="uk-text-bold uk-text-contrast uk-margin-top-remove uk-h1">{{ trans('frontend.free') }}</h2>
                            <p style="max-width:200px" class="uk-align-center uk-contrast">{{ trans('frontend.free_text') }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="uk-width-1-1 uk-margin-top">
                <a href="{{ url('/auth/register') }}">
                    <div class="uk-grid">
                        <div class="uk-width-3-5">
                            <h2 class="uk-text-bold uk-text-contrast uk-margin-top-remove uk-h1">{{ trans('frontend.efective') }}</h2>
                            <p style="max-width:200px" class="uk-align-center uk-contrast">{{ trans('frontend.efective_text') }}</p>
                        </div>
                        <div class="uk-width-2-5">
                            <img src="{{ asset('images/fp/icon_3.png') }}" class="uk-border-circle">
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Register and publish -->

    <div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
        <!-- Featured listings -->
        @if(count($featured) > 0)
            <h2 class="uk-margin-bottom uk-margin-top uk-text-bold">{{ trans('frontend.featured_listing') }}</h2>

            <!-- Slider main container -->
            <div class="swiper-container" id="swiper-featured">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach($featured as $bike)
                    <div class="swiper-slide">
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front_2x'])) }}">
                        </a>
                        <div class="uk-h3">
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted uk-h4" style="margin-top:-4px; margin-bottom:0px">
                                {{ money_format('$%!.0i', $bike->price) }} | {{ number_format($bike->odometer) }}
                            </p>
                        </div>
                    </div>
                @endforeach
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>       
        @endif
        <!-- Featured listings -->
    </div>

    <div class="uk-block uk-block-primary">
        <div class="uk-container uk-container-center uk-margin-top-remove">
            <div class="uk-visible-small">
                <h2 class="uk-text-contrast uk-text-bold">Pronto podrás encontrar nuestra aplicación para dispositivos móviles</h2>
                <img src="{{ asset('/images/fp/app_store.png') }}" style="max-width: 250px" class="uk-align-center">
                <img src="{{ asset('/images/fp/app.png') }}" class="uk-align-center" style="margin-bottom:-20px">
            </div>
        </div>
    </div>
    
@endsection

@section('js')
	@parent
    
    <noscript><link href="{{ asset('/css/swiper.min.css') }}" rel="stylesheet"/></noscript>
    <script src="{{ asset('/js/swiper.jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#engine_size').change(function() {
                sender = $('#engine_size');
                if(sender.val() == 1){
                    $('#engine_size_min').val(0);
                    $('#engine_size_max').val(125);
                }else if(sender.val() == 2){
                    $('#engine_size_min').val(125);
                    $('#engine_size_max').val(250);
                }else if(sender.val() == 3){
                    $('#engine_size_min').val(250);
                    $('#engine_size_max').val(450);
                }else if(sender.val() == 4){
                    $('#engine_size_min').val(450);
                    $('#engine_size_max').val(600);
                }else if(sender.val() == 5){
                    $('#engine_size_min').val(600);
                    $('#engine_size_max').val(1000);
                }
            });

            var mySwiper = new Swiper ('.swiper-container', {
                // Optional parameters
                direction: 'horizontal',
                loop: true,
                
                // Navigation arrows
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',                
            });

            var featured = new Swiper ('#swiper-featured', {
                // Optional parameters
                direction: 'horizontal',
                loop: true,
                
                // Navigation arrows
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',                
            });
        });
    </script>
@endsection