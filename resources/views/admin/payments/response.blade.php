@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.transaction_result') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.transaction_result') }}</h1>

	    <div class="uk-grid uk-margin-top">
		    <div class="uk-width-small-1-1 uk-width-medium-2-3 uk-width-large-2-3">
		    	@if(Request::get('transactionState') == 4)
			    	<img src="{{ asset('/images/support/payments/payment_succeeded.png') }}" class="uk-align-center">
					
					<!-- Listing preview -->
			    	<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom">							
						<div class="uk-panel uk-panel-hover uk-margin-remove">
							<a style="text-decoration:none">
								<div id="tag" style="background-color:#1C7BBA; position:absolute; top:15px; left:15px;" class="uk-text-center uk-text-contrast uk-h3">
									<p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right">
										<i class="uk-icon-check"></i>
									</p>
								</div>
								<img src="{{ asset(Image::url($payment->listing->image_path(),['mini_image_2x'])) }}">
					    		<div class="">
					    			<p class="uk-margin-bottom-remove">
					    				<strong class="uk-text-primary">{{ $payment->listing->title }}</strong>
					    				<br>
					    				<b class="uk-text-bold">{{ money_format('$%!.0i', $payment->listing->price) }}</b> | 
					    				<i class="uk-text-muted">{{ number_format($payment->listing->odometer) }} kms</i>
					    			</p>
					    		</div>
							</a>
						</div>
					</div>
					<!-- Listing preview -->

			    @elseif(Request::get('transactionState') == 6 && Request::get('polResponseCode') == 6)
			    	<img src="{{ asset('/images/support/payments/payment_denied_funds.png') }}" class="uk-align-center">
			    @elseif(Request::get('transactionState') == 6)
			    	<img src="{{ asset('/images/support/payments/payment_denied.png') }}" class="uk-align-center">
			    @elseif(Request::get('transactionState') == 7 && Request::get('polResponseCode') == 25)
			    	<img src="{{ asset('/images/support/payments/payment_pending_generated.png') }}" class="uk-align-center">
			    @elseif(Request::get('transactionState') == 7)
			    	<img src="{{ asset('/images/support/payments/payment_pending.png') }}" class="uk-align-center">
			    @else
			    	<img src="{{ asset('/images/support/payments/payment_denied.png') }}" class="uk-align-center">
			    	<h3 class="uk-margin-remove">{{ trans('admin.payment_response_error_text') }}</h3>
			    @endif
		    </div>

	    	<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
			@if($signature == Request::get('signature'))
				<h2 class="uk-text-bold">{{ trans('admin.transaction_info') }}</h2>
				<table class="uk-table uk-table-striped uk-table-hover">
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_state') }}</td>
						<td>
							@if(Request::get('transactionState') == 4)
								<b class="uk-text-success uk-h3">{{ trans('admin.transaction_approved') }}</b>
							@elseif(Request::get('transactionState') == 6)
								<b class="uk-text-warning">"{{ trans('admin.transaction_denied') }}</b>
							@elseif(Request::get('transactionState') == 104)
								<b class="uk-text-danger">{{ trans('admin.transaction_error') }}</b>
							@elseif(Request::get('transactionState') == 7)
								<b class="uk-text-bold">{{ trans('admin.transaction_pending') }}</b>
							@else
								{{Request::get('mensaje')}}
							@endif
						</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_description') }}</td>
						<td>{{Request::get('description')}}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_id') }}</td>
						<td>{{Request::get('transactionId')}}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_sale_reference') }}</td>
						<td>{{Request::get('reference_pol')}}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_reference') }}</td>
						<td>{{Request::get('referenceCode')}}</td>
					</tr>
				@if(Request::get('pseBank')) {
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_cus') }}</td>
						<td>{{Request::get('cus')}}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_bank') }}</td>
						<td>{{Request::get('pseBank')}}</td>
					</tr>
				@endif
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_total') }}</td>
						<td>{{ money_format('$%!.1i', Request::get('TX_VALUE')) }}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_currency') }}</td>
						<td>{{Request::get('currency')}}</td>
					</tr>
					<tr>
						<td class="uk-text-bold">{{ trans('admin.transaction_entity') }}</td>
						<td>{{Request::get('lapPaymentMethod')}}</td>
					</tr>
				</table>
			@else
				<h3 class="uk-text-warning">{{ trans('admin.error_validating_signature') }}</h3>
			@endif
			</div>
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection