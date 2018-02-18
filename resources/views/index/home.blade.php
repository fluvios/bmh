<?php
$settings = App\Models\AdminSettings::first();
$total_raised_funds = App\Models\Donations::where('payment_status', '=', 'paid')->sum('donation');
$total_campaigns    = App\Models\Campaigns::where('status','active')->count();
$total_members      = App\Models\User::count();
?>
@extends('app')

@section('content')
<!-- <div id="myCarousel" data-ride="carousel" class="carousel slidejumbotron index-header jumbotron_set jumbotron-cover @if( Auth::check() ) session-active-cover @endif"> -->
<div id="myCarousel" data-ride="carousel" class="carousel slidejumbotron index-header jumbotron_set jumbotron-cover">
  <div class="wrap-jumbotron position-relative">
    <!-- <h1 class="title-site txt-left" id="titleSite">{{$settings->welcome_text}}</h1>
    <p class="subtitle-site txt-left"><strong>{{$settings->welcome_subtitle}}</strong></p> -->
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <img src="https://www.sec.gov/files/crowdfunding-v5b-2016.jpg" alt="Los Angeles" style="width:100%; height:350px;">
      </div>

      <div class="item">
        <img src="http://d3gtswiihfkcji.cloudfront.net/uploads/2015/08/05104938/Crowdfunding-concept.jpg" alt="Chicago" style="width:100%;height:350px;">
      </div>

      <div class="item">
        <img src="https://hipgive.org/wp-content/uploads/2015/08/what-is-crowdfunding-copy.jpg" alt="Chicago" style="width:100%;height:350px;">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

@if( $data->total() != 0 )
<div class="container margin-bottom-40">
  <div class="col-md-12 btn-block margin-bottom-40">
    <h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow">{{trans('misc.campaigns')}}</h1>
    <h5 class="btn-block text-center class-montserrat subtitle-color">{{trans('misc.recent_campaigns')}}</h5>
  </div>

  <div class="margin-bottom-30">
    @include('includes.campaigns')
  </div>

  <div class="row margin-bottom-40">

    <div class="container">
      <div class="col-md-4 border-stats">
        <h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow"><span class=".numbers-with-commas counter"><?php echo html_entity_decode( App\Helper::formatNumbersStats($total_members) ) ?></span></h1>
        <h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.members')}}</h5>
      </div><!-- col-md-3 -->

      <div class="col-md-4 border-stats">
        <h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow"><span class=".numbers-with-commas counter"><?php echo html_entity_decode( App\Helper::formatNumbersStats($total_campaigns) ) ?></span></h1>
        <h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.campaigns')}}</h5>
      </div><!-- col-md-3 -->

      <div class="col-md-4 border-stats">
        <h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow">{{ $settings->currency_symbol }}<?php echo html_entity_decode( App\Helper::formatNumbersStats($total_raised_funds) ) ?></h1>
        <h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.funds_raised')}}</h5>
      </div><!-- col-md-3 -->

    </div><!-- row -->
  </div>

</div><!-- container wrap-ui -->

@else
<div class="container margin-bottom-40">
  <div class="margin-bottom-30">
    <div class="btn-block text-center margin-top-40">
      <i class="ion ion-speakerphone ico-no-result"></i>
    </div>

    <h3 class="margin-top-none text-center no-result no-result-mg">
      {{ trans('misc.no_campaigns') }}
    </h3>
  </div>
</div>
@endif

@if( $categories->count() != 0 )
<div class="container margin-bottom-40">

  <div class="col-md-12 btn-block margin-bottom-40 head-home">
    <h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow">{{trans('misc.categories')}}</h1>
    <h5 class="btn-block text-center class-montserrat subtitle-color">
      <a href="{{ url('categories') }}">
        <strong>{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i></strong>
      </a>
    </h5>
  </div>

  @foreach(App\Models\Categories::where('mode','on')->where('slug','!=','public')->orderBy('name')->take(4)->get() as $category )

  @include('includes.categories-listing')

  @endforeach


</div><!-- container -->
@endif

<div class="jumbotron jumbotron-bottom margin-bottom-zero jumbotron-cover">
  <div class="container wrap-jumbotron position-relative">
    <h1 class="title-site">{{trans('misc.title_cover_bottom')}}</h1>
    <p class="subtitle-site txt-center"><strong>{{$settings->welcome_subtitle}}</strong></p>

  </div><!-- container wrap-jumbotron -->
</div><!-- jumbotron -->
@endsection

@section('javascript')
<script src="{{ asset('public/plugins/jquery.counterup/waypoints.min.js') }}"></script>
<script src="{{ asset('public/plugins/jquery.counterup/jquery.counterup.min.js') }}"></script>

<script type="text/javascript">

$(document).on('click','#campaigns .loadPaginator', function(r){
  r.preventDefault();
  $(this).remove();
  $('.loadMoreSpin').remove();
  $('<div class="col-xs-12 loadMoreSpin"><a class="list-group-item text-center"><i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></a></div>').appendTo( "#campaigns" );

  var page = $(this).attr('href').split('page=')[1];
  $.ajax({
    url: '{{ url("ajax/campaigns") }}?page=' + page
  }).done(function(data){
    if( data ) {
      $('.loadMoreSpin').remove();

      $( data ).appendTo( "#campaigns" );
    } else {
      bootbox.alert( "{{trans('misc.error')}}" );
    }
    //<**** - Tooltip
  });
});

jQuery(document).ready(function( $ ) {
  $('.counter').counterUp({
    delay: 10, // the delay time in ms
    time: 1000 // the speed time in ms
  });
});

@if (session('success_verify'))
swal({
  title: "{{ trans('misc.welcome') }}",
  text: "{{ trans('users.account_validated') }}",
  type: "success",
  confirmButtonText: "{{ trans('users.ok') }}"
});
@endif

@if (session('error_verify'))
swal({
  title: "{{ trans('misc.error_oops') }}",
  text: "{{ trans('users.code_not_valid') }}",
  type: "error",
  confirmButtonText: "{{ trans('users.ok') }}"
});
@endif

</script>

@endsection
