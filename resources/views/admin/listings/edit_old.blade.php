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

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/listings/'.$listing->id) }}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">
        	<input type="hidden" name="save_close" value="0" id="save_close">

			<div class="uk-grid uk-margin-top">

				<div class="uk-width-large-1-10 uk-width-medium-1-10">

					<ul class="uk-list uk-hidden-small" data-uk-sticky="{boundary: true}">
					    <li data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_1') }}">
					    	<a href="#1" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/1.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_2') }}">
					    	<a href="#3" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/2.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_3') }}">
					    	<a href="#4" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/3.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_4') }}">
					    	<a href="#5" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/4.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_5') }}">
					    	<a href="#6" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/5.png') }}"></a>
					  	</li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_6') }}">
					    	<a href="#7" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/6.png') }}"></a>
					    </li>
					</ul>
				</div>

				<div class="uk-width-large-9-10 uk-width-medium-9-10" id="1">
					<!-- Categoria - tipo de publicacion - ubicacion -->
					<div class="uk-panel">
						<h2 class="uk-display-inline uk-text-primary uk-text-bold uk-float-left" style="text-transform: uppercase">{{ trans('admin.listing_data_location') }}</h2>
						<h2 class="uk-display-inline uk-float-right uk-padding-remove uk-margin-remove"><i id="points_main">0</i>/50</h2>
					</div>
					
					<div class="uk-grid">
						<div class="uk-width-large-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.category') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="category" name="category_id" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.category_tooltip') }}" onchange="calculatePoints('main')">
						                @foreach($categories as $category)
						                	@if($listing->category->id == $category->id)
												<option value="{{ $category->id }}" selected>{{ str_singular($category->name) }}</option>
						                	@else
						                		<option value="{{ $category->id }}">{{ str_singular($category->name) }}</option>
						                	@endif				                	
						                @endforeach
						            </select>
						        </div>
						    </div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.city') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="city" type="text" name="city_id" onchange="calculatePoints('main')">
						                @foreach($cities as $city)
						                	@if($listing->city->id == $city->id)
												<option value="{{ $city->id }}" selected="true">{{ $city->name }} ({{ $city->department->name }})</option>
						                	@else
						                		<option value="{{ $city->id }}">{{ $city->name }} ({{ $city->department->name }})</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.district') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="district" value="{{ $listing->district }}" placeholder="{{ trans('admin.district') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.district_tooltip') }}" onchange="calculatePoints('main')">
						    </div>
						</div>

						<div class="uk-width-large-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.listing_type') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="listing_type" name="listing_type" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_type_tooltip') }}" onchange="calculatePoints('main')">
						                @foreach($listingTypes as $listingType)
						                	@if($listing->listingType->id == $listingType->id)
												<option value="{{ $listingType->id }}" selected>{{ $listingType->name }}</option>
						                	@else
						                		<option value="{{ $listingType->id }}">{{ $listingType->name }}</option>
						                	@endif	
						                @endforeach
						            </select>
						        </div>
						    </div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.address') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="direction" type="text" name="direction" value="{{ $listing->direction }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.direction_tooltip') }}" onchange="calculatePoints('main')">
							</div>
						</div>
						<!-- Categoria - tipo de publicacion - ubicacion -->

						<!-- Mapa -->
						<div class="uk-margin uk-width-1-1" id="2">
							<p class="uk-text-primary uk-text-bold">{{ trans('admin.select_map_location') }}</p>
							<input class="uk-width-large-5-10 uk-form-large uk-margin-bottom" id="gmap_search" type="text" placeholder="{{ trans('admin.gmap_search') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.gmap_search_tooltip') }}">
							<?php echo $map['html']; ?>
						</div>
						<!-- Mapa -->
					</div>

					<div class="uk-grid uk-margin-top-remove" id="3">
						<!-- Informacion basica del inmueble -->
						<div class="uk-panel uk-width-1-1 uk-margin-bottom">
							<hr>
							<h2 class="uk-display-inline uk-text-primary uk-text-bold uk-float-left uk-padding-remove uk-margin-remove" style="text-transform: uppercase">{{ trans('admin.listing_basic_information') }}</h2>
							<h2 class="uk-display-inline uk-float-right uk-padding-remove uk-margin-remove"><i id="points_basics">0</i>/150</h2>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.price') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="price" type="text" name="price" placeholder="{{ trans('admin.price') }}" value="{{ $listing->price }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.price_tooltip') }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.stratum') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="stratum" placeholder="{{ trans('admin.stratum') }}" value="{{ $listing->stratum }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.area') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="area" type="text" name="area" placeholder="{{ trans('admin.area') }}" value="{{ $listing->area }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.area_tooltip') }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.lot_area') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" id="lot_area" type="text" name="lot_area" placeholder="{{ trans('admin.lot_area') }}" value="{{ $listing->lot_area }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.lot_area_tooltip') }}" onchange="calculatePoints('basics')">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.rooms') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="rooms" placeholder="{{ trans('admin.rooms') }}" value="{{ $listing->rooms }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.bathrooms') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="bathrooms" placeholder="{{ trans('admin.bathrooms') }}" value="{{ $listing->bathrooms }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.garages') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="garages" placeholder="{{ trans('admin.garages') }}" value="{{ $listing->garages }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.floor') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="floor" placeholder="{{ trans('admin.floor') }}" value="{{ $listing->floor }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.floor_tooltip') }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.construction_year') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="construction_year" placeholder="{{ trans('admin.construction_year') }}" value="{{ $listing->construction_year }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.construction_year_tooltip') }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.administration_fees') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" id="administration" type="text" name="administration" placeholder="{{ trans('admin.administration_fees') }}" value="{{ $listing->administration }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.administration_fees_tooltip') }}" onkeyup="format(this);" onchange="calculatePoints('basics')">
							</div>
						</div>
						<!-- Informacion basica del inmueble -->
					</div>

					<hr>

					<!-- Caracteristicas del inmueble -->
					<div id="4">
						<div class="uk-panel">
							<h2 class="uk-display-inline uk-text-primary uk-text-bold uk-float-left" style="text-transform: uppercase">{{ trans('admin.listing_caracteristics') }}</h2>
							<h2 class="uk-display-inline uk-float-right uk-padding-remove uk-margin-remove"><i id="points_caracteristics">0</i>/150</h2>
						</div>

						<h3>{{ trans('admin.interior') }}</h3>
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
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif										
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.exterior') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 2)
									<div class="uk-width-large-1-3 uk-width-1-2">
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif										
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.sector') }}</h3>
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
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
										@else
											<label><input type="checkbox" onchange="calculatePoints('caracteristics')" name="{{ $feature->id }}"> {{ $feature->name }}</label>
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
							<h2 class="uk-display-inline uk-text-primary uk-text-bold uk-float-left" style="text-transform: uppercase">{{ trans('admin.listing_description') }}</h2>
							<h2 class="uk-display-inline uk-float-right uk-padding-remove uk-margin-remove"><i id="points_aditional">0</i>/150</h2>
						</div>
						<p class="uk-margin-top-remove">{{ trans('admin.listing_description_help') }}</p>
						<textarea id="description" class="uk-width-large-10-10 uk-margin-small-bottom" rows="5" name="description" maxlength="2000" onkeyup="calculatePoints('description')">{{ $listing->description }}</textarea>
					</div>
					<!-- Informacion adicional -->

					<hr>

					<!-- Image upload -->
					<div id="6">
						<div class="uk-panel">
							<h2 class="uk-display-inline uk-text-primary uk-text-bold uk-float-left" style="text-transform: uppercase">{{ trans('admin.images') }}</h2>
							<h2 class="uk-display-inline uk-float-right uk-padding-remove uk-margin-remove"><i id="points_images">0</i>/150</h2>
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

					<div class="uk-margin-top uk-flex">
				        <!-- This is a button toggling the modal -->
				        <button form="create_form" type="submit" class="uk-width-1-3 uk-margin-right uk-button uk-button-large uk-button-success uk-text-bold uk-margin-bottom" onclick="blockUI()">{{ trans('admin.save') }}</button>
				        <button form="create_form" type="submit" class="uk-width-1-3 uk-margin-right uk-button uk-button-large uk-text-bold uk-margin-bottom" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
				        <a class="uk-width-1-3 uk-button uk-button-large uk-text-bold uk-margin-bottom" target="_blank" href="{{ url($listing->path())}}">{{ trans('admin.view_listing') }}</a>
				    </div>
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

	        <div class="uk-grid uk-grid-collapse">
	        	<div class="uk-width-1-1">
	        		<div id="upload_drop_modal" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top">
					    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_listing_images_or') }} <a class="uk-form-file uk-text-primary">{{ trans('admin.select_an_image') }}<input id="upload_select_modal" type="file" multiple></a>
					</div>

					<div id="progressbar_modal" class="uk-progress uk-hidden">
					    <div class="uk-progress-bar" style="width: 0%;"></div>
					</div>
	        	</div>
	        </div>

	        <div class="uk-margin-large-top uk-grid" id="images_div_modal">
						
			</div>

		    <div class="uk-modal-footer">
		    	<a href="" class="uk-button uk-button-success uk-modal-close">{{ trans('admin.save') }}</a>
		    </div>
	    </div>
	</div>
