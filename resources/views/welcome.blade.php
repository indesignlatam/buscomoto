@extends('layouts.home')

@section('head')
    <title>{{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
    <meta property="og:image" content="{{ asset('/images/defaults/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('site_description') }}"/>
@endsection

@section('css')
	@parent
    <script type="text/javascript">
        loadCSS("{{ asset('/css/jquery/jquery-slider.min.css') }}");
        loadCSS("{{ asset('/css/select2front.min.css') }}");
        loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
    </script>
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')

	<div class="uk-cover-background uk-position-relative" style="background-image:url('{{ asset('/images/fp/search_bg.jpg') }}'); height: 550px">
        <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-visible-small">
            <h1 class="uk-text-contrast uk-text-bold">{{ trans('frontend.mobile_greeting') }}</h1>
        </div>
	    <div class="uk-position-cover uk-hidden-small">
            <div class="uk-panel uk-panel-box uk-panel-box-primary uk-width-3-10" style="position:absolute; top: 5%; left:10%">
                <h1 class="uk-text-primary uk-text-bold">{{ strtoupper(trans('frontend.search_intro')) }}</h1>
                <form id="search_form" class="uk-form uk-form-stacked" method="GET" action="{{ url('/buscar') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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

                    <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="engine_size" onchange="setEngineSize(this)">
                        <option value>{{ trans('frontend.search_engine_size') }}</option>
                        <option value="1">0cc - 125cc</option>
                        <option value="2">125cc - 250cc</option>
                        <option value="3">250cc - 450cc</option>
                        <option value="4">450cc - 600cc</option>
                        <option value="5">600cc o más</option>
                    </select>

                    <div>
                        <label for="price_range" class="uk-form-label">{{ trans('frontend.search_price') }}</label>
                        <input type="text" id="price_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-top: -10px;">
                    </div>
                    <div id="slider-range-price" class="uk-margin-bottom"></div>
                    <input type="hidden" id="price_min" name="price_min" value="{{Request::get('price_min')}}">
                    <input type="hidden" id="price_max" name="price_max" value="{{Request::get('price_max')}}">

                    <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="search_manufacturer" name="manufacturers[]" multiple="multiple">
                        @foreach($manufacturers as $manufacturer)
                            @if(is_array(Request::get('manufacturers')) && in_array($manufacturer->id, Request::get('manufacturers')))
                                <option value="{{ $manufacturer->id }}" selected>{{ $manufacturer->text }}</option>
                            @else
                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->text }}</option>
                            @endif
                        @endforeach
                    </select>

                    <button form="search_form" id="search_button" type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-small-top">{{ trans('frontend.search_button') }}</button>
                </form>
                <div class="uk-grid uk-margin-top uk-margin-bottom-remove uk-grid-small">
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=73') }}"><img src="{{ asset('images/logos/mvagusta.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=12') }}"><img src="{{ asset('images/logos/bmw.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=51') }}"><img src="{{ asset('images/logos/ktm.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=110') }}"><img src="{{ asset('images/logos/yamaha.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=48') }}"><img src="{{ asset('images/logos/kawasaki.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                    <div class="uk-width-1-6">
                        <a href="{{ url('/buscar?manufacturers[]=73') }}"><img src="{{ asset('images/logos/honda.jpg') }}" class="uk-align-center uk-margin-remove"></a>
                    </div>
                </div>
            </div>

	    </div>
	</div>

	<div class="uk-container uk-container-center uk-margin">
        <!-- Search for mobile devices -->
        <div class="uk-visible-small">
            <h3 class="uk-text-primary">{{trans('frontend.search_intro')}}</h3>
            <form id="mobile_search_form" class="uk-form" method="GET" action="{{ url('/buscar') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="engine_size">
                    <option value>{{ trans('frontend.search_engine_size') }}</option>
                    @foreach($engineSizes as $size)
                        @if($size->id == Request::get('engine_size'))
                            <option value="{{ $size->id }}" selected>{{ $size->name }}</option>
                        @else
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="price_range">
                    <option value>{{ trans('frontend.search_price') }}</option>
                    @foreach($priceRanges as $range)
                        @if($range['id'] == Request::get('price_range'))
                            <option value="{{ $range['id'] }}" selected>{{ $range['name'] }}</option>
                        @else
                            <option value="{{ $range['id'] }}">{{ $range['name'] }}</option>
                        @endif
                    @endforeach
                </select>

                 <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="search_manufacturer_mobile" name="manufacturers" multiple="multiple">
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
        </div>
        <!-- Search for mobile devices end -->

        <!-- latest listings on turism-->
        @if(count($turism))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_turism') }}</h1>

            <div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
                <ul class="uk-grid uk-slideset">
                    @foreach($turism as $bike)
                    <li>
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
                        </a>
                        <div>
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $bike->area }} mt2 - {{ money_format('$%!.0i', $bike->price) }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
            </div>
            <div class="uk-panel">
                <a href="{{ url('ventas') }}" class="uk-button uk-float-right">{{ trans('admin.view_more_listings') }}</a>
            </div>
            <hr>
        @endif
        <!-- latest listings on turism -->

        <!-- latest listings on sport/naked -->
        @if(count($sport))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_naked') }}</h1>

            <div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
                <ul class="uk-grid uk-slideset">
                    @foreach($sport as $bike)
                    <li>
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
                        </a>
                        <div>
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $bike->area }} mt2 - {{ money_format('$%!.0i', $bike->price) }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
            </div>
            <div class="uk-panel">
                <a href="{{ url('ventas') }}" class="uk-button uk-float-right">{{ trans('admin.view_more_listings') }}</a>
            </div>
            <hr>
        @endif
        <!-- latest listings on sport/naked -->

        <!-- latest listings on street -->
        @if(count($street))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_street') }}</h1>

            <div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
                <ul class="uk-grid uk-slideset">
                    @foreach($street as $bike)
                    <li>
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
                        </a>
                        <div>
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $bike->area }} mt2 - {{ money_format('$%!.0i', $bike->price) }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
            </div>
            <div class="uk-panel">
                <a href="{{ url('ventas') }}" class="uk-button uk-float-right">{{ trans('admin.view_more_listings') }}</a>
            </div>
            <hr>
        @endif
        <!-- latest listings on street -->

        <!-- latest listings on cross -->
        @if(count($cross))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_cross') }}</h1>

            <div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
                <ul class="uk-grid uk-slideset">
                    @foreach($cross as $bike)
                    <li>
                        <a href="{{ url($bike->path()) }}">
                            <img src="{{ asset(Image::url($bike->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
                        </a>
                        <div>
                            <a href="{{ url($bike->path()) }}">{{ $bike->title }}</a>
                            <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $bike->area }} mt2 - {{ money_format('$%!.0i', $bike->price) }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
            </div>
            <div class="uk-panel">
                <a href="{{ url('ventas') }}" class="uk-button uk-float-right">{{ trans('admin.view_more_listings') }}</a>
            </div>
            <hr>
        @endif
        <!-- latest listings on cross -->

        
    </div>

    <!-- Register and publish -->
    <div class="uk-block uk-block-secondary" style="background-color:#00a99d; background-image: url('{{ asset('images/fp/icons_bg.png') }}');">
        <div class="uk-container uk-container-center">
            <h1 class="uk-text-bold uk-text-contrast uk-text-center" style="margin-top:-10px; margin-bottom:30px;">{{ trans('frontend.register_publish_title') }}</h1>

            <div class="uk-grid">
                <div class="uk-width-large-1-3 uk-width-medium-1-3 uk-text-center">
                    <img src="{{ asset('images/fp/icon_1.png') }}" class="uk-border-circle" style="max-width:160px">
                    <h2 class="uk-text-bold uk-text-contrast uk-margin-top-remove uk-h1">{{ trans('frontend.free') }}</h2>
                    <p style="max-width:200px" class="uk-align-center uk-contrast">{{ trans('frontend.free_text') }}</p>
                </div>
                <div class="uk-width-large-1-3 uk-width-medium-1-3 uk-text-center">
                    <img src="{{ asset('images/fp/icon_2.png') }}" class="uk-border-circle" style="max-width:160px">
                    <h2 class="uk-text-bold uk-text-contrast uk-margin-top-remove uk-h1">{{ trans('frontend.easy') }}</h2>
                    <p style="max-width:200px" class="uk-align-center uk-contrast">{{ trans('frontend.easy_text') }}</p>
                </div>
                <div class="uk-width-large-1-3 uk-width-medium-1-3 uk-text-center">
                    <img src="{{ asset('images/fp/icon_3.png') }}" class="uk-border-circle" style="max-width:160px">
                    <h2 class="uk-text-bold uk-text-contrast uk-margin-top-remove uk-h1">{{ trans('frontend.efective') }}</h2>
                    <p style="max-width:200px" class="uk-align-center uk-contrast">{{ trans('frontend.efective_text') }}</p>
                </div>
            </div>
            
            <div class="uk-text-center uk-margin-top">
                @if(!Auth::check())
                    <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large" style="background-color:#444">{{ trans('admin.register_publish_free') }}</a>
                @else
                    <a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-primary uk-button-large" style="background-color:#444">{{ trans('admin.publish_listing') }}</a>
                @endif
            </div>
        </div>
    </div>
    <!-- Register and publish -->

    <div class="uk-container uk-container-center uk-margin-top">
        <!-- Featured listings -->
        @if(count($featured) > 0)
            <h1 class="uk-margin-bottom uk-margin-top uk-text-bold">{{ trans('frontend.featured_listing') }}</h1>

            <div class="uk-grid uk-grid-small uk-margin" data-uk-grid-margin data-uk-grid-match="featured">
            <?php $i = 0; ?>
            @foreach ($featured as $featuredListing)
                @if($i == 4)
                <div class="uk-width-medium-2-3 uk-width-large-2-3 featured">
                    <div class="uk-overlay uk-overlay-hover uk-margin-small">
                        <img src="{{ asset(Image::url( $featuredListing->image_path(), ['featured_mosaic_large']) ) }}" alt="{{$featuredListing->title}}" data-uk-scrollspy="{cls:'uk-animation-fade'}">
                @else
                <div class="uk-width-medium-1-3 uk-width-large-1-3 featured">
                    <div class="uk-overlay uk-overlay-hover uk-margin-small">
                        <img src="{{ asset(Image::url( $featuredListing->image_path(), ['featured_mosaic']) ) }}" alt="{{$featuredListing->title}}" data-uk-scrollspy="{cls:'uk-animation-fade'}">
                @endif
                        <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-vertical-align">
                            <div class="uk-vertical-align-middle">
                                <h3 class="uk-text-bold uk-h2">{{ $featuredListing->title }}</h3>
                                <h3 class="uk-text-bold uk-h2 uk-margin-remove">{{ money_format('$%!.0i', $featuredListing->price) }}</h3>
                            </div>
                        </div>
                        <a class="uk-position-cover" href="{{ url($featuredListing->path()) }}"></a>
                    </div>
                </div>
                <?php $i++; ?>
            @endforeach        
            </div>
        @endif
        <!-- Featured listings -->
    </div>
@endsection

@section('js')
	@parent

    <noscript><link href="{{ asset('/css/jquery/jquery-slider.min.css') }}" rel="stylesheet"></noscript>
    <noscript><link href="{{ asset('/css/select2front.min.css') }}" rel="stylesheet"/></noscript>
    <noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"/></noscript>
    <script src="{{ asset('/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/components/slideset.min.js') }}"></script>
    <script src="{{ asset('/js/jquery/jquery-slider.min.js') }}"></script>
    <script src="{{ asset('/js/accounting.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#search_manufacturer").select2({
                placeholder: "{{ trans('frontend.search_manufacturer') }}",
                lang: 'es',
                maximumSelectionLength: 10,
            });

            $("#search_manufacturer_mobile").select2({
                placeholder: "{{ trans('frontend.search_manufacturer') }}",
                lang: 'es',
                maximumSelectionLength: 10,
            });

            $( "#slider-range-price" ).slider({
                range: true,
                step: 1000000,
                min: 0,// TODO get from settings
                max: 50000000,// TODO get from settings

                @if(Request::has('price_min') && Request::has('price_max'))
                    values: [{{Request::get('price_min')}}, {{Request::get('price_max')}}],
                @else
                    values: [0, 50000000],// TODO get from settings
                @endif
                slide: function( event, ui ) {
                    tag = "";
                    if(ui.values[ 1 ] == 50000000){// TODO get from settings
                        tag = "+";
                    }
                    $( "#price_range" ).val( "$" + accounting.formatNumber(ui.values[ 0 ]) + " - $" + accounting.formatNumber(ui.values[ 1 ]) + tag );
                    $( "#price_min" ).val(ui.values[ 0 ]);
                    $( "#price_max" ).val(ui.values[ 1 ]);
                },
                change: function(){
                    //getMarkers(true);
                }
            });
            $( "#price_range" ).val( "$" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 0 )) +
                " - $" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 1 )) + "+" );
        });

        function setEngineSize(sender){
            if(sender.value == 1){
                $('#engine_size_min').val(0);
                $('#engine_size_max').val(125);
            }else if(sender.value == 2){
                $('#engine_size_min').val(125);
                $('#engine_size_max').val(250);
            }else if(sender.value == 3){
                $('#engine_size_min').val(250);
                $('#engine_size_max').val(450);
            }else if(sender.value == 4){
                $('#engine_size_min').val(450);
                $('#engine_size_max').val(600);
            }else if(sender.value == 5){
                $('#engine_size_min').val(600);
                $('#engine_size_max').val(1000);
            }
        }
    </script>
@endsection