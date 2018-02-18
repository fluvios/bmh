@extends('admin.layout')

@section('css')
  <link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ asset('public/plugins/datepicker/datepicker3.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h4>
      {{ trans('admin.admin') }}
      <i class="fa fa-angle-right margin-separator"></i>
      {{ trans('admin.amil') }}
      <i class="fa fa-angle-right margin-separator"></i>
      {{ trans('misc.add_new') }}
    </h4>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('misc.add_new') }}</h3>
            </div><!-- /.box-header -->

            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ url('panel/admin/amils/add') }}" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              @include('errors.errors-forms')

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.name') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('name') }}" name="name" class="form-control" placeholder="{{ trans('admin.name') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.branch') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('branch') }}" name="branch" class="form-control" placeholder="{{ trans('admin.branch') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.gender') }}</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="gender">
                      <option value="male">Laki-Laki</option>
                      <option value="female">Perempuan</option>
                    </select>
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.height') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('height') }}" name="height" class="form-control" placeholder="{{ trans('admin.height') }} (Cm)">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.weight') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('weight') }}" name="weight" class="form-control" placeholder="{{ trans('admin.weight') }} (Kg)">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.birthplace') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('birthplace') }}" name="birthplace" class="form-control" placeholder="{{ trans('admin.birthplace') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.birthdate') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('birthdate') }}" name="birthdate" id="birthdate" class="form-control" placeholder="{{ trans('admin.birthdate') }} (Hari-Bulan-Tahun)">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.address') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('address') }}" name="address" class="form-control" placeholder="{{ trans('admin.address') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.postalcode') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('postalcode') }}" name="postalcode" class="form-control" placeholder="{{ trans('admin.postalcode') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.province') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('provinsi') }}" name="provinsi" class="form-control" placeholder="{{ trans('admin.province') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.city') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('kota') }}" name="kota" class="form-control" placeholder="{{ trans('admin.city') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.region') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('kecamatan') }}" name="kecamatan" class="form-control" placeholder="{{ trans('admin.region') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.state') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('kelurahan') }}" name="kelurahan" class="form-control" placeholder="{{ trans('admin.state') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.phone') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control" placeholder="{{ trans('admin.phone') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.amil_stats') }}</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="amil_type">
                      <option value="tetap">Tetap</option>
                      <option value="tidak_tetap">Tidak Tetap</option>
                      <option value="relawan">Relawan</option>
                    </select>
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.home_stats') }}</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="home_type">
                      <option value="sendiri">Milik Sendiri</option>
                      <option value="sewa">Sewa</option>
                      <option value="keluarga">Keluarga</option>
                    </select>
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('auth.email') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ old('email') }}" name="email" class="form-control" placeholder="{{ trans('auth.email') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('admin.role') }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="amil" disabled name="role" class="form-control">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('auth.password') }}</label>
                  <div class="col-sm-10">
                    <input type="password" value="" name="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <!-- Start Box Body -->
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ trans('auth.confirm_password') }}</label>
                  <div class="col-sm-10">
                    <input type="password" value="" name="password_confirmation" class="form-control" placeholder="{{ trans('auth.confirm_password') }}">
                  </div>
                </div>
              </div><!-- /.box-body -->

              <div class="box-footer">
                <a href="{{ url('panel/admin/amils') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>
                <button type="submit" class="btn btn-success pull-right">{{ trans('admin.save') }}</button>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div><!-- /. col-md-9 -->
      </div><!-- /.row -->
    </div><!-- /.content -->
    <!-- Your Page Content Here -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection

@section('javascript')
<!-- icheck -->
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/plugins/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>

<script type="text/javascript">

$('#birthdate').datepicker({
  autoclose: true,
  format: 'dd-mm-yyyy',
  language: 'en'
});

$(".actionDelete").click(function(e) {
  e.preventDefault();
  var element = $(this);
  var id     = element.attr('data-url');
  var form    = $(element).parents('form');

  element.blur();

  swal(
    {   title: "{{trans('misc.delete_confirm')}}",
    text: "{{trans('admin.delete_user_confirm')}}",
    type: "warning",
    showLoaderOnConfirm: true,
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "{{trans('misc.yes_confirm')}}",
    cancelButtonText: "{{trans('misc.cancel_confirm')}}",
    closeOnConfirm: false,
  },

  function(isConfirm){
    if (isConfirm) {
      form.submit();
      //$('#form' + id).submit();
    }
  });
});

//Flat red color scheme for iCheck
$('input[type="radio"]').iCheck({
  radioClass: 'iradio_flat-red'
});
</script>
@endsection
