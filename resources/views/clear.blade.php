@extends('spark::layouts.app') @section("title") Home @endsection @section('breadcrumb')
<h1>Home</h1>
<ol class="breadcrumb">
    <li>
        <a href="/home">
            <i class="fa fa-fw ti-home"></i> Home
        </a>
    </li>
</ol>
@endsection @section('content')
<invoice></invoice>
@endsection
