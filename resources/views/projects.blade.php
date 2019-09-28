@extends('spark::layouts.app') @section("title") Home @endsection
@section('breadcrumb')
@endsection @section('content')

{{--    <projects></projects>--}}
<div id="project">
    <router-view></router-view>
</div>
@endsection
