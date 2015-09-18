<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom" id="listing_{{ $listing->id }}">		
	<div class="uk-panel uk-panel-box uk-margin-remove">
		<a href="{{ url($listing->path()) }}" style="text-decoration:none">
			<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}">
    		<div class="">
    			<p class="uk-margin-bottom-remove">
    				<strong class="uk-text-primary">{{ $listing->title }} 
    				@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
					<i class="uk-text-success uk-icon-check"></i>
					@endif
					</strong>
    				<br>
    				<b class="uk-text-bold">{{ money_format('$%!.0i', $listing->price) }}</b> | 
    				<i class="uk-text-muted">{{ number_format($listing->odometer) }} kms</i>
    			</p>
    		</div>
		</a>
	</div>
</div>