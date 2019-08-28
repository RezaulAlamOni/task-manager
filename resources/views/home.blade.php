@extends('spark::layouts.app')

@section('breadcrumb')
    <h1>Home</h1>
    <ol class="breadcrumb">
        <li>
            <a href="/home">
                <i class="fa fa-fw ti-home"></i> Home
            </a>
        </li>
    </ol>
@endsection
@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default border-success">
                    <div class="card-header bg-success text-white">{{__('Dashboard')}}</div>

                    <div class="card-body">
                        {{__('Your application\'s dashboard.')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
