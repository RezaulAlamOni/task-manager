<form role="form">
    @if (Spark::usesTeams() && Spark::onlyTeamPlans())
        <!-- Team Name -->
        <div class="form-group row" v-if=" ! invitation">
            {{--<label class="col-md-4 col-form-label text-md-right">{{ __('teams.team_name') }}</label>--}}

            <div class="col-md-12">
                <input type="text" class="form-control" placeholder="Teamname" name="team" v-model="registerForm.team" :class="{'is-invalid': registerForm.errors.has('team')}" autofocus>

                <span class="invalid-feedback" v-show="registerForm.errors.has('team')">
                    @{{ registerForm.errors.get('team') }}
                </span>
            </div>
        </div>

        @if (Spark::teamsIdentifiedByPath())
            <!-- Team Slug (Only Shown When Using Paths For Teams) -->
            <div class="form-group row" v-if=" ! invitation">
                <label class="col-md-4 col-form-label text-md-right">{{ __('teams.team_slug') }}</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="team_slug" v-model="registerForm.team_slug" :class="{'is-invalid': registerForm.errors.has('team_slug')}" autofocus>

                    <small class="form-text text-muted" v-show="! registerForm.errors.has('team_slug')">
                        {{__('teams.slug_input_explanation')}}
                    </small>

                    <span class="invalid-feedback" v-show="registerForm.errors.has('team_slug')">
                        @{{ registerForm.errors.get('team_slug') }}
                    </span>
                </div>
            </div>
        @endif
    @endif

    <!-- Name -->
    <div class="form-group row">
        {{--<label class="col-md-4 col-form-label text-md-right">{{__('Name')}}</label>--}}

        <div class="col-md-12">
            <input type="text" class="form-control" placeholder="Name" name="name" v-model="registerForm.name" :class="{'is-invalid': registerForm.errors.has('name')}" autofocus>

            <span class="invalid-feedback" v-show="registerForm.errors.has('name')">
                @{{ registerForm.errors.get('name') }}
            </span>
        </div>
    </div>

    <!-- E-Mail Address -->
    <div class="form-group row">
        {{--<label class="col-md-4 col-form-label text-md-right">{{__('E-Mail Address')}}</label>--}}

        <div class="col-md-12">
            <input type="email" class="form-control" placeholder="Email" name="email" v-model="registerForm.email" :class="{'is-invalid': registerForm.errors.has('email')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('email')">
                @{{ registerForm.errors.get('email') }}
            </span>
        </div>
    </div>

    <!-- Password -->
    <div class="form-group row">
        {{--<label class="col-md-4 col-form-label text-md-right">{{__('Password')}}</label>--}}

        <div class="col-md-12">
            <input type="password" class="form-control" placeholder="Password" name="password" v-model="registerForm.password" :class="{'is-invalid': registerForm.errors.has('password')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('password')">
                @{{ registerForm.errors.get('password') }}
            </span>
        </div>
    </div>

    <!-- Password Confirmation -->
    <div class="form-group row">
        {{--<label class="col-md-4 col-form-label text-md-right">{{__('Confirm Password')}}</label>--}}

        <div class="col-md-12">
            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" v-model="registerForm.password_confirmation" :class="{'is-invalid': registerForm.errors.has('password_confirmation')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('password_confirmation')">
                @{{ registerForm.errors.get('password_confirmation') }}
            </span>
        </div>
    </div>

    <!-- Terms And Conditions -->
    <div v-if=" ! selectedPlan || selectedPlan.price == 0">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input " id="terms" :class="{'is-invalid': registerForm.errors.has('terms')}" v-model="registerForm.terms">
                    <label class="form-check-label login-check-label" for="terms">
                        &nbsp;&nbsp;{!! __('I Accept :linkOpen The Terms Of Service :linkClose', ['linkOpen' => '<a href="/terms" target="_blank">', 'linkClose' => '</a>']) !!}
                    </label>
                    <div class="invalid-feedback" v-show="registerForm.errors.has('terms')">
                        <strong>@{{ registerForm.errors.get('terms') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-12">
                <button class="btn btn-primary btn-block" @click.prevent="register" :disabled="registerForm.busy">
                    <span v-if="registerForm.busy">
                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Registering')}}
                    </span>

                    <span v-else>
                        <i class="fa fa-btn fa-check-circle"></i> {{__('Register')}}
                    </span>
                </button>
            </div>
            <div class="pl-3 mt-3">
                <span class="sign-in">Already a member? <a href="{{ url('/login') }}">Sign In</a></span>
            </div>

        </div>
        <div class="row text-center social_register">
            <div class="col-md-12 col-xs-12">
                <p class="alter">Sign up with</p>

                <div class="col-12">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-lg btn-facebook">
                                <i class="ti-facebook"></i>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-lg btn-twitter">
                                <i class="ti-twitter-alt"></i>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-lg btn-google">
                                <i class="ti-google"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
