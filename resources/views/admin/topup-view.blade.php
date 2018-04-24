@extends('admin.layout')

@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h4>

      {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> Top Up #{{$data->id}}

    </h4>

  </section>


  <!-- Main content -->

  <section class="content">


    <div class="row">

      <div class="col-xs-6">

        <div class="box">


          <div class="box-body">

            <dl class="dl-horizontal">


              <!-- start -->

              <dt>ID</dt>

              <dd>{{$data->id}}</dd>

              <!-- ./end -->


              <!-- start -->

              <dt>{{ trans('auth.full_name') }}</dt>

              <dd>{{$data->fullname}}</dd>

              <!-- ./end -->

              <!-- start -->

              <dt>{{ trans('auth.email') }}</dt>

              <dd>{{$data->email}}</dd>

              <!-- ./end -->


              <!-- start -->

              <dt>Nominal Top Up</dt>

              <dd><strong class="text-success">{{$settings->currency_symbol.number_format($data->amount)}}</strong></dd>

              <!-- ./end -->

              <!-- start -->

              <dt>{{ trans('misc.payment_gateway') }}</dt>

              <dd>{{$data->payment_gateway}}</dd>

              <!-- ./end -->


              <!-- start -->

              <dt>{{ trans('misc.comment') }}</dt>

              <dd>

                @if( $data->comment != '' )

                {{$data->comment}}

                @else

                -------------------------------------

                @endif

              </dd>

              <!-- ./end -->


              <!-- start -->

              <dt>{{ trans('admin.date') }}</dt>

              <dd>{{date('d M, y', strtotime($data->transfer_date))}}</dd>

              <!-- ./end -->

              <!-- start -->
              <dt>Status Pembayaran</dt>
              <dd>
                @if($data->payment_status == 'pending')
                  Belum dibayarkan
                @elseif($data->payment_status == 'paid')
                  Sudah dibayarkan
                @elseif($data->payment_status == 'expired')
                  Kadaluarsa
                @else
                  Pembayaran ditolak
                @endif
              </dd>
              <!-- ./end -->

            </dl>

          </div><!-- box body -->


          <div class="box-footer">

            <a href="{{ url('panel/admin/top_up') }}" class="btn btn-default">{{ trans('auth.back') }}</a>

          </div><!-- /.box-footer -->


        </div><!-- box -->

      </div><!-- col -->


      @if(!is_null($data->logs) && !is_null($data->banks))
      <div class="col-xs-6">
        <div class="box">
          <div class="box-body">
            <dl class="dl-horizontal">
              <!-- start -->
              <dt>Bukti Pembayaran</dt>
              <dd><img src="{{ asset('public/topup').'/'.$data->logs->transfer_evidance}}" height="200" width="300"></dd>
              <!-- ./end -->

              <!-- start -->
              <dt>Nama Bank</dt>
              <dd>{{$data->banks->name}}</dd>
              <!-- ./end -->

              <!-- start -->
              <dt>Tanggal Transaksi</dt>
              <dd>{{$data->logs->transfer_date}}</dd>
              <!-- ./end -->
            </dl>
          </div><!-- box body -->

          <div class="box-footer">
              @if($data->payment_status == 'pending')
              <a href="{{ url('panel/admin/top_up/status/reject').'/'.$data->id }}" class="btn btn-danger">Tolak</a>
              <a href="{{ url('panel/admin/top_up/status/accept').'/'.$data->id }}" class="btn btn-success">Terima</a>
              @elseif($data->payment_status == 'paid')
              Sudah Dibayar
              @elseif($data->payment_status == 'denied')
              Kadaluarsa
              @endif
          </div><!-- /.box-footer -->

        </div><!-- box -->

      </div><!-- col -->
      @endif
    </div><!-- row -->


    <!-- Your Page Content Here -->


  </section><!-- /.content -->

</div><!-- /.content-wrapper -->

@endsection

