<?php

    $settings = App\Models\AdminSettings::first();

	if($response->goal > 0) {
		$percentage = round($response->donations()->where('payment_status','=','paid')->sum('donation') / $response->goal * 100);
	} else {
		$percentage = round($response->donations()->where('payment_status','=','paid')->sum('donation') / 100);
	}

    // if ($percentage > 100) {
    //     $percentage = 100;
    // } else {
    //     $percentage = $percentage;
    // }

    // All Donations
    $donations = $response->donations()->where('payment_status','=','paid')->orderBy('id', 'desc')->paginate(2);

    // Updates
    $updates = $response->updates()->orderBy('id', 'desc')->paginate(1);

    if (str_slug($response->title) == '') {
        $slug_url  = '';
    } else {
        $slug_url  = '/'.str_slug($response->title);
    }

    // Banks
    $banks = \App\Models\Banks::all();

 ?>
 @extends('app')

 @section('title'){{ trans('misc.donate').' - '.$response->title.' - ' }}@endsection

 @section('css')
 <link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
 @endsection

 @section('content')



 <div class="container margin-top-90 margin-bottom-40 padding-top-40">

	 <!-- Col MD -->
	 <div class="login-form-2 col-md-8 margin-bottom-20">

		 <!-- form start -->
		 <form method="POST" action="{{ url('donate',$response->id) }}" enctype="multipart/form-data" id="formDonation">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="_id" value="{{ $response->id }}">

			 <div class="form-group">
				 <label>Jumlah Donasi</label>

				 <div class="input-group has-success">
					 <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>

					 <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="amount" value="{{ old('donation') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
				 </div>

			 </div>

			 <!-- Start -->
			 <div class="form-group">
				 <label>Nama</label>
				 <input type="text"  value="@if( Auth::check() ){{Auth::user()->name}}@endif" name="full_name" class="form-control input-lg" placeholder="{{ trans('misc.first_name_and_last_name') }}">
			 </div><!-- /. End-->

			 <!-- Start -->
			 <div class="form-group">
				 <label>Email</label>
				 <input type="text"  value="@if( Auth::check() ){{Auth::user()->email}}@endif" name="email" class="form-control input-lg" placeholder="{{ trans('auth.email') }}">
			 </div><!-- /. End-->

			 <!-- Start -->
			 <div class="form-group">
				 <label>Pesan</label>
				 <input type="text" value="{{ old('comment') }}" name="comment" class="form-control input-lg" placeholder="{{ trans('misc.leave_comment') }}">
			 </div><!-- /. End-->

       <div class="form-group">
         <label for="payment_gateway">Metode Pembayaran</label>
         @foreach(  $banks as $bank )
          <div class="radio">
            <label><input type="radio" name="payment_gateway" value="{{$bank->id}}">{{ $bank->name }}</label>
          </div>
         @endforeach
           <div class="radio">
              <label><input type="radio" name="payment_gateway" value="Deposit">Saldo</label>
           </div>
           <div class="radio">
              <label><input type="radio" name="payment_gateway" value="Midtrans">Payment Gateway</label>
           </div>
           <div class="radio">
              <label><input type="radio" name="payment_gateway" value="Payment" disabled>Pembayaran Lain</label>
           </div>
       </div>

       <div class="form-group">
         <label>Tipe Donasi</label>
         <div class="radio">
           <label><input type="radio" name="donation_type" value="Routine">Routine</label>
         </div>
         <div class="radio">
           <label><input type="radio" name="donation_type" value="Isidentil">insidentil</label>
         </div>
       </div>

			 <div class="form-group checkbox icheck">
				 <label class="margin-zero">
					 <input class="no-show" name="anonymous" type="checkbox" value="1">
					 <span class="margin-lft5 keep-login-title">{{ trans('misc.anonymous_donation') }}</span>
				 </label>
			 </div>
			 <!-- Alert -->
			 <div class="alert alert-danger display-none" id="errorDonation">
				 <ul class="list-unstyled" id="showErrorsDonation"></ul>
			 </div><!-- Alert -->

			 <div class="box-footer text-center">
				 <hr />
				 <button type="submit" id="buttonDonation" class="btn-padding-custom btn btn-lg btn-main custom-rounded">{{ trans('misc.donate') }}</button>
				 <div class="btn-block text-center margin-top-20">
					 <a href="{{url('campaign',$response->id)}}" class="text-muted">
						 <i class="fa fa-long-arrow-left"></i>	{{trans('auth.back')}}</a>
					 </div>
				 </div><!-- /.box-footer -->

			 </form>

		 </div><!-- /COL MD -->

		 <div class="col-md-4">
       @if($response->categories_id == 21)
         <div class="panel panel-default">
           <div class="panel-body">
               <div class="btn-group btn-block margin-bottom-20 @if( Auth::check() && Auth::user()->id == $response->user()->id ) display-none @endif">
                 <a class="btn btn-main btn-donate btn-lg btn-block custom-rounded" data-toggle="modal" data-target="#myModal">
                   Kalkulator
                 </a>
               </div>
           </div>
         </div>
       @endif

       <!-- Start Panel -->
       <div class="panel panel-default">
				 <div class="panel-body">
					 <img class="img-responsive img-rounded" style="display: inline-block;" src="{{url('public/campaigns/small',$response->small_image)}}" />
				 </div>
			 </div>
			 <div class="panel panel-default">
				 <div class="panel-body">
				 	<a class="subtitle-color-6 text-uppercase ">{{$response->title}}</a>
					 <h3 class="btn-block margin-zero" style="line-height: inherit;">
						 <strong class="font-default">{{$settings->currency_symbol.number_format($response->donations()->where('payment_status','=','paid')->sum('donation'))}}</strong>
						 <small>{{trans('misc.of')}} {{$settings->currency_symbol.number_format($response->goal)}} {{strtolower(trans('misc.goal'))}}</small>
					 </h3>

					 <span class="progress margin-top-10 margin-bottom-10">
						 <span class="percentage" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
					 </span>

					 <small class="btn-block margin-bottom-10 text-muted">
						 {{$percentage }}% {{trans('misc.raised')}} {{trans('misc.by')}} {{number_format($response->donations()->count())}} {{trans_choice('misc.donation_plural',$response->donations()->count())}}
					 </small>
				 </div>
			 </div><!-- End Panel -->

			 <!-- Start Panel -->
			 <div class="panel panel-default">
				 <div class="panel-body">
					 <div class="media none-overflow">

						 <span class="btn-block text-center margin-bottom-10 text-muted"><strong>{{trans('misc.organizer')}}</strong></span>

						 <div class="media-center margin-bottom-5">
							 <img class="img-circle center-block" src="{{url('public/avatar/',$response->user()->avatar)}}" width="60" height="60" >
						 </div>

						 <div class="media-body text-center">

							 <h4 class="media-heading">
								 {{$response->user()->name}}

								 @if( Auth::guest()  || Auth::check() && Auth::user()->id != $response->user()->id )
								 <a href="#" title="{{trans('misc.contact_organizer')}}" data-toggle="modal" data-target="#sendEmail">
									 <i class="fa fa-envelope myicon-right"></i>
								 </a>
								 @endif
							 </h4>

							 <small class="media-heading text-muted btn-block margin-zero">{{trans('misc.created')}} {{ date('M d, Y', strtotime($response->date) ) }}</small>
							 @if( $response->location != '' )
							 <small class="media-heading text-muted btn-block"><i class="fa fa-map-marker myicon-right"></i> {{$response->location}}</small>
							 @endif
						 </div>
					 </div>
				 </div>
			 </div><!-- End Panel -->

			 <div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog">
					 <div class="modal-content">
						 <div class="modal-header headerModal">
							 <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

							 <h4 class="modal-title text-center" id="myModalLabel">
								 {{ trans('misc.contact_organizer') }}
							 </h4>
						 </div><!-- Modal header -->

						 <div class="modal-body listWrap text-center center-block modalForm">

							 <!-- form start -->
							 <form method="POST" class="margin-bottom-15" action="{{ url('contact/organizer') }}" enctype="multipart/form-data" id="formContactOrganizer">
								 <input type="hidden" name="_token" value="{{ csrf_token() }}">
								 <input type="hidden" name="id" value="{{ $response->user()->id }}">

								 <!-- Start -->
								 <div class="form-group">
									 <input type="text"  name="name" class="form-control" placeholder="{{ trans('users.name') }}">
								 </div><!-- /. End-->

								 <!-- Start -->
								 <div class="form-group">
									 <input type="text"  name="email" class="form-control" placeholder="{{ trans('auth.email') }}">
								 </div><!-- /. End-->

								 <!-- Start -->
								 <div class="form-group">
									 <textarea name="message" rows="4" class="form-control" placeholder="{{ trans('misc.message') }}"></textarea>
								 </div><!-- /. End-->

								 <!-- Alert -->
								 <div class="alert alert-danger display-none" id="dangerAlert">
									 <ul class="list-unstyled text-left" id="showErrors"></ul>
								 </div><!-- Alert -->

								 <button type="submit" class="btn btn-lg btn-main custom-rounded" id="buttonFormSubmit">{{ trans('misc.send_message') }}</button>

							 </form>

							 <!-- Alert -->
							 <div class="alert alert-success display-none" id="successAlert">
								 <ul class="list-unstyled" id="showSuccess"></ul>
							 </div><!-- Alert -->


						 </div><!-- Modal body -->
					 </div><!-- Modal content -->
				 </div><!-- Modal dialog -->
			 </div><!-- Modal -->

		 </div><!-- /COL MD -->

     <!-- Modal -->
     <div id="myModal" class="modal fade" role="dialog">
       <div class="modal-dialog">

         <!-- Modal content-->
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">Kalkulator Zakat</h4>
           </div>
           <div class="modal-body">
             <ul class="nav nav-tabs">
               <li class="active"><a data-toggle="tab" href="#fitrah">Zakat Fitrah</a></li>
               <li><a data-toggle="tab" href="#maal">Zakat Maal</a></li>
               <li><a data-toggle="tab" href="#profesi">Zakat Profesi</a></li>
             </ul>

             <div class="tab-content">
               <div id="fitrah" class="tab-pane fade in active">
                 <form>
                   <div class="form-group">
                     <label>Jumlah Anggota Keluarga</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="familyNumber" onkeyup="addZakatFitrah()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Harga Beras</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="ricePrice" value="{{ $settings->harga_beras }}" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Jumlah Zakat Fitrah</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="fitrahTotal" disabled>
                     </div>
                   </div>
                 </form>
               </div>
               <div id="maal" class="tab-pane fade">
                 <form>
                   <div class="form-group">
                     <label>Jumlah Tabungan Per bulan</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="savings" onkeyup="addZakatMal()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Jumlah Keuntungan Investasi Per bulan</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="investation" onkeyup="addZakatMal()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Harga Emas</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="goldPrice" value="{{ $settings->harga_emas }}" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>NISHAB (85 Gram)</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="nishabMal" value="{{ ($settings->harga_emas*85) }}" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Wajib Membayar zakat</label>
                     <div class="input-group has-success">
                       <input type="text" autocomplete="off" class="form-control input-lg" name="isZakatMal" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Jumlah Zakat Mal</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="malTotal" disabled>
                     </div>
                   </div>
                 </form>
               </div>
               <div id="profesi" class="tab-pane fade">
                 <form>
                   <div class="form-group">
                     <label>Jumlah Penghasilan Per bulan</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="payment" onkeyup="addZakatProfesi()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Jumlah Pendapatan Lain Per bulan</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="others" onkeyup="addZakatProfesi()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Pengeluaran (Utang, Kebutuhan Pokok) Per Bulan</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="debt" onkeyup="addZakatProfesi()">
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Harga Beras</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="ricePrice" value="{{ $settings->harga_beras }}" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>NISHAB (520 Kg Beras)</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="nishabProfesi" value="{{ ($settings->harga_beras*520) }}" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Wajib Membayar zakat</label>
                     <div class="input-group has-success">
                       <input type="text" autocomplete="off" class="form-control input-lg" name="isZakatProfesi" disabled>
                     </div>
                   </div>
                   <div class="form-group">
                     <label>Jumlah Zakat Profesi</label>
                     <div class="input-group has-success">
                       <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
                       <input type="text" autocomplete="off" id="onlyNumber" class="form-control input-lg" name="profesiTotal" disabled>
                     </div>
                   </div>
                 </form>
               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
             </div>
           </div>
         </div>

       </div>
     </div>

	 </div><!-- container wrap-ui -->

 <?php /*
 <form id="form_pp" name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post"  style="display:none">
    <input type="hidden" name="cmd" value="_donations">
    <input type="hidden" name="return" value="'.$urlSuccess.'">
    <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
    <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
    <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
    <input type="hidden" name="amount" id="amount" value="'.$this->request->amount.'">
    <input type="hidden" name="custom" value="id='.$this->request->_id.'&fn='.$this->request->full_name.'&cc='.$this->request->country.'&pc='.$this->request->postal_code.'&cm='.$this->request->comment.'">
    <input type="hidden" name="item_name" value="'.trans('misc.donation_for').' '.$response->title.'">
    <input type="hidden" name="business" value="'.$this->settings->paypal_email.'">
    <input type="submit">
</form>
  */
  ?>

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

