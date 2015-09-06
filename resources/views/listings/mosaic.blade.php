@if(isset($mosaicClass) && $mosaicClass)
<div class="{{$mosaicClass}}">
@else
<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom">
@endif
	<a href="{{ url($listing->path()) }}" style="text-decoration:none">
	@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
    	<div class="uk-panel uk-panel-box" style="border-bottom-width:4px; border-bottom-color:{{$listing->featuredType->color}}; border-bottom-style: solid;">
    		<div class="uk-overlay uk-overlay-hover">
    			<div style="background-color:{{$listing->featuredType->color}}; position:absolute;" class="uk-text-center uk-text-contrast uk-h3">
					<p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right"><i class="{{$listing->featuredType->uk_class}}"></i> {{ strtoupper($listing->featuredType->name) }}</p>
				</div>
    @else
    	<div class="uk-panel uk-panel-hover uk-margin-remove">
    		<div class="uk-overlay uk-overlay-hover">
    		@if($listing->created_at->diffInDays(Carbon::now()) < 5)
				<div style="background-color:#2089cf; position:absolute;" class="uk-text-center uk-text-contrast uk-h3">
					<p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right" style="font-weight:300">{{ trans('frontend.new')}}</p>
				</div>
			@endif
    @endif
				<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}" style="width:380px; float:left" class="uk-margin-right">
			    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade">
			    	<ul style="list-style-type: none;margin-top:-5px" class="uk-text-contrast">
	    				@if($listing->rooms)
	    				<li><i class="uk-icon-check"></i> {{ $listing->rooms }} {{ trans('admin.rooms') }}</li>
	    				@endif
	    			</ul>
			    </div>
			    @if(isset($mosaicClass) && $mosaicClass)
			    <a onclick="unlike()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-primary" id="like_button_image"></i></a>
			    @endif
		@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
			</div>
		@else
			</div>
		@endif
    		<div class="">
    			<p class="">
    				<strong class="uk-text-primary">{{ $listing->title }}</strong>
    				<br>
    				<b class="uk-text-bold">{{ money_format('$%!.0i', $listing->price) }}</b> | 
    				<i class="uk-text-muted">{{ number_format($listing->odometer) }} kms</i>
    			</p>
    		</div>
	@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
		</div>
	@else
		</div>
	@endif
	</a>
</div>