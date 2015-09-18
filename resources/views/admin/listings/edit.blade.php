@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.edit_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.edit_listing') }}</h1>

	    <hr>
	    
	    <div class="uk-panel">
			<button class="uk-button uk-button-large uk-width-small-1-1 uk-width-medium-2-10 uk-width-large-1-10 uk-align-right" onclick="leave()">{{ trans('admin.close') }}</button>
			<button form="create_form" type="submit" class="uk-button uk-button-large uk-width-small-1-1 uk-width-medium-3-10 uk-width-large-2-10 uk-align-right" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
	        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-width-small-1-1 uk-width-medium-3-10 uk-width-large-2-10 uk-align-right" onclick="blockUI()">{{ trans('admin.save') }}</button>
	    </div>

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/listings/'.$listing->id) }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">
        	<input type="hidden" name="save_close" value="0" id="save_close">

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
				                            @if($listing->listingType->id == $type->id)
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
						                	@if($listing->manufacturer->id == $manufacturer->id)
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
						                <option value="{{ $listing->model->id }}">{{ $listing->model->name }}</option>
					            	</select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.fuel') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.fuel_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" type="text" name="fuel_type">
						                <option value="">{{ trans('admin.select_option') }}</option>
						                @foreach($fuels as $fuel)
						                	@if($listing->fuelType->id == $fuel->id)
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
						                	@if($listing->city->id == $city->id)
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
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="district" value="{{ $listing->district }}" placeholder="{{ trans('admin.district') }}">
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.transmission') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.transmission_tooltip') }}"></i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" type="text" name="transmission_type">	
						                <option>{{ trans('admin.select_option') }}</option>
						                @foreach($transmissions as $transmission)
						                	@if($listing->transmissionType->id == $transmission->id)
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
								<input class="uk-width-large-10-10 uk-form-large" id="price" type="text" name="price" placeholder="{{ trans('admin.price') }}" value="{{ $listing->price }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.year') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.year_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="year" placeholder="{{ trans('admin.year') }}" value="{{ $listing->year }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.license_number') }} <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.license_number_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="license_number" placeholder="{{ trans('admin.license_number') }}" value="{{ $listing->license_number }}">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.odometer') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="odometer" type="text" name="odometer" placeholder="{{ trans('admin.odometer') }}" value="{{ $listing->odometer }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.color') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="color" placeholder="{{ trans('admin.color') }}" value="{{ $listing->color }}">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.engine_size') }} <i class="uk-text-danger">*</i> <i class="uk-icon-info-circle" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.engine_size_tooltip') }}"></i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="engine_size" type="text" name="engine_size" placeholder="{{ trans('admin.engine_size') }}" value="{{ $listing->engine_size }}" onkeyup="format(this);">
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
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
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
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
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
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
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
						<textarea class="uk-width-large-10-10 uk-margin-small-bottom" id="description" rows="5" name="description" maxlength="2000">{{ $listing->description }}</textarea>
					</div>
					<!-- Informacion adicional -->

					<hr>

					<!-- Image upload -->
					<div id="6">
						<div class="uk-panel">
							<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.images') }}</h2>
						</div>

						<p class="uk-margin-remove">{{ trans('admin.add_images_to_listing') }} {{ trans('admin.order_images') }}</p>

				    	<div id="upload-drop" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top">
						    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_listing_images_or') }} <a class="uk-form-file uk-text-primary">{{ trans('admin.select_an_image') }}<input id="upload-select" type="file" multiple></a>
						</div>

						<div id="progressbar" class="uk-progress uk-hidden">
						    <div class="uk-progress-bar" style="width: 0%;"></div>
						</div>

						<ul class="uk-sortable uk-margin-large-top uk-grid" data-uk-sortable="{handleClass:'uk-panel'}" id="images-div">
						@foreach($listing->images->sortBy('ordering') as $image)
							<li data-id="{{ $image->id }}" class="uk-width-large-1-4 uk-width-medium-1-3 uk-panel uk-margin-small-bottom" id="image-{{ $image->id }}">
								<i class="uk-close uk-close-alt uk-panel-badge" id="{{ $image->id }}" onclick="deleteImage(this)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminate_image') }}"></i>
								<input type="hidden" name="image[{{ $image->id }}]" value="{{ $image->ordering }}">
						    	<img src="{{ asset($image->image_path) }}">
						    	<div class="uk-badge uk-badge-notification uk-panel-badge" style="right:40%;">0</div>
							</li>
						@endforeach
						</ul>
				    </div>
				    <!-- Image upload -->

				    <hr>

				    <!-- Share listing -->
				    <div class="uk-margin-large-bottom" id="7">
				    	<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.share_social') }}</h2>

						<div class="uk-flex">
							<a onclick="share('{{ url($listing->path()) }}')" class="uk-icon-button uk-icon-facebook uk-margin-right"></a>
	        				<a class="uk-icon-button uk-icon-twitter twitter-share-button uk-margin-right" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
	    					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus uk-margin-right"></a>
				            <a href="#send_mail" class="uk-icon-button uk-icon-envelope" onclick="setListing({{ $listing->id }})" data-uk-modal="{center:true}"></a>
						</div>
				    </div>
				    <!-- Share listing -->

				    @if(!Agent::isMobile())
					<div class="uk-margin-top uk-flex">
				        <!-- This is a button toggling the modal -->
				        <button form="create_form" type="submit" class="uk-width-1-3 uk-margin-right uk-button uk-button-large uk-button-success uk-text-bold uk-margin-bottom" onclick="blockUI()">{{ trans('admin.save') }}</button>
				        <button form="create_form" type="submit" class="uk-width-1-3 uk-margin-right uk-button uk-button-large uk-text-bold uk-margin-bottom" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
				        <a class="uk-width-1-3 uk-button uk-button-large uk-text-bold uk-margin-bottom" target="_blank" href="{{ url($listing->path())}}">{{ trans('admin.view_listing') }}</a>
				    </div>
				    @else
				    <div class="uk-margin-top">
				        <!-- This is a button toggling the modal -->
				        <button form="create_form" type="submit" class="uk-width-1-1 uk-button uk-button-large uk-button-success uk-text-bold uk-margin-small-bottom" onclick="blockUI()">{{ trans('admin.save') }}</button>
				        <button form="create_form" type="submit" class="uk-width-1-1 uk-button uk-button-large uk-text-bold uk-margin-small-bottom" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
				        <a class="uk-width-1-1 uk-button uk-button-large uk-text-bold uk-margin-bottom" target="_blank" href="{{ url($listing->path())}}">{{ trans('admin.view_listing') }}</a>
				    </div>
				    @endif
			    </div>
			</div>
		</form>

	</div>
