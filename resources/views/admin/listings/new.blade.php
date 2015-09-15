@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.new_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.new_listing') }}</h1>

	    <hr>
	    
	    <div class="uk-panel">
			<button class="uk-button uk-button-large uk-float-right uk-margin-left uk-width-small-1-1 uk-width-medium-2-10 uk-width-large-1-10" onclick="leave()">{{ trans('admin.close') }}</button>
	        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-float-right uk-width-small-1-1 uk-width-medium-3-10 uk-width-large-2-10" onclick="blockUI()">{{ trans('admin.save') }}</button>
	    </div>

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/listings') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="uk-grid uk-margin-top">

				<!-- Categoria - tipo de publicacion - ubicacion -->
				<div class="uk-width-1-1" id="1">
					<div class="uk-panel uk-margin-bottom">
						<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_data_location') }}</h2>
					</div>

					<div class="uk-grid">
						<div class="uk-width-large-1-2">

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.listing_type') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_type_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="listing_type" name="listing_type">
						                <option value="">{{ trans('admin.select_option') }}</option>
				                        @foreach($listingTypes as $type)
				                            @if(old('listing_type') == $type->id)
				                                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
				                            @else
				                                <option value="{{ $type->id }}">{{ $type->name }}</option>
				                            @endif
				                        @endforeach
						            </select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.manufacturer') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.manufacturer_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="manufacturers" type="text" name="manufacturer_id">
						                <option value="">{{ trans('admin.select_option') }}</option>
						                @foreach($manufacturers as $manufacturer)
						                	@if(old('manufacturer_id') == $manufacturer->id)
												<option value="{{ $manufacturer->id }}" selected>{{ ucwords(strtolower($manufacturer->text)) }}</option>
						                	@else
						                		<option value="{{ $manufacturer->id }}">{{ ucwords(strtolower($manufacturer->text)) }}</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.model') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.model_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="models" type="text" name="model_id" style="width:100%">	
						                <option value="{{ old('model_id') }}">{{ trans('admin.select_option') }}</option>
					            	</select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.fuel') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.fuel_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" type="text" name="fuel_type">
						                @foreach($fuels as $fuel)
						                	@if(old('fuel_type') == $fuel->id)
												<option value="{{ $fuel->id }}" selected>{{ $fuel->name }}</option>
						                	@elseif(1 == $fuel->id)
						                		<option value="{{ $fuel->id }}" selected>{{ $fuel->name }}</option>
						                	@else
						                		<option value="{{ $fuel->id }}">{{ $fuel->name }}</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>
						</div>

						<div class="uk-width-large-1-2">

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.city') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.city_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="cities" type="text" name="city_id">
						                <option value="">{{ trans('admin.select_option') }}</option>
						                @foreach($cities as $city)
						                	@if(old('city_id') == $city->id)
												<option value="{{ $city->id }}" selected>{{ $city->name }} ({{ $city->department->name }})</option>
						                	@else
						                		<option value="{{ $city->id }}">{{ $city->name }} ({{ $city->department->name }})</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.district') }} <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.district_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="district" value="{{ old('district') }}" placeholder="{{ trans('admin.district') }}">
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.transmission') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.transmission_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" type="text" name="transmission_type">	
						                <option value="">{{ trans('admin.select_option') }}</option>
						                @foreach($transmissions as $transmission)
						                	@if(old('transmission_type') == $transmission->id)
												<option value="{{ $transmission->id }}" selected>{{ $transmission->name }}</option>
						                	@elseif(2 == $transmission->id)
						                		<option value="{{ $transmission->id }}" selected>{{ $transmission->name }}</option>
						                	@else
						                		<option value="{{ $transmission->id }}">{{ $transmission->name }}</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>
						</div>					    
					</div>
					<!-- Categoria - tipo de publicacion - ubicacion -->

					<hr>

					<div class="uk-grid uk-margin-top-remove" id="3">
						<!-- Informacion basica del inmueble -->
						<div class="uk-panel uk-width-1-1 uk-margin-bottom">
							<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_basic_information') }}</h2>
						</div>

						<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.price') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="price" type="text" name="price" placeholder="{{ trans('admin.price') }}" value="{{ old('price') }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.year') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.year_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="year" placeholder="{{ trans('admin.year') }}" value="{{ old('year') }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.license_number') }} <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.license_number_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="license_number" placeholder="{{ trans('admin.license_number') }}" value="{{ old('license_number') }}">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.odometer') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="odometer" type="text" name="odometer" placeholder="{{ trans('admin.odometer') }}" value="{{ old('odometer') }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.color') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" id="color" type="text" name="color" placeholder="{{ trans('admin.color') }}" value="{{ old('color') }}">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.engine_size') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.engine_size_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="engine_size" type="text" name="engine_size" placeholder="{{ trans('admin.engine_size') }}" value="{{ old('engine_size') }}" onkeyup="format(this);">
							</div>
						</div>
					</div>
					<!-- Informacion basica del inmueble -->

					<hr>

					<!-- Caracteristicas del inmueble -->
					<div id="4">
						<div class="uk-panel">
							<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_caracteristics') }}</h2>
						</div>

						<h3>{{ trans('admin.security') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 1)
									<div class="uk-width-large-1-3 uk-width-1-2">
										@if( old($feature->id) )
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.generals') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 3)
									<div class="uk-width-large-1-3 uk-width-1-2">
										@if( old($feature->id) )
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.accesories') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 4)
									<div class="uk-width-large-1-3 uk-width-1-2">
										@if( old($feature->id) )
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif
									</div>
								@endif
							@endforeach
						</div>
					</div>
					<!-- Caracteristicas del inmueble -->

					<hr>

					<!-- Informacion adicional -->
					<div id="5">
						<div class="uk-panel">
							<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_description') }}</h2>
						</div>
						<p class="uk-margin-top-remove">{{ trans('admin.listing_description_help') }}</p>
						<textarea class="uk-width-large-10-10 uk-margin-small-bottom" id="description" rows="5" name="description" maxlength="2000" onkeyup="calculatePoints('description')">{{ old('description') }}</textarea>
					</div>
					<!-- Informacion adicional -->

					<hr>

					<!-- Images -->
					<div>
						<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.images') }}</h2>
						<h3>{{ trans('admin.images_notice') }}</h3>
					</div>
					<!-- Images -->

					<div class="uk-margin-top">
				        <button form="create_form" type="submit" class="uk-button uk-width-1-1 uk-button-large uk-button-success" onclick="blockUI()">{{ trans('admin.save') }}</button>
				    </div>
			    </div>
			</div>
		</form>

	</div>
</div>

<!-- Email confirmation message sent modal -->
@if(Session::get('new_user'))
	<div id="confirmation_email_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.confirm_email') }}
	        </div>

	        <div class="uk-text-center">
	        	<img src="{{ asset('images/support/user/welcome.png') }}" style="width:80%">

	        	<h3>{{ trans('admin.welcome_new_user') }}</h3>

	        	<a class="uk-button uk-button-large uk-button-success" id="open" href="" target="_blank">{{ trans('admin.open') }}</a>
	        </div>
	    </div>
	</div>
@endif
<!-- Email confirmation message sent modal -->

@endsection

@section('js')
	@parent

	<!-- CSS -->
	<link href="{{ asset('/css/components/tooltip.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<!-- CSS -->

	<!-- JS -->
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<!-- JS -->

	<script type="text/javascript">
		$(document).ready(function() {

			$('#manufacturers').select2({lang:'es'}).on('change', function() {
		        $('#models').removeClass('select2-offscreen').select2({
		        	ajax: {
					    url: "{{ url('models') }}/"+$('#manufacturers').val(),
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

		  	$("#cities").select2();

			@if(Session::pull('new_user'))
				$("#open").html('{{ trans('admin.open') }}'+' '+emailProvider('{{ Auth::user()->email }}'));
		  		$("#open").attr("href", "http://"+emailProvider('{{ Auth::user()->email }}'));

			  	var modal = UIkit.modal("#confirmation_email_modal");
				modal.show()
			@endif
		});

		function blockUI(){
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">{{ trans('admin.saving_wait') }}</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>', {center: true});
	    }

	    function leave() {
	    	UIkit.modal.confirm("{{ trans('admin.sure_leave') }}", function(){
			    window.location.replace("{{ url('/admin/listings') }}");
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

		function format(field){
	        field.value = accounting.formatNumber(field.value);
	    }

	    function emailProvider(str){
	    	var afterComma = str.substr(str.indexOf("@") + 1);
	    	return afterComma;
	    }
	</script>
@endsection