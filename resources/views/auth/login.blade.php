<?php $settings = App\Models\AdminSettings::first(); ?>
@extends('app')

@section('title')
{{ trans('auth.login') }} -
@stop

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')



<div class="container margin-top-100 margin-bottom-40">

	<div class="row">
		<!-- Col MD -->
		<div class="col-md-12">

			<h2 class="text-center line position-relative">{{ trans('misc.welcome_back') }}</h2>

			<div class="login-form">
				<!-- <h1 class="subtitle-color-5 text-uppercase text-center">{{ trans('auth.login') }}</h1>
				<p class="subtitle-color-6 text-center"><strong>{{$settings->title}}</strong></p> -->
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#login">Sign In</a></li>
					<li><a data-toggle="tab" href="#register">Sign Up</a></li>
				</ul>

				<div class="tab-content">
					<div id="login" class="tab-pane fade in active">
						@include('errors.errors-forms')

						@if (session('login_required'))
						<div class="alert alert-danger" id="dangerAlert">
							<i class="glyphicon glyphicon-alert myicon-right"></i> {{ session('login_required') }}
						</div>
						@endif

						<br>

						<form action="{{ url('login') }}" method="post" name="form" id="signin_form">

							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group has-feedback">
								<input type="text" class="form-control login-field custom-rounded" value="{{ old('email') }}" name="email" id="username_or_email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>

							<div class="form-group has-feedback">
								<input type="password" class="form-control login-field custom-rounded" name="password" id="password" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" autocomplete="off">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>

							<div class="row margin-bottom-15">
								<div class="col-xs-7">
									<div class="checkbox icheck margin-zero">
										<label class="margin-zero">
											<input @if( old('remember') ) checked="checked" @endif id="keep_login" class="no-show" name="remember" type="checkbox" value="1">
											<span class="keep-login-title">{{ trans('auth.remember_me') }}</span>
										</label>
									</div>
								</div>

								<div class="col-xs-5">
									<label class="btn-block">
										<a href="{{url('/password/reset')}}" class="label-terms recover">{{ trans('auth.forgot_password') }}</a>
									</label>
								</div>
							</div><!-- row -->

							<button type="submit" id="submitLogin" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.sign_in') }}</button>
						</form>

					</div>
					<div id="register" class="tab-pane fade">
						@if (session('notification'))
						<div class="alert alert-success text-center">

							<div class="btn-block text-center margin-bottom-10">
								<i class="glyphicon glyphicon-ok ico_success_cicle"></i>
							</div>

							{{ session('notification') }}
						</div>
						@endif

						@include('errors.errors-forms')

						<form action="{{ url('register') }}" method="post" name="form" id="signup_form">

							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<!-- FORM GROUP -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group has-feedback">
										<input type="hidden" class="form-control login-field custom-rounded" value="{{ old('user_id') }}" name="user_id" placeholder="KTP" title="{{ trans('users.id') }}" autocomplete="off">

									</div><!-- ./FORM GROUP -->
								</div>
							</div>

							<!-- FORM GROUP -->
							<div class="form-group has-feedback">
								<input type="text" class="form-control login-field custom-rounded" value="{{ old('name') }}" name="name" placeholder="{{ trans('users.name') }}" title="{{ trans('users.name') }}" autocomplete="off">
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div><!-- ./FORM GROUP -->

							<div class="row">
								<div class="col-md-6">
									<!-- FORM GROUP -->
									<div class="form-group has-feedback">
										<input type="text" class="form-control login-field custom-rounded" value="{{ old('phone1') }}" name="phone1" placeholder="{{ trans('users.phone1') }}" title="{{ trans('users.phone1') }}" autocomplete="off">
										<span class="glyphicon glyphicon-phone form-control-feedback"></span>
									</div>
								</div>

								<div class="col-md-6">
									<!-- FORM GROUP -->
									<div class="form-group has-feedback">
										<input type="text" class="form-control login-field custom-rounded" value="{{ old('email') }}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
										<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
									</div><!-- ./FORM GROUP -->
								</div>
							</div>

							<!-- FORM GROUP -->
							<div class="form-group has-feedback">
								<input type="password" class="form-control login-field custom-rounded" name="password" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" autocomplete="off">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div><!-- ./FORM GROUP -->

							<div class="form-group has-feedback">
								<input type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('auth.confirm_password') }}" title="{{ trans('auth.confirm_password') }}" autocomplete="off">
								<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
							</div>


							@if( $settings->captcha == 'on' )
							<div class="form-group has-feedback">
								<input type="text" class="form-control login-field" name="captcha" id="lcaptcha" placeholder="" title="">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>

								<div class="alert alert-danger btn-sm margin-top-alert" id="errorCaptcha" role="alert" style="display: none;">
									<strong><i class="glyphicon glyphicon-alert myicon-right"></i> {{Lang::get('auth.error_captcha')}}</strong>
								</div>
							</div>
							@endif

							<button type="submit" id="submitRegister" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.sign_up') }}</button>

						</form>
					</div>
				</div>

			</div><!-- Login Form -->

		</div><!-- /COL MD -->

	</div><!-- ROW -->

</div><!-- row -->

<!-- container wrap-ui -->

@endsection

@section('javascript')
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>

<script type="text/javascript">

$('#username_or_email').focus();

$('#submitLogin').click(function(){
	$(this).css('display','none');
	$('.auth-social').css('display','none');
	$('<div class="btn-block text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw fa-loader"></i></div>').insertAfter('#signin_form');
});

$(document).ready(function(){
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});
});

@if (count($errors) > 0)
scrollElement('#dangerAlert');
@endif
</script>

<script src="{{ asset('public/plugins/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>

<script type="text/javascript">
$('#datebirth').datepicker({
	autoclose: true,
	format: 'dd-mm-yyyy',
	language: 'en'
});


@if( $settings->captcha == 'on' )
/*
*  ==============================================  Captcha  ============================== * /
*/
var captcha_a = Math.ceil( Math.random() * 5 );
var captcha_b = Math.ceil( Math.random() * 5 );
var captcha_c = Math.ceil( Math.random() * 5 );
var captcha_e = ( captcha_a + captcha_b ) - captcha_c;

function generate_captcha( id ) {
	var id = ( id ) ? id : 'lcaptcha';
	$("#" + id ).html( captcha_a + " + " + captcha_b + " - " + captcha_c + " = ").attr({'placeholder' : captcha_a + " + " + captcha_b + " - " + captcha_c, title: 'Captcha = '+captcha_a + " + " + captcha_b + " - " + captcha_c });
}
$("input").attr('autocomplete','off');
generate_captcha('lcaptcha');

$(document).on('click','#submitRegister', function(e){
	e.preventDefault();
	var captcha        = $("#lcaptcha").val();
	if( captcha != captcha_e ){
		$('.wrap-loader').hide();
		var error = true;
		$("#errorCaptcha").fadeIn(500);
		$('#lcaptcha').focus();

		return false;
	} else {
		$(this).css('display','none');
		$('.auth-social').css('display','none');
		$('<div class="btn-block text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw fa-loader"></i></div>').insertAfter('#signup_form');
		$('#signup_form').submit();
	}
});

@else

$('#submitRegister').click(function(){
	$(this).css('display','none');
	$('.auth-social').css('display','none');
	$('<div class="btn-block text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw fa-loader"></i></div>').insertAfter('#signup_form');
});

@endif

@if (count($errors) > 0)
scrollElement('#dangerAlert');
@endif

@if (session('notification'))
$('#signup_form, #dangerAlert').remove();
@endif

</script>
@endsection
