<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <meta name="author" content="{{ Settings::get('site_name') }}">
        <meta name="description" content="{{ Settings::get('site_description') }}">
        @section('head')
            <title>{{ trans('admin.administration_panel') }} - {{ Settings::get('site_name') }}</title>
        @show

        @section('css')
            <link href="{{ asset('/css/uikit.flat.min.css') }}" rel="stylesheet">
        @show
    </head>

    <body>
        @section('navbar')
            @include('includes.mobile.navbar')
        @show

        @section('header')
        @show
        
        @yield ('content')
        
        @section('footer')
            @include('includes.mobile.footer')
        @show
        
        <!-- Scripts -->
        @section('js')
            <!-- Necessary Scripts -->
            <script src="{{ asset('/js/jquery.min.js') }}"></script>
            <script src="{{ asset('/js/uikit.min.js') }}"></script>

            <!-- Other Scripts -->
            <link href="{{ asset('/css/components/notify.css') }}" rel="stylesheet">
            <script src="{{ asset('/js/components/notify.min.js') }}"></script>
        @show

        @section('alerts')
            @include('includes.mobile.alerts')
        @show

        @if(env('APP_ENV') == 'production')
            @if(!Auth::check())
                <!-- Facebook Pixel Code -->
                <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','//connect.facebook.net/en_US/fbevents.js');

                fbq('init', '1598843093713019');
                fbq('track', 'PageView');
                </script>
                <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id=1598843093713019&ev=PageView&noscript=1"
                /></noscript>
                <!-- End Facebook Pixel Code -->

                <!--Start of Zopim Live Chat Script-->
                <script type="text/javascript">
                window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
                d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
                _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
                $.src="//v2.zopim.com/?3JSQBAV4Z1Lfbp8037PLzpSnUFdRn1EV";z.t=+new Date;$.
                type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
                </script>
                <!--End of Zopim Live Chat Script-->
                
                <!-- Other Scripts -->
                {!! Analytics::render() !!}
            @elseif(Auth::check() && !Auth::user()->isAdmin())
                <!-- Facebook Pixel Code -->
                <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','//connect.facebook.net/en_US/fbevents.js');

                fbq('init', '1598843093713019');
                fbq('track', 'PageView');
                </script>
                <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id=1598843093713019&ev=PageView&noscript=1"
                /></noscript>
                <!-- End Facebook Pixel Code -->
                
                <!--Start of Zopim Live Chat Script-->
                <script type="text/javascript">
                window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
                d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
                _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
                $.src="//v2.zopim.com/?3JSQBAV4Z1Lfbp8037PLzpSnUFdRn1EV";z.t=+new Date;$.
                type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
                </script>
                <!--End of Zopim Live Chat Script-->
                
                <!-- Other Scripts -->
                {!! Analytics::render() !!}
            @endif
        @endif
    </body>
</html>