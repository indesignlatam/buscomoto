<script type="text/javascript">
'<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom">
	<a href="{{ url('/') }}/buscar/'+listing.slug+'" style="text-decoration:none">
    	<div class="uk-panel uk-panel-hover uk-margin-remove">
    		<img src="'+listing.image_url+'" style="width:380px; float:left" class="uk-margin-right">
    		<div class="">
    			<p class="">
    				<strong class="uk-text-primary">'+listing.title+'</strong>
    				<br>
    				<b class="uk-text-bold">$'+accounting.formatNumber(listing.price)+'</b> | 
    				<i class="uk-text-muted">'+accounting.formatNumber(listing.odometer)+' kms</i>
    			</p>
    		</div>
		</div>
	</a>
</div>'
</script>
