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
		<h1 class="subtitle-color-5 text-uppercase text-center">{{ trans('auth.login') }}</h1>
        <p class="subtitle-color-6 text-center"><strong>{{$settings->title}}</strong></p>
		@include('errors.errors-forms')

					@if (session('login_required'))
			<div class="alert alert-danger" id="dangerAlert">
            		<i class="glyphicon glyphicon-alert myicon-right"></i> {{ session('login_required') }}
            		</div>
            	@endif

          	<form action="{{ url('login') }}" method="post" name="form" id="signup_form">

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

           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.sign_in') }}</button>
           <a href="{{url('/register')}}" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.sign_up') }}</a>
          </form>

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

	$('#buttonSubmit').click(function(){
    	$(this).css('display','none');
    	$('.auth-social').css('display','none');
    	$('<div class="btn-block text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw fa-loader"></i></div>').insertAfter('#signup_form');
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
@endsection