function tandaPemisahTitik(b){
  var _minus = false;
  if (b<0) _minus = true;
  b = b.toString();
  b=b.replace(".","");
  b=b.replace("-","");
  c = "";
  panjang = b.length;
  j = 0;
  for (i = panjang; i > 0; i--){
    j = j + 1;
    if (((j % 3) == 1) && (j != 1)){
      c = b.substr(i-1,1) + "." + c;
    } else {
      c = b.substr(i-1,1) + c;
    }
  }
  if (_minus) c = "-" + c ;
  return c;
}

function numbersonly(ini, e){
  if (e.keyCode>=49){
    if(e.keyCode<=57){
      a = ini.value.toString().replace(".","");
      b = a.replace(/[^\d]/g,"");
      b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
      ini.value = tandaPemisahTitik(b);
      return false;
    }
    else if(e.keyCode<=105){
      if(e.keyCode>=96){
        //e.keycode = e.keycode - 47;
        a = ini.value.toString().replace(".","");
        b = a.replace(/[^\d]/g,"");
        b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
        ini.value = tandaPemisahTitik(b);
        //alert(e.keycode);
        return false;
      }
      else {return false;}
    }
    else {
      return false; }
    }else if (e.keyCode==48){
      a = ini.value.replace(".","") + String.fromCharCode(e.keyCode);
      b = a.replace(/[^\d]/g,"");
      if (parseFloat(b)!=0){
        ini.value = tandaPemisahTitik(b);
        return false;
      } else {
        return false;
      }
    }else if (e.keyCode==95){
      a = ini.value.replace(".","") + String.fromCharCode(e.keyCode-48);
      b = a.replace(/[^\d]/g,"");
      if (parseFloat(b)!=0){
        ini.value = tandaPemisahTitik(b);
        return false;
      } else {
        return false;
      }
    }else if (e.keyCode==8 || e.keycode==46){
      a = ini.value.replace(".","");
      b = a.replace(/[^\d]/g,"");
      b = b.substr(0,b.length -1);
      if (tandaPemisahTitik(b)!=""){
        ini.value = tandaPemisahTitik(b);
      } else {
        ini.value = "";
      }

      return false;
    } else if (e.keyCode==9){
      return true;
    } else if (e.keyCode==17){
      return true;
    } else {
      //alert (e.keyCode);
      return false;
    }
  }

