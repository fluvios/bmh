@extends('admin.layout')


@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h4>

      {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> Broadcast ({{$broadcasts->total()}})

      <a href="{{ route('admin-broadcast-create') }}" class="btn btn-sm btn-success no-shadow pull-right">
        <i class="glyphicon glyphicon-plus myicon-right"></i> {{{ trans('misc.add_new') }}}
      </a>
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

        <div class="box">

          <div class="box-header">

            <h3 class="box-title"> 

              Broadcast                    		

            </h3>

          </div><!-- /.box-header -->


          <div class="box-body table-responsive no-padding">

            <table id="donation" class="table table-hover">


                @if( $broadcasts->total() !=  0 && $broadcasts->count() != 0 )
                <thead>

                <tr>

                  <th class="active">ID</th>

                  <th class="active">Penerima</th>

                  <th class="active">Isi Pesan</th>

                  <!-- <th class="active">{{ trans('admin.actions') }}</th> -->

                </tr><!-- /.TR -->

                </thead>

                <tbody>
                  @foreach( $broadcasts as $broadcast )

                  <tr>

                    <td>{{ $broadcast->id }}</td>

                    <td>{{ $broadcast->reciever }}</td>
                    <td>{{ $broadcast->content }}</td>
                    <!-- <td> 
                      <a href="{{ route('admin-broadcast-detail', $broadcast->id) }}" class="btn btn-success btn-xs padding-btn">
                        {{ trans('admin.detail') }}
                      </a>
                    </td> -->

                  </tr><!-- /.TR -->

                  @endforeach


                @else

                <hr />

                <h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>


                @endif


              </tbody>


            </table>


          </div><!-- /.box-body -->

        </div><!-- /.box -->

        @if( $broadcasts->lastPage() > 1 )

        {{ $broadcasts->links() }}

        @endif

      </div>

    </div>        	


    <!-- Your Page Content Here -->


  </section><!-- /.content -->

</div><!-- /.content-wrapper -->

@endsection
