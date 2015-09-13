<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="BuscoMoto.co">
        @section('head')
            <title>{{ Settings::get('site_name') }}</title>
            <meta name="description" content="{{ Settings::get('site_description') }}">
            <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
            <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
            <meta property="og:type" content="website"/>
            <meta property="og:description" content="{{ Settings::get('site_description') }}" />
        @show
        <meta property="fb:app_id" content="{{ Settings::get('facebook_app_id') }}"/>
        <meta property="og:site_name" content="{{ Settings::get('site_name') }}"/>

        @section('css')
            <script type="text/javascript" id="loadcss">
                function loadCSS( href, before, media, callback ){
                    "use strict";
                    var ss = window.document.createElement( "link" );
                    var ref = before || window.document.getElementsByTagName( "script" )[ 0 ];
                    var sheets = window.document.styleSheets;
                    ss.rel = "stylesheet";
                    ss.href = href;
                    ss.media = "only x";
                    if( callback ) {
                        ss.onload = callback;
                    }

                    ref.parentNode.insertBefore( ss, ref );

                    ss.onloadcssdefined = function( cb ){
                        var defined;
                        for( var i = 0; i < sheets.length; i++ ){
                            if( sheets[ i ].href && sheets[ i ].href === ss.href ){
                                defined = true;
                            }
                        }
                        if( defined ){
                            cb();
                        } else {
                            setTimeout(function() {
                                ss.onloadcssdefined( cb );
                            });
                        }
                    };
                    ss.onloadcssdefined(function() {
                        ss.media = media || "all";
                    });
                    return ss;
                }
            </script>
            <link href="{{ asset('/css/uikit.flat.min.css') }}" rel="stylesheet">
        @show
    </head>

    <style>
    	nav{z-index:2;height: 50px;position: absolute;top:50px;}.uk-navbar-brand{text-shadow: none;color:white;}#footer{width: 100%;height: 350px;background-color: #2e3234;}
    </style>

    <body>
        @section('navbar')
            @include('includes.navbarHome')
        @show

        @section('header')
        @show
        
        @yield ('content')
        
        @section('footer')
            <div class="" id="footer">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid">
                        <div class="uk-width-small-1-1 uk-width-medium-2-10 uk-width-large-2-10 uk-margin-large-top">
                            <div class="uk-text-center-small">
                                <img src="{{ asset('/images/logo_h_contrast_mini.png') }}" class="uk-hidden-small">
                                <img src="{{ asset('/images/logo_h_contrast_mini.png') }}" style="max-width:280px" class="uk-visible-small">
                                <br>
                                <p class="uk-text-contrast">
                                    Mail: comercial@buscomoto.co<br>
                                </p>

                                <div style="margin-top:25px">
                                    <a onclick="share('{{ url('') }}')" class="uk-icon-hover uk-icon-large uk-icon-facebook"></a> 
                                    <a class="uk-icon-hover uk-icon-large uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text={{ Settings::get('site_name') }}%20{{ url('/') }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
                                    <a href="https://plus.google.com/share?url={{ url('/') }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-hover uk-icon-large uk-icon-google-plus"></a>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-6 uk-width-large-2-10 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">MARCAS</h3>
                            <ul class="uk-list">
                                <li><a href="{{ url('/buscar?manufacturers[]=73') }}" style="text-decoration:none" class="uk-text-contrast">MV Agusta</a></li>
                                <li><a href="{{ url('/buscar?manufacturers[]=23') }}" style="text-decoration:none" class="uk-text-contrast">KTM</a></li>
                                <li><a href="{{ url('/buscar?manufacturers[]=12') }}" style="text-decoration:none" class="uk-text-contrast">BMW</a></li>
                                <li><a href="{{ url('/buscar?manufacturers[]=110') }}" style="text-decoration:none" class="uk-text-contrast">Yamaha</a></li>
                                <li><a href="{{ url('/buscar?manufacturers[]=137') }}" style="text-decoration:none" class="uk-text-contrast">Bajaj</a></li>
                                <li><a href="{{ url('/buscar?manufacturers[]=136') }}" style="text-decoration:none" class="uk-text-contrast">AKT</a></li>
                            </ul>
                        </div>
                        <div class="uk-width-medium-1-6 uk-width-large-2-10 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">POPULARES</h3>
                            <ul class="uk-list">
                                <li><a href="{{ url('/buscar?models=87') }}" style="text-decoration:none" class="uk-text-contrast">Bajaj Pulsar</a></li>
                                <li><a href="{{ url('/buscar?models=28') }}" style="text-decoration:none" class="uk-text-contrast">KTM Duke</a></li>
                                <li><a href="{{ url('/buscar?models=1') }}" style="text-decoration:none" class="uk-text-contrast">Yamaha FZ 16</a></li>
                                <li><a href="{{ url('/buscar?models=1') }}" style="text-decoration:none" class="uk-text-contrast">Yamaha Bwis</a></li>
                                <li><a href="{{ url('/buscar?models=1') }}" style="text-decoration:none" class="uk-text-contrast">BMW R1200 GS</a></li>
                                <li><a href="{{ url('/buscar?models=1') }}" style="text-decoration:none" class="uk-text-contrast">MV Agusta Brutale</a></li>
                            </ul>
                        </div>
                        
                        <div class="uk-width-medium-1-6 uk-width-large-2-10 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">NOSOTROS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">Quienes Somos</li>
                                <li class="uk-text-contrast">Nuestros Servicios</li>
                                <li class="uk-text-contrast">Preguntas Frequentes</li>
                                <li class="uk-text-contrast">Blog</li>
                                <li class="uk-text-contrast">Publica</li>
                            </ul>
                        </div>

                        <div class="uk-width-medium-1-6 uk-width-large-2-10 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">OTROS SITIOS</h3>
                            <ul class="uk-list">
                                <li><a href="http://buscocasa.co" style="text-decoration:none" class="uk-text-contrast">BuscoCasa</a></li>
                                <li><a href="{{ url('/') }}" style="text-decoration:none" class="uk-text-contrast">BuscoCarro</a></li>
                                <li><a href="http://buscomoto.co" style="text-decoration:none" class="uk-text-contrast">BuscoMoto</a></li>
                                <li><a href="http://motomoteros.co" style="text-decoration:none" class="uk-text-contrast">Motomoteros</a></li>
                                <li><a href="http://mvagustacolombia.com" style="text-decoration:none" class="uk-text-contrast">MV Agusta</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="footer uk-margin-large-top"><!--/data-uk-scrollspy="{cls:'uk-animation-slide-bottom'}"-->
                        <div class="uk-text-center uk-text-middle uk-margin-bottom uk-text-contrast uk-text-small">
                            <a href="http://www.indesigncolombia.com">
                                <img src="{{ asset('/images/indesign/logo_h_contrast.png') }}" alt="logo" width="100px">
                            </a>
                            <br>
                            Designed and developed by <a href="http://www.indesigncolombia.com" class="uk-text-primary">Indesign Colombia</a>
                            <p class="uk-margin-remove">Usar este sitio web implica que usted acepta nuestras <a href="{{ url('terms') }}" class="uk-text-primary">Políticas y Términos</a> | <a href="{{ url('privacy') }}" class="uk-text-primary">Aviso de Privacidad</a></p>
                        </div>
                    </div><!--/footer-->

                </div>
            </div>
        @show

        @section('alerts')
            
        @show
        

        <!-- Scripts -->
        @section('js')
            <!-- Necessary Scripts -->
            <script src="{{ asset('/js/jquery.min.js') }}"></script>
            <script src="{{ asset('/js/uikit.min.js') }}"></script>
        @show
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : {{ Settings::get('facebook_app_id') }},
                    xfbml      : true,
                    version    : 'v2.3'
                });
            };
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            function share(path){
                FB.ui({
                    method: 'share_open_graph',
                    action_type: 'og.shares',
                    action_properties: JSON.stringify({
                    object: path,
                })
                }, function(response){
                    $.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+id, value: true, time:11520}, function(result){
                    
                    });
                });
            }
        </script>

        @if(env('APP_ENV') == 'production')
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
    </body>
</html>