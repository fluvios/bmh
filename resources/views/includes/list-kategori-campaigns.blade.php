<div class="col-xs-12 col-sm-6 col-md-3 col-thumb">
  <?php

  $settings = App\Models\AdminSettings::first();

  if( str_slug( $key->title ) == '' ) {
    $slugUrl  = '';
  } else {
    $slugUrl  = '/'.str_slug( $key->title );
  }

  $url = url('campaign',$key->campaign_id).$slugUrl;

  if ($key->goal > 0) {
    $percentage = round($key->donations()->where('payment_status', '=', 'paid')->sum('donation') / $key->goal * 100);
  } else {
    $percentage = round($key->donations()->where('payment_status', '=', 'paid')->sum('donation') / 100);
  }

  // if( $percentage > 2000 ) {
  //   $percentage = 2000;
  // } else {
  //   $percentage = $percentage;
  // }
  ?>
  <div class="thumbnail padding-top-zero">

    <a class="position-relative btn-block img-grid" href="{{$url}}">

      @if( $key->featured == 1 )
      <span class="box-featured" title="{{trans('misc.featured_campaign')}}"><i class="fa fa-trophy"></i></span>
      @endif

      <img title="{{ e($key->title) }}" src="{{ asset('public/campaigns/small').'/'.$key->small_image }}" class="image-url img-responsive btn-block radius-image" />
    </a>

    <div class="caption">
       <p class="desc-campaigns">
        @if( isset($key->user()->id) )
        <img src="{{ asset('public/avatar').'/'.$key->user()->avatar }}" width="50" height="50" class="img-circle avatar-campaign" /> {{ $key->user()->name}}
        @else
        <img src="{{ asset('public/avatar/default.jpg') }}" width="50" height="50" class="img-circle avatar-campaign" /> {{ trans('misc.user_not_available') }}
        @endif
      </p>
      <h1 class="title-campaigns btn-block class-montserrat text-uppercase">
        <a title="{{ e($key->title) }}" class="item-link" href="{{$url}}">
          {{ e($key->title) }}
        </a>
        </h1>
        @if(isset($key->kabupaten))
          <h5 class="btn-block  subtitle-color text-uppercase>:"> WILAYAH: {{ $key->kabupaten->nama }}</h5>
        @endif

      <p class="desc-campaigns">
        <span class="stats-campaigns">

          <span class="pull-left">
          {{trans('misc.raised')}}</br>
            <strong>{{$settings->currency_symbol.number_format($key->donations()->where('payment_status','=','paid')->sum('donation'))}}</strong>

          </span>

          <span class="pull-right"></br><strong>{{$percentage }}%</strong></span>
        </span>

        <span class="progress">
          <span class="percentage" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="200" role="progressbar"></span>
        </span>
      </p>

      <h6 class="margin-bottom-zero">
        <em><strong>{{ trans('misc.goal') }} {{$settings->currency_symbol.number_format($key->goal)}}</strong></em>

      </h6>

    </div><!-- /caption -->
  </div><!-- /thumbnail -->
</div><!-- /col-sm-6 col-md-4 -->
