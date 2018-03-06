@extends('app')

@section('title') {{ $title }} @endsection

@section('content') 


<div class="container margin-bottom-40 margin-top-100">
	  <h2 class="subtitle-color-5 text-uppercase ">{{ $response->title }}</h2>
	<div class="row"></div>
<!-- Col MD -->
<div class="col-md-12">	
	<ol class="breadcrumb bg-none pull-right">
          	<li><a href="{{ URL::to('/') }}"><i class="glyphicon glyphicon-home myicon-right"></i></a></li>
          	<li class="active">{{ $response->title }}</li>
          </ol>
	<hr />
     	</br>
     <dl>
     	<dd>
     		<?php echo html_entity_decode($response->content) ?>
     	</dd>
     </dl>	
 </div><!-- /COL MD -->
 
 </div><!-- row -->
 
 <!-- container wrap-ui -->
@endsection

