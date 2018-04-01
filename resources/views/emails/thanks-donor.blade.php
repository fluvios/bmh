<!DOCTYPE html>

<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">

        /* Media Queries */

        @media  only screen and (max-width: 500px) {

            .button {

                width: 100% !important;

            }

        }

    </style>

	@include('includes.css_general')
</head>

<body style="margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;">

    <table width="100%" cellpadding="0" cellspacing="0">

        <tr>

            <td style="width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;" align="center">

                <table width="100%" cellpadding="0" cellspacing="0">

                    <!-- Logo -->

                    <tr>

                        <td style="padding: 25px 0; text-align: center;">

                            <a style="font-family: Arial, &#039;Helvetica Neue&#039;, Helvetica, sans-serif; font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;" href="{{url('/')}}" target="_blank">

                                {{$title_site}}

                            </a>

                        </td>

                    </tr>



                    <!-- Email Body -->

                    <tr>

                        <td style="width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;" width="100%">

                            <table style="width: auto; max-width: 570px; margin: 0 auto; padding: 0;" align="center" width="570" cellpadding="0" cellspacing="0">

                                <tr>

                                    <td style="font-family: Arial, &#039;Helvetica Neue&#039;, Helvetica, sans-serif; padding: 35px;">

                                        <!-- Greeting -->

                                        <h1 style="margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;">

                                                                                                                                                Thank you for your donation! {{$fullname}}

                                                                                                                                    </h1>



                                        <!-- Intro -->

                                                                                    <p style="margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;">

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
               <img class="margin-top-20" src="{{ asset('public/bank/large/'. $bank->logo) }}" height="85%" width="85%">
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
       </div>

       <!-- Alert -->
       <div class="alert alert-danger display-none" id="errorDonation">
         <ul class="list-unstyled" id="showErrorsDonation"></ul>
       </div><!-- Alert -->

       <div class="box-footer text-center">
        <div class="col-md-4">
  </div>
         <hr />
         
         </div><!-- /.box-footer -->

       </form>

                                            </p>

                                                                                                                            

                                        

                                        <!-- Salutation -->

                                        <p style="margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;">

                                            Regards,<br>{{$title_site}}

                                        </p>





                                                                            </td>

                                </tr>

                            </table>

                        </td>

                    </tr>



                    <!-- Footer -->

                    <tr>

                        <td>

                            <table style="width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;" align="center" width="570" cellpadding="0" cellspacing="0">

                                <tr>

                                    <td style="font-family: Arial, &#039;Helvetica Neue&#039;, Helvetica, sans-serif; color: #AEAEAE; padding: 35px; text-align: center;">

                                        <p style="margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;">

                                            &copy; 2016

                                            {{$title_site}}

                                            All rights reserved.

                                        </p>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>

<script>
		@include('includes.javascript_general')
</script>
</html>