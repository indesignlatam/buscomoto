<!-- This is the modal -->
<div id="order_modal" class="uk-modal">
    <div class="uk-modal-dialog">

        <div>
            <h3 class="uk-margin-top-remove uk-text-bold uk-display-inline">{{ trans('frontend.order') }}</h3>
            <a class="uk-modal-close uk-button uk-align-right">Ocultar <i class="uk-icon-close"></i></a>
        </div>
        
        <div class="uk-form">
            <select form="search_form" class="uk-form-large uk-width-1-1 uk-margin-small-top" name="take" onchange="this.form.submit()">
                <option value="">{{ trans('admin.elements_amount') }}</option>
                @if(Request::get('take') == 50)
                    <option value="50" selected>{{ trans('admin.elements_50') }}</option>
                @elseif(session('listings_take') == 50)
                    <option value="50" selected>{{ trans('admin.elements_50') }}</option>
                @else
                    <option value="50">{{ trans('admin.elements_50') }}</option>
                @endif

                @if(Request::get('take') == 30)
                    <option value="30" selected>{{ trans('admin.elements_30') }}</option>
                @elseif(session('listings_take') == 30)
                    <option value="30" selected>{{ trans('admin.elements_30') }}</option>
                @else
                    <option value="30">{{ trans('admin.elements_30') }}</option>
                @endif

                @if(Request::get('take') == 10)
                    <option value="10" selected>{{ trans('admin.elements_10') }}</option>
                @elseif(session('listings_take') == 10)
                    <option value="10" selected>{{ trans('admin.elements_10') }}</option>
                @else
                    <option value="10">{{ trans('admin.elements_10') }}</option>
                @endif
            </select>

            <select form="search_form" class="uk-form-large uk-width-1-1 uk-margin-small-top" name="order_by" onchange="this.form.submit()">
                <option value="0">{{ trans('admin.order_by_relevance') }}</option>
                @if(Request::get('order_by') && Request::get('order_by') == 'id_desc')
                    <option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
                @elseif(session('listings_order_by') == 'id_desc')
                    <option value="id_desc" selected>{{ trans('admin.order_newer_first')}}</option>
                @else
                    <option value="id_desc">{{ trans('admin.order_newer_first')}}</option>
                @endif

                @if(Request::get('order_by') && Request::get('order_by') == 'id_asc')
                    <option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
                @elseif(session('listings_order_by') == 'id_asc')
                    <option value="id_asc" selected>{{ trans('admin.order_older_first')}}</option>
                @else
                    <option value="id_asc">{{ trans('admin.order_older_first')}}</option>
                @endif

                @if(Request::get('order_by') && Request::get('order_by') == 'price_max')
                    <option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
                @elseif(session('listings_order_by') == 'price_max')
                    <option value="price_max" selected>{{ trans('admin.order_expensive_first') }}</option>
                @else
                    <option value="price_max">{{ trans('admin.order_expensive_first') }}</option>
                @endif

                @if(Request::get('order_by') && Request::get('order_by') == 'price_min')
                    <option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
                @elseif(session('listings_order_by') == 'price_min')
                    <option value="price_min" selected>{{ trans('admin.order_cheaper_first') }}</option>
                @else
                    <option value="price_min">{{ trans('admin.order_cheaper_first') }}</option>
                @endif
            </select>
        </div>
	    
    </div>
</div>