@endif
@include('modals.email_listing')
@endsection

@section('js')
	@parent

	<!-- Styles -->
    <link href="{{ asset('/css/components/sortable.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/form-file.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/upload.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/placeholder.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/progress.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('/css/selectize.min.css') }}" rel="stylesheet"/>
	<!-- Styles -->

	<!-- JS -->
    <script src="{{ asset('/js/components/sortable.min.js') }}"></script>
	<script src="{{ asset('/js/components/upload.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<script src="{{ asset('/js/selectize.min.js') }}"></script>
	<!-- JS -->

	<script type="text/javascript">
		function calculatePoints(sender){
			if(sender == 'main' || !sender){
				var $main = $("#points_main");
				var mainPoints = 0;
				var number = 5;

				if($('select[name="category_id"]').val() && $('select[name="category_id"]').val() != ''){
					mainPoints += 50/number;
				}
				if($('select[name="listing_type"]').val() && $('select[name="listing_type"]').val() != ''){
					mainPoints += 50/number;
				}
				if($('select[name="city_id"]').val() && $('select[name="city_id"]').val() != ''){
					mainPoints += 50/number;
				}
				if($('input[name="direction"]').val() && $('input[name="direction"]').val() != ''){
					mainPoints += 50/number;
				}
				if($('input[name="district"]').val() && $('input[name="district"]').val() != ''){
					mainPoints += 50/number;
				}

				$({someValue: $main.html()}).animate({someValue: mainPoints}, {
				    duration: 500,
				    easing:'swing', // can be anything
				    step: function() { // called on every step
				        // Update the element's text with rounded-up value:
				        $main.text(Math.round(this.someValue));
				    }
				});
			}

			if(sender == 'basics' || !sender){
				var $basics = $("#points_basics");
				var basicsPoints = 0;
				var number = $('#3').find('input').length;

				if($('input[name="price"]').val() && $('input[name="price"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="stratum"]').val() && $('input[name="stratum"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="area"]').val() && $('input[name="area"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="lot_area"]').val() && $('input[name="lot_area"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="rooms"]').val() && $('input[name="rooms"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="bathrooms"]').val() && $('input[name="bathrooms"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="garages"]').val() && $('input[name="garages"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="floor"]').val() && $('input[name="floor"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="construction_year"]').val() && $('input[name="construction_year"]').val() != ''){
					basicsPoints += 150/number;
				}
				if($('input[name="administration"]').val() && $('input[name="administration"]').val() != ''){
					basicsPoints += 150/number;
				}

				$({someValue: $basics.html()}).animate({someValue: basicsPoints}, {
				    duration: 500,
				    easing:'swing', // can be anything
				    step: function() { // called on every step
				        // Update the element's text with rounded-up value:
				        $basics.text(Math.round(this.someValue));
				    }
				});
			}

			if(sender == 'caracteristics' || !sender){
				var $caracteristics = $("#points_caracteristics");
				caracteristicsPoints = ($('#4').find('input[type="checkbox"]:checked').length/($('#4').find('input[type="checkbox"]').length*0.5))*150;
				if(caracteristicsPoints > 150){
					caracteristicsPoints = 150;
				}
				$({someValue: $caracteristics.html()}).animate({someValue: caracteristicsPoints}, {
				    duration: 500,
				    easing:'swing', // can be anything
				    step: function() { // called on every step
				        // Update the element's text with rounded-up value:
				        $caracteristics.text(Math.round(this.someValue));
				    }
				});
			}

			if(sender == 'description' || !sender){
				var $el = $("#points_aditional");
				string = $("#description").val();
				aditionalPoints = 0;
				if(string){
					aditionalPoints = (string.length/1000)*150;
				}
				if(aditionalPoints > 150){
					aditionalPoints = 150;
				}
				$({someValue: $el.html()}).animate({someValue: aditionalPoints}, {
				    duration: 500,
				    easing:'swing', // can be anything
				    step: function() { // called on every step
				        // Update the element's text with rounded-up value:
				        $el.text(Math.round(this.someValue));
				    }
				});
			}
			
			if(sender == 'images' || !sender){
				// Image points
				var $images = $('#points_images');
				imagesPoints = ($('#images-div').children().size()/10)*200;
				if(imagesPoints > 200){
					imagesPoints = 200;
				}
				$({someValue: $images.html()}).animate({someValue: imagesPoints}, {
				    duration: 500,
				    easing:'swing', // can be anything
				    step: function() { // called on every step
				        // Update the element's text with rounded-up value:
				        $images.text(Math.round(this.someValue));
				    }
				});
			}	
		}

		var sortable = null;
		$(function() {
			sortable = $('[data-uk-sortable]');
            sortable.on('stop.uk.sortable', function (e, el, type) {
                setOrdering(sortable, el);
            });
            setOrdering(sortable);
            calculatePoints();

			$("#city").select2();

			$('#price').val(accounting.formatNumber(document.getElementById('price').value));
			$('#area').val(accounting.formatNumber(document.getElementById('area').value));
			$('#lot_area').val(accounting.formatNumber(document.getElementById('lot_area').value));
			$('#administration').val(accounting.formatNumber(document.getElementById('administration').value));

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
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">Guardando inmueble, porfavor espere.</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>', {center: true});
	    }

		function format(field){
			if(field.value){
				field.value = accounting.formatNumber(field.value);
			}
	    }

        function deleteImage(sender, modal) {
	        $.post("{{ url('/admin/images') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	        	if(result.success){
	        		$("#image-"+sender.id).fadeOut(500, function() { $(this).remove(); setOrdering(sortable); calculatePoints('images'); });
	        		if(modal){
	            		$("#image-modal-"+sender.id).fadeOut(500, function() { $(this).remove(); setOrdering(sortable); calculatePoints('images'); });
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

		            		$("#images_div_modal").append('<div class="uk-width-1-4" id="image-modal-'+response.image.id+'" style="display: none;"><figure class="uk-overlay uk-overlay-hover uk-margin-bottom"><img src="{{asset("")}}'+response.image.image_path+'"><div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center uk-vertical-align"><i class="uk-icon-large uk-icon-remove uk-vertical-align-middle" id="'+response.image.id+'" onclick="deleteImage(this, true)"></i></div></figure></div>');
		            		$("#image-modal-"+response.image.id).show('normal');

		            		// Insite uploader images
		            		$("#images-div").append('<li data-id="'+response.image.id+'" class="uk-width-large-1-4 uk-width-medium-1-3 uk-panel" id="image-'+response.image.id+'"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"><div class="uk-badge uk-badge-notification uk-panel-badge" style="right:40%;">0</div></li>');
		            		setOrdering(sortable);
		            		calculatePoints('images');
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

		            		$("#images-div").append('<li data-id="'+response.image.id+'" class="uk-width-large-1-4 uk-width-medium-1-3 uk-panel" id="image-'+response.image.id+'"><i class="uk-close uk-close-alt uk-panel-badge" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i><input type="hidden" name="image['+response.image.id+']" value><img src="{{asset("/")}}'+response.image.image_path+'"><div class="uk-badge uk-badge-notification uk-panel-badge" style="right:40%;">0</div></li>');
		            		$("#image-"+response.image.id).show('normal');
		            		setOrdering(sortable);
		            		calculatePoints('images');
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

	<!-- Google map js -->
	<script type="text/javascript">var centreGot = false;</script>
	<?php echo $map['js']; ?>
@endsection