<aside id="aside" class="app-aside modal fade" role="menu">
    <div class="left">
        <div class="box bg-white">
            <div class="navbar md-whiteframe-z1 no-radius blue">
                <!-- brand -->
                <a class="navbar-brand">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"
                         style="width: 24px; height: 24px;">
                         <path d="M 50 0 L 100 14 L 92 80 Z" fill="rgba(139, 195, 74, 0.5)"/>
                        <path d="M 92 80 L 50 0 L 50 100 Z" fill="rgba(139, 195, 74, 0.8)"/>
                        <path d="M 8 80 L 50 0 L 50 100 Z" fill="#f3f3f3"/>
                        <path d="M 50 0 L 8 80 L 0 14 Z" fill="rgba(220, 220, 220, 0.6)"/>
                    </svg>
                    <img src="{{ asset('vendor/forone/images/logo.png') }}" alt="." style="max-height: 36px; display:none">
                    <span class="hidden-folded m-l inline">{{ $siteConfig['site_name'] }}</span>
                </a>
                <!-- / brand -->
            </div>

            <div class="box-row">
                <div class="box-cell scrollable hover">
                    <div class="box-inner">
                        @include('forone::partials.profile')
                        <div id="nav">
                            <nav ui-nav>
                                <ul class="nav">
                                    @inject('ns', 'Forone\Admin\Services\NavService')
                                    @foreach(config('forone.menus') as $title => $value)
                                        @can($value['permission_name'])
                                            <li class="{{ $ns->isActive($value['active_uri']) }}">
                                                <a md-ink-ripple @if($value['is_redirect']) href='{{ route($value['route_name']) }}' @endif >
                                                    <i class="icon {{ $value['icon'] }} i-20"></i>
                                                    @if(array_key_exists('children', $value) && count($value['children']))
                                                        <span class="pull-right text-muted">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    @endif
                                                    <span class="font-normal">{{ $title }}</span>
                                                </a>
                                                @if(array_key_exists('children', $value) && count($value['children']))
                                                    <ul class="nav nav-sub">
                                                        @foreach($value['children'] as $childTitle => $chidrenValue)
                                                            <li class="{{ $ns->isActive($chidrenValue['active_uri']) }}">
                                                                <a md-ink-ripple href='{{ route($chidrenValue['route_name']) }}'>{{ $childTitle }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div><!-- / box-inner -->
                </div><!-- / box-cell -->
            </div><!-- / box-row -->

            <nav>
                <ul class="nav b-t b">
                    <li class="m-v-sm b-b b"></li>
                    <li>
                        <a md-ink-ripple href="{{ url('admin/auth/logout') }}">
                            <i class="icon mdi-action-exit-to-app i-20"></i>
                            <span>退出登录</span>
                        </a>
                    </li>
                    <li>
                        <div class="nav-item" ui-toggle-class="folded" target="#aside">
                            <label class="md-check">
                                <input type="checkbox">
                                <i class="purple no-icon"></i>
                                <span class="hidden-folded">收起侧边栏</span>
                            </label>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</aside>