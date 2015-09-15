@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.dashboard') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
    @parent
@endsection
 
@section('content')

<div class="uk-container uk-container-center uk-margin-large-top">
    <h1 class="uk-text-center" style="margin-top:-30px">Dashboard</h1>

    <hr>

    <div class="uk-grid">
        <div class="uk-width-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                <h2>Registered users</h2>
                <canvas id="users_chart" width="500" height="250"></canvas>
            </div>
        </div>
        <div class="uk-width-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                <h2>Created listings</h2>
                <canvas id="listings_chart" width="500" height="250px"></canvas>
            </div>
        </div>

        <div class="uk-width-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h2>Messages sent</h2>
                <canvas id="messages_chart" width="500" height="250px"></canvas>
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
        });
    </script>
@endsection