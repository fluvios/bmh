<?php
  $settings = App\Models\AdminSettings::first();
  $donations = \App\Models\Donations::findOrFail($response);
  $bank = \App\Models\Banks::findOrFail($donations->bank_id);
  $amount = $donations->donation - $donations->amount_key;
 ?>
 @extends('app')

 @section('title')
  Transfer
 @endsection

 @section('css')
  <link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
 @endsection

 @section('content')

 

 <div class="container margin-bottom-40 padding-top-40">

   <!-- Col MD -->
   <div class="col-md-12 margin-bottom-20">

     <!-- form start -->
     <form method="POST" action="{{ url('transfer',$response) }}" enctype="multipart/form-data" id="formDonation">

       <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <!-- Start -->
       <div class="col-md-8 panel panel-default">
         <div class="panel-body">
           <div class="row">
               <div class="col-sm-8 subtitle-color-15">Nominal Donasi</div>
               <div class="col-sm-4  subtitle-color-15 pull-left">{{ $settings->currency_symbol.number_format($amount) }}</div>
           </div>
           <div class="row ">
               <div class="col-sm-8 subtitle-color-15">Kode Unik (akan didonasikan)</div>
               <div class="col-sm-4 subtitle-color-15 pull-left garis">{{  $settings->currency_symbol.number_format($donations->amount_key) }}</div>
               
           </div>
           <div class="row margin-bottom-20">
               <div class="col-sm-8 subtitle-color-15">Total</div>
               <div class="col-sm-4 subtitle-color-16 pull-left">{{ $settings->currency_symbol.number_format($donations->donation) }}</div>
           </div>
           <div class="row">
             <div class=" alert alert-warning btn-sm alert-fonts" role="alert">
               PENTING! Transfer sampai 3 digit terakhir agar donasi dapat diproses
             </div>
           </div>
           <div class="row">
             <div class="col-sm-6 margin-bottom-5 subtitle-color-13 text-uppercase">
               Silahkan transfer :
               <img class="margin-top-20" src="{{ asset('public/bank/'. $bank->logo) }}" height="85%" width="85%">
             </div>
             <div class="col-sm-4 pull-right">
               <h3 class="text-uppercase subtitle-color-14">{{ $bank->account_number }}</h3>
               <h5 class="text-uppercase">Atas nama: {{ $bank->account_name }}</h5>
               <h5 class="text-uppercase">Cabang: {{ $bank->branch }}</h5>
               <h5 class="text-uppercase">{{ $bank->name }} ({{ $bank->slug }})</h5>
             </div>
           </div>
           <div class="row">
             <div class="col-sm-8 margin-top-20">
               Pastikan anda transfer sebelum <b>{{ $donations->expired_date }} WIB</b> atau donasi anda otomatis dibatalkan oleh sistem.
             </div>
           </div>
         </div>
         <center>
         <button type="submit" id="buttonDonation" class="btn-padding-custom btn btn-lg btn-main custom-rounded">Selesai</button></center>
       </div>

       <!-- Alert -->
       <div class="alert alert-danger display-none" id="errorDonation">
         <ul class="list-unstyled" id="showErrorsDonation"></ul>
       </div><!-- Alert -->

       <div class="box-footer text-center">
        <div class="col-md-4">
    @include('users.navbar-edit')
  </div>
         <hr />
         
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
