<!-- This is the modal -->
<div id="new_object_modal" class="uk-modal" data-focus-on="input:first">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="uk-modal-header">
            {{ trans('admin.create_feature') }}
        </div>

        <form id="create_form" class="uk-form uk-form-horizontal" method="POST" action="{{ url('/admin/features') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="category" name="category_id" placeholder="{{ trans('admin.category') }}" value="{{ old('category_id') }}">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

			<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">
		</form>

		<div class="uk-modal-footer">
			<button form="create_form" type="submit" class="uk-button uk-button-primary">{{ trans('admin.save') }}</button>
	        <a href="" class="uk-button uk-modal-close">{{ trans('admin.cancel') }}</a>
	    </div>
	    
    </div>
</div>