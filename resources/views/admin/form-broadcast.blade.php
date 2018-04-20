@extends('admin.layout')


@section('css')

<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" type="text/css">

@endsection


@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h4>

      {{ trans('admin.admin') }} 

      <i class="fa fa-angle-right margin-separator"></i> 

      {{ trans('misc.broadcast') }}

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
                <label class="col-sm-2 control-label">Penerima</label>
                <div class="col-sm-5">
                <select name="reciever[]" class="form-control" id="reciever-select2"></select>
                </div>
              </div>
            </div><!-- /.box-body -->

            <!-- Start Box Body -->

            <div class="box-body">

              <div class="form-group">

                <label class="col-sm-2 control-label">Judul Pesan</label>

                <div class="col-sm-10">

                  <input type="text" value="{{  old('title') }}" name="title" class="form-control" placeholder="Judul Pesan">

                </div>

              </div>

            </div><!-- /.box-body -->            

            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Content</label>
                <div class="col-sm-10">
                <textarea name="content" rows="4" id="content" class="form-control tinymce-txt" placeholder="Content">{{ old('content') }}</textarea>
                </div>
              </div>
            </div><!-- /.box-body -->  

            <div class="box-footer">

              <a href="{{ route('admin-broadcast-index') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>

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
<script src="{{ asset('public/plugins/tinymce/tinymce.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/js/select2.full.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
//Flat red color scheme for iCheck

function initTinymce() {
  tinymce.remove('.tinymce-txt');
  tinymce.init({
    selector: '.tinymce-txt',
    relative_urls: false,
    resize: true,
    menubar:false,
    statusbar: false,
    forced_root_block : false,
    extended_valid_elements : "span[!class]",
    //visualblocks_default_state: true,
    setup: function(editor){

      editor.on('change',function(){
        editor.save();
      });
    },
    theme: 'modern',
    height: 150,
    plugins: [
      'advlist autolink autoresize lists link image charmap preview hr anchor pagebreak', //image
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save code contextmenu directionality', //
      'emoticons template paste textcolor colorpicker textpattern ' //imagetools
    ],
    toolbar1: 'undo redo | bold italic underline strikethrough charmap | bullist numlist  | link | image | media',
    image_advtab: true,
  });

}

initTinymce();

$('input[type="radio"]').iCheck({

  radioClass: 'iradio_flat-red'

});

$('#reciever-select2').select2({
  multiple: true,
  ajax: {
    url: '{{ url('api/user') }}',
    dataType : 'json',
    delay : 220,
    data : function(params){
        return {
            q : params.term,
            page : params.page
        };
    },
    processResults : function(data, params){
        params.page = params.page || 1;
        return {
            results : data.data,
            pagination: {
                more : (data.per_page  * 10) < data.total
            }
        };
    }
  }
});

</script>

@endsection

