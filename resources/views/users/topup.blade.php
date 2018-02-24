<?php
// ** Data User logged ** //
$user = Auth::user();

// Banks
$banks = \App\Models\Banks::all();
?>
@extends('app')

@section('title') {{ trans('users.account_settings') }} - @endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
	<div class="container wrap-jumbotron position-relative">
		<h2 class="title-site">{{ trans('users.account_settings') }}</h2>
	</div>
</div>

<div class="container margin-bottom-40">

	<!-- Col MD -->
	<div class="col-md-8 margin-bottom-20">

		@if (session('notification'))
		<div class="alert alert-success btn-sm alert-fonts" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ session('notification') }}
		</div>
		@endif

		@include('errors.errors-forms')

		<!-- form start -->
		<form method="POST" action="{{ url('topup',$response) }}" enctype="multipart/form-data" id="formDonation">

			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_id" value="{{ $response }}">

			<div class="form-group">
				<label>Pilih Nominal Top Up</label>
				<select name="amount" class="form-control input-lg" >
					<option value="">Pilih Nominal Top Up</option>
					<option value="50000">Rp.50,000</option>
					<option value="100000">Rp.100,000</option>
					<option value="200000">Rp.200,000</option>
					<option value="500000">Rp.500,000</option>
					<option value="1000000">Rp.1,000,000</option>
				</select>
			</div>

			<div class="form-group">
				<label for="payment_gateway">Metode Pembayaran</label>
				@foreach(  $banks as $bank )
				 <div class="radio">
					 <label><input type="radio" name="payment_gateway" value="{{$bank->id}}">Transfer {{ $bank->name }}</label>
				 </div>
				@endforeach
				<div class="radio">
					 <label><input type="radio" name="payment_gateway" value="Deposit">Potong Saldo</label>
				</div>
				<div class="radio">
				   <label><input type="radio" name="payment_gateway" value="Midtrans">Midtrans</label>
				</div>
				<div class="radio">
					 <label><input type="radio" name="payment_gateway" value="Payment" disabled>Pembayaran Lain</label>
				</div>
			</div>
			<!-- Alert -->
			<div class="alert alert-danger display-none" id="errorDonation">
				<ul class="list-unstyled" id="showErrorsDonation"></ul>
			</div><!-- Alert -->

			<div class="box-footer text-center">
				<hr />
				<button type="submit" id="buttonDonation" class="btn-padding-custom btn btn-lg btn-main custom-rounded">Top Up</button>
			</div><!-- /.box-footer -->

		</form>

	</div><!-- /COL MD -->

	<div class="col-md-4">
		@include('users.navbar-edit')
	</div>


</div><!-- container -->

<!-- container wrap-ui -->
@endsection

@section('javascript')
@endsection
