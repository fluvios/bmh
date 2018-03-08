@extends('admin.layout')


@section('css')

<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />

@endsection


@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h4>

      {{ trans('admin.admin') }} 

      <i class="fa fa-angle-right margin-separator"></i> 

      Kategori

      <i class="fa fa-angle-right margin-separator"></i> 

      {{ $title }}

    </h4>


  </section>


  <!-- Main content -->

  <section class="content">


    <div class="content">


      <div class="row">


        <div class="box box-danger">

          <div class="box-header with-border">

            <h3 class="box-title">{{ $title }}</h3>

          </div><!-- /.box-header -->


          <!-- form start -->

          <form class="form-horizontal" method="post" action="{{ $action }}" enctype="multipart/form-data">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">	


            @include('errors.errors-forms')

            <!-- Start Box Body -->

            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Nama kategori</label>
                <div class="col-sm-10">
                  <input type="text" value="{{ $kategori->nama?:old('nama') }}" name="nama" class="form-control" placeholder="Nama Kategori">
                </div>
              </div>
            </div><!-- /.box-body -->

            <!-- Start Box Body -->

            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Slug</label>
                <div class="col-sm-10">
                  <input type="text" value="{{ $kategori->slug?:old('slug') }}" name="slug" class="form-control" placeholder="Slug (Tanpa Spasi)">
                </div>
              </div>
            </div><!-- /.box-body -->            

            <!-- Start Box Body -->

            <div class="box-body">

              <div class="form-group">

                <label class="col-sm-2 control-label">{{ trans('admin.status') }}</label>

                <div class="col-sm-10">

                  

                  <div class="radio">

                  <label class="padding-zero">

                    <input type="radio" name="is_active" value="1" @if( $kategori->is_active == '1' ) checked @endif>

                    {{ trans('admin.active') }}

                  </label>

                </div>

                

                <div class="radio">

                  <label class="padding-zero">

                    <input type="radio" name="is_active" value="0" @if( $kategori->is_active == '0' ) checked @endif>

                    {{ trans('admin.disabled') }}

                  </label>

                </div>

                

                </div>

              </div>

            </div><!-- /.box-body -->


            <div class="box-footer">

              <a href="{{ route('admin-kategori-index') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>

              <button type="submit" class="btn btn-success pull-right">{{ trans('admin.save') }}</button>

            </div><!-- /.box-footer -->

          </form>

        </div>


      </div><!-- /.row -->


    </div><!-- /.content -->


    <!-- Your Page Content Here -->


  </section><!-- /.content -->

</div><!-- /.content-wrapper -->

@endsection


@section('javascript')


<!-- icheck -->

<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>


<script type="text/javascript">

//Flat red color scheme for iCheck

$('input[type="radio"]').iCheck({

  radioClass: 'iradio_flat-red'

});

</script>



@endsection

