@extends('admin.layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h4>
      {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.donations') }} ({{$data->total()}})
    </h4>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
              {{ trans('misc.donations') }}
            </h3>

          </div><!-- /.box-header -->

          <div class="box-body table-responsive no-padding">
            <table id="donation" class="table table-hover">
              <thead>
                <tr>
                  <th class="active">No</th>
                  <th class="active">{{ trans('auth.full_name') }}</th>
                  <th class="active">{{ trans_choice('misc.campaigns_plural', 1) }}</th>
                  <th class="active">{{ trans('auth.email') }}</th>
                  <th class="active">{{ trans('misc.donation') }}</th>
                  <th class="active">{{ trans('admin.date') }}</th>
                  <th class="active">Bank</th>
                  <th class="active">Status</th>
                  <th class="active">{{ trans('admin.actions') }}</th>
                </tr><!-- /.TR -->
              </thead>
              <tbody>
                @if( $data->total() !=  0 && $data->count() != 0 )
                  @php
                    $i = 1;
                  @endphp
                  @foreach($data as $donation)
                    <tr>
                      <td>{{ $i }}</td>
                      <td>{{ $donation->fullname }}</td>
                      <td><a href="{{url('campaign',$donation->campaigns_id)}}" target="_blank">{{ str_limit($donation->campaigns()->title, 10, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ $donation->email }}</td>
                      <td>{{ $settings->currency_symbol.number_format($donation->donation) }}</td>
                      <td>{{ date('d M Y', strtotime($donation->payment_date)) }}</td>
                      @if(!empty($donation->bank))
                        <td>{{ $donation->bank->slug }}</td>
                      @else
                        <td> - </td>
                      @endif
                      @if($donation->payment_status == 'paid')
                      <td>Sudah Dibayarkan</td>
                      @elseif($donation->payment_status == 'denied')
                      <td>Donasi Ditolak</td>
                      @else
                      <td>Menunggu Konfirmasi</td>
                      @endif
                      <td>
                        <a href="{{ url('panel/admin/donations',$donation->id) }}" class="btn btn-default btn-xs padding-btn">
                          {{ trans('admin.view') }}
                        </a>
                        <a href="{{ url('panel/admin/donations/reject').'/'.$donation->id }}" class="btn btn-danger btn-xs padding-btn">Tolak</a>
                        <a href="{{ url('panel/admin/donations/accept').'/'.$donation->id }}" class="btn btn-success btn-xs padding-btn">Terima</a>
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