<?php 
// ** Data User logged ** //
     $user = Auth::user();
	  ?>
@extends('app')

@section('title') {{ trans('users.account_settings') }} - @endsection

@section('content') 


<div class="container margin-top-90 margin-bottom-40">
	
			<!-- Col MD -->
		<div class="login-form-2 col-md-8 margin-bottom-20">
	<h2 class="subtitle-color-7 text-uppercase margin-bottom-20">Informasi <a> Kontak</a></h2>
			@if (session('notification'))
			<div class="alert alert-success btn-sm alert-fonts" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ session('notification') }}
            		</div>
            	@endif
            	
			@include('errors.errors-forms')
			
			
		
		<!-- *********** AVATAR ************* -->
		
		<form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
    		
    		<div class="text-center">
    			<img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" alt="User" width="100" height="100" class="img-rounded avatarUser"  />
    		</div>
    		
    		<div class="text-center">
    			<button type="button" class="btn btn-default btn-border btn-sm" id="avatar_file" style="margin-top: 10px;">
	    		<i class="icon-camera myicon-right"></i> {{ trans('misc.change_avatar') }}
	    		</button>
	    		<input type="file" name="photo" id="uploadAvatar" accept="image/*" style="visibility: hidden;">
    		</div>
			
			</form><!-- *********** AVATAR ************* -->
		

			
		<!-- ***** FORM ***** -->
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#personal">Data Personal</a></li>
			<li><a data-toggle="tab" href="#address">Alamat</a></li>
		</ul>
			
		<div class="tab-content">
			<div id="personal" class="tab-pane fade in active">
				<form action="{{ url('account') }}" method="post" name="form">
					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">{{ trans('misc.full_name_misc') }}</label>
						<input type="text" class="form-control login-field custom-rounded" value="{{ e( $user->name ) }}" name="full_name" placeholder="{{ trans('misc.full_name_misc') }}" title="{{ trans('misc.full_name_misc') }}" autocomplete="off">
						</div><!-- ***** Form Group ***** -->
						
						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">{{ trans('users.phone1') }}</label>
						<input type="text" class="form-control login-field custom-rounded" value="{{$user->phone_number_1}}" name="phone_number_1" placeholder="{{ trans('users.phone1') }}" title="{{ trans('users.phone1') }}" autocomplete="off">
					</div><!-- ***** Form Group ***** -->
					
					<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">{{ trans('users.phone2') }}</label>
						<input type="text" class="form-control login-field custom-rounded" value="{{$user->phone_number_2}}" name="phone_number_2" placeholder="{{ trans('users.phone2') }}" title="{{ trans('users.phone2') }}" autocomplete="off">
					</div><!-- ***** Form Group ***** -->
					

					<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">{{ trans('auth.email') }}</label>
						<input type="email" class="form-control login-field custom-rounded" value="{{$user->email}}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
					</div><!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">{{ trans('misc.country') }}</label>
							<select name="countries_id" class="form-control" >
										<option value="">{{trans('misc.select_your_country')}}</option>
									@foreach(  App\Models\Countries::orderBy('country_name')->get() as $country ) 	
										<option @if( $user->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
									@endforeach
									</select>
								</div><!-- ***** Form Group ***** -->
					
					<button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('misc.save_changes') }}</button>	
				</form>
			</div>
			<div id="address" class="tab-pane fade">
				<form action="{{ url('address/home') }}" method="post" name="form">

						<H4>Alamat Pribadi</H4>
					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Alamat Rumah</label>
						<textarea type="text" class="form-control login-field custom-rounded" value="" name="fullhomeaddress">
						</textarea>
						</div><!-- ***** Form Group ***** -->
						
						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Telepon</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homephone">
						</div>
						<!-- ***** Form Group ***** -->

						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Kode Pos</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homepostalcode">
						</div>
						<!-- ***** Form Group ***** -->

						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Provinsi</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homeprovince">
						</div>
						<!-- ***** Form Group ***** -->

						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Kabupaten</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homestate">
						</div>
						<!-- ***** Form Group ***** -->

						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Kecamatan</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homeregion">
						</div>
						<!-- ***** Form Group ***** -->

						<!-- ***** Form Group ***** -->
						<div class="form-group has-feedback">
							<label class="font-default">Kelurahan</label>
							<input type="text" class="form-control login-field custom-rounded" value="" name="homevillage">
						</div>
						<!-- ***** Form Group ***** -->

					<button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('misc.save_changes') }}</button>	
				</form>

				<form action="{{ url('address/company') }}" method="post" name="form">

					<H4>Alamat Kantor</H4>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Alamat</label>
					<textarea type="text" class="form-control login-field custom-rounded" value="" name="fullcompanyaddress">
					</textarea>
					</div><!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Telepon</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companyphone">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Ext.</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companyext">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Kode Pos</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companypostalcode">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Provinsi</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companyprovince">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Kabupaten</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companystate">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Kecamatan</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companyregion">
					</div>
					<!-- ***** Form Group ***** -->

					<!-- ***** Form Group ***** -->
					<div class="form-group has-feedback">
						<label class="font-default">Kelurahan</label>
						<input type="text" class="form-control login-field custom-rounded" value="" name="companyvillage">
					</div>
					<!-- ***** Form Group ***** -->

					<button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('misc.save_changes') }}</button>	
				</form>
			</div>
		</div>
	   <!-- ***** END FORM ***** -->
			
		</div><!-- /COL MD -->
		
		<div class="col-md-4">
			@include('users.navbar-edit')
		</div>
		

 </div><!-- container -->
 
 <!-- container wrap-ui -->
@endsection

@section('javascript')

<script type="text/javascript">

	//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
    $(document).on('change', '#uploadAvatar', function(){
    
    $('.wrap-loader').show();
    
   (function(){
	 $("#formAvatar").ajaxForm({
	 dataType : 'json',	
	 success:  function(e){
	 if( e ){
        if( e.success == false ){
		$('.wrap-loader').hide();
		
		var error = '';
                        for($key in e.errors){
                        	error += '' + e.errors[$key] + '';
                        }
		swal({   
    			title: "{{ trans('misc.error_oops') }}",   
    			text: ""+ error +"",   
    			type: "error",   
    			confirmButtonText: "{{ trans('users.ok') }}" 
    			});
		
			$('#uploadAvatar').val('');

		} else {
			
			$('#uploadAvatar').val('');
			$('.avatarUser').attr('src',e.avatar);
			$('.wrap-loader').hide();
		}
		
		}//<-- e
			else {
				$('.wrap-loader').hide();
				swal({   
    			title: "{{ trans('misc.error_oops') }}",   
    			text: '{{trans("misc.error")}}',   
    			type: "error",   
    			confirmButtonText: "{{ trans('users.ok') }}" 
    			});
    			
				$('#uploadAvatar').val('');
			}
		   }//<----- SUCCESS
		}).submit();
    })(); //<--- FUNCTION %
});//<<<<<<<--- * ON * --->>>>>>>>>>>
//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
</script>
@endsection
