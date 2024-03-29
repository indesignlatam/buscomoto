@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.confirm_payment') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.confirm_payment') }}</h1>

	    <hr>

	    <div class="uk-grid">
	    	<div class="uk-width-small-1-1 uk-width-medium-1-1 uk-width-large-7-10">
			   	<div>
					<h3>{{ trans('admin.confirm_payment_title') }}</h3>
					<p>{{ trans('admin.confirm_payment_text') }}</p>
				</div>

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

	    	</div>

	    	<div class="uk-hidden-large uk-margin-top"></div>

	    	<div class="uk-width-small-1-1 uk-width-medium-1-1 uk-width-large-3-10">
	    		<div class="uk-panel uk-panel-box uk-panel-box-primary">
	    		<h3 class="uk-panel-title">{{ trans('admin.shop_basket') }}</h3>
		    		<table class="uk-table uk-table-striped">
		    			<thead>
		    				<tr>
					            <th style="width:65%">{{ trans('admin.item') }}</th>
					            <th style="width:35%">{{ trans('admin.price') }}</th>
					        </tr>
		    			</thead>
		    			<tbody>
		    				<tr>
		    					<td id="name">{{ $payment->description }}</td>
		    					<td id="price">{{ money_format('$%!.1i', $payment->tax_return_base) }}</td>
		    				</tr>
		    				<tr><td></td><td></td></tr>
		    			</tbody>

		    			<tfoot>
					        <tr>
					            <td>{{ trans('admin.tax') }}</td>
					            <td id="iva">{{ money_format('$%!.1i', $payment->tax) }}</td>
					        </tr>
					        <tr class="uk-text-bold">
					            <td>{{ trans('admin.total') }}</td>
					            <td id="total">{{ money_format('$%!.1i', $payment->amount) }}</td>
					        </tr>
					    </tfoot>
		    		</table>

		    		@if(Settings::get('payu_test', 1))
		    			<form id="payu" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ config('payu.test_url') }}">
							<input name="merchantId" 	type="hidden"  value="{{ config('payu.test_merchant_id') }}"/>
							<input name="accountId" 	type="hidden"  value="{{ config('payu.test_account_id') }}"/>
						    <input name="description"   type="hidden"  value="{{ $payment->description }}"  />
						    <input name="referenceCode" type="hidden"  value="{{ $payment->reference_code }}" />
						    <input name="amount"        type="hidden"  value="{{ number_format($payment->amount, 2, '.', '') }}"   />
						    <input name="tax"           type="hidden"  value="{{ number_format($payment->tax, 2, '.', '') }}"  />
						    <input name="taxReturnBase" type="hidden"  value="{{ number_format($payment->tax_return_base, 2, '.', '') }}" />
						    <input name="currency"      type="hidden"  value="{{ $payment->currency }}" />
						    <input name="signature"     type="hidden"  value="{{ $payment->signature }}"  />
						    <input name="test"          type="hidden"  value="1" />
						    <input name="buyerEmail"    type="hidden"  value="{{ $payment->user->email }}" />
						    <input name="lng" 			type="hidden"  value="es"/>
							<input name="sourceUrl"  	type="hidden"  value="{{ Request::url() }}"/>
						    <input name="responseUrl"   type="hidden"  value="{{ config('payu.response_url') }}" />
						    <input name="confirmationUrl" type="hidden"  value="{{ config('payu.confirmation_url') }}" />

							<div class="uk-margin-top uk-text-center">
						        <button form="payu" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1" onclick="blockUI()">{{ trans('admin.confirm_payment') }}</button>
								<a class="uk-button uk-margin-top uk-width-1-1" onclick="cancelPayment()">{{ trans('admin.cancel') }}</a>
						    </div>
						</form>
					@else	
						<form id="payu" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ config('payu.url') }}">
							<input name="merchantId" 	type="hidden"  value="{{ config('payu.merchant_id') }}"/>
							<input name="accountId" 	type="hidden"  value="{{ config('payu.account_id') }}"/>
						    <input name="description"   type="hidden"  value="{{ $payment->description }}"  />
						    <input name="referenceCode" type="hidden"  value="{{ $payment->reference_code }}" />
						    <input name="amount"        type="hidden"  value="{{ number_format($payment->amount, 0, '.', '') }}"   />
						    <input name="tax"           type="hidden"  value="{{ number_format($payment->tax, 0, '.', '') }}"  />
						    <input name="taxReturnBase" type="hidden"  value="{{ number_format($payment->tax_return_base, 0, '.', '') }}" />
						    <input name="currency"      type="hidden"  value="{{ $payment->currency }}" />
						    <input name="signature"     type="hidden"  value="{{ $payment->signature }}"  />
						    <input name="test"          type="hidden"  value="0" />
						    <input name="buyerEmail"    type="hidden"  value="{{ $payment->user->email }}" />
						    <input name="lng" 			type="hidden"  value="es"/>
							<input name="sourceUrl"  	type="hidden"  value="{{ Request::url() }}"/>
						    <input name="responseUrl"   type="hidden"  value="{{ config('payu.response_url') }}" />
						    <input name="confirmationUrl" type="hidden"  value="{{ config('payu.confirmation_url') }}" />

							<div class="uk-margin-top uk-text-center">
						        <button form="payu" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1" onclick="blockUI()">{{ trans('admin.pay') }}</button>
								<a class="uk-button uk-margin-top uk-width-1-1" onclick="cancelPayment()">{{ trans('admin.cancel') }}</a>
						    </div>
						</form>
					@endif

					
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="{{ asset('/js/accounting.min.js') }}"></script>

	<script type="text/javascript">
		$(function() {
			// window.onbeforeunload = function() {
			// 	return "Estas seguro que deseas abandonar el pago?";
			// }
		});

		function blockUI(){
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">{{ trans("admin.wait_while_redirect_payment") }}</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>', {center: true}); // modal.hide() to unblock
	    }

	    function cancelPayment() {
	    	UIkit.modal.confirm("{{ trans('admin.cancel_payment_sure') }}", function(){
			    // will be executed on confirm.
			    $.post("{{ url('/admin/pagos/'.$payment->id) }}", {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(response){
	                window.location.replace("{{url('/admin/pagos')}}");
	            });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
            
        }
	</script>
@endsection