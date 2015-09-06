@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.manufacturers') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.manufacturers') }}</h1>
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
	                    <th style="width:120px">{{ trans('admin.country') }}</th>
	                    <th style="width:120px">{{ trans('admin.actions') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($manufacturers as $manufacturer)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$manufacturer->id}}"/></td>
	                        <td>{{ $manufacturer->id }}</td>
	                        <td>{{ $manufacturer->name }}</td>
	                        <td></td><!--  $manufacturer->country->name  -->
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
	                                        <li><a id="{{ $manufacturer->id }}" onclick="deleteObject(this)">{{ trans('admin.delete') }}</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $manufacturers->render(); ?>
		</div>
		@include('admin.manufacturers.new')
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
	        $.post("{{ url('/admin/manufacturers') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	            location.reload();
	        });
	    }

	    function deleteObjects() {
            var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
                return this.value;
            }).get();
            $.post("{{ url('/admin/manufacturers/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
                location.reload();
            });
        }
	</script>
@endsection