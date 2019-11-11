<template>
    <div class="Rules">
        <div class="container rules-create" v-if="rules_action === 'create'">
            <section>
                <div class="container-header">
                    <h2><i class="fa fa-fw fa-magic"></i> Rules For [[Project Name Here]]
                        <button type="submit" class="btn btn-primary pull-right"> Create Rule</button>
                    </h2>
                    <p class="compltit-p">Rules will automatically assign and move card between boards, columns and
                        users.</p>
                </div>
            </section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default border-primary">
                        <div class="card-header bg-primary text-white">
                            [[CLICK HERE TO NAME RULE]]
                            <span class="badge-pill badge-success">Live [[Click to disable]]</span> <span
                            class="badge-pill badge-danger">Paused [[Click to enable]]</span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/from-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select221" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
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
                                    </select></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/to-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select21" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
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
                                    </select></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/than-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select231" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
                                        <option value="">Assign to</option>
                                        <option value="NoOne">No One</option>
                                        <optgroup label="Users">
                                            <option value="Bryan">Bryan</option>
                                            <option value="Ian">Ian</option>
                                        </optgroup>
                                    </select></div>
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
        <div class="container rules-create">
            <section>
                <div class="container-header">
                    <h2><i class="fa fa-fw fa-magic"></i> Rules For [[Project Name Here]]
                        <button type="submit" class="btn btn-primary pull-right"> Create Rule</button>
                    </h2>
                    <p class="compltit-p">Rules will automatically assign and move card between boards, columns and
                        users.</p>
                </div>
            </section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default border-primary">
                        <div class="card-header bg-primary text-white">
                            [[CLICK HERE TO NAME RULE]]
                            <span class="badge-pill badge-success">Live [[Click to disable]]</span> <span
                            class="badge-pill badge-danger">Paused [[Click to enable]]</span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/from-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select221" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
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
                                    </select></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/to-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select21" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
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
                                    </select></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-3"><img src="/img/than-arrow.png" class="img-responsive"
                                                                     style="max-width: 200px;"></div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group"><select id="select231" class="form-control select2"
                                                                    style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
                                        <option value="">Assign to</option>
                                        <option value="NoOne">No One</option>
                                        <optgroup label="Users">
                                            <option value="Bryan">Bryan</option>
                                            <option value="Ian">Ian</option>
                                        </optgroup>
                                    </select></div>
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
    </div>
</template>

<script>
    export default {
        props: {},
        data() {
            return {
                rules_action : 'create',
            }
        }
    };
</script>
