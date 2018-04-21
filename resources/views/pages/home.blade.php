@extends('app')

@section('title') {{ $title }} @endsection

@section('content') 


<div class="container margin-bottom-40 margin-top-80">
     <h2 class="subtitle-color-7 margin-bottom-5  text-uppercase"><a>{{ $response->title }}</a></h2>
	<div class="row"></div>
<!-- Col MD -->
<div class="col-md-12" >	
	<ol class="breadcrumb bg-none pull-right">
          	<li><a href="{{ URL::to('/') }}"><i class="glyphicon glyphicon-home myicon-right"></i></a></li>
          	<li class="active">{{ $response->title }}</li>
          </ol>
	<hr />
     	</br>
     <dl>
     	<dd class="login-form-2">
     		<?php echo html_entity_decode($response->content) ?>
     	</dd>
     </dl>	
 </div><!-- /COL MD -->
 
 </div><!-- row -->
 
 <!-- container wrap-ui -->
@endsection

