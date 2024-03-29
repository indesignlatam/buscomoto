@extends('layouts.master')

@section('head')
    <title>Users - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="/css/components/datepicker.almost-flat.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<div>
			<h1 class="uk-display-inline">Users</h1>
			<a class="uk-button uk-button-large uk-button-primary uk-align-right" href="">New</a>
		</div>

		<hr>

		<div class="uk-panel">
			<table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">id</th>
	                    <th style="width:15px"></th>
	                    <th>Name</th>
	                    <th>email</th>
	                    <th><i class="uk-icon-home"></i></th>
	                    <th style="width:120px">Updated</th>
	                    <th style="width:90px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($users as $user)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$user->id}}" /></td>
	                        <td>{{ $user->id }}</td>
	                        <td>@if($user->confirmed)<i class="uk-icon-check uk-text-success"></i>@else<i class="uk-icon-remove uk-text-danger"></i>@endif</td>
	                        <td><a href="{{ url('admin/users/'.$user->id.'/edit') }}">{{$user->name}}</a></td>
	                        <td>{{ $user->email }}</td>
	                        <td>{{ $user->listingCount }}</td>
	                        <td>{{ $user->created_at->diffForHumans() }}</td>
	                        <td>
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="{{ url('admin/users/'.$user->id.'/edit') }}">Edit</a></li>
	                                        <li><a>Send message</a></li>
	                                        <li><a onclick="sendConfirmation({{ $user->id }})">Send confirmation</a></li>
	                                        <li><a>Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $users->render(); ?>
		</div>
	
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="/js/components/datepicker.min.js"></script>

	<script type="text/javascript">
		function sendConfirmation(id){
			UIkit.modal.confirm("{{ trans('admin.sure_send_confirmation') }}", function(){
			    // will be executed on confirm.
			    $.get("{{ url('/admin/user/send_confirmation_email') }}/" + id, {_token: "{{ csrf_token() }}"}, function(result){
			    	if(result.success){
			    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 5000});
			    	}else if(result.error){
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 5000});
			    	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
		}
	</script>
@endsection
