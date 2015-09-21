@extends('layouts.mobile.master')

@section('head')
    <title>{{ trans('admin.dashboard') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
    @parent
@endsection
 
@section('content')

<div class="uk-container uk-container-center uk-margin-large-top">
    <h2 class="uk-text-center" style="margin-top:-30px">{{ trans('admin.dashboard') }}</h2>

    <hr>

    <div class="uk-grid">
        <div class="uk-width-1-1 uk-margin-small-bottom">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h2>Registered users <i class="uk-text-primary uk-h4">({{ $counts['listings'] }})</i></h2>
                <canvas id="users_chart" width="280px" height="200px"></canvas>
            </div>
        </div>
        <div class="uk-width-1-1 uk-margin-small-bottom">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h2>Created listings <i class="uk-text-primary uk-h4">({{ $counts['users'] }})</i></h2>
                <canvas id="listings_chart" width="280px" height="200px"></canvas>
            </div>
        </div>

        <div class="uk-width-1-1 uk-margin-small-bottom">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h2>Messages sent <i class="uk-text-primary uk-h4">({{ $counts['messages'] }})</i></h2>
                <canvas id="messages_chart" width="280px" height="200px"></canvas>
            </div>
        </div>
        <div class="uk-width-1-1 uk-margin-small-bottom">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h2>No images listings <i class="uk-text-primary uk-h4">({{ $counts['points_avg'] }} points avg)</i></h2>
                <canvas id="no_images_chart" width="280px" height="200px"></canvas>
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
            var data1 = {!! json_encode($data) !!}
            // Get the context of the canvas element we want to select
            var ctx = $("#users_chart").get(0).getContext("2d");
            var myNewChart = new Chart(ctx).Line(data1);

            var data2 = {!! json_encode($data2) !!}
            // Get the context of the canvas element we want to select
            var ctx = $("#listings_chart").get(0).getContext("2d");
            var myNewChart = new Chart(ctx).Line(data2);

            var data3 = {!! json_encode($data3) !!}
            // Get the context of the canvas element we want to select
            var ctx = $("#messages_chart").get(0).getContext("2d");
            var myNewChart = new Chart(ctx).Line(data3);


            var data4 = [{value: {{ $counts['nIListings'] }},
                         color:"#F7464A",
                         highlight: "#FF5A5E",
                         label: "Listings with no images"
                        },{
                         value: {{ $counts['listings'] }},
                         color: "#46BFBD",
                         highlight: "#5AD3D1",
                         label: "Listings with images"
                        }];
            var ctx = $("#no_images_chart").get(0).getContext("2d");
            var myDoughnutChart = new Chart(ctx).Doughnut(data4);
        });
    </script>
@endsection