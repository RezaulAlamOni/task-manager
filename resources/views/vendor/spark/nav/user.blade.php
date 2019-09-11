<!-- NavBar For Authenticated Users -->
<spark-navbar
        :user="user"
        :teams="teams"
        :current-team="currentTeam"
        :unread-announcements-count="unreadAnnouncementsCount"
        :unread-notifications-count="unreadNotificationsCount"
        inline-template>

    <nav class="navbar navbar-light navbar-expand-md navbar-spark">
        <div class="container-fluid" v-if="user">
            <!-- Branding Image -->
            @include('spark::nav.brand')

            {{--<button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"--}}
                    {{--aria-expanded="false" aria-label="Toggle navigation">--}}
                <span class="fa fa-bars fa-2x" style="cursor: pointer;"></span>
            </button>


            <div id="navbarSupportedContent" class="collapse navbar-collapse show">
                <ul class="navbar-nav mr-auto">
                    @includeIf('spark::nav.user-left')
                </ul>

                <a @click="showNotifications" class="ml-3 mb-3 mb-md-0 mr-md-0 ml-md-auto float-sm-left mt-2">
                    <i class="fa fa-fw ti-bell" style="font-size:17px;"></i>
                    <span class="badge badge-pill" style="background-color:#33CCFF;">@{{notificationsCount}}</span>
                </a>

                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                                @if (Spark::showsTeamSwitcher())
                                    @foreach (Auth::user()->teams as $team)
                                            @if (Auth::user()->current_team_id === $team->id)
                                               {{ $team->name }}
                                            @else
                                                <img src="{{ $team->photo_url }}" class="spark-profile-photo-xs" alt="{{__('Team Photo')}}" /> {{ $team->name }}
                                            @endif
                                    @endforeach
                                @endif
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @if (Spark::usesTeams() && (Spark::createsAdditionalTeams() || Spark::showsTeamSwitcher()))
                                <!-- Team Settings -->
                                @include('spark::nav.teams')
                            @endif
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
</spark-navbar>


