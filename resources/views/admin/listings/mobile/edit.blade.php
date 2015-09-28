@extends('layouts.mobile.master')

@section('head')
    <title>{{ trans('admin.edit_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h2>{{ trans('admin.edit_listing') }}</h2>

	    <hr>
	    
	    <!-- Action buttons -->
		<div class="uk-flex uk-margin-small-bottom">
	        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-2" onclick="blockUI()">{{ trans('admin.save') }}</button>
			<button class="uk-button uk-button-large uk-width-1-2" onclick="leave()">{{ trans('admin.close') }}</button>
	    </div>
		<button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1 uk-margin-small-bottom" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
	    <!-- Action buttons -->

	    <!-- No images notification -->
	    @if(count($listing->images) < 1)
	    <div class="uk-visible-small uk-alert-warning uk-text-center uk-margin-top" id="no_images_notification">
	    	<p class="uk-text-danger uk-h4">{{ trans('admin.no_images_warning') }}</p>
	    	<a href="#upload_modal" class="uk-button uk-button-large uk-button-success uk-margin-small-bottom uk-width-2-3" data-uk-modal="">{{ trans('admin.upload_image') }}</a>
	    </div>
	    @endif
	    <!-- No images notification -->

	    <hr>

		<form id="create_form" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ url('/admin/listings/'.$listing->id) }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">
        	<input type="hidden" name="save_close" value="0" id="save_close">

			<!-- Categoria - tipo de publicacion - ubicacion -->
			<div class="uk-panel uk-margin-bottom">
				<h3 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_data_location') }}</h3>
			</div>

			<div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.listing_type') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" id="listing_type" name="listing_type">
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
		        <label class="uk-form-label" for="">{{ trans('admin.manufacturer') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" id="manufacturers" type="text" name="manufacturer_id">
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
		        <label class="uk-form-label" for="">{{ trans('admin.model') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" id="models" type="text" name="model_id" style="width:100%">	
		                <option value="{{ $listing->model->id }}">{{ $listing->model->name }}</option>
	            	</select>
		        </div>
		    </div>

		    <div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.city') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" id="cities" type="text" name="city_id">
		                <option value="{{ $listing->city->id }}">{{ $listing->city->name }} ({{ $listing->city->department->name }})</option>
	            	</select>
		        </div>
		    </div>

			<div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.district') }}</label>
				<input class="uk-width-1-1 uk-form-large" type="text" name="district" value="{{ $listing->district }}" placeholder="{{ trans('admin.district') }}">
		    </div>

		    <div class="uk-form-row uk-hidden">
		        <label class="uk-form-label" for="">{{ trans('admin.fuel') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" type="text" name="fuel_type">
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

		    <div class="uk-form-row uk-hidden">
		        <label class="uk-form-label" for="">{{ trans('admin.transmission') }} <i class="uk-text-danger">*</i></label>
		        <div class="uk-form-controls">
		        	<select class="uk-width-1-1 uk-form-large" type="text" name="transmission_type">	
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
			<!-- Categoria - tipo de publicacion - ubicacion -->

			<hr>

			<!-- Informacion basica del inmueble -->
			<div class="uk-panel uk-width-1-1 uk-margin-bottom">
				<h3 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_basic_information') }}</h3>
			</div>

			<div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.price') }} <i class="uk-text-danger">*</i></label>
				<input class="uk-width-1-1 uk-form-large" id="price" type="text" name="price" placeholder="{{ trans('admin.price') }}" value="{{ $listing->price }}" onkeyup="format(this);">
			</div>

			<div class="uk-form-row uk-flex">
				<div class="">
			        <label class="uk-form-label" for="">{{ trans('admin.year') }} <i class="uk-text-danger">*</i></label>
					<input class="uk-width-1-1 uk-form-large" type="text" name="year" placeholder="{{ trans('admin.year') }}" value="{{ $listing->year }}" onkeyup="format(this);">
				</div>

				<div class=" uk-margin-small-left">
			        <label class="uk-form-label" for="">{{ trans('admin.license_number') }}</label>
					<input class="uk-width-1-1 uk-form-large" type="text" name="license_number" placeholder="{{ trans('admin.license_number') }}" value="{{ $listing->license_number }}">
				</div>
			</div>

			<div class="uk-form-row uk-flex">
				<div class="">
			        <label class="uk-form-label" for="">{{ trans('admin.odometer') }} <i class="uk-text-danger">*</i></label>
					<input class="uk-width-1-1 uk-form-large" id="odometer" type="text" name="odometer" placeholder="{{ trans('admin.odometer') }}" value="{{ $listing->odometer }}" onkeyup="format(this);">
				</div>

				<div class="uk-margin-small-left">
			        <label class="uk-form-label" for="">{{ trans('admin.engine_size') }} <i class="uk-text-danger">*</i></label>
					<input class="uk-width-large-1-1 uk-form-large" id="engine_size" type="text" name="engine_size" placeholder="{{ trans('admin.engine_size') }}" value="{{ $listing->engine_size }}" onkeyup="format(this);">
				</div>
			</div>
			
			<div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.color') }}</label>
				<input class="uk-width-1-1 uk-form-large" type="text" name="color" placeholder="{{ trans('admin.color') }}" value="{{ $listing->color }}">
			</div>
			<!-- Informacion basica del inmueble -->

			<hr>

			<!-- Caracteristicas del inmueble -->
			<div class="uk-panel">
				<h3 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_caracteristics') }}</h3>
			</div>

			<h3 class="uk-margin-top">{{ trans('admin.security') }}</h3>
			<div class="uk-grid">
				@foreach($features as $feature)
					@if($feature->category->id == 1)
						<div class="uk-width-1-2">
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
						<div class="uk-width-1-2">
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
						<div class="uk-width-1-2">
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
			<!-- Caracteristicas del inmueble -->

			<hr>

			<!-- Informacion adicional -->
			<div class="uk-panel">
				<h3 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_description') }}</h3>
			</div>

			<p class="uk-margin-top-remove">{{ trans('admin.listing_description_help') }}</p>

			<textarea class="uk-width-1-1 uk-margin-small-bottom" id="description" rows="5" name="description" maxlength="2000">{{ $listing->description }}</textarea>
			<!-- Informacion adicional -->

			<hr>

			<!-- Image upload -->
			<div class="uk-panel">
				<h3 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.images') }}</h3>
			</div>

			<p class="uk-margin-remove">{{ trans('admin.order_images_mobile') }}</p>


			<div class="uk-form-file uk-width-1-1 uk-margin-top">
			    <h3 class="uk-width-1-1 uk-text-center uk-text-primary uk-margin-bottom-remove">{{ trans('admin.click_upload_image') }}</h3>
				<div class="uk-width-1-1 uk-text-center uk-margin-top-remove"><i class="uk-icon-chevron-down uk-icon-medium"></i></div>

				<div class="uk-grid">
					<div class="uk-width-1-2">
						<img src="{{ asset('/images/support/listings/photos/type_1.png') }}" class="uk-margin-small-top" id="image_type_1">
					</div>
					<div class="uk-width-1-2">
						<img src="{{ asset('/images/support/listings/photos/type_2.png') }}" class="uk-margin-small-top" id="image_type_2">
					</div>
					<div class="uk-width-1-2">
						<img src="{{ asset('/images/support/listings/photos/type_3.png') }}" class="uk-margin-small-top" id="image_type_3">
					</div>
					<div class="uk-width-1-2">
						<img src="{{ asset('/images/support/listings/photos/type_4.png') }}" class="uk-margin-small-top" id="image_type_4">
					</div>
				</div>
				
			    <input id="upload-select" type="file" multiple>
			</div>

			<div id="progressbar" class="uk-progress uk-hidden">
			    <div class="uk-progress-bar" style="width: 0%;"></div>
			</div>

			<ul class="uk-margin-top uk-grid" id="images-div">
			@foreach($listing->images->sortBy('ordering') as $image)
				<li data-id="{{ $image->id }}" class="uk-width-1-1 uk-margin-small-bottom" id="image-{{ $image->id }}" style="position:relative">
					<i class="uk-close uk-close-alt uk-panel-badge" id="{{ $image->id }}" onclick="deleteImage(this)"></i>
					<input type="hidden" name="image[{{ $image->id }}]" value="{{ $image->ordering }}">
			    	<img src="{{ asset($image->image_path) }}">
				</li>
			@endforeach
			</ul>
		    <!-- Image upload -->

		    <hr>

		    <!-- Share listing -->
		    <h3 class="uk-text-primary uk-text-bold uk-margin-small-top" style="text-transform: uppercase">{{ trans('admin.share_social') }}</h3>

			<div class="uk-flex uk-flex-space-between">
				<a onclick="share('{{ url($listing->path()) }}')" class="uk-icon-button uk-icon-facebook"></a>

				<a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>

				<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>

	            <a href="#send_mail" class="uk-icon-button uk-icon-envelope" onclick="setListing({{ $listing->id }})" data-uk-modal="{center:true}"></a>
			</div>
		    <!-- Share listing -->

		    <hr>

		    <!-- No images notification -->
		    @if(count($listing->images) < 1)
		    <div class="uk-visible-small uk-alert-warning uk-text-center uk-margin-top" id="no_images_notification_bottom">
		    	<p class="uk-text-danger uk-h4">{{ trans('admin.no_images_warning') }}</p>
		    	<a href="#upload_modal" class="uk-button uk-button-large uk-button-success uk-margin-small-bottom uk-width-2-3" data-uk-modal="">{{ trans('admin.upload_image') }}</a>
		    </div>

		    <hr>
		    @endif
		    <!-- No images notification -->

		    <!-- Action buttons -->
		    <div class="uk-margin-top">
		        <button form="create_form" type="submit" class="uk-width-1-1 uk-margin-small-bottom uk-button uk-button-large uk-button-success" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>

		        <div class="uk-flex">
		        	<button form="create_form" type="submit" class="uk-width-1-1 uk-button uk-button-large uk-button-success" onclick="blockUI()">{{ trans('admin.save') }}</button>
		        	<a class="uk-width-1-1 uk-button uk-button-large" target="_blank" href="{{ url($listing->path())}}">{{ trans('admin.view_listing') }}</a>
		        </div>
		    </div>
		    <!-- Action buttons -->
		</form>

	</div>
</div>

@if($listing->expires_at && $listing->expires_at < Carbon::now()->addDays(5))
<!-- Is expiring modal -->
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
<!-- Is expiring modal -->
@endif

@if(!count($listing->images))
<!-- No images modal -->
	<div id="upload_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.images_modal') }}
	        </div>

	        <p class="uk-hidden-small">{{ trans('admin.no_images_text') }}</p>

	        <div class="uk-grid uk-grid-collapse">
	        	<div class="uk-width-1-1">
					<p class="">{{ ucfirst(trans('admin.images_recomendations')) }}</p>
					
					<a class="uk-form-file uk-grid">
						<h3 class="uk-width-1-1 uk-text-center uk-text-primary uk-text-bold">{{ trans('admin.click_upload_image') }}</h3>
						<div class="uk-width-1-1 uk-text-center"><i class="uk-icon-chevron-down uk-icon-medium"></i></div>
						<div class="uk-width-1-2">
							<img src="{{ asset('/images/support/listings/photos/type_1.png') }}" class="uk-margin-small-top">
						</div>
						<div class="uk-width-1-2">
							<img src="{{ asset('/images/support/listings/photos/type_2.png') }}" class="uk-margin-small-top">
						</div>

						<div class="uk-width-1-2">
							<img src="{{ asset('/images/support/listings/photos/type_3.png') }}" class="uk-margin-small-top">
						</div>
						<div class="uk-width-1-2">
							<img src="{{ asset('/images/support/listings/photos/type_4.png') }}" class="uk-margin-small-top">
						</div>

						<input id="upload_select_modal" type="file" multiple>
					</a>

					<div id="progressbar_modal" class="uk-progress uk-hidden">
					    <div class="uk-progress-bar" style="width: 0%;"></div>
					</div>
	        	</div>
	        </div>

	        <div class="uk-margin-top uk-grid" id="images_div_modal">
						
			</div>

		    <div class="uk-modal-footer uk-hidden-small">
		    	<a href="" class="uk-button uk-button-success uk-modal-close">{{ trans('admin.save') }}</a>
		    </div>

		    <div class="uk-modal-footer uk-visible-small">
		    	<a id="image_modal_save" href="" class="uk-button uk-button-success uk-button-large uk-width-1-1 uk-modal-close uk-hidden">{{ trans('admin.save') }}</a>
		    </div>
	    </div>
	</div>
<!-- No images modal -->
@endif

@include('modals.email_listing')

@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<!-- CSS -->
	<link href="{{ asset('/css/components/form-file.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/progress.min.css') }}" rel="stylesheet">
	<!-- CSS -->

	<!-- JS -->
	<script src="{{ asset('/js/components/upload.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
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
					      };
					    },
					    processResults: function (data, page) {
						  	return {
						    	results: data
						  	};
						},
						cache: true
						},
						minimumInputLength: 2,
					  	language: {
							// You can find all of the options in the language files provided in the
							// build. They all must be functions that return the string that should be
							// displayed.
							inputTooShort: function () {
								return "Debes escribir mínimo 3 letras";
							},
							noMatches: function () {
								return "No encontramos ningun resultado";
							},
							searching: function () {
								return "Buscando...";
							},
						}
		        });
		    }).trigger('change');

		  	$('#cities').removeClass('select2-offscreen').select2({
	        	ajax: {
				    url: "{{ url('/api/cities') }}",
				    dataType: 'json',
				    delay: 250,
				    data: function (params) {
				      return {
				        q: params.term, // search term
				      };
				    },
				    processResults: function (data, page) {
				      return {
				        results: data
				      };
				    },
				    cache: true
				  	},
				  	minimumInputLength: 3,
				  	language: {
						// You can find all of the options in the language files provided in the
						// build. They all must be functions that return the string that should be
						// displayed.
						inputTooShort: function () {
							return "Debes escribir mínimo 3 letras";
						},
						noMatches: function () {
							return "No encontramos ningun resultado";
						},
						searching: function () {
							return "Buscando...";
						},
					}
	        });
		});

		var sortable = null;
		$(function() {
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
	        		$("#image-"+sender.id).fadeOut(500, function() { $(this).remove(); });
	        		if(modal){
	            		$("#image-modal-"+sender.id).fadeOut(500, function() { $(this).remove(); });
	        		}
	        		
	                UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'info', timeout: 5000});
	        	}else if(response.error){
		            UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 5000});
	        	}
	            
	        });
	    }

	    // Uploaders
        $(function(){
	        var progressbar 		= $("#progressbar"),
	        	progressbar_modal 	= $("#progressbar_modal"),
	            bar         		= progressbar.find('.uk-progress-bar'),
	            bar_modal         	= progressbar_modal.find('.uk-progress-bar'),
	            settings    		= {
		            action: '{{ url("/admin/images") }}', // upload url
		            single: 'false',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}", listing_id:{{ $listing->id }}},
		            allow : '*.(jpg|jpeg)', // allow only images

		            loadstart: function() {
		                bar.css("width", "0%").text("0%");
		                bar_modal.css("width", "0%").text("0%");
		                progressbar.removeClass("uk-hidden");
		                progressbar_modal.removeClass("uk-hidden");
		            },

		            progress: function(percent) {
		                percent = Math.ceil(percent);
		                bar.css("width", percent+"%").text(percent+"%");
		                bar_modal.css("width", percent+"%").text(percent+"%");
		            },

		            error: function(response) {
		                alert("Error uploading: " + response);
		            },

		            complete: function(response) {
		            	bar.css("width", "0%").text("0%");
		                bar_modal.css("width", "0%").text("0%");

		            	if(response.image && response.success){
		            		UIkit.notify('<i class="uk-icon-check-circle"></i> '+response.success, {pos:'top-right', status:'success', timeout: 3000});

		            		// Modal image
		            		$("#images_div_modal").append('<li data-id="'+response.image.id+'" class="uk-width-1-1 uk-margin-small-bottom" id="image-modal-'+response.image.id+'" style="position:relative"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this, true)"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"></li>');
		            		$("#image-modal-"+response.image.id).show('normal');

		            		// Insite image
		            		$("#images-div").append('<li data-id="'+response.image.id+'" class="uk-width-1-1 uk-margin-small-bottom" id="image-'+response.image.id+'" style="position:relative"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this)"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"></li>');

		            		// Show save button in modal
		            		$('#image_modal_save').removeClass('uk-hidden');

		            		// Hide no images notifications
		            		$('#no_images_notification_bottom').addClass('uk-hidden');
		            		$('#no_images_notification').addClass('uk-hidden');

		            		// Set images ordering
		            		
		            	}else if(response.error){
		            		if(response.error instanceof Array){
		            			response.error.forEach(function(entry) {
		            				UIkit.notify('<i class="uk-icon-close"></i> '+entry['image'], {pos:'top-right', status:'danger', timeout: 3000});
								});
		            		}else{
		            			UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 3000});
		            		}
		            	}
		            },

		            allcomplete: function(response) {
		                bar.css("width", "100%").text("100%");
		                bar_modal.css("width", "100%").text("100%");
		                setTimeout(function(){
		                    progressbar.addClass("uk-hidden");
		                    progressbar_modal.addClass("uk-hidden");
		                }, 500);
		            }
		        };

	        var select_modal 	= UIkit.uploadSelect($("#upload_select_modal"), settings);
	        var select 			= UIkit.uploadSelect($("#upload-select"), settings);
	    });

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