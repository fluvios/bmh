<?php
$settings = App\Models\AdminSettings::first();
$cities = App\Models\Kabupaten::all();
$cabangs = App\Models\Cabang::all();
$provinces = App\Models\Provinsi::all();
$tags = App\Models\Categories::where('is_funding_type', 'no')->get();
?>
@extends('app')

@section('title'){{ trans('misc.create_campaign').' - ' }}@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('public/plugins/datepicker/datepicker3.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
  <div class="container wrap-jumbotron position-relative">
    <h2 class="title-site">{{ trans('misc.create_campaign') }}</h2>
  </div>
</div>

<div class="container margin-bottom-40 padding-top-40">
  <div class="row">

    <!-- col-md-8 -->
    <div class="col-md-12">
      <div class="wrap-center center-block">
        @include('errors.errors-forms')

        @if( Auth::user()->status == 'active' )
        <!-- form start -->
        <form method="POST" action="{{ url('create/campaign') }}" enctype="multipart/form-data" id="formUpload">

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="filer-input-dragDrop position-relative" id="draggable">
            <input type="file" accept="image/*" name="photo" id="filePhoto">

            <!-- previewPhoto -->
            <div class="previewPhoto">

              <div class="btn btn-danger btn-sm btn-remove-photo" id="removePhoto">
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

          <!-- Start Form Group -->
          <div class="form-group">
            <label>Judul Campaign</label>
            <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control" placeholder="{{ trans('misc.campaign_title') }}">
          </div><!-- /.form-group-->

          <!-- Start Form Group -->
          <div class="form-group">
            <label>Jenis Dana</label>
            <select name="categories_id" class="form-control">
              <option value="0">{{trans('misc.select_one')}}</option>
              @foreach(  App\Models\Categories::where('mode','on')->where('is_funding_type','yes')->orderBy('name')->get() as $category )
              <option value="{{$category->id}}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div><!-- /.form-group-->

          <div class="form-group">
            <label>Kategori Campaign</label>
            <select name="kategori[]" class="form-control" id="kategori-select2"></select>
          </div>

          <div class="form-group">
            <label>Kebutuhan Dana</label>
            <div class="input-group">
              <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
              <input type="text" class="form-control text-right" name="goal" id="onlyNumber" value="{{ old('goal') }}" placeholder="Kebutuhan Dana" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
            </div>
          </div>

          <div class="form-group">
            <label for=""> {{ trans('misc.from_cabang') }} </label>
            <select class="form-control select2" id="cabang-id" name="cabang_id">
                @foreach($cabangs as $cabang)
                  <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                @endforeach            
            </select>
          </div>

          <!-- Start Form Group -->
          <div class="form-group">
            <label>Lokasi Campaign</label>
            <select name="location" class="form-control input-lg select2" id="location">
              @foreach($cities as $city)
                <option value="{{ $city->id_kab }}">{{ $city->nama }}</option>
              @endforeach
            </select>
          </div><!-- /.form-group-->

          <!-- Start Form Group -->
          <div class="form-group">
            <label>Provinsi Campaign</label>
            <select name="province" class="form-control input-lg select2" id="province">
              @foreach($provinces as $province)
                <option value="{{ $province->id_prov }}">{{ $province->nama }}</option>
              @endforeach
            </select>
          </div><!-- /.form-group-->          

          <!-- Start Form Group -->
          <div class="form-group">
            <label for="deadline">Deadline Campaign</label>
            <div class="radio">
              <label><input type="radio" name="deadline" value="7">7 Hari</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="deadline" value="30">30 Hari</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="deadline" value="90">90 Hari</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="deadline" value="180">3 Bulan</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="deadline" value="365">1 Tahun</label>
            </div>
            <span class="help-block">{{ trans('misc.deadline_desc') }}</span>
          </div><!-- /.form-group-->


          <div class="form-group">
            <label>Deskripsi Program</label>
            <textarea name="description" rows="4" id="description" class="form-control tinymce-txt" placeholder="{{ trans('misc.campaign_description_placeholder') }}">{{ old('description') }}</textarea>
          </div>

          <!-- Start Form Group -->
          <div class="form-group">
            <label>Persentase Bonus Affiliasi</label>
            <input type="text" value="{{ old('affiliation_bonus') }}" name="affiliation_bonus" id="affiliation_bonus" class="form-control" placeholder="Percentage (0-100)">
          </div><!-- /.form-group-->

          <!-- Alert -->
          <div class="alert alert-danger display-none" id="dangerAlert">
            <ul class="list-unstyled" id="showErrors"></ul>
          </div><!-- Alert -->

          <div class="box-footer">
            <hr />
            <button type="submit" id="buttonFormSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('misc.create_campaign') }}</button>
          </div><!-- /.box-footer -->
        </form>

        @else

        <div class="btn-block text-center margin-top-40">
          <i class="icon-warning ico-no-result"></i>
        </div>

        <h3 class="margin-top-none text-center no-result no-result-mg">
          {{trans('misc.confirm_email')}} <strong>{{Auth::user()->email}}</strong>
        </h3>

        @endif

      </div><!-- wrap-center -->
    </div><!-- col-md-12-->

  </div><!-- row -->
</div><!-- container -->

@endsection

@section('javascript')
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/plugins/tinymce/tinymce.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/plugins/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/js/select2.full.min.js') }}" type="text/javascript"></script>

<script>
	var categories = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  prefetch: {
		url: '{{ url('ajax/tags') }}',
		filter: function(list) {
  		return $.map(list, function(name) {
  			return { name: name }; });
  		}
	  }
	});

	categories.initialize();

  function tandaPemisahTitik(b){
    var _minus = false;
    if (b<0) _minus = true;
    b = b.toString();
    b=b.replace(".","");
    b=b.replace("-","");
    c = "";
    panjang = b.length;
    j = 0;
    for (i = panjang; i > 0; i--){
      j = j + 1;
      if (((j % 3) == 1) && (j != 1)){
        c = b.substr(i-1,1) + "." + c;
      } else {
        c = b.substr(i-1,1) + c;
      }
    }
    if (_minus) c = "-" + c ;
    return c;
  }

  function numbersonly(ini, e){
    if (e.keyCode>=49){
      if(e.keyCode<=57){
        a = ini.value.toString().replace(".","");
        b = a.replace(/[^\d]/g,"");
        b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
        ini.value = tandaPemisahTitik(b);
        return false;
      }
      else if(e.keyCode<=105){
        if(e.keyCode>=96){
          //e.keycode = e.keycode - 47;
          a = ini.value.toString().replace(".","");
          b = a.replace(/[^\d]/g,"");
          b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
          ini.value = tandaPemisahTitik(b);
          //alert(e.keycode);
          return false;
        }
        else {return false;}
      }
      else {
        return false; }
      }else if (e.keyCode==48){
        a = ini.value.replace(".","") + String.fromCharCode(e.keyCode);
        b = a.replace(/[^\d]/g,"");
        if (parseFloat(b)!=0){
          ini.value = tandaPemisahTitik(b);
          return false;
        } else {
          return false;
        }
      }else if (e.keyCode==95){
        a = ini.value.replace(".","") + String.fromCharCode(e.keyCode-48);
        b = a.replace(/[^\d]/g,"");
        if (parseFloat(b)!=0){
          ini.value = tandaPemisahTitik(b);
          return false;
        } else {
          return false;
        }
      }else if (e.keyCode==8 || e.keycode==46){
        a = ini.value.replace(".","");
        b = a.replace(/[^\d]/g,"");
        b = b.substr(0,b.length -1);
        if (tandaPemisahTitik(b)!=""){
          ini.value = tandaPemisahTitik(b);
        } else {
          ini.value = "";
        }

        return false;
      } else if (e.keyCode==9){
        return true;
      } else if (e.keyCode==17){
        return true;
      } else {
        //alert (e.keyCode);
        return false;
      }
    }

	$('#tags-input').tagsinput({
	  typeahead: {
		name: 'tags',
		displayKey: 'name',
		valueKey: 'name',
		source: categories.ttAdapter()
	  }
	});

  $('.bootstrap-tagsinput').addClass('form-control');
