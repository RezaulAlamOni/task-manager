@extends('spark::layouts.app')

@section('breadcrumb')
    <nav class="navbar-expand-md navbar-spark">
        <div class="container-fluid" v-if="user">

            <div class="collapse navbar-collapse show">

                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               Ideas
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Lists</h6>
                            <a href="" class="dropdown-item">S&O Ideas List</a>
                            <a href="" class="dropdown-item">Marketing Ideas</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Idea List
                            </a>
                        </div>
                    </li>
                </ul>

                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               Scopes
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Lists</h6>
                            <a href="" class="dropdown-item">CompltIt Scope</a>
                            <a href="" class="dropdown-item">Facebook Scope</a>
                            <a href="" class="dropdown-item">Affiliate Scope</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Scope List
                            </a>
                        </div>
                    </li>
                </ul>

                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               Dev Board
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Boards</h6>
                            <a href="" class="dropdown-item">CompltIt Board</a>
                            <a href="" class="dropdown-item">Marketing Board</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Dev Board
                            </a>
                        </div>
                    </li>
                </ul>

                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               Testing
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Board</h6>
                            <a href="" class="dropdown-item">CompltIt Test</a>
                            <a href="" class="dropdown-item">Facebook Test</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Testing Board
                            </a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-4 float-sm-left">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               Complete
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Board</h6>
                            <a href="" class="dropdown-item">All Complete Dev Tasks</a>
                            <a href="" class="dropdown-item">Marketing</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Complete Board
                            </a>
                        </div>
                    </li>
                </ul>


                <ul class="navbar-nav ml-4" style="position: absolute;right: 20px;">
                    <li class="nav-item dropdown">
                        <a href="#" class="d-block d-md-flex text-center nav-link" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-md-block">
                               <i class="fa fa-fw fa-plus-circle" style="color:#33CCFF;font-size: 26px;"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Manage Nav</h6>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                Create Task View
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header"> Edit Task View</h6>
                            <a href="" class="dropdown-item"> Ideas</a>
                            <a href="" class="dropdown-item"> Scopes</a>
                            <a href="" class="dropdown-item"> Dev Board</a>
                            <a href="" class="dropdown-item"> Testing</a>
                            <a href="" class="dropdown-item"> Complete</a>

                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
@endsection
@section('content')
    <home :user="user" inline-template>
        <div class="container">
            <!-- Application Dashboard -->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <img src="/img/top.png" class="img-responsive" />
                    <img src="/img/bottom.png" class="img-responsive" />
                </div>
            </div>

        </div>
    </home>
@endsection
