<!-- This is the modal -->
<div id="new_object_modal" class="uk-modal" data-focus-on="input:first">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="uk-modal-header">
            {{ trans('admin.create_model') }}
        </div>

        <form class="uk-form uk-form-horizontal" onkeypress="return event.keyCode != 13;">
			<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" id="input_name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">

            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="input_manufacturer_id" placeholder="{{ trans('admin.manufacturer') }}" value="{{ old('manufacturer_id') }}">
                @foreach($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                @endforeach
            </select>
        </form>

		<div class="uk-modal-footer">
			<button type="button" class="uk-button uk-button-primary" onclick="newObject()" id="new_button">{{ trans('admin.save') }}</button>
	        <a href="" class="uk-button uk-modal-close">{{ trans('admin.cancel') }}</a>
	    </div>
	    
    </div>
</div>