</script>

<script type="text/javascript">

// money
// var cleave = new Cleave('.input-money', {
//     numeral: true,
//     numeralThousandsGroupStyle: 'thousand'
// });



//Date picker
$('#datepicker').datepicker({
  autoclose: true,
  format: 'dd-mm-yyyy',
  startDate: '+7d',
  language: 'en'
});

$(document).ready(function() {

  $("#onlyNumber").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
    // Allow: Ctrl+A, Command+A
    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
    // Allow: home, end, left, right, down, up
    (e.keyCode >= 35 && e.keyCode <= 40)) {
      // let it happen, don't do anything
      return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
    }
  });

  // $('input').iCheck({
  //   checkboxClass: 'icheckbox_square-red',
  //   radioClass: 'iradio_square-red',
  //   increaseArea: '20%' // optional
  // });

});

//Flat red color scheme for iCheck
// $('input[type="radio"]').iCheck({
//   radioClass: 'iradio_flat-red'
// });

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
      <?php $dimensions = explode('x', $settings->min_width_height_image); ?>

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

// $('#cabang-id').select2({
//   ajax: {
//     url: '{{ url('api/cabang') }}',
//     dataType : 'json',
//     delay : 220,
//     data : function(params){
//         return {
//             q : params.term,
//             page : params.page
//         };
//     },
//     processResults : function(data, params){
//         params.page = params.page || 1;
//         return {
//             results : data.data,
//             pagination: {
//                 more : (data.per_page  * 10) < data.total
//             }
//         };
//     }
//   }
// });

$('#kategori-select2').select2({
  multiple: true,
  ajax: {
    url: '{{ url('api/kategori') }}',
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

$('#cabang-id').select2();
$('#location').select2();
</script>
@endsection
