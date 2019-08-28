@extends('spark::layouts.app')
@section("title") Home @endsection
@section('styles')
    <link href="vendors/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="vendors/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <style>
        #select25 + .select2-container .select2-selection{
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
            height: 32px;
        }
        #select24 + .select2-container .select2-selection{
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            height: 32px;
        }
        .select2-container .select2-selection--single{
            height: 32px;
            border: 1px solid #d1d1d1;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,.select2-container--default .select2-selection--multiple{
            border: 1px solid #d1d1d1;
        }
        .input-group.append,.input-group.prepend{
            width:101%;
        }
        @media (max-width:1024px){
            .append .select2-container, .prepend .select2-container{
                 width: 88% !important;
            }
        }
        @media(max-width:320px){
            .append .select2-container, .prepend .select2-container{
                width: 85% !important;
            }
        }
    </style>
@endsection
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
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                                <i class="fa fa-fw ti-widget-alt"></i> Select2
                            </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="select21" class="control-label">
                                Select2 single select
                            </label>
                            <select id="select21" class="form-control select2" style="width:100%">
                                <option value="">Select value...</option>
                                <optgroup label="Alaskan/Hawaiian Time Zone">
                                    <option value="AK">Alaska</option>
                                    <option value="HI">Hawaii</option>
                                </optgroup>
                                <optgroup label="Pacific Time Zone">
                                    <option value="CA">California</option>
                                    <option value="NV">Nevada</option>
                                    <option value="OR">Oregon</option>
                                    <option value="WA">Washington</option>
                                </optgroup>
                                <optgroup label="Mountain Time Zone">
                                    <option value="AZ">Arizona</option>
                                    <option value="CO">Colorado</option>
                                    <option value="ID">Idaho</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="ND">
                                        North Dakota
                                    </option>
                                    <option value="UT">Utah</option>
                                    <option value="WY">Wyoming</option>
                                </optgroup>
                                <optgroup label="Central Time Zone">
                                    <option value="AL">Alabama</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">
                                        Mississippi
                                    </option>
                                    <option value="MO">Missouri</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="SD">
                                        South Dakota
                                    </option>
                                    <option value="TX">Texas</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="WI">Wisconsin</option>
                                </optgroup>
                                <optgroup label="Eastern Time Zone">
                                    <option value="CT">
                                        Connecticut
                                    </option>
                                    <option value="DE">Delaware</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="IN">Indiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">
                                        Massachusetts
                                    </option>
                                    <option value="MI">Michigan</option>
                                    <option value="NH">
                                        New Hampshire
                                    </option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">
                                        North Carolina
                                    </option>
                                    <option value="OH">Ohio</option>
                                    <option value="PA">
                                        Pennsylvania
                                    </option>
                                    <option value="RI">
                                        Rhode Island
                                    </option>
                                    <option value="SC">
                                        South Carolina
                                    </option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WV">
                                        West Virginia
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select22" class="control-label">
                                Select2 multi select
                            </label>
                            <select id="select22" class="form-control select2" multiple style="width:100%">
                                <optgroup label="Alaskan/Hawaiian Time Zone">
                                    <option value="AK">Alaska</option>
                                    <option value="HI">Hawaii</option>
                                </optgroup>
                                <optgroup label="Pacific Time Zone">
                                    <option value="CA">California</option>
                                    <option value="NV">Nevada</option>
                                    <option value="OR">Oregon</option>
                                    <option value="WA">Washington</option>
                                </optgroup>
                                <optgroup label="Mountain Time Zone">
                                    <option value="AZ">Arizona</option>
                                    <option value="CO">Colorado</option>
                                    <option value="ID">Idaho</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="ND">
                                        North Dakota
                                    </option>
                                    <option value="UT">Utah</option>
                                    <option value="WY">Wyoming</option>
                                </optgroup>
                                <optgroup label="Central Time Zone">
                                    <option value="AL">Alabama</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">
                                        Mississippi
                                    </option>
                                    <option value="MO">Missouri</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="SD">
                                        South Dakota
                                    </option>
                                    <option value="TX">Texas</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="WI">Wisconsin</option>
                                </optgroup>
                                <optgroup label="Eastern Time Zone">
                                    <option value="CT">
                                        Connecticut
                                    </option>
                                    <option value="DE">Delaware</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="IN">Indiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">
                                        Massachusetts
                                    </option>
                                    <option value="MI">Michigan</option>
                                    <option value="NH">
                                        New Hampshire
                                    </option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">
                                        North Carolina
                                    </option>
                                    <option value="OH">Ohio</option>
                                    <option value="PA">
                                        Pennsylvania
                                    </option>
                                    <option value="RI">
                                        Rhode Island
                                    </option>
                                    <option value="SC">
                                        South Carolina
                                    </option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WV">
                                        West Virginia
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select24" class="control-label">
                                Select2 append
                            </label>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group-append">
                                        <select id="select24" class="form-control custom-select" style="width:93%">
                                            <option value="">Select value...</option>
                                            <optgroup label="Alaskan/Hawaiian Time Zone">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </optgroup>
                                            <optgroup label="Pacific Time Zone">
                                                <option value="CA">California</option>
                                                <option value="NV">Nevada</option>
                                                <option value="OR">Oregon</option>
                                                <option value="WA">Washington</option>
                                            </optgroup>
                                            <optgroup label="Mountain Time Zone">
                                                <option value="AZ">Arizona</option>
                                                <option value="CO">Colorado</option>
                                                <option value="ID">Idaho</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="ND">
                                                    North Dakota
                                                </option>
                                                <option value="UT">Utah</option>
                                                <option value="WY">Wyoming</option>
                                            </optgroup>
                                            <optgroup label="Central Time Zone">
                                                <option value="AL">Alabama</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">
                                                    Mississippi
                                                </option>
                                                <option value="MO">Missouri</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="SD">
                                                    South Dakota
                                                </option>
                                                <option value="TX">Texas</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="WI">Wisconsin</option>
                                            </optgroup>
                                            <optgroup label="Eastern Time Zone">
                                                <option value="CT">
                                                    Connecticut
                                                </option>
                                                <option value="DE">Delaware</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="IN">Indiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">
                                                    Massachusetts
                                                </option>
                                                <option value="MI">Michigan</option>
                                                <option value="NH">
                                                    New Hampshire
                                                </option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">
                                                    North Carolina
                                                </option>
                                                <option value="OH">Ohio</option>
                                                <option value="PA">
                                                    Pennsylvania
                                                </option>
                                                <option value="RI">
                                                    Rhode Island
                                                </option>
                                                <option value="SC">
                                                    South Carolina
                                                </option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WV">
                                                    West Virginia
                                                </option>
                                            </optgroup>
                                        </select>
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="select24"> <span class="ti-search"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group prepend">
                            <label for="select25" class="control-label">
                                Select2 prepend
                            </label>
                            <div class="row">
                                <div class="col-12">

                                    <div class="input-group-prepend">
                                        <div class="input-group-prepend" data-select2-open="select25">
                                            <label class="input-group-text" for="inputGroupSelect01"><span class="ti-search"></span></label>
                                        </div>
                                        <select class="custom-select" id="select25"  style="width:94%">
                                            <option value="">Select value...</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--ends-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--col-md-6 ends-->
</div>
@endsection @section('custom_scripts')
<script src="vendors/select2/js/select2.js" type="text/javascript"></script>
{{--<script src="https://raw.githubusercontent.com/select2/select2/master/dist/js/select2.full.js"></script>--}}
<script>
    "use strict"
    $(document).ready(function(){
        $("#select21").select2({
//            theme: "bootstrap",
            placeholder: "single select"
        });
        $("#select22").select2({
//            theme: "bootstrap",
            placeholder: "multi select"
        });
        $("#select24").select2({
//            theme: "bootstrap",
            placeholder: "multi select"
        });
        $("#select25").select2({
           theme: "bootstrap",
            placeholder: "multi select",
            // containerCssClass:all
        });
    });
</script>
@endsection
