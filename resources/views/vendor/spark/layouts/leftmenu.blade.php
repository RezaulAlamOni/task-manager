<section class="sidebar">
    <div id="menu" role="navigation">
        <div class="nav_profile">
            <div class="media profile-left">
                <a class="pull-left profile-thumb" href="javascript:void(0)">
                    <img :src="user.photo_url" class="rounded-circle img-fluid" alt="User Image">
                </a>
                <div class="content-profile ml-3">
                    <h4 class="media-heading">@{{ user.name }}</h4>
                    <ul class="icon-list pl-0">
                        <li>
                            <a href="/settings">
                                <i class="fa fa-fw ti-settings" title="settings"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/settings#/security">
                                <i class="fa fa-fw ti-lock" title="Update password"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/settings#/api">
                                <i class="fa fa-fw ti-list" title="API settings"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/logout">
                                <i class="fa fa-fw ti-shift-right" title="Log out"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="navigation" id="navigation">
            <li {!! (Request::is( 'home') ? 'class="active"' : '') !!}>
                <a href="/home">
                    <i class="menu-icon ti-desktop"></i>
                    <span class="mm-text ">Home</span>
                </a>
            </li>
            <li class="menu-dropdown">
                <a data-toggle="collapse" href="#collapseSetting">
                    <i class="menu-icon ti-settings"></i>
                    <span>
                                    Settings
                                </span>
                    <span class="fa arrow"></span>
                </a>
                <ul id="collapseSetting" class="collapse" data-parent="#navigation">
                    <!-- Profile Link -->
                    <li>
                        <a href="/settings#/profile" aria-controls="profile">
                            <i class="fa fa-fw fa-btn fa-edit"></i> Profile
                        </a>
                    </li>
                    <!-- Teams Link -->
                    @if (Spark::usesTeams())
                    <li>
                        <a href="/settings#/{{str_plural(Spark::teamsPrefix())}}" aria-controls="teams">
                            <i class="fa fa-fw fa-btn fa-users"></i> {{ ucfirst(str_plural(Spark::teamsPrefix())) }}
                        </a>
                    </li>
                    @endif
                    <!-- Security Link -->
                    <li>
                        <a href="/settings#/security" aria-controls="security">
                            <i class="fa fa-fw fa-btn fa-lock"></i> Security
                        </a>
                    </li>
                    <!-- API Link -->
                    @if (Spark::usesApi())
                    <li>
                        <a href="/settings#/api" aria-controls="api">
                            <i class="fa fa-fw fa-btn fa-cubes"></i> API
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @if (Spark::canBillCustomers())
            <li class="menu-dropdown">
                <a data-toggle="collapse" href="#collapseBilling">
                    <i class="menu-icon ti-list"></i>
                    <span>
                                    Billing
                                </span>
                    <span class="fa arrow"></span>
                </a>
                <ul id="collapseBilling" class="collapse" data-parent="#navigation">
                    @if (Spark::hasPaidPlans())
                    <!-- Subscription Link -->
                    <li>
                        <a href="/settings#/subscription" aria-controls="subscription">
                            <i class="fa fa-fw fa-btn fa-shopping-bag"></i>Subscription
                        </a>
                    </li>
                    @endif
                    <!-- Payment Method Link -->
                    <li>
                        <a href="/settings#/payment-method" aria-controls="payment-method">
                            <i class="fa fa-fw fa-btn fa-credit-card"></i>Payment Method
                        </a>
                    </li>
                    <!-- Invoices Link -->
                    <li>
                        <a href="/settings#/invoices" aria-controls="invoices">
                            <i class="fa fa-fw fa-btn fa-history"></i>Invoices
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li {!! (Request::is( 'clear') ? 'class="active"' : '') !!}>
                <a href="/clear">
                    <i class="menu-icon ti-desktop"></i>
                    <span class="mm-text ">Clear Invoice</span>
                </a>
            </li>
            <li {!! (Request::is( 'select') ? 'class="active"' : '') !!}>
                <a href="/select">
                    <i class="menu-icon ti-desktop"></i>
                    <span class="mm-text ">Select2</span>
                </a>
            </li>
            @if (Spark::developer(Auth::user()->email))
            <li class="menu-dropdown">
                <a data-toggle="collapse" href="#collapseKiosk">
                    <i class="menu-icon ti-user"></i>
                    <span>kiosk</span>
                    <span class="fa arrow"></span>
                </a>
                <ul id="collapseKiosk" class="collapse" data-parent="#navigation">
                    <!-- Announcements Link -->
                    <li>
                        <a href="/spark/kiosk#/announcements">
                            <i class="fa fa-fw fa-btn fa-bullhorn"></i> Announcements
                        </a>
                    </li>
                    <!-- Metrics Link -->
                    <li>
                        <a href="/spark/kiosk#/metrics">
                            <i class="fa fa-fw fa-btn fa-bar-chart"></i> Metrics
                        </a>
                    </li>
                    <!-- Users Link -->
                    <li>
                        <a href="/spark/kiosk#/users">
                            <i class="fa fa-fw fa-btn fa-user"></i> Users
                        </a>
                    </li>
                </ul>
            </li>
            @endif @if (Spark::usesTeams())
            <li {!! (Request::is('settings/'.str_plural(Spark::teamsPrefix()).'/*') ? 'class="menu-dropdown active"' : 'class="menu-dropdown"') !!}>
                <a data-toggle="collapse" href="#collapseTeam"><i class="ti-user"></i><span> {{ ucfirst(str_plural(Spark::teamsPrefix())) }} Settings</span><span class="fa arrow"></span></a>
                <ul {!! (Request::is('settings/'.str_plural(Spark::teamsPrefix()).'/*') ? 'class="collapse show active"' : 'class="collapse"') !!} id="collapseTeam" data-parent="#navigation">
                    <li v-for="team in teams" :class="{active:team.id=={{Request::is('settings/'.str_plural(Spark::teamsPrefix()).'/*')?substr(Request::path(),strlen('settings/'.str_plural(Spark::teamsPrefix()).'/')):0}}}">
                        <a :href="'/settings/{{str_plural(Spark::teamsPrefix())}}/'+team.id">
                            <span>
                <img :src="team.photo_url" class="spark-team-photo-xs"><i class="fa fa-btn"></i><span> @{{ team.name }}</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif @if (Spark::usesTeams() && (Spark::createsAdditionalTeams() || Spark::showsTeamSwitcher()))
            <!-- Team Settings -->
            @include('spark::nav.teams') @endif
        </ul>
        <!-- / .navigation -->
    </div>
    <!-- menu -->
</section>
