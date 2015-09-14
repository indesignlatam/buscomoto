<li class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-bottom" id="listing-{{ $listing->id }}">
	<div class="uk-grid">
		<div class="uk-width-large-2-10 uk-width-medium-2-10 uk-width-small-1-1">
			<a href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">
				<img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}">
			</a>
		</div>

		<div class="uk-width-large-6-10 uk-width-medium-6-10 uk-width-small-1-1">
			<!-- Listing title -->
			<a class="uk-h3 uk-text-bold" style="color:black;" href="{{ url($listing->pathEdit()) }}">{{ $listing->title }}</a>
			<!-- Listing title -->

			<!-- Featured tag -->
        	@if($listing->featured_type && $listing->featured_expires_at && $listing->featured_expires_at > Carbon::now())
				<i class="uk-icon-small {{$listing->featuredType->uk_class}} uk-text-success" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.featured_listing') }}"></i>
        	@endif
        	<!-- Featured tag -->

			<!-- Listing info and share -->
			<div class="uk-grid uk-margin-top uk-hidden-small">

    			<ul class="uk-list uk-list-line uk-width-4-10">
    				<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> {{ money_format('$%!.0i', $listing->price) }}</li>
    				@if($listing->engine_size)
    					<li><i class="uk-text-muted">{{ trans('admin.engine_size') }}</i> {{ number_format($listing->engine_size) }} cc</li>
    				@endif
    				@if($listing->odometer >= 0)
    					<li><i class="uk-text-muted">{{ trans('admin.odometer') }}</i> {{ number_format($listing->odometer) }} kms</li>
    				@endif
    				<li><i class="uk-text-muted">{{ trans('admin.code') }}</i> #{{ $listing->code }}</li>
    			</ul>

    			<ul class="uk-list uk-list-line uk-width-4-10">
    				<li>
    					<a href="{{ $listing->pathEdit() }}#6" style="text-decoration: none">
    					@if(count($listing->images)>0)
    					 	<i class="uk-icon-check uk-text-success"> </i>
    					@else
    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.images_check_tooltip') }}"> </i>
    					@endif
						<i class="uk-text-muted">{{ trans('admin.images') }}</i>
						</a>
    				</li>
    				<li>
    					<a href="{{ $listing->pathEdit() }}#7" style="text-decoration: none">
    					@if(Cookie::get('shared_listing_'.$listing->id))
							<i class="uk-icon-check uk-text-success"> </i>
    					@else
    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.shared_check_tooltip') }}"> </i>
    					@endif
    					<i class="uk-text-muted">{{ trans('admin.shared') }}</i>
    					</a>
    				<li>
    					<a href="{{ $listing->pathEdit() }}#5" style="text-decoration: none">
    					@if(strlen($listing->description) > 50)
							<i class="uk-icon-check uk-text-success"> </i>
    					@else
    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.aditional_check_tooltip') }}"> </i>
    					@endif
    					<i class="uk-text-muted">{{ trans('admin.description') }}</i>
    					</a>
    				</li>
    				<li>
    					<a href="{{ $listing->pathEdit() }}#4" style="text-decoration: none">
    					@if(count($listing->features) > 5)
							<i class="uk-icon-check uk-text-success"> </i>
    					@else
    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.features_check_tooltip') }}"> </i>
    					@endif
    					<i class="uk-text-muted">{{ trans('admin.features') }}</i>
    					</a>
    				</li>
    			</ul>
    			
    			<!-- Share buttons -->
    			<div class="uk-width-2-10 uk-text-center">
    				<h4 class="uk-text-bold" style="color:black">{{ trans('admin.share') }}</h4>
    				<ul class="uk-list" style="list-style: none;">
        				<li style="margin-left:-20px;">
            				<a onclick="share('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook"></a> 
            				<a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
        				</li>
        				<li class="uk-margin-small-top" style="margin-left:-20px;">
        					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>
        					<a href="#send_mail" class="uk-icon-button uk-icon-envelope" onclick="setListing({{ $listing->id }})" data-uk-modal="{center:true}"></a>
        				</li>
        			</ul>
    			</div>
    			<!-- Share buttons -->
			</div>
			<!-- Listing info and share -->
		</div>

		<div class="uk-width-large-2-10 uk-width-medium-2-10 uk-width-small-1-1">
			@if(!$listing->deleted_at)
				@if($listing->expires_at > Carbon::now()->addDays(5))
					<b>{{ trans('admin.expires_in') . $listing->expires_at->diffForHumans() }}</b>

                    <!-- Featured button -->
                    @if($listing->featured_type && $listing->featured_expires_at && $listing->featured_expires_at > Carbon::now())
                        <button class="uk-button uk-button-success uk-width-1-1 uk-margin-small-bottom" disabled>{{ trans('admin.feature') }}</button>
                    @else
                        <a class="uk-button uk-button-success uk-width-1-1 uk-margin-small-bottom" href="{{ url('admin/destacar/'.$listing->id) }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.feature_listing') }}">{{ trans('admin.feature') }}</a>
                    @endif
                    <!-- Featured button -->

	    			<!-- View messages button -->
					<a class="uk-button uk-width-1-1 uk-margin-small-bottom" href="{{ url('/admin/messages/'.$listing->id) }}">{{ trans('admin.view_messages') }}</a>
	    			<!-- View messages button -->
				@else
					<a class="uk-text-bold uk-text-danger" href="{{ url('admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.expires_in') . $listing->expires_at->diffForHumans() }}</a>

					<a class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-small-bottom" href="{{ url('admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a>
				@endif

    			<!-- View in frontend button -->
                <a class="uk-button uk-width-1-1 uk-margin-small-bottom" href="{{ url($listing->path()) }}" target="_blank">{{ trans('admin.view_listing') }}</a>
    			<!-- View in frontend button -->

    			<!-- Edit and delete buttons -->
        		<div class="uk-flex uk-flex-center uk-flex-space-between">
        			<a class="uk-button" href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">{{ trans('admin.edit') }}</a>
        			<button class="uk-button" href="{{ url('/admin/banners/create') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.print_banner') }}" disabled><i class="uk-icon-print"></i></button>
                    <a class="uk-button uk-button-danger" id="{{ $listing->id }}" onclick="deleteObject(this)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminate_listing') }}"><i class="uk-icon-trash"></i></a>
    			</div>
    			<!-- Edit and delete buttons -->
    		@endif
        </div>
	</div>
</li>