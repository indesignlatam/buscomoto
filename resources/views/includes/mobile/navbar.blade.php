<nav class="uk-navbar uk-navbar-attached" style="border-bottom-style: solid; border-bottom: 1px solid #e1e1e1;">
    <div class="uk-container uk-container-center">
    	<a href="#menuoffcanvas" class="uk-navbar-toggle" style="margin-left:-10px" data-uk-offcanvas></a>

        <a href="{{ url('/') }}">
            <img src="{{ asset('/images/logo_h_mini.png') }}" alt="logo" style="max-height:25px; margin-top:6px">
        </a>

        <!-- This is the off-canvas sidebar -->
        <div id="menuoffcanvas" class="uk-offcanvas">
            <div class="uk-offcanvas-bar">
                @role('admin')
                    <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>
                        <li><a href="#" class="uk-text-bold">{{ trans('admin.hello') }} {{ Auth::user()->name }}</a></li>
                        <li class="uk-nav-divider"></li>

                        <li class="uk-parent">
                            <a href="#">{{ trans('admin.system') }}</a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ url('/admin') }}">{{ trans('admin.dashboard') }}</a></li>
                                <li><a href="{{ url('/admin/users') }}">{{ trans('admin.users') }}</a></li>
                                <li><a href="{{ url('/admin/roles') }}">{{ trans('admin.roles') }}</a></li>
                                <li><a href="{{ url('/admin/permissions') }}">{{ trans('admin.permissions') }}</a></li>

                                <li class="uk-nav-divider"></li>
                                <li><a href="{{ url('/admin/config') }}">{{ trans('admin.configuration') }}</a></li>
                            </ul>
                        </li>

                        <li class="uk-parent">
                            <a href="#">{{ trans('admin.categories') }}</a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ url('/admin/listing-types') }}">{{ trans('admin.listing_types') }}</a></li>
                                <li><a href="{{ url('/admin/manufacturers') }}">{{ trans('admin.manufacturers') }}</a></li>
                                <li><a href="{{ url('/admin/models') }}">{{ trans('admin.models') }}</a></li>

                                <li class="uk-nav-divider"></li>

                                <li><a href="{{ url('/admin/feature-categories') }}">{{ trans('admin.feature_categories') }}</a></li>
                                <li><a href="{{ url('/admin/features') }}">{{ trans('admin.features') }}</a></li>
                                <li><a href="{{ url('/admin/cities') }}">{{ trans('admin.cities') }}</a></li>
                            </ul>
                        </li>

                        <li class="uk-parent">
                            <a href="#">{{ trans('admin.listings') }}</a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ url('/admin/listings') }}">{{ trans('admin.listings') }}</a></li>
                                <li><a href="{{ url('/admin/messages') }}">{{ trans('admin.messages') }}</a></li>
                            </ul>
                        </li>

                        <li class="uk-parent">
                            <a href="#">{{ trans('admin.payments') }}</a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a></li>
                            </ul>
                        </li>

                        <li class="uk-nav-divider"></li>
                        <li><a href="{{ url('/') }}" target="_blank">{{ trans('admin.live_site') }}</a></li>
                        <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ trans('admin.profile_menu') }}</a></li>
                        <li><a href="{{ url('/auth/logout') }}">{{ trans('admin.logout') }}</a></li>
                    </ul>
                @else
                    <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>
                        <li><a href="#" class="uk-text-bold">{{ trans('admin.hello') }} {{ Auth::user()->name }}</a></li>
                        <li class="uk-nav-divider"></li>

                        <li><a href="{{ url('/admin') }}">{{ trans('admin.dashboard') }}</a></li>

                        <li><a href="{{ url('/admin/listings') }}">{{ trans('admin.my_listings_menu') }}</a></li>

                        <li><a href="{{ url('/admin/messages') }}">{{ trans('admin.my_messages_menu') }}</a></li>
                        
                        <li><a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a></li>

                        <li class="uk-nav-divider"></li>
                        <li><a href="{{ url('/') }}" target="_blank">{{ trans('admin.live_site') }}</a></li>
                        <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ trans('admin.profile_menu') }}</a></li>
                        <li><a href="{{ url('/auth/logout') }}">{{ trans('admin.logout') }}</a></li>
                    </ul>                    
                @endrole
            </div>
        </div>
        <!-- This is the off-canvas sidebar -->
    </div>
</nav>