</div>

@if($listing->featured_expires_at && $listing->featured_expires_at < Carbon::now()->addDays(5) && $listing->featured_expires_at > Carbon::now())
	<!-- This is the modal -->
	<div id="expires_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.listing_expiring_soon') }}
	        </div>

	        <div class="uk-text-center">
	        	<img src="{{ asset('/images/support/listings/expiring_listing.png') }}" style="max-width:80%">

	        	@if($listing->featured_expires_at < Carbon::now())
	        		<h2 class="uk-text-danger">{{ trans('admin.listing_featured_expired') }} {{ $listing->featured_expires_at->diffForHumans() }}</h2>
				@else
	        		<h2 class="uk-text-danger">{{ trans('admin.listing_featured_expires') }} {{ $listing->featured_expires_at->diffForHumans() }}</h2>
				@endif

		        <a class="uk-button uk-button-large uk-button-success" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a>
	        </div>
	    </div>
	</div>
@elseif($listing->expires_at && $listing->expires_at < Carbon::now()->addDays(5))
	<div id="expires_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.listing_expiring_soon') }}
	        </div>

	        <div class="uk-text-center">
	        	<img src="{{ asset('/images/support/listings/expiring_listing.png') }}" style="max-width:80%">

		        @if($listing->expires_at < Carbon::now())
	        		<h2 class="uk-text-danger">{{ trans('admin.listing_expired') }} {{ $listing->expires_at->diffForHumans() }}</h2>
				@else
	        		<h2 class="uk-text-danger">{{ trans('admin.listing_expires') }} {{ $listing->expires_at->diffForHumans() }}</h2>
				@endif

		        <a class="uk-button uk-button-large uk-button-success" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a>
	        </div>
	        
	    </div>
	</div>
