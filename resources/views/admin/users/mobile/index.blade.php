@extends('layouts.mobile.master')

@section('head')
    <title>{{ trans('admin.users') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">

		<h2>{{ trans('admin.users') }}</h2>
		<a class="uk-button uk-button-large uk-button-primary uk-width-1-1" href="">{{ trans('admin.new') }}</a>

		<hr>

		@foreach($users as $user)
			<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-bottom">
				<a href="{{ url('admin/users/'.$user->id.'/edit') }}" class="uk-h3">{{$user->name}}</a>
				<br>
				<span>@if($user->confirmed)<i class="uk-icon-check uk-text-success"></i>@else<i class="uk-icon-remove uk-text-danger"></i>@endif</span> | 
				<span><i class="uk-icon-motorcycle"></i> {{ $user->listingCount }}</span> | 
				<span><i class="uk-icon-envelope-o"></i> {{ $user->email }}</span>
				<h4 class="uk-margin-remove">{{ $user->created_at->diffForHumans() }}</h4>

				<div class="uk-button-dropdown uk-width-1-1" data-uk-dropdown>
                    <button class="uk-button uk-button-primary uk-width-1-1">{{ trans('admin.actions') }} <i class="uk-icon-caret-down"></i></button>
                    <div class="uk-dropdown uk-dropdown-small">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><a href="{{ url('admin/users/'.$user->id.'/edit') }}">{{ trans('admin.edit') }}</a></li>
                            <li><a>Send message</a></li>
                            <li><a onclick="sendConfirmation({{ $user->id }})">Send confirmation</a></li>
                            <li><a>{{ trans('admin.delete') }}</a></li>
                        </ul>
                    </div>
                </div>
			</div>
		@endforeach
		<?php echo $users->render(); ?>	
	</div>
</div>
@endsection

@section('js')
	@parent
	<script type="text/javascript">
		function sendConfirmation(id){
			UIkit.modal.confirm("{{ trans('admin.sure_send_confirmation') }}", function(){
			    // will be executed on confirm.
			    $.get("{{ url('/admin/user/send_confirmation_email') }}/" + id, {_token: "{{ csrf_token() }}"}, function(result){
			    	if(result.success){
			    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 3000});
			    	}else if(result.errors){
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.errors, {pos:'top-right', status:'danger', timeout: 3000});
			    	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
		}
	</script>
@endsection
