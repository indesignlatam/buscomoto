<!-- This is the modal -->
<div id="search_modal" class="uk-modal">
    <div class="uk-modal-dialog">

        <div>
            <h3 class="uk-margin-top-remove uk-text-bold uk-display-inline">{{ trans('frontend.search_button') }}</h3>
            <a class="uk-modal-close uk-button uk-align-right">{{ trans('frontend.hide') }} <i class="uk-icon-close"></i></a>
        </div>
        
        <form id="search_form" class="uk-form uk-form-stacked uk-margin-top" method="GET" action="{{ url(Request::path()) }}">
            <div class="uk-form-row">
                <label class="uk-form-label">{{ trans('frontend.search_listing_type') }}</label>
                <select form="search_form" class="uk-width-1-1" name="listing_type">
                    <option value>{{ trans('frontend.search_select_option') }}</option>
                    @foreach($listingTypes as $type)
                        @if($type->id == Request::get('listing_type'))
                            <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                        @else
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="uk-form-row uk-margin-remove">
                <label class="uk-form-label">{{ trans('frontend.search_manufacturer') }}</label>
                <select form="search_form" class="uk-width-1-1" id="search_manufacturer" name="manufacturers[]" multiple="multiple">
                    @foreach($manufacturers as $manufacturer)
                        @if(is_array(Request::get('manufacturers')) && in_array($manufacturer->id, Request::get('manufacturers')))
                            <option value="{{ $manufacturer->id }}" selected>{{ $manufacturer->text }}</option>
                        @else
                            <option value="{{ $manufacturer->id }}">{{ $manufacturer->text }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="uk-form-row uk-margin-small-top">
                <label class="uk-form-label">{{ trans('frontend.search_price_max') }}</label>
                <input form="search_form" type="hidden" name="price_min" class="uk-width-1-2" value="0">
                <select form="search_form" class="uk-width-1-1" id="price_max" name="price_max">
                    <option value="30000001" selected>{{ trans('frontend.no_max_price') }}</option>
                    @for($i = 3000000; $i <= 30000000; $i+=3000000)
                        @if(Request::get('price_max') == $i)
                            @if($i == 30000000)
                                <option value="{{ $i }}" selected>{{ money_format('$%!.0i', $i) }} o más</option>
                            @else
                                <option value="{{ $i }}" selected>hasta {{ money_format('$%!.0i', $i) }}</option>
                            @endif
                        @elseif($i == 30000000)
                            <option value="{{ $i }}">{{ money_format('$%!.0i', $i) }} o más</option>
                        @else
                            <option value="{{ $i }}">hasta {{ money_format('$%!.0i', $i) }}</option>
                        @endif
                    @endfor
                </select>
            </div>

            <div class="uk-form-row uk-margin-small-top">
                <label for="engine_size_range" class="uk-form-label">{{ trans('frontend.search_engine_size') }}</label>
                <div class="uk-flex">
                    <select form="search_form" class="uk-width-1-1" id="engine_size_min" name="engine_size_min">
                        <option value="0" selected>{{ trans('frontend.no_min_engine_size') }}</option>
                        @for($i = 0; $i <= 1000; $i+=25)
                            @if(Request::get('engine_size_min') == $i)
                                <option value="{{ $i }}" selected>desde {{ $i }}cc</option>
                            @else
                                <option value="{{ $i }}">desde {{ $i }}cc</option>
                            @endif
                        @endfor
                    </select>

                    <select form="search_form" class="uk-width-1-1" id="engine_size_max" name="engine_size_max">
                        <option value="1000" selected>{{ trans('frontend.no_max_engine_size') }}</option>
                        @for($i = 25; $i <= 1000; $i+=25)
                            @if(Request::get('engine_size_max') == $i)
                                @if($i == 1000)
                                    <option value="{{ $i }}" selected>{{ $i }}cc o más</option>
                                @else
                                    <option value="{{ $i }}" selected>hasta {{ $i }}cc</option>
                                @endif
                            @elseif($i == 1000)
                                <option value="{{ $i }}">{{ $i }}cc o más</option>
                            @else
                                <option value="{{ $i }}">hasta {{ $i }}cc</option>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>

            <div class="uk-form-row uk-margin-small-top">
                <label for="year_range" class="uk-form-label">{{ trans('frontend.search_year') }}</label>
                <div class="uk-flex">
                    <select form="search_form" class="uk-width-1-1" id="year_min" name="year_min">
                        <option value="1970" selected>{{ trans('frontend.no_min_year') }}</option>
                        @for($i = 1970; $i <= (int)date('Y')+1; $i++)
                            @if(Request::get('year_min') == $i)
                                <option value="{{ $i }}" selected>desde {{ $i }}</option>
                            @else
                                <option value="{{ $i }}">desde {{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <select form="search_form" class="uk-width-1-1" id="year_max" name="year_max">
                        <option value="{{ date('Y') }}" selected>{{ trans('frontend.no_max_year') }}</option>
                        @for($i = 1970; $i <= (int)date('Y')+1; $i++)
                            @if(Request::get('year_max') == $i)
                                <option value="{{ $i }}" selected>hasta {{ $i }}</option>
                            @else
                                <option value="{{ $i }}">hasta {{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>

            <div class="uk-form-row uk-margin-small-top">
                <label for="odometer_range" class="uk-form-label">{{ trans('frontend.search_odometer') }}</label>
                <input form="search_form" type="hidden" name="odometer_min" class="uk-width-1-1 " value="0">
                <input form="search_form" type="number" name="odometer_max" class="uk-width-1-1 " placeholder="{{ trans('frontend.search_odometer_max') }}" value="{{Request::get('odometer_max')}}">
            </div>

            <button type="submit" form="search_form" class="uk-button uk-button-success uk-button-large uk-width-1-1 uk-margin-small-top">{{ trans('frontend.search_button') }}</button>
        </form>
	    
    </div>
</div>