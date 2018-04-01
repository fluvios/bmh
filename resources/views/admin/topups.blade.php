@extends('admin.layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h4>
      {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> Top Up ({{$data->total()}})
    </h4>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
              Top Up
            </h3>

          </div><!-- /.box-header -->



          <div class="box-body table-responsive no-padding">

            <table id="donation" class="table table-hover">

              <thead>
                <tr>

                  <th class="active">No</th>
                  <th class="active">{{ trans('auth.full_name') }}</th>
                  <th class="active">{{ trans('auth.email') }}</th>
                  <th class="active">Nominal Top Up</th>
                  <th class="active">{{ trans('admin.date') }}</th>
                  <th class="active">Metode Pembayaran</th>
                  <th class="active">Status</th>
                  <th class="active">{{ trans('admin.actions') }}</th>

                </tr><!-- /.TR -->
              </thead>

              <tbody>

                @if( $data->total() !=  0 && $data->count() != 0 )

                @php
                $i = 1;
                @endphp
                @foreach( $data as $topup )

                <tr>

                  <td>{{ $i }}</td>

                  <td>{{ $topup->fullname }}</td>

                  <td>{{ $topup->email }}</td>

                  <td class="text-right">{{ $settings->currency_symbol.number_format($topup->amount) }}</td>

                  <td>{{ date('d M Y', strtotime($topup->transfer_date)) }}</td>

                  <td>{{ $topup->getPaymentMethod() }}</td>

                  <td>{{ $topup->payment_status }}</td>

                  <td>
                    <a href="{{ url('panel/admin/top_up',$topup->id) }}" class="btn btn-default btn-xs padding-btn">
                      {{ trans('admin.view') }}
                    </a>
                    <a href="{{ url('panel/admin/top_up/status/reject').'/'.$topup->id }}" class="btn btn-danger btn-xs padding-btn">
                      Tolak
                    </a>
                    <a href="{{ url('panel/admin/top_up/status/accept').'/'.$topup->id }}" class="btn btn-success btn-xs padding-btn">
                      Terima
                    </a>
                  </td>
                </tr><!-- /.TR -->
                @php
                $i++;
                @endphp
                @endforeach



                @else

                <hr />

                <h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>



                @endif



              </tbody>



            </table>



          </div><!-- /.box-body -->

        </div><!-- /.box -->

        @if( $data->lastPage() > 1 )

        {{ $data->links() }}

        @endif

      </div>

    </div>



    <!-- Your Page Content Here -->



  </section><!-- /.content -->

</div><!-- /.content-wrapper -->

@endsection