@endif

@if(!count($listing->images))
	<!-- This is the modal -->
	<div id="upload_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.add_images_to_listing') }}
	        </div>

	        <p>{{ trans('admin.no_images_text') }}</p>

	        <div class="uk-grid uk-grid-collapse">
	        	<div class="uk-width-1-1">
	        		<div id="upload_drop_modal" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top uk-hidden-small">
					    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_listing_images_or') }} 
					</div>

					<a class="uk-form-file uk-text-primary uk-text-bold uk-h3 uk-visible-small">{{ ucfirst(trans('admin.select_an_image_mobile')) }}<input id="upload_select_modal" type="file" multiple></a>

					<div id="progressbar_modal" class="uk-progress uk-hidden">
					    <div class="uk-progress-bar" style="width: 0%;"></div>
					</div>
	        	</div>
	        </div>

	        <div class="uk-margin-large-top uk-grid" id="images_div_modal">
						
			</div>

		    <div class="uk-modal-footer uk-hidden-small">
		    	<a href="" class="uk-button uk-button-success uk-modal-close">{{ trans('admin.save') }}</a>
		    </div>

		    <div class="uk-modal-footer uk-visible-small">
		    	<a id="image_modal_save" href="" class="uk-button uk-button-success uk-button-large uk-width-1-1 uk-modal-close uk-hidden">{{ trans('admin.save') }}</a>
		    </div>
	    </div>
	</div>
@endif
@include('modals.email_listing')

@endsection

