@extends('app')

@section('title'){{ 'Cabang - ' }}@endsection

@section('content') 
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h2 class="title-site">Branch</h2>
        <p class="subtitle-site"><strong>Browse By Branch</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">
	
	    		@foreach ($data as $category)
	        				@include('includes.cabang-listing')
	        			@endforeach

 </div><!-- container wrap-ui -->
@endsection

