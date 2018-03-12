
<?php
$userAuth = Auth::user();
?>
<div class="panel panel-default ">
          <div class="panel-body">
          	<center>
          	<img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" alt="User" s class="img-circle avatarUser margin-bottom-10" width="100" height="100"   />
            <p class=" subtitle-color-9 text-uppercase"><a >Informasi Kontak</a></p>
            <h5 class="subtitle-color-10" >Nama  : {{ Auth::user()->name }}</h5>
            <h5 class="subtitle-color-10">Alamat: {{ Auth::user()->phone_number_1 }}</h5>
            <h5 class="subtitle-color-10">Email : {{ Auth::user()->email }}</h5>
            <a href="{{ url('account') }}" class="btn btn-primary">
              <i class="icon icon-pencil myicon-right"></i> Edit Kontak
            </a>

          </div>
        </div>
<ul class="nav nav-pills nav-stacked">

	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('dashboard') }}" class="list-group-item @if(Request::is('dashboard'))active @endif">

			<i class="icon icon-dashboard myicon-right"></i> Dashboard

		</a> <!-- **** ./ list-group-item **** -->

	</li>

	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account') }}" class="list-group-item @if(Request::is('account'))active @endif">

			<i class="icon icon-pencil myicon-right"></i> {{ trans('users.account_settings') }}

		</a> <!-- **** ./ list-group-item **** -->

	</li>

	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account/password') }}" class="list-group-item @if(Request::is('account/password'))active @endif">

			<i class="icon icon-lock myicon-right"></i> {{ trans('auth.password') }}

		</a> <!-- **** ./ list-group-item **** -->

	</li>

@if( $userAuth->role == 'admin' )

	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account/campaigns') }}" class="list-group-item @if(Request::is('account/campaigns'))active @endif">

			<i class="ion ion-speakerphone myicon-right"></i> {{ trans('misc.campaigns') }}

		</a> <!-- **** ./ list-group-item **** -->

	</li>
	@endif



	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account/donations') }}" class="list-group-item @if(Request::is('account/donations'))active @endif">

			<i class="ion ion-social-usd myicon-right"></i> {{ trans('misc.donations') }}

		</a> <!-- **** ./ list-group-item **** -->

	</li>



	<!-- ****<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<!-- ****<a href="{{ url('account/withdrawals') }}" class="list-group-item @if(Request::is('account/withdrawals'))active @endif">

			<i class="fa fa-money myicon-right"></i> {{ trans('misc.withdrawals') }}

		</a> <!-- **** ./ list-group-item

	</li>**** -->

	<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account/topup') }}" class="list-group-item @if(Request::is('account/topups'))active @endif">

			<i class="fa fa-cloud-upload"></i> Tambah Saldo

		</a> <!-- **** ./ list-group-item **** -->

	</li>
<li class="margin-bottom-5">

		<!-- **** list-group-item **** -->

		<a href="{{ url('account/mutasi') }}" class="list-group-item @if(Request::is('account/mutasi'))active @endif">

			<i class="ion ion-speakerphone myicon-right"></i> Mutasi Saldo

		</a> <!-- **** ./ list-group-item **** -->

	</li>

  <li class="margin-bottom-5">

  		<!-- **** list-group-item **** -->

  		<a href="{{ url('account/referral') }}" class="list-group-item @if(Request::is('account/referral'))active @endif">

  			<i class="ion ion-link myicon-right"></i> Affiliation System

  		</a> <!-- **** ./ list-group-item **** -->

  	</li>

</ul>
