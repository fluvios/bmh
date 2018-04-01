@extends('admin.layout')


@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/main.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h4>

      {{ trans('admin.admin') }} 

      <i class="fa fa-angle-right margin-separator"></i> 

      Bank
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

          <div class="filer-input-dragDrop position-relative @if($bank->logo != '') hoverClass @endif" id="draggable">
            <input type="file" accept="image/*" name="image" id="filePhoto">

            <!-- previewPhoto -->
            <div class="previewPhoto" @if($bank->logo != '')style='display: block; background-image: url("{{asset('public/bank/large/'.$bank->logo)}}");' @endif>

              <div class="btn btn-danger btn-sm btn-remove-photo" id="removePhoto" data-id="{{$bank->id}}">
                <i class="fa fa-trash myicon-right"></i> {{trans('misc.delete')}}
              </div>

            </div><!-- previewPhoto -->

            <div class="filer-input-inner">
              <div class="filer-input-icon">
                <i class="fa fa-cloud-upload"></i>
              </div>
              <div class="filer-input-text">
                <h3 class="margin-bottom-10">{{ trans('misc.click_select_image') }}</h3>
                <h3>{{ trans('misc.max_size') }}: {{App\Helper::formatBytes($settings->file_size_allowed * 1024) .' - '.$settings->min_width_height_image}} </h3>
              </div>
            </div>
          </div>            

            <!-- Start Box Body -->

            <div class="box-body">

              <div class="form-group">

                <label class="col-sm-2 control-label">{{ trans('admin.name') }}</label>

                <div class="col-sm-10">

                  <input type="text" value="{{  $bank->name?:old('name') }}" name="name" class="form-control" placeholder="{{ trans('admin.name') }}">

                </div>

              </div>

            </div><!-- /.box-body -->


            <!-- Start Box Body -->

            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">No Rekening</label>
                <div class="col-sm-10">
                  <input type="text" value="{{ $bank->account_number?:old('account_number') }}" name="account_number" class="form-control" placeholder="No Rekening">
                </div>
              </div>
            </div><!-- /.box-body -->


            <div class="box-footer">

              <a href="{{ url('panel/admin/settings/bank') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>

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

  $('#removePhoto').click(function(){
    $('#filePhoto').val('');
    $('.previewPhoto').css({backgroundImage: 'none'}).hide();
    $('.filer-input-dragDrop').removeClass('hoverClass');
  });

  //================== START FILE IMAGE FILE READER
  $("#filePhoto").on('change', function(){

    var loaded = false;
    if(window.File && window.FileReader && window.FileList && window.Blob){
      if($(this).val()){ //check empty input filed
        oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
        if($(this)[0].files.length === 0){return}


        var oFile = $(this)[0].files[0];
        var fsize = $(this)[0].files[0].size; //get file size
        var ftype = $(this)[0].files[0].type; // get file type


        if(!rFilter.test(oFile.type)) {
          $('#filePhoto').val('');
          $('.popout').addClass('popout-error').html("{{ trans('misc.formats_available') }}").fadeIn(500).delay(5000).fadeOut();
          return false;
        }

        var allowed_file_size = {{$settings->file_size_allowed * 1024}};

        if(fsize>allowed_file_size){
          $('#filePhoto').val('');
          $('.popout').addClass('popout-error').html("{{trans('misc.max_size').': '.App\Helper::formatBytes($settings->file_size_allowed * 1024)}}").fadeIn(500).delay(5000).fadeOut();
          return false;
        }
        <?php $dimensions = explode('x',$settings->min_width_height_image); ?>

        oFReader.onload = function (e) {

          var image = new Image();
          image.src = oFReader.result;

          image.onload = function() {

            if( image.width < {{ $dimensions[0] }}) {
              $('#filePhoto').val('');
              $('.popout').addClass('popout-error').html("{{trans('misc.width_min',['data' => $dimensions[0]])}}").fadeIn(500).delay(5000).fadeOut();
              return false;
            }

            if( image.height < {{ $dimensions[1] }} ) {
              $('#filePhoto').val('');
              $('.popout').addClass('popout-error').html("{{trans('misc.height_min',['data' => $dimensions[1]])}}").fadeIn(500).delay(5000).fadeOut();
              return false;
            }

            $('.previewPhoto').css({backgroundImage: 'url('+e.target.result+')'}).show();
            $('.filer-input-dragDrop').addClass('hoverClass');
            var _filname =  oFile.name;
            var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
          };// <<--- image.onload


        }

        oFReader.readAsDataURL($(this)[0].files[0]);

      }
    } else{
      $('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(5000).fadeOut();
      return false;
    }
  });

  $('input[type="file"]').attr('title', window.URL ? ' ' : '');

</script>



@endsection