function addZakatFitrah() {
  var keluarga = document.getElementsByName('familyNumber')[0].value;
  var beras = document.getElementsByName('ricePrice')[0].value;
  var total = 3.5 * keluarga * beras;
  var zakat = document.getElementsByName('fitrahTotal')[0];
  zakat.value = total;
  document.getElementsByName('amount')[0].value = total;
}

function addZakatMal() {
  var tabungan = document.getElementsByName('savings')[0].value;
  var investasi = document.getElementsByName('investation')[0].value;
  var nishab = document.getElementsByName('nishabMal')[0].value;
  var harta = (tabungan * 12) + (investasi * 12);
  if (harta > nishab) {
    var isZakatMal = document.getElementsByName('isZakatMal')[0];
    isZakatMal.value = "Iya";
    var total = 0.025 * harta;
    var zakat = document.getElementsByName('malTotal')[0];
    zakat.value = total;
    document.getElementsByName('amount')[0].value = total;
  } else {
    var isZakatMal = document.getElementsByName('isZakatMal')[0];
    isZakatMal.value = "Tidak";
    var zakat = document.getElementsByName('malTotal')[0];
    zakat.value = 0;
    document.getElementsByName('amount')[0].value = 0;
  }

}

function addZakatProfesi() {
  var payment = document.getElementsByName('payment')[0].value;
  var others = document.getElementsByName('others')[0].value;
  var debt = document.getElementsByName('debt')[0].value;
  var nishab = document.getElementsByName('nishabProfesi')[0].value;
  var harta = (payment * 12) + (others * 12) - (debt * 12);
  if (harta > nishab) {
    var isZakatProfesi = document.getElementsByName('isZakatProfesi')[0];
    isZakatProfesi.value = "Iya";
    var total = 0.025 * harta;
    var zakat = document.getElementsByName('profesiTotal')[0];
    zakat.value = total;
    document.getElementsByName('amount')[0].value = total;
  } else {
    var isZakatMal = document.getElementsByName('isZakatProfesi')[0];
    isZakatMal.value = "Tidak";
    var zakat = document.getElementsByName('profesiTotal')[0];
    zakat.value = 0;
    document.getElementsByName('amount')[0].value = 0;
  }
}

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

    // $('input').iCheck({
	  // 	checkboxClass: 'icheckbox_square-red',
    // 	radioClass: 'iradio_square-red',
	  //   increaseArea: '20%' // optional
	  // });

});
</script>
@endsection