@section('js')
	@parent

	<!-- CSS -->
    <link href="{{ asset('/css/components/sortable.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/form-file.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/upload.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/placeholder.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/progress.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('/css/selectize.min.css') }}" rel="stylesheet"/>
	<!-- CSS -->

	<!-- JS -->
    <script src="{{ asset('/js/components/sortable.min.js') }}"></script>
	<script src="{{ asset('/js/components/upload.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<script src="{{ asset('/js/selectize.min.js') }}"></script>
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
		});

		var sortable = null;
		$(function() {
			sortable = $('[data-uk-sortable]');
            sortable.on('stop.uk.sortable', function (e, el, type) {
                setOrdering(sortable, el);
            });
            setOrdering(sortable);

			$("#city").select2();

			$('#price').val(accounting.formatNumber($('#price').val()));
			$('#odometer').val(accounting.formatNumber($('#odometer').val()));
			$('#engine_size').val(accounting.formatNumber($('#engine_size').val()));

		@if($listing->featured_expires_at && $listing->featured_expires_at < Carbon::now()->addDays(5))
			var modal = UIkit.modal("#expires_modal");
			modal.show()
		@elseif($listing->expires_at && $listing->expires_at < Carbon::now()->addDays(5))
			var modal = UIkit.modal("#expires_modal");
			modal.show()
		@elseif(!count($listing->images))
			var modal = UIkit.modal("#upload_modal");
			modal.show()
		@endif

			var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                  '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

			$('#emails').selectize({
			    persist: false,
			    maxItems: 5,
			    valueField: 'email',
			    labelField: 'name',
			    searchField: ['name', 'email'],
			    options: [],
			    render: {
			        item: function(item, escape) {
			            return '<div>' +
			                (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
			                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
			            '</div>';
			        },
			        option: function(item, escape) {
			            var label = item.name || item.email;
			            var caption = item.name ? item.email : null;
			            return '<div>' +
			                '<span class="label">' + escape(label) + '</span>' +
			                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
			            '</div>';
			        }
			    },
			    createFilter: function(input) {
			        var match, regex;

			        // email@address.com
			        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[0]);

			        // name <email@address.com>
			        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[2]);

			        return false;
			    },
			    create: function(input) {
			        if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
			            return {email: input};
			        }
			        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
			        if (match) {
			            return {
			                email : match[2],
			                name  : $.trim(match[1])
			            };
			        }
			        alert('Correo electrónico invalido.');
			        return false;
			    }
			});

		});

		function setOrdering(sortable, activeEl) {
            var ordering = 1;
            sortable.find('>li').each(function () {
                var $ele = $(this);
                $ele.data('ordering', ordering);
                $ele.find('div.uk-badge').text(ordering);
                $ele.find('input[type=hidden]').val(ordering);
                ordering++;
            });
            if (activeEl) {
                activeEl.find('div.uk-badge').addClass('uk-animation-scale-down');
            }
        }
  		

		function blockUI(){
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">{{ trans('admin.saving_wait') }}</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>', {center: true});
	    }

		function format(field){
			if(field.value){
				field.value = accounting.formatNumber(field.value);
			}
	    }

        function deleteImage(sender, modal) {
	        $.post("{{ url('/admin/images') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	        	if(result.success){
	        		$("#image-"+sender.id).fadeOut(500, function() { $(this).remove(); setOrdering(sortable); });
	        		if(modal){
	            		$("#image-modal-"+sender.id).fadeOut(500, function() { $(this).remove(); setOrdering(sortable); });
	        		}
	        		
	                UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
	        	}else if(response.error){
		            UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 5000});
	        	}
	            
	        });
	    }

	    // Modal uploader
        $(function(){
	        var progressbar = $("#progressbar_modal"),
	            bar         = progressbar.find('.uk-progress-bar'),
	            settings    = {
		            action: '{{ url("/admin/images") }}', // upload url
		            single: 'false',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}", listing_id:{{ $listing->id }}},
		            allow : '*.(jpg|jpeg|png)', // allow only images

		            loadstart: function() {
		                bar.css("width", "0%").text("0%");
		                progressbar.removeClass("uk-hidden");
		            },

		            progress: function(percent) {
		                percent = Math.ceil(percent);
		                bar.css("width", percent+"%").text(percent+"%");
		            },

		            error: function(response) {
		                alert("Error uploading: " + response);
		            },

		            complete: function(response) {
		            	if(response.image && response.success){
		            		UIkit.notify('<i class="uk-icon-check-circle"></i> '+response.success, {pos:'top-right', status:'success', timeout: 5000});

		            		$("#images_div_modal").append('<div class="uk-width-large-1-4 uk-width-medium-1-2 uk-margin-bottom" id="image-modal-'+response.image.id+'" style="display: none;"><figure class="uk-overlay uk-overlay-hover uk-margin-bottom"><img src="{{asset("")}}'+response.image.image_path+'"><div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center uk-vertical-align"><i class="uk-icon-large uk-icon-remove uk-vertical-align-middle" id="'+response.image.id+'" onclick="deleteImage(this, true)"></i></div></figure></div>');
		            		$("#image-modal-"+response.image.id).show('normal');

		            		// Insite uploader images
		            		$("#images-div").append('<li data-id="'+response.image.id+'" class="uk-width-large-1-4 uk-width-medium-1-3 uk-panel uk-margin-bottom" id="image-'+response.image.id+'"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"><div class="uk-badge uk-badge-notification uk-panel-badge" style="right:40%;">0</div></li>');
		            		$('#image_modal_save').removeClass('uk-hidden');
		            		setOrdering(sortable);
		            	}else if(response.error){
		            		if(response.error instanceof Array){
		            			response.error.forEach(function(entry) {
		            				UIkit.notify('<i class="uk-icon-remove"></i> '+entry['image'], {pos:'top-right', status:'danger', timeout: 5000});
								});
		            		}else{
		            			UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 5000});
		            		}
		            	}
		            },

		            allcomplete: function(response) {
		                bar.css("width", "100%").text("100%");
		                setTimeout(function(){
		                    progressbar.addClass("uk-hidden");
		                }, 250);
		            }
		        };

	        var select = UIkit.uploadSelect($("#upload_select_modal"), settings),
	            drop   = UIkit.uploadDrop($("#upload_drop_modal"), settings);
	    });
		// Modal uploader

		// Inpage uploader
		$(function(){
	        var progressbar = $("#progressbar"),
	            bar         = progressbar.find('.uk-progress-bar'),
	            settings    = {
		            action: '{{ url("/admin/images") }}', // upload url
		            single: 'false',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}", listing_id:{{ $listing->id }}},
		            allow : '*.(jpg|jpeg|png)', // allow only images

		            loadstart: function() {
		                bar.css("width", "0%").text("0%");
		                progressbar.removeClass("uk-hidden");
		            },

		            progress: function(percent) {
		                percent = Math.ceil(percent);
		                bar.css("width", percent+"%").text(percent+"%");
		            },

		            error: function(response) {
		                alert("Error uploading: " + response);
		            },

		            complete: function(response) {
		            	if(response.image && response.success){
		            		UIkit.notify('<i class="uk-icon-check-circle"></i> '+response.success, {pos:'top-right', status:'success', timeout: 5000});

		            		$("#images-div").append('<li data-id="'+response.image.id+'" class="uk-width-large-1-4 uk-width-medium-1-3 uk-panel uk-margin-bottom" id="image-'+response.image.id+'"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"><div class="uk-badge uk-badge-notification uk-panel-badge" style="right:40%;">0</div></li>');
		            		$("#image-"+response.image.id).show('normal');
		            		setOrdering(sortable);
		            	}else if(response.error){
		            		if(response.error instanceof Array){
		            			response.error.forEach(function(entry) {
		            				UIkit.notify('<i class="uk-icon-remove"></i> '+entry['image'], {pos:'top-right', status:'danger', timeout: 5000});
								});
		            		}else{
		            			UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 5000});
		            		}
		            	}
		            },

		            allcomplete: function(response) {
		                bar.css("width", "100%").text("100%");
		                setTimeout(function(){
		                    progressbar.addClass("uk-hidden");
		                }, 250);
		            }
		        };

	        var select = UIkit.uploadSelect($("#upload-select"), settings),
	            drop   = UIkit.uploadDrop($("#upload-drop"), settings);
	    });
		// Inpage uploader


		window.fbAsyncInit = function() {
        	FB.init({
         		appId      : {{ Settings::get('facebook_app_id') }},
          		xfbml      : true,
          		version    : 'v2.3'
        	});
      	};
      	(function(d, s, id){
         	var js, fjs = d.getElementsByTagName(s)[0];
         	if (d.getElementById(id)) {return;}
         	js = d.createElement(s); js.id = id;
         	js.src = "//connect.facebook.net/en_US/sdk.js";
         	fjs.parentNode.insertBefore(js, fjs);
       	}(document, 'script', 'facebook-jssdk'));

       	function share(path){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response){
				UIkit.notify('<i class="uk-icon-check-circle"></i> {{ trans("admin.listing_shared") }}', {pos:'top-right', status:'success', timeout: 15000});
				$.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+{{ $listing->id }}, value: true, time:11520}, function(result){
	                
	            });
			});
       	}

       	function setListing(id){
			$('#listingId').val(id);
			$("#emails").val('');
			$("#message").val('');
		}

		function sendMail(sender) {
	    	$('#sendMail').prop('disabled', true);
	    	var message = $('#message').val();
	    	var emails = $('#emails').val().replace(/ /g,'').split(',');
	    	var validemails = [];
	    	$.each(emails, function( index, value ) {
			  	if(validateEmail(value)){
			  		validemails.push(value);
			  	}
			});

			if(validemails.length < 1){
				UIkit.notify('<i class="uk-icon-remove"></i> {{ trans('admin.no_emails') }}', {pos:'top-right', status:'danger', timeout: 5000});
				$('#sendMail').prop('disabled', false);
				return;
			}

			if(message.length < 1){
				UIkit.notify('<i class="uk-icon-remove"></i> {{ trans('admin.no_message') }}', {pos:'top-right', status:'danger', timeout: 5000});
				$('#sendMail').prop('disabled', false);
				return;
			}

	    	$.post("{{ url('/admin/listings') }}/"+ $('#listingId').val() +"/share", {_token: "{{ csrf_token() }}", email: validemails, message: message}, function(result){
		    	$('#sendMail').prop('disabled', false);
		    	if(result.success){
		    		UIkit.modal("#send_mail").hide();
		    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
		    	}else if(result.error || !result){
		    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 5000});
		    	}
	        });
	    }

	    function validateEmail(email) {
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		    return re.test(email);
		}

       	function saveClose(){
       		$("#save_close").val('1');
       		blockUI();
       	}

       	function leave() {
	    	UIkit.modal.confirm("{{ trans('admin.sure_leave') }}", function(){
			    window.location.replace("{{ url('/admin/listings') }}");
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }
	</script>
@endsection