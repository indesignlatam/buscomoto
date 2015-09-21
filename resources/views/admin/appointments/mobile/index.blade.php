@extends('layouts.mobile.master')

@section('head')
    <title>{{ trans('admin.messages') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<style type="text/css">
		.read{
			max-height: 95px;
		}
	</style>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
	@if(Auth::user()->isAdmin())
		<h2>{{ trans('admin.messages') }}</h2>

		@if(isset($listing))
			<h3 class="uk-margin-remove"><i class="uk-text-primary">{{ $listing->title }}</i></h3>
		@endif

		<hr>

		<div class="uk-panel">
			<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
		        <input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-width-1-1 uk-margin-small-bottom" value="{{ Request::get('search') }}">
				<select name="take" onchange="this.form.submit()" class="uk-width-1-1 uk-margin-small-bottom">
			    	<option value="">Cantidad de publicaciones</option>
			    	@if(Request::get('take') == 50)
			    		<option value="50" selected>Ver 50</option>
			    	@else
			    		<option value="50">Ver 50</option>
			    	@endif

			    	@if(Request::get('take') == 30)
			    		<option value="30" selected>Ver 30</option>
			    	@else
			    		<option value="30">Ver 30</option>
			    	@endif

			    	@if(Request::get('take') == 10)
			    		<option value="10" selected>Ver 10</option>
			    	@else
			    		<option value="10">Ver 10</option>
			    	@endif
			    </select>

			    <select name="order_by" onchange="this.form.submit()" class="uk-width-1-1 uk-margin-small-bottom">
			    	<option value="">Ordenar por</option>
			    	
			    	@if(Request::get('order_by') == 'id_desc')
			    		<option value="id_desc" selected>Fecha creación</option>
			    	@else
			    		<option value="id_desc">Fecha creación</option>
			    	@endif

			    	@if(Request::get('order_by') == 'exp_desc')
			    		<option value="exp_desc" selected>Fecha expiración</option>
			    	@else
			    		<option value="exp_desc">Fecha expiración</option>
			    	@endif
			    </select>
			</form>
		</div>

	    <div class="uk-panel uk-margin-top">
			@if(count($appointments) > 0)
				<div id="messages">
					@foreach($appointments as $appointment)
					<div class="uk-panel uk-panel-box uk-margin-bottom" id="message-{{ $appointment->id }}">
						<div class="uk-grid">
							<div class="uk-width-1-3">
								<img src="{{ asset(Image::url($appointment->listing->image_path(),['map_mini'])) }}">
							</div>

							<div class="uk-width-2-3">
								<p class="uk-margin-remove uk-text-bold">{{ $appointment->name }}</p>
								<span><i class="uk-icon-phone"></i> {{ $appointment->phone }}</span> | 
								@if($appointment->listing->user->confirmed)
								<span><i class="uk-icon-user uk-text-success"></i></span>
		                        @else
								<span><i class="uk-icon-user uk-text-warning"></i></span>
		                        @endif
		                        <br>
								<span><i class="uk-icon-envelope-o"></i> <i class="uk-text-small">{{ $appointment->email }}</i></span>
							</div>

							<div class="uk-width-1-1">
								<p>{{ $appointment->comments }}</p>
							</div>

							<div class="uk-width-1-1 uk-margin-small-top buttons">
								<div class="uk-flex">
		                    		<!-- Reply button -->
		                    		@if(Auth::user()->confirmed)
				    					@if(!$appointment->answered)
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})"><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-button-primary uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
					    				@else
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
					    				@endif
					    			@else
					    				@if(!$appointment->answered)
											<a href="{{ url('admin/user/not_confirmed') }}" class="uk-button uk-button-success uk-width-1-1"><i class="uk-icon-reply"></i></a>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
					    				@else
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
					    				@endif
					    			@endif
					    			<!-- Reply button -->
				    				<a id="delete-{{$appointment->id}}" class="uk-button uk-button-danger uk-width-1-1" onclick="deleteObject({{ $appointment->id }})"><i class="uk-icon-remove"></i></a>
		                    	</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<?php echo $appointments->appends(Request::all())->render(); ?>
			@endif
		</div>
	@else
	<!-- Non admin view -->
		<h2>{{ trans('admin.my_messages') }}</h2>

		@if(isset($listing))
			<h3 class="uk-margin-remove"><i class="uk-text-primary">{{ $listing->title }}</i></h3>
		@endif

		<hr>

		<div class="uk-panel uk-margin-top">
			<!-- Order by -->
			<div class="uk-text-right">
				<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-hidden-small">
		        	<input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-width-1-1" value="{{ Request::get('search') }}">
					<select name="take" onchange="this.form.submit()" class="uk-width-1-1">
				    	<option value="">Cantidad de publicaciones</option>
				    	@if(Request::get('take') == 50)
				    		<option value="50" selected>Ver 50</option>
				    	@else
				    		<option value="50">Ver 50</option>
				    	@endif

				    	@if(Request::get('take') == 30)
				    		<option value="30" selected>Ver 30</option>
				    	@else
				    		<option value="30">Ver 30</option>
				    	@endif

				    	@if(Request::get('take') == 10)
				    		<option value="10" selected>Ver 10</option>
				    	@else
				    		<option value="10">Ver 10</option>
				    	@endif
				    </select>

				    <select name="order_by" onchange="this.form.submit()" class="uk-width-1-1">
				    	<option value="">Ordenar por</option>
				    	
				    	@if(Request::get('order_by') == 'id_desc')
				    		<option value="id_desc" selected>Recientes primero</option>
				    	@else
				    		<option value="id_desc">Recientes primero</option>
				    	@endif

				    	@if(Request::get('order_by') == 'id_asc')
				    		<option value="id_asc" selected>Antiguos primero</option>
				    	@else
				    		<option value="id_asc">Antiguos primero</option>
				    	@endif
				    </select>
				</form>
			</div>
			<!-- Order by -->

			@if(count($appointments) > 0)
				<div id="messages">
					@foreach($appointments as $appointment)
					<div class="uk-panel uk-panel-box uk-margin-bottom" id="message-{{ $appointment->id }}">
						<div class="uk-grid">
							<div class="uk-width-1-3">
								<img src="{{ asset(Image::url($appointment->listing->image_path(),['map_mini'])) }}">
							</div>

							<div class="uk-width-2-3">
								<p class="uk-margin-remove uk-text-bold">{{ $appointment->name }}</p>
								<span><i class="uk-icon-phone"></i> {{ $appointment->phone }}</span>
		                        <br>
								<span><i class="uk-icon-envelope-o"></i> <i class="uk-text-small">{{ $appointment->email }}</i></span>
							</div>

							<div class="uk-width-1-1">
								<p>{{ $appointment->comments }}</p>
							</div>

							<div class="uk-width-1-1 uk-margin-small-top buttons">
								<div class="uk-flex">
		                    		<!-- Reply button -->
		                    		@if(Auth::user()->confirmed)
				    					@if(!$appointment->answered)
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})"><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-button-primary uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
					    				@else
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
					    				@endif
					    			@else
					    				@if(!$appointment->answered)
											<a href="{{ url('admin/user/not_confirmed') }}" class="uk-button uk-button-success uk-width-1-1"><i class="uk-icon-reply"></i></a>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
					    				@else
				    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>

				    						<button id="mark-read-{{$appointment->id}}" class="uk-button uk-width-1-1" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
					    				@endif
					    			@endif
					    			<!-- Reply button -->
				    				<a id="delete-{{$appointment->id}}" class="uk-button uk-button-danger uk-width-1-1" onclick="deleteObject({{ $appointment->id }})"><i class="uk-icon-remove"></i></a>
		                    	</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<?php echo $appointments->appends(Request::all())->render(); ?>
			@else
		    	<div class="uk-text-center">
					<h2 class="uk-text-bold uk-text-muted">{{ trans('admin.you_have_no_messages') }}</h2>
		    	</div>
			@endif
		</div>
	@endif
	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>

	<script type="text/javascript">
	    function answerMessage(objectID){
	    	message = $('#message-'+objectID).clone();
	    	message.find('.buttons').remove();

	    	UIkit.modal.prompt('<h3 class="uk-modal-header uk-margin-bottom">{{ trans("admin.answer_message_modal") }}</h3>' + message.html() + '<hr>{{ trans("admin.answer_message_prompt") }}', '', function(newvalue){
	    		$("#answer-"+objectID).prop('disabled', true);
			    $("#mark-read-"+objectID).prop('disabled', true);
			    $("#delete-"+objectID).prop('disabled', true);
			    // will be executed on submit.
			    $.post("{{ url('/admin/messages') }}/"+objectID+"/answer", {_token: "{{ csrf_token() }}", comments : newvalue}, function(result){
			    	if(result.success){
			    		$("#message-"+objectID).appendTo($('messages'));
			    		$("#delete-"+objectID).prop('disabled', false);
			    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 3000});
			    	}else{
			    		$("#answer-"+objectID).prop('disabled', false);
			    		$("#mark-read-"+objectID).prop('disabled', false);
			    		$("#delete-"+objectID).prop('disabled', false);
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 3000});
			    	}
		        });
			}, {row:5, labels:{Ok:'{{trans("admin.send")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

	    function mark(objectID, read){
	    	$("#answer-"+objectID).prop('disabled', true);
			$("#mark-read-"+objectID).prop('disabled', true);
			$("#delete-"+objectID).prop('disabled', true); 	
		    // will be executed on submit.
		    $.post("{{ url('/admin/messages') }}/"+objectID+"/mark", {_token: "{{ csrf_token() }}", mark : read}, function(result){
		    	if(result.success){
		        	$("#message-"+objectID).appendTo($('messages'));
				    $("#answer-"+objectID).prop('disabled', result.mark);
				    $("#mark-read-"+objectID).prop('disabled', result.mark);
					$("#delete-"+objectID).prop('disabled', false);
				    UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 3000});
				}else{
					if(read){
						$("#answer-"+objectID).prop('disabled', false);
				    	$("#mark-read-"+objectID).prop('disabled', false);
				    	$("#delete-"+objectID).prop('disabled', false);
					}else{
						$("#answer-"+objectID).prop('disabled', true);
				    	$("#mark-read-"+objectID).prop('disabled', true);
				    	$("#delete-"+objectID).prop('disabled', false);
					}
					UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 3000});
				}
	        });
	    }

	    function deleteObject(objectID) {
	    	UIkit.modal.confirm("{{ trans('admin.sure') }}", function(){
			    // will be executed on confirm.
			    $("#message-"+objectID).fadeOut(500);

		        $.post("{{ url('/admin/messages') }}/" + objectID, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
		        	if(result.success){
		        		$("#message-"+objectID).remove();
		        		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 3000});
		        	}else if(result.error){
		        		$("#message-"+objectID).fadeIn(500);
		        		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 3000});
		        	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

	    // function toggle(source){
	    //     checkboxes = document.getElementsByName('checkedLine');
	    //     for(var i=0, n=checkboxes.length;i<n;i++) {
	    //         checkboxes[i].checked = source.checked;
	    //     }
	    // }

	    // function deleteObjects() {
     //        var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
     //            return this.value;
     //        }).get();
     //        $.post("{{ url('/admin/appointments/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
     //            location.reload();
     //        });
     //    }
	</script>
@endsection