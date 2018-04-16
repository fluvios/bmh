@extends('admin.layout')


@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">
    <h4>
      {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> Bonus Affiliator
    </h4>

  </section>


  <!-- Main content -->

  <section class="content">

    @if(Session::has('success_message'))

    <div class="alert alert-success">

      <button type="button" class="close" data-dismiss="alert" aria-label="Close">

        <span aria-hidden="true">×</span>

      </button>

      <i class="fa fa-check margin-separator"></i>  {{ Session::get('success_message') }}	        

    </div>

    @endif


    <div class="row">

      <div class="col-xs-12">

        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home">Bonus Donasi</a></li>
          <li><a data-toggle="tab" href="#menu1">Bonus Registrasi</a></li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
            <div class="box">

              <div class="box-header">

                <h3 class="box-title"> 

                  Bonus Donasi                   		

                </h3>

              </div><!-- /.box-header -->

              <div class="box-body table-responsive no-padding">
                @php
                  $i= 1;
                @endphp
                <table id="donation" class="table table-hover">
                    @if( $donasi->total() !=  0 && $donasi->count() != 0 )
                    <thead>

                    <tr>

                      <th class="active">No</th>

                      <th class="active">Email</th>

                      <th class="active">Total</th>

                      <th class="active">{{ trans('admin.actions') }}</th>

                    </tr><!-- /.TR -->

                    </thead>

                    <tbody>
                      @foreach( $donasi as $d )

                      <tr>

                        <td>{{ $i }}</td>

                        <td>{{ $d->email }}</td>
                        <td>{{ $d->bonus }}</td>
                        <td> 
                          <a href="{{ route('admin-bonus-accept-donasi', $d->email) }}" class="btn btn-success btn-xs padding-btn">
                            Set as Send
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
          </div>
          <div id="menu1" class="tab-pane fade">
            <div class="box">

              <div class="box-header">

                <h3 class="box-title"> 

                  Bonus Registrasi                   		

                </h3>

              </div><!-- /.box-header -->

              <div class="box-body table-responsive no-padding">
                @php
                  $i= 1;
                @endphp
                <table id="donation" class="table table-hover">
                    @if( $registrasi->total() !=  0 && $registrasi->count() != 0 )
                    <thead>

                    <tr>

                      <th class="active">No</th>

                      <th class="active">Email</th>

                      <th class="active">Total</th>

                      <th class="active">{{ trans('admin.actions') }}</th>

                    </tr><!-- /.TR -->

                    </thead>

                    <tbody>
                      @foreach( $registrasi as $r )

                      <tr>

                        <td>{{ $i }}</td>

                        <td>{{ $r->email }}</td>
                        <td>{{ $r->bonus }}</td>
                        <td> 
                          <a href="{{ route('admin-bonus-accept-registrasi', $r->email) }}" class="btn btn-success btn-xs padding-btn">
                            Set as Send
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
          </div>
        </div>

      </div>

    </div>        	


    <!-- Your Page Content Here -->


  </section><!-- /.content -->

</div><!-- /.content-wrapper -->

@endsection
