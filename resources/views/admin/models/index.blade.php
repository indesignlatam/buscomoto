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

	        <div class="uk-align-right">
	        	<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
			        <input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-form-width-small" value="{{ Request::get('search') }}">

			        <select name="manufacturer" onchange="this.form.submit()">
				    	<option value="">{{ trans('admin.manufacturer') }}</option>
				    	@foreach($manufacturers as $manufacturer)
				    		@if(Request::get('manufacturer') == $manufacturer->id)
		                    	<option value="{{ $manufacturer->id }}" selected="">{{ $manufacturer->name }}</option>
		                    @else
		                    	<option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
		                    @endif
		                @endforeach
				    </select>

					<select name="take" onchange="this.form.submit()">
				    	<option value="">{{ trans('admin.elements_amount') }}</option>
				    	@if(Request::get('take') == 50)
				    		<option value="50" selected>{{ trans('admin.elements_50') }}</option>
				    	@else
				    		<option value="50">{{ trans('admin.elements_50') }}</option>
				    	@endif

				    	@if(Request::get('take') == 30)
				    		<option value="30" selected>{{ trans('admin.elements_30') }}</option>
				    	@else
				    		<option value="30">{{ trans('admin.elements_30') }}</option>
				    	@endif

				    	@if(Request::get('take') == 10)
				    		<option value="10" selected>{{ trans('admin.elements_10') }}</option>
				    	@else
				    		<option value="10">{{ trans('admin.elements_10') }}</option>
				    	@endif
				    </select>

				    <select name="order_by" onchange="this.form.submit()">
				    	<option value="">{{ trans('admin.order_by') }}</option>
				    	
				    	@if(Request::get('order_by') == 'id_desc')
				    		<option value="id_desc" selected>{{ trans('admin.order_newer_first') }}</option>
				    	@else
				    		<option value="id_desc">{{ trans('admin.order_newer_first') }}</option>
				    	@endif

				    	@if(Request::get('order_by') == 'id_asc')
				    		<option value="id_asc" selected>{{ trans('admin.order_older_first') }}</option>
				    	@else
				    		<option value="id_asc">{{ trans('admin.order_older_first') }}</option>
				    	@endif

				    	@if(Request::get('order_by') == 'manufacturer_desc')
				    		<option value="manufacturer_desc" selected>{{ trans('admin.order_manufacturer_desc') }}</option>
				    	@else
				    		<option value="manufacturer_desc">{{ trans('admin.order_manufacturer_desc') }}</option>
				    	@endif

				    	@if(Request::get('order_by') == 'manufacturer_asc')
				    		<option value="manufacturer_asc" selected>{{ trans('admin.order_manufacturer_asc') }}</option>
				    	@else
				    		<option value="manufacturer_asc">{{ trans('admin.order_manufacturer_asc') }}</option>
				    	@endif
				    </select>
				</form>
			</div>
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
	            <tbody id="models">
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

	    function newObject(sender) {
	    	$('#new_button').prop("disabled");
	    	name = $('#input_name').val();
	    	manufacturer = $('#input_manufacturer_id').val();
	    	console.log(manufacturer);

	        $.post("{{ url('/admin/models') }}", {_token: "{{ csrf_token() }}", manufacturer_id: manufacturer, name: name}, function(result){
	            console.log(result);
	            if(result.success && result.model){
	            	model = result.model;
	            	div = '<tr><td><input type="checkbox" name="checkedLine" value="'+ model.id +'"/></td><td>'+ model.id +'</td><td>'+ model.name +'</td><td>'+ model.manufacturer.name +'</td><td><div class="uk-button-dropdown" data-uk-dropdown><button class="uk-button">{{ trans('admin.actions') }} <i class="uk-icon-caret-down"></i></button><div class="uk-dropdown uk-dropdown-small"><ul class="uk-nav uk-nav-dropdown"><li><a href="">{{ trans('admin.edit') }}</a></li><li><a href="">{{ trans('admin.clone') }}</a></li><li><a id="'+ model.id +'" onclick="deleteObject(this)">{{ trans('admin.delete') }}</a></li></ul></div></div></td></tr>'
	            	$('#models').prepend(div);
	            	$('#input_name').val('');
		    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
	            }else{
	            	console.log(result.errors);
		    		UIkit.notify('<i class="uk-icon-remove"></i> Failed to create item' , {pos:'top-right', status:'danger', timeout: 5000});
	            }
	        });
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