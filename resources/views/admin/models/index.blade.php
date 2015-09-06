@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.models') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.models') }}</h1>
	    <hr>
	    <div class="">
	        <!-- This is a button toggling the modal -->
	        <button class="uk-button" data-uk-modal="{target:'#new_object_modal', center: true}">{{ trans('admin.new') }}</button>
	        <button class="uk-button uk-button-danger" onclick="deleteObjects()"><i class="uk-icon-trash"></i></button>
	    </div>

		<div class="uk-margin-top">
			<table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">{{ trans('admin.id') }}</th>
	                    <th>{{ trans('admin.name') }}</th>
	                    <th style="width:120px">{{ trans('admin.manufacturer') }}</th>
	                    <th style="width:120px">{{ trans('admin.actions') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($models as $model)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$model->id}}"/></td>
	                        <td>{{ $model->id }}</td>
	                        <td>{{ $model->name }}</td>
	                        <td>{{ $model->manufacturer->name }}</td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">{{ trans('admin.actions') }} <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="">{{ trans('admin.edit') }}</a></li>
	                                        <li><a href="">{{ trans('admin.clone') }}</a></li>
	                                        <li><a id="{{ $model->id }}" onclick="deleteObject(this)">{{ trans('admin.delete') }}</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $models->render(); ?>
		</div>
		@include('admin.models.new')
	</div>
</div>
@endsection

@section('js')
	@parent
	<script type="text/javascript">
	    function toggle(source){
	        checkboxes = document.getElementsByName('checkedLine');
	        for(var i=0, n=checkboxes.length;i<n;i++) {
	            checkboxes[i].checked = source.checked;
	        }
	    }

	    function deleteObject(sender) {
	        $.post("{{ url('/admin/models') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	            location.reload();
	        });
	    }

	    function deleteObjects() {
            var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
                return this.value;
            }).get();
            $.post("{{ url('/admin/models/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
                location.reload();
            });
        }
	</script>
@endsection