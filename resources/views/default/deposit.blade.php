<?php
  $settings = App\Models\AdminSettings::first();
  $donations = \App\Models\Donations::findOrFail($response);
  $amount = $donations->donation +  Auth::user()->saldo;
 ?>
 @extends('app')

 @section('title')
  Deposit
 @endsection

 @section('css')
  <link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
 @endsection

 @section('content')

 <div class="jumbotron md header-donation jumbotron_set">
	 <div class="container wrap-jumbotron position-relative">
		 <h2 class="title-site">Saldo</h2>
	 </div>
 </div>

 <div class="container margin-bottom-40 padding-top-40">

	 <!-- Col MD -->
	 <div class="col-md-8 margin-bottom-20">

		 <!-- form start -->
		 <form method="POST" action="{{ url('deposit',$response) }}" enctype="multipart/form-data" id="formDonation">

       <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <!-- Start -->
       <div class="panel panel-default">
         <div class="panel-body">
           <div class="row">
               <div class="col-sm-8">Nominal Donasi</div>
               <div class="col-sm-4 pull-right">{{ $settings->currency_symbol.number_format($donations->donation) }}</div>
           </div>
           <div class="row">
               <div class="col-sm-8">Saldo Anda</div>
               <div class="col-sm-4 pull-right">{{ $settings->currency_symbol.number_format($amount) }}</div>
           </div>
           <div class="row">
               <div class="col-sm-8">Saldo Akhir</div>
               <div class="col-sm-4 pull-right">{{ $settings->currency_symbol.number_format(Auth::user()->saldo) }}</div>
           </div>
           <div class="row">
             <div class="col-sm-8">
               Pastikan saldo anda berkurang dengan benar
             </div>
           </div>
         </div>
       </div>

			 <!-- Alert -->
			 <div class="alert alert-danger display-none" id="errorDonation">
				 <ul class="list-unstyled" id="showErrorsDonation"></ul>
			 </div><!-- Alert -->

			 <div class="box-footer text-center">
				 <hr />
				 <button type="submit" id="buttonDonation" class="btn-padding-custom btn btn-lg btn-main custom-rounded">Selesai</button>
				 <div class="btn-block text-center margin-top-20">
					 <a href="{{url('campaign')}}" class="text-muted">
						 <i class="fa fa-long-arrow-left"></i>{{trans('auth.back')}}</a>
					 </div>
				 </div><!-- /.box-footer -->

			 </form>

		 </div><!-- /COL MD -->

     <div class="col-md-4">

       <!-- Panel right side -->

     </div><!-- /COL MD -->
	 </div><!-- container wrap-ui -->
@endsection

@section('javascript')
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>

<script type="text/javascript">
/*function onlyNumber(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if ((charCode < 48 || charCode > 57))
        return false;
    return true;
}*/

$('#onlyNumber').focus();

$(document).ready(function() {

	$("#onlyNumber").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


    $('input').iCheck({
	  	checkboxClass: 'icheckbox_square-red',
    	radioClass: 'iradio_square-red',
	    increaseArea: '20%' // optional
	  });

});
</script>
@endsection
