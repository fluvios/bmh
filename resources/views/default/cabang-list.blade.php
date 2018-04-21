@extends('app')

@section('title'){{ 'Cabang - ' }}@endsection

@section('content') 
<div class="container margin-top-80 margin-bottom-40">
<!-- Col MD -->
<div class="col-md-12 margin-top-20 margin-bottom-20">	

	<h2 class="subtitle-color-7 margin-bottom-20 text-center text-uppercase">CABANG <A>CAMPAIGN</A></h2>
</div>
</div>
<div class="container margin-bottom-40">
	
	    		@foreach ($data as $category)
	        				@include('includes.cabang-listing')
	        			@endforeach

 </div><!-- container wrap-ui -->
@endsection

