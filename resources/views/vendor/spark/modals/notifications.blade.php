<!-- Notifications Modal -->
<spark-notifications
    :notifications="notifications"
    :has-unread-announcements="hasUnreadAnnouncements"
    :loading-notifications="loadingNotifications" inline-template>

    <div>
        <div class="modal docked docked-right" id="modal-notifications" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center" style="padding: 10px 0;">
                        <div class="panel-tabs" style="width: 100%;">
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="nav-item text-center">
                                    <a href="#r_tab1" role="tab" data-toggle="tab" class="nav-link active ">
                                        <i class="fa fa-fw fa-bullhorn"></i>
                                    </a>
                                </li>
                                <li class="text-center nav-item">
                                    <a href="#r_tab2" role="tab" data-toggle="tab" class="nav-link">
                                        <i class="fa fa-fw ti-bell"></i>
                                    </a>
                                </li>
                                <li class="text-center nav-item">
                                    <a href="#r_tab3" role="tab" data-toggle="tab" class="nav-link">
                                        <i class="fa fa-fw ti-settings"></i>
                                    </a>
                                </li>
                                <li class="text-center nav-item" id="getAllComment">
                                    <a href="#r_tab4" role="tab" data-toggle="tab" class="nav-link">
                                        <i class="fa fa-comments"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- <div class="btn-group">
                            <button class="btn btn-light text-dark" :class="{'active': showingNotifications}" @click="showNotifications" style="width: 50%;">
                                {{__('Notifications')}}
                            </button>

                            <button class="btn btn-light text-dark" :class="{'active': showingAnnouncements}" @click="showAnnouncements" style="width: 50%;">
                                {{__('Announcements')}} <i class="fa fa-circle text-danger p-l-xs" v-if="hasUnreadAnnouncements"></i>
                            </button>
                        </div> -->
                    </div>

                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="r_tab1">
                                <div id="slim_t1">
                                    <h5 class="rightsidebar-right-heading text-uppercase text-xs" style="width: 100%;float: left;margin-top: 70px;clear: both;">
                                        <i class="menu-icon fa fa-fw fa-bullhorn"></i>
                                        Announcements
                                    </h5>

                                    <!-- List Of Announcements -->
                                    <div class="notification-container" style="width:100%;float: left;">
                                        <div class="notification" v-for="announcement in notifications.announcements">

                                            <!-- Notification Icon -->
                                            <figure>
                                                <img :src="announcement.creator.photo_url" class="spark-profile-photo">
                                            </figure>

                                            <!-- Announcement -->
                                            <div class="notification-content">
                                                <div class="meta">
                                                    <p class="title">@{{ announcement.creator.name }}</p>

                                                    <div class="date">
                                                        @{{ announcement.created_at | relative }}
                                                    </div>
                                                </div>

                                                <div class="notification-body" v-html="announcement.parsed_body"></div>

                                                <!-- Announcement Action -->
                                                <a :href="announcement.action_url" class="btn btn-primary" v-if="announcement.action_text">
                                                    @{{ announcement.action_text }}
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="r_tab2">
                                <div id="slim_t2">
                                    <!-- Informational Messages -->
                                    <h5 class="rightsidebar-right-heading text-uppercase text-xs" style="width: 100%;float: left;margin-top: 70px;clear: both;">
                                        <i class="menu-icon fa fa-fw ti-bell"></i>
                                        Notifications
                                    </h5>
                                    <div class="notification-container" v-if="loadingNotifications"  style="width:100%;float: left;">
                                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Loading Notifications')}}
                                    </div>

                                    <div class="notification-container" v-if=" ! loadingNotifications && activeNotifications.length == 0"  style="width:100%;float: left;">
                                        <div class="alert alert-warning m-b-none">
                                            {{__('We don\'t have anything to show you right now! But when we do, we\'ll be sure to let you know. Talk to you soon!')}}
                                        </div>
                                    </div>

                                    <!-- List Of Notifications -->
                                    <div class="notification-container" v-if="showingNotifications && hasNotifications">
                                        <div class="notification" v-for="notification in notifications.notifications">  style="width:100%;float: left;"

                                            <!-- Notification Icon -->
                                            <figure>
                                                <img v-if="notification.creator" :src="notification.creator.photo_url" class="spark-profile-photo">

                                                <span v-else class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i :class="['fa', 'fa-stack-1x', 'fa-inverse', notification.icon]"></i>
                                    </span>
                                            </figure>

                                            <!-- Notification -->
                                            <div class="notification-content">
                                                <div class="meta">
                                                    <p class="title">
                                            <span v-if="notification.creator">
                                                @{{ notification.creator.name }}
                                            </span>

                                                        <span v-else>
                                                {{ Spark::product() }}
                                            </span>
                                                    </p>

                                                    <div class="date">
                                                        @{{ notification.created_at | relative }}
                                                    </div>
                                                </div>

                                                <div class="notification-body" v-html="notification.parsed_body"></div>

                                                <!-- Notification Action -->
                                                <a :href="notification.action_url" class="btn btn-primary" v-if="notification.action_text">
                                                    @{{ notification.action_text }}
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="r_tab3">
                                <div id="slim_t3" style="padding-top: 70px;">
                                    <h5 class="rightsidebar-right-heading text-uppercase text-xs">Settings</h5>
                                    <div>
                                        <ul class="list-unstyled">
                                            <li class="rightsidebar-notification">
                                                <a href="/settings#/profile" aria-controls="profile">
                                                    <i class="fa fa-fw fa-btn fa-edit"></i> Profile
                                                </a>
                                            </li>
                                            @if (Spark::usesTeams())
                                            <li class="rightsidebar-notification">
                                                <a href="/settings#/{{str_plural(Spark::teamsPrefix())}}" aria-controls="teams">
                                                    <i class="fa fa-fw fa-btn fa-users"></i> {{ ucfirst(str_plural(Spark::teamsPrefix())) }}
                                                </a>
                                            </li>
                                            @endif
                                        <!-- Security Link -->
                                            <li class="rightsidebar-notification">
                                                <a href="/settings#/security" aria-controls="security">
                                                    <i class="fa fa-fw fa-btn fa-lock"></i> Security
                                                </a>
                                            </li>
                                            <!-- API Link -->
                                            @if (Spark::usesApi())
                                                <li class="rightsidebar-notification">
                                                    <a href="/settings#/api" aria-controls="api">
                                                        <i class="fa fa-fw fa-btn fa-cubes"></i> API
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="r_tab4">
                                <div id="slim_t2">
                                    <!-- Informational Messages -->
                                    <h5 class="rightsidebar-right-heading text-uppercase text-xs" style="width: 100%;float: left;margin-top: 70px;clear: both;">
                                        <i class="menu-icon fa fa-comments"></i>
                                        Comments
                                    </h5>
                                    <div class="notification-container" v-if="loadingNotifications"  style="width:100%;float: left;">
                                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Loading Notifications')}}
                                    </div>

                                    <!-- List Of Notifications -->
                                    <div class="notification-container" v-if="showingNotifications && hasNotifications">
                                        <div class="notification" v-for="notification in notifications.notifications">

                                            <!-- Notification Icon -->
                                            <figure>
                                                <img v-if="notification.creator" :src="notification.creator.photo_url" class="spark-profile-photo">

                                                <span v-else class="fa-stack fa-2x">
                                                    <i class="fa fa-circle fa-stack-2x"></i>
                                                    <i :class="['fa', 'fa-stack-1x', 'fa-inverse', notification.icon]"></i>
                                                </span>
                                            </figure>

                                            <!-- Notification -->
                                            <div class="notification-content">
                                                <div class="meta">
                                                    <p class="title">
                                                        <span v-if="notification.creator">
                                                            @{{ notification.creator.name }}
                                                        </span>

                                                        <span v-else>
                                                            {{ Spark::product() }}
                                                        </span>
                                                    </p>

                                                    <div class="date">
                                                        @{{ notification.created_at | relative }}
                                                    </div>
                                                </div>

                                                <div class="notification-body" v-html="notification.parsed_body"></div>

                                                <!-- Notification Action -->
                                                <a :href="notification.action_url" class="btn btn-primary" v-if="notification.action_text">
                                                    @{{ notification.action_text }}
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default " data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</spark-notifications>

