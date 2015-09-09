@extends('layouts.home')

@section('head')
	<title>{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}</title>
	<meta property="og:title" content="{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}"/>

	<meta name="description" content="{{ Settings::get('listings_description') }}">
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('listings_description') }}" />
@endsection

@section('css')
	@parent

	<script type="text/javascript">
		loadCSS("{{ asset('/css/jquery/jquery-slider.min.css') }}");
        loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
        loadCSS("{{ asset('/css/components/tooltip.min.css') }}");
		loadCSS("{{ asset('/css/select2.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
	<div class="uk-panel">
		<div class="uk-panel">
			<h1 class="uk-display-inline">{{ trans('frontend.search_listings') }} <h3 id="listings_number" class="uk-display-inline"></h3></h1>

			<div class="uk-form uk-float-right uk-hidden-small">
    			<select form="search_form" name="take" onchange="getListings()" id="take">
			    	<option value="">{{ trans('admin.elements_amount') }}</option>
			    	@if(Request::get('take') == 50)
			    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
			    	@elseif(session('listings_take') == 50)
			    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
			    	@else
			    		<option value="50">{{ trans('admin.elements_50') }}</option>
			    	@endif

			    	@if(Request::get('take') == 30)
			    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
			    	@elseif(session('listings_take') == 30)
			    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
			    	@else
			    		<option value="30">{{ trans('admin.elements_30') }}</option>
			    	@endif

			    	@if(Request::get('take') == 10)
			    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
			    	@elseif(session('listings_take') == 10)
			    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
			    	@else
			    		<option value="10">{{ trans('admin.elements_10') }}</option>
			    	@endif
			    </select>

			    <select form="search_form" name="order_by" onchange="getListings()" id="order_by">
			    	<option value="0">{{ trans('admin.order_by') }}</option>
			    	@if(Request::get('order_by') && Request::get('order_by') == 'id_desc')
			    		<option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
			    	@elseif(session('listings_order_by') == 'id_desc')
			    		<option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
			    	@else
			    		<option value="id_desc">{{ trans('admin.order_newer_first')}}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'id_asc')
			    		<option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
			    	@elseif(session('listings_order_by') == 'id_asc')
			    		<option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
			    	@else
			    		<option value="id_asc">{{ trans('admin.order_older_first')}}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'price_max')
			    		<option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
			    	@elseif(session('listings_order_by') == 'price_max')
			    		<option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
			    	@else
			    		<option value="price_max">{{ trans('admin.order_expensive_first') }}</option>
			    	@endif

			    	@if(Request::get('order_by') && Request::get('order_by') == 'price_min')
			    		<option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
			    	@elseif(session('listings_order_by') == 'price_min')
			    		<option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
			    	@else
			    		<option value="price_min">{{ trans('admin.order_cheaper_first') }}</option>
			    	@endif
			    </select>
			</div>
		</div>
	    
	    <hr>

	    <div class="uk-flex uk-margin-top">
	    	<!-- Search bar for pc -->
	    	<div class="uk-width-large-1-4 uk-visible-large" style="margin-right:15px; padding-right:20px; border-right-style:solid; border-right: 1px solid #dddddd;">
				<form id="search_form" class="uk-form uk-form-stacked">

					<div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_listing_type') }}</label>
						<select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="listing_type" id="listing_type" onchange="getListings()">
	                        <option value>{{ trans('frontend.search_select_option') }}</option>
	                        @foreach($listingTypes as $type)
	                            @if($type->id == Request::get('listing_type'))
	                                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
	                            @else
	                                <option value="{{ $type->id }}">{{ $type->name }}</option>
	                            @endif
	                        @endforeach
	                    </select>
			        </div>

			        <div class="uk-form-row uk-margin-remove">
						<label class="uk-form-label">{{ trans('frontend.search_manufacturer') }}</label>
						<select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="search_manufacturer" name="manufacturers[]" multiple="multiple" onchange="getListings()">
	                        @foreach($manufacturers as $manufacturer)
	                            @if(is_array(Request::get('manufacturers')) && in_array($manufacturer->id, Request::get('manufacturers')))
	                                <option value="{{ $manufacturer->id }}" selected>{{ $manufacturer->text }}</option>
	                            @else
	                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->text }}</option>
	                            @endif
	                        @endforeach
	                    </select>
			        </div>

			        <div class="uk-form-row uk-margin-small-top">
						<label class="uk-form-label">{{ trans('frontend.search_model') }} <i class="uk-icon-info-circle" data-uk-tooltip title="{{ trans('frontend.search_models_tooltip') }}"></i></label>
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="search_models" name="models" onchange="getListings()">
			                <option value>{{ trans('frontend.search_select_option') }}</option>
			            </select>
			        </div>

			        <div class="uk-form-row uk-margin-small-top">
					  	<label for="price_range" class="uk-form-label">{{ trans('frontend.search_price') }}</label>
					  	<input type="text" id="price_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-top:-10px">
					</div>
					<div id="slider-range-price"></div>
					<input type="hidden" id="price_min" name="price_min" value="{{Request::get('price_min')}}">
					<input type="hidden" id="price_max" name="price_max" value="{{Request::get('price_max')}}">

			        <div class="uk-form-row uk-margin-small-top">
					  	<label for="engine_size_range" class="uk-form-label">{{ trans('frontend.search_engine_size') }}</label>
					  	<input type="text" id="engine_size_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-top:-10px">
					</div>
					<div id="slider_engine_size_range"></div>
					<input type="hidden" id="engine_size_min" name="engine_size_min" value="{{Request::get('engine_size_min')}}">
					<input type="hidden" id="engine_size_max" name="engine_size_max" value="{{Request::get('engine_size_max')}}">

			        <div class="uk-form-row uk-margin-small-top">
					  	<label for="year_range" class="uk-form-label">{{ trans('frontend.search_year') }}</label>
					  	<input type="text" id="year_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-top:-10px">
					</div>
					<div id="slider_year_range"></div>
					<input type="hidden" id="year_min" name="year_min" value="{{Request::get('year_min')}}">
					<input type="hidden" id="year_max" name="year_max" value="{{Request::get('year_max')}}">

			        <div class="uk-form-row uk-margin-small-top">
					  	<label for="odometer_range" class="uk-form-label">{{ trans('frontend.search_odometer') }}</label>
					  	<input type="text" id="odometer_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-top:-10px">
					</div>
					<div id="slider_odometer_range"></div>
					<input type="hidden" id="odometer_min" name="odometer_min" value="{{Request::get('odometer_min')}}">
					<input type="hidden" id="odometer_max" name="odometer_max" value="{{Request::get('odometer_max')}}">

					<div class="uk-form-row uk-margin-top">
						<label class="uk-form-label">{{ trans('frontend.search_city') }}</label>
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="search_cities" name="city_id" onchange="getListings()">
			                <option value>{{ trans('frontend.search_select_option') }}</option>
			            </select>
			        </div>

					<input class="uk-width-large-10-10 uk-margin-bottom uk-form-large uk-margin-top" type="text" name="listing_code" placeholder="{{ trans('frontend.search_code') }}" value>
				</form>
	    	</div>
	    	<!-- End search bar -->
	    	
	    	<!-- Listings space -->
	    	<div class="uk-width-large-3-4 uk-width-small-1-1">
	    		<div class="uk-panel">
	    			<!-- This is the modal -->
					<div id="loading_listings" style="position:relative;" class="uk-hidden">
					    <div style="height: 60px" class="uk-width-1-1">
					    	<div class="uk-text-center uk-margin-small-top">
					        	<i class="uk-icon-large uk-icon-spinner uk-icon-spin uk-text-primary"></i>
					        	<h3 class="uk-text-primary uk-margin-top-remove">{{ trans('frontend.loading') }}</h3>
					    	</div>
					    </div>
					</div>

					<div class="uk-grid" id="listings"></div>
				</div>
	    	</div>
	    	<!-- Listings space -->
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/jquery/jquery-slider.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet"/></noscript>
	<noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"/></noscript>
	<noscript><link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet"/></noscript>
	<!-- CSS -->

	<script type="text/javascript">
		$(function() {
		    $( "#slider-range-price" ).slider({
		      	range: true,
		      	step: 500000,
		      	min: 0,// TODO get from settings
		      	max: 30000000,// TODO get from settings

		      	@if(Request::has('price_min') && Request::has('price_max'))
					values: [{{Request::get('price_min')}}, {{Request::get('price_max')}}],
				@else
					values: [0, 30000000],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = "";
		      		if(ui.values[ 1 ] == 30000000){// TODO get from settings
		      			tag = "+";
		      		}
		        	$( "#price_range" ).val( "$" + accounting.formatNumber(ui.values[ 0 ]) + " - $" + accounting.formatNumber(ui.values[ 1 ]) + tag );
		        	$( "#price_min" ).val(ui.values[ 0 ]);
		        	$( "#price_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getListings();
		      	}
		    });
		    $( "#price_range" ).val( "$" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 0 )) +
		      	" - $" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 1 )) + "+" );

		    $( "#slider_engine_size_range" ).slider({
		      	range: true,
		      	min: 0,// TODO get from settings
		      	max: 1000,// TODO get from settings
		      	step: 25,

		      	@if(Request::has('engine_size_min') && Request::has('engine_size_max'))
					values: [{{Request::get('engine_size_min')}}, {{Request::get('engine_size_max')}}],
				@else
					values: [0, 1000],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = "cc";
		      		if(ui.values[ 1 ] == 1000){
		      			tag = "cc +";
		      		}
		        	$( "#engine_size_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + "cc - " + accounting.formatNumber(ui.values[ 1 ]) + tag);
		        	$( "#engine_size_min" ).val(ui.values[ 0 ]);
		        	$( "#engine_size_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getListings();
		      	}
		    });
		    $( "#engine_size_range" ).val( accounting.formatNumber($( "#slider_engine_size_range" ).slider( "values", 0 )) +
		      	"cc - " + accounting.formatNumber($( "#slider_engine_size_range" ).slider( "values", 1 )) + "cc +");

		    $( "#slider_year_range" ).slider({
		      	range: true,
		      	min: 1970,// TODO get from settings
		      	max: 2016,// TODO get from settings
		      	step: 1,

		      	@if(Request::has('year_min') && Request::has('year_max'))
					values: [{{Request::get('year_min')}}, {{Request::get('year_max')}}],
				@else
					values: [1970, 2016],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = "";
		      		if(ui.values[ 1 ] == 2016){
		      			tag = "+";
		      		}
		        	$( "#year_range" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] + tag);
		        	$( "#year_min" ).val(ui.values[ 0 ]);
		        	$( "#year_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getListings();
		      	}
		    });
		    $( "#year_range" ).val($( "#slider_year_range").slider( "values", 0 ) +
		      	" - " + $("#slider_year_range").slider( "values", 1 ) + "+");

		    $( "#slider_odometer_range" ).slider({
		      	range: true,
		      	min: 0,// TODO get from settings
		      	max: 50000,// TODO get from settings
		      	step: 1000,

		      	@if(Request::has('odometer_min') && Request::has('odometer_max'))
					values: [{{Request::get('odometer_min')}}, {{Request::get('odometer_max')}}],
				@else
					values: [0, 50000],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = " kms";
		      		if(ui.values[ 1 ] == 50000){
		      			tag = " kms +";
		      		}
		        	$( "#odometer_range" ).val(accounting.formatNumber(ui.values[ 0 ]) + " kms - " + accounting.formatNumber(ui.values[ 1 ]) + tag);
		        	$( "#odometer_min" ).val(ui.values[ 0 ]);
		        	$( "#odometer_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getListings();
		      	}
		    });
		    $( "#odometer_range" ).val(accounting.formatNumber($("#slider_odometer_range").slider( "values", 0 )) + " kms - " + accounting.formatNumber($("#slider_odometer_range").slider( "values", 1 )) + " kms +");


		    $("#search_cities").select2({
		    	data: {!! $cities !!}
		    });

		    $('#search_manufacturer').select2({lang:'es', placeholder: "{{ trans('frontend.search_manufacturer') }}"}).on('change', function() {
		        $('#search_models').removeClass('select2-offscreen').select2({
		        	ajax: {
					    url: "{{ url('models') }}/"+$('#search_manufacturer').val(),
					    dataType: 'json',
					    delay: 250,
					    data: function (params) {
					      return {
					        q: params.term, // search term
					        page: params.page
					      };
					    },
					    processResults: function (data, page) {
					      return {
					        results: data
					      };
					    },
					    cache: true
					  },
		        });
		    }).trigger('change');

	  	});

		var page = 1;

		function getListings(paginate){
			$("#loading_listings").slideDown(500).removeClass('uk-hidden');

			paging = null;
			if(paginate){
				$('#load_button').html('<i class="uk-icon-spinner uk-icon-spin"></i>');
				paging = page;
			}else{
				page = 1;
			}
			$.get("{{ url('/api/listings') }}", {   manufacturers: $('#search_manufacturer').val(),
													models: $('#search_models').val(),
													listing_type: $('#listing_type').val(),
													price_min: $('#price_min').val(),
													price_max: $('#price_max').val(),
													engine_size_min: $('#engine_size_min').val(),
													engine_size_max: $('#engine_size_max').val(),
													year_min: $('#year_min').val(),
													year_max: $('#year_max').val(),
													odometer_min: $('#odometer_min').val(),
													odometer_max: $('#odometer_max').val(),
													city_id: $('#search_cities').val(),
													take:  $('#take').val(),
													order_by: $('#order_by').val(),
													page: paging,
													_token: "{{ csrf_token() }}", 
												}, 
			function(response){
				$("#loading_listings").slideUp(500);
				if(response && !paginate){
					$('#listings').html('');
				}else{
					$('#load_button').remove();
				}
				if(response.data.length > 0){
					$('#listings_number').html("("+response.total+" encontrados)")
					jQuery.each(response.data , function(index, listing){
						var view = '<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom"><a href="'+listing.url+'" style="text-decoration:none"><div class="uk-panel uk-panel-hover uk-margin-remove"><img src="'+listing.image_url+'" style="width:380px; float:left" class="uk-margin-right">'+listing.tag+'<div class=""><p class=""><strong class="uk-text-primary">'+listing.title+'</strong><br><b class="uk-text-bold">$'+accounting.formatNumber(listing.price)+'</b> | <i class="uk-text-muted">'+accounting.formatNumber(listing.odometer)+' kms</i></p></div></div></a></div>'
					    $('#listings').append(view);
					});
					if(response.total > response.to){
						loadMore = '<div class="uk-width-1-1"><button onclick="page++;getListings(true);" class="uk-button uk-button-large uk-button-primary uk-align-center" id="load_button">{{ trans("frontend.load_more") }}</button></div>';
						$('#listings').append(loadMore);
					}else{
						var view = '<div class="uk-text-center uk-width-1-1"><h3 class="uk-text-muted">{{ trans('frontend.no_listings_left') }}</h3></div>'
						$('#listings').append(view);
					}
				}else if(paginate){
					var view = '<div class="uk-text-center uk-width-1-1"><h3 class="uk-text-muted">{{ trans('frontend.no_listings_left') }}</h3></div>'
					$('#listings').append(view);
				}else{
					var view = '<div class="uk-text-center uk-width-1-1"><h3 class="uk-text-primary">{{ trans('frontend.sorry') }}<br>{{ trans('frontend.no_listings_found') }}</h3><h4>{{ trans('frontend.try_other_parameters') }}</h4></div>'
					$('#listings').append(view);
				}
            });
		}
	</script>

	<!-- JS -->
	<script src="{{ asset('/js/jquery/jquery-slider.min.js') }}"></script>
	<script src="{{ asset('/js/components/slideset.min.js') }}"></script>
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<!-- JS -->
@endsection