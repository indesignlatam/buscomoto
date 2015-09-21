@extends('layouts.mobile.master')

@section('head')
    <title>{{ trans('admin.dashboard') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
    @parent
    <style>
    #canvas-holder {
        width: 100%;
        margin-top: 50px;
        text-align: center;
    }
    #chartjs-tooltip {
        opacity: 1;
        position: absolute;
        background: rgba(0, 0, 0, .7);
        color: white;
        padding: 3px;
        border-radius: 3px;
        -webkit-transition: all .1s ease;
        transition: all .1s ease;
        pointer-events: none;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    #chartjs-tooltip.below {
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    #chartjs-tooltip.below:before {
        border: solid;
        border-color: #111 transparent;
        border-color: rgba(0, 0, 0, .8) transparent;
        border-width: 0 8px 8px 8px;
        bottom: 1em;
        content: "";
        display: block;
        left: 50%;
        position: absolute;
        z-index: 99;
        -webkit-transform: translate(-50%, -100%);
        transform: translate(-50%, -100%);
    }
    #chartjs-tooltip.above {
        -webkit-transform: translate(-50%, -100%);
        transform: translate(-50%, -100%);
    }
    #chartjs-tooltip.above:before {
        border: solid;
        border-color: #111 transparent;
        border-color: rgba(0, 0, 0, .8) transparent;
        border-width: 8px 8px 0 8px;
        bottom: 1em;
        content: "";
        display: block;
        left: 50%;
        top: 100%;
        position: absolute;
        z-index: 99;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    </style>
@endsection
 
@section('content')

<div class="uk-container uk-container-center uk-margin-top">
    <h2>{{ trans('admin.dashboard') }}</h2>
    <a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-primary uk-button-large uk-width-1-1">{{ trans('admin.publish_listing') }}</a>
    
    <div class="uk-grid uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">
        <!-- First row -->
        <div class="uk-width-1-2 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <a href="{{ url('/admin/listings') }}" style="text-decoration:none">
                    <h3 class="uk-panel-title">{{ trans('admin.listings') }}</h3>
                </a>
                <a href="{{ url('/admin/listings') }}" style="text-decoration:none">
                    <h1 class="uk-text-center">{{ $listingCount }} <i class="uk-icon-motorcycle"></i></h1>
                </a>
            </div>
        </div>

        <div class="uk-width-1-2 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <a href="{{ url('/admin/messages') }}" style="text-decoration:none"><h3 class="uk-panel-title">{{ trans('admin.messages') }}</h3></a>

                @if($notAnsweredMessages == 0)
                    <a href="{{ url('/admin/messages') }}" style="text-decoration:none">
                        <h1 class="uk-text-center uk-text-success">{{ $notAnsweredMessages }} <i class="uk-icon-check"></i></h1>
                    </a>
                @else
                    <a href="{{ url('/admin/messages') }}" style="text-decoration:none">
                        <h1 class="uk-text-center uk-text-warning">{{ $notAnsweredMessages }} <i class="uk-icon-envelope-o"></i></h1>
                    </a>
                @endif
            </div>
        </div>
        <!-- First row -->

        @if(count($listings->all()) > 0)
        <div class="uk-width-1-1 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.notifications_dash') }}</h3>
                <ul class="uk-list uk-list-striped">
                    @foreach($listings as $listing)
                        @if($listing->featured_expires_at && $listing->featured_expires_at > Carbon::now())
                            <li><a class="" href="{{ url($listing->pathEdit()) }}">Publicación #{{ $listing->code }} {{ strtolower(trans('admin.expires_in')) }} {{ $listing->featured_expires_at->diffForHumans() }}</a></li>
                        @else
                            <li><a class="" href="{{ url($listing->pathEdit()) }}">Publicación #{{ $listing->code }} {{ strtolower(trans('admin.expires_in')) }} {{ $listing->expires_at->diffForHumans() }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if(count($messages->all()) > 0)
        <div class="uk-width-1-1 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.unanswered_messages') }}</h3>
                <ul class="uk-list uk-list-striped">
                    @foreach($messages as $message)
                        <li><a class="" href="{{ url('/admin/messages') }}">{{ $message->name }} #{{ $message->listing->code }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="uk-width-small-1-1 uk-width-medium-1-2 uk-width-large-1-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.visit_stats') }}</h3>
                <div class="uk-text-center">
                    <canvas id="listingMessages" width="250" height="250"></canvas>
                    <div id="chartjs-tooltip"></div>
                </div>
            </div>
        </div>

    </div>
</div>

@stop

@section('js')
    @parent
    <script src="{{ asset('js/Chart.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            // Handler for .ready() called.
            Chart.defaults.global.customTooltips = function(tooltip) {
                // Tooltip Element
                var tooltipEl = $('#chartjs-tooltip');
                // Hide if no tooltip
                if (!tooltip) {
                    tooltipEl.css({
                        opacity: 0
                    });
                    return;
                }
                // Set caret Position
                tooltipEl.removeClass('above below');
                tooltipEl.addClass(tooltip.yAlign);
                // Set Text
                tooltipEl.html(tooltip.text);
                // Find Y Location on page
                var top;
                if (tooltip.yAlign == 'above') {
                    top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
                } else {
                    top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
                }
                // Display, position, and set styles for font
                tooltipEl.css({
                    opacity: 1,
                    left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
                    top: tooltip.chart.canvas.offsetTop + top + 'px',
                    fontFamily: tooltip.fontFamily,
                    fontSize: tooltip.fontSize,
                    fontStyle: tooltip.fontStyle,
                });
            };


            var data = {!! json_encode($data) !!}

            // Get the context of the canvas element we want to select
            var ctx = document.getElementById("listingMessages").getContext("2d");
            var myNewChart = new Chart(ctx).PolarArea(data, {
                tooltipFontSize: 12,
                tooltipTemplate: "<%if (label){%><%=label%> <%}%><br><b style=\"font-size:14px\"><%= value %> visitas</b>",
                percentageInnerCutout : 1
            });
        });
    </script>
@endsection