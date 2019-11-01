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
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle compltit-blue"></i>
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
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle compltit-blue"></i>
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
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle  compltit-blue"></i>
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
                               <i class="fa fa-fw fa-plus-circle compltit-blue" style="font-size: 26px;"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                            <h6 class="dropdown-header"> Manage Nav</h6>
                            <a href="" class="dropdown-item">
                                <i class="fa fa-fw text-left fa-btn fa-plus-circle compltit-blue"></i>
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
            <section>
                <div class="container-header">
                    <h2>
                        <i class="fa fa-fw fa-magic"></i> Rules For [[Project Name Here]]
                        <button type="submit" class="btn btn-primary pull-right"> Create Rule</button>
                    </h2>
                    <p class="compltit-p">Rules will automatically assign and move card between boards, columns and users.</p>
                </div>
            </section>

            <!-- List of rules -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default border-primary">
                        <div class="card-header bg-primary text-white">
                            [[CLICK HERE TO NAME RULE]]
                            <span class="badge-pill badge-success">Live [[Click to disable]]</span>
                            <span class="badge-pill badge-danger">Paused [[Click to enable]]</span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <img src="/img/from-arrow.png" class="img-responsive" style="max-width: 200px;" />
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <select id="select21" class="form-control select2"
                                                style="width:100%;
                                                margin-top: 10px;
                                                border-top: none;
                                                border-left: none;
                                                border-right: none;
                                                box-shadow: none;
                                                border-radius: 0;
                                            ">
                                            <option value="">Select a Board and Column</option>
                                            <optgroup label="Dev Board Columns">
                                                <option value="ToDo">ToDo</option>
                                                <option value="InProgress">In Progress</option>
                                                <option value="Complete">Complete</option>
                                            </optgroup>
                                            <optgroup label="Testing Board Columns">
                                                <option value="ToDo">ToDo</option>
                                                <option value="InProgress">In Progress</option>
                                                <option value="Complete">Complete</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <img src="/img/to-arrow.png" class="img-responsive" style="max-width: 200px;" />
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <select id="select21" class="form-control select2"
                                                style="width:100%;
                                                margin-top: 10px;
                                                border-top: none;
                                                border-left: none;
                                                border-right: none;
                                                box-shadow: none;
                                                border-radius: 0;
                                            ">
                                            <option value="">Select a Board and Column</option>
                                            <optgroup label="Dev Board Columns">
                                                <option value="ToDo">ToDo</option>
                                                <option value="InProgress">In Progress</option>
                                                <option value="Complete">Complete</option>
                                            </optgroup>
                                            <optgroup label="Testing Board Columns">
                                                <option value="ToDo">ToDo</option>
                                                <option value="InProgress">In Progress</option>
                                                <option value="Complete">Complete</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <img src="/img/than-arrow.png" class="img-responsive" style="max-width: 200px;" />
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <select id="select21" class="form-control select2"
                                                style="width:100%;
                                                margin-top: 10px;
                                                border-top: none;
                                                border-left: none;
                                                border-right: none;
                                                box-shadow: none;
                                                border-radius: 0;
                                            ">
                                            <option value="">Assign to</option>
                                            <option value="NoOne">No One</option>
                                            <optgroup label="Users">
                                                <option value="Bryan">Bryan</option>
                                                <option value="Ian">Ian</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 30px;">
                                <button type="submit" class="btn btn-danger pull-left"> Delete</button>
                                <button type="submit" class="btn btn-default pull-left"> Cancel</button>
                                <button type="submit" class="btn btn-primary pull-right"> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
