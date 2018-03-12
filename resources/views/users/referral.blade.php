<?php
// ** Data User logged ** //
$settings = App\Models\AdminSettings::first();

$data = App\Models\ReferralRegistrasi::paginate(20);
$data2 = App\Models\ReferralDonasi::paginate(20);

$total = App\Models\ReferralRegistrasi::sum('bonus') + App\Models\ReferralDonasi::sum('bonus');
?>
@extends('app')

@section('title') Referral - @endsection

@section('content')


<div class=" container margin-top-90 margin-bottom-40">
	<h2 class="subtitle-color-7 text-uppercase">Referral</h2>
	<!-- Col MD -->
	<div class=" login-form-2 col-md-8 margin-bottom-20">

		<div class=" table-responsive">
			<table class="table table-striped">
				<h4>Referral Registrasi</h4>
				<h5>Link: {{url('ref/register/'.Auth::user()->email)}}</h5>
				@if( $data->total() !=  0 && $data->count() != 0 )
				<thead>
					<tr>
						<th class="active">ID</th>
						<th class="active">Email</th>
						<th class="active">Tanggal Registrasi</th>
						<th class="active">Bonus</th>
					</tr>
				</thead>

				<tbody>
					@foreach( $data as $registration )
					<tr>
						<td>{{ $registration->id }}</td>
						<td>{{ $registration->email }}</td>
						<td>{{ date('d M, y', strtotime($registration->registration_date)) }}</td>
						<td>{{ $settings->currency_symbol.number_format($registration->bonus) }}</td>
					</tr><!-- /.TR -->
					@endforeach

					@else
					<hr />
					<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>

					@endif
				</tbody>
			</table>
		</div>

		@if( $data2->lastPage() > 1 )
		{{ $data2->links() }}
		@endif

		<div class=" table-responsive">
			<table class="table table-striped">
				<h4>Referral Donasi</h4>

				@if( $data2->total() !=  0 && $data2->count() != 0 )
				<thead>
					<tr>
						<th class="active">ID</th>
						<th class="active">Link Donasi</th>
						<th class="active">Tanggal Donasi</th>
						<th class="active">Email</th>
						<th class="active">Bonus</th>
					</tr>
				</thead>

				<tbody>
					@foreach( $data2 as $donation )
					<tr>
						<td>{{ $donation->id }}</td>
						<td>{{ $donation->link_donasi }}</td>
						<td>{{ date('d M, y', strtotime($donation->date_donasi)) }}</td>
						<td>{{ $donation->email }}</td>
						<td>{{ $settings->currency_symbol.number_format($donation->bonus) }}</td>
					</tr><!-- /.TR -->
					@endforeach

					@else
					<hr />
					<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>

					@endif
				</tbody>
			</table>
		</div>

		@if( $data->lastPage() > 1 )
		{{ $data->links() }}
		@endif
		<h3>Total Bonus: {{ $settings->currency_symbol.number_format($total) }}</h3>
	</div><!-- /COL MD -->

	<div class="col-md-4">
		@include('users.navbar-edit')
	</div>

</div><!-- container -->

<!-- container wrap-ui -->
@endsection
