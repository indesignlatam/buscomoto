@extends('layouts.mobile.master')

@section('head')
	@if(Auth::user()->is('admin'))
    	<title>{{ trans('admin.payments') }} - {{ Settings::get('site_name') }}</title>
    @else
    	<title>{{ trans('admin.my_payments') }} - {{ Settings::get('site_name') }}</title>
    @endif
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		@if(Auth::user()->isAdmin())
		<h2>{{ trans('admin.payments') }}</h2>
		@else
		<h2>{{ trans('admin.my_payments') }}</h2>
		@endif

		<hr>

		<div class="uk-panel uk-margin-top">
			@if(count($payments) > 0)
				<table class="uk-table uk-table-hover">
					<thead>
						<tr>
							<th style="width:15px"></th>
							<th>{{ trans('admin.reference') }}</th>
							<th>{{ trans('admin.price') }}</th>
							<th style="width:15%" class="uk-hidden-small">{{ trans('admin.payment_method') }}</th>
							<th style="width:12%" class="uk-hidden-small">{{ trans('admin.updated') }}</th>
							<th style="width:5%"></th>
						</tr>
					</thead>
					@foreach($payments as $payment)
						<tr id="payment-{{ $payment->id }}">
							@if($payment->confirmed)
								<td class="uk-text-center uk-text-success"><i class="uk-icon-check-circle"></i></td>
							@elseif($payment->canceled)
								<td class="uk-text-center uk-text-danger"><i class="uk-icon-remove"></i></td>
							@else
								<td class="uk-text-center uk-text-warning"><i class="uk-icon-spinner uk-icon-spin"></i></td>
							@endif
							<td>{{ $payment->description }}</td>
							<td>{{ money_format('$%!.1i', $payment->amount) }}</td>
							<td class="uk-hidden-small">{{ $payment->payment_method_name }}</td>								
							<td class="uk-hidden-small">{{ $payment->updated_at->diffForHumans() }}</td>

							@if($payment->confirmed || $payment->canceled || $payment->state_pol)
								<td><button class="uk-button uk-button-small uk-button-danger" id="{{ $payment->id }}" onclick="cancel(this)" disabled><i class="uk-icon-remove"></i></button></td>
							@else
								<td><button class="uk-button uk-button-small uk-button-danger" id="{{ $payment->id }}" onclick="cancel(this)"><i class="uk-icon-remove"></i></button></td>
							@endif
						</tr>
					@endforeach
				</table>
			@else
				<div class="uk-text-center">
		    		<h3 class="uk-text-primary">{{ trans('admin.you_have_no_payments') }}</h3>
		    	</div>
			@endif
			
			<?php echo $payments->render(); ?>
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
	
	<script type="text/javascript">
		function cancel(sender) {
	    	UIkit.modal.confirm("{{ trans('admin.cancel_payment_sure') }}", function(){
			    $.post("{{ url('/admin/pagos') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
			    	if(result.success){
		            	$( "#payment-"+sender.id ).animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
		            	UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 3000});
			    	}else if(result.error){
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 3000});
			    	}
		        });			    
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }
	</script>
@endsection