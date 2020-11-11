<!-- 
this  file is for a video upload project
it checks for a user video and details of the video
Javascript code is in the last of the file
It checks for the video details to be added
it verify for a video and all the details to be correct
If there is some error it will return user and display error
 -->

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<style type="text/css">
	.nav-tabs { border-bottom: 2px solid #DDD; }
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
	.nav-tabs > li > a { border: none; color: #ffffff;background: #437c9c; }
	.nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none;  color: #437c9c !important; background: #fff; }
	.nav-tabs > li > a::after { content: ""; background: #6262bb; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }  
	.nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
	.tab-nav > li > a::after { background: ##6262bb none repeat scroll 0% 0%; color: #fff; }
	.tab-pane { padding: 15px 0; }
	.tab-content{padding:20px}
	.nav-tabs > li  {width:20%; text-align:center;}
	.card {background: #FFF none repeat scroll 0% 0%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3); margin-bottom: 30px; }

	@media all and (max-width:724px){
		.nav-tabs > li > a > span {display:unset !important;}   
		.nav-tabs > li > a {padding: 5px 5px;}
	}


	.side-nav .side-nav-link {
		font-size: 1.29rem;
	}


	.box1{
		border: 2px solid #8080dc;
		border-radius: 7px;
		text-align: center;
		height: 37px;
	}
	.txtStyle, .txtStyle:hover, .txtStyle:hover:after{
		float: left;
		padding-left: 12px;
		text-decoration: none;
		content: "";
	}
	.txtStyle12, .txtStyle12:hover{
		float: right;
		padding-right: 13px;
		text-decoration: none;
		content: "";
	}

.select2-container--classic .select2-selection--multiple .select2-selection__choice {
	color: black;
}
body {
	background: #FBFAF5;
}

.button {
	display: block;
	width: 150px;
	height: 45px;
	line-height: 45px;
	margin: 125px auto 0;
	border-radius: 3px;
	text-align: center;
	text-decoration: none;
	background: #A38CDC;
	color: #fff;
}
.textDe:hover, textDe:hover:after{
	text-decoration: none;
}
</style>
<style>
progress {
  border-radius: 2px; 
  width: 100%;
  height: 22px;
  /* margin-left:-11.5%; */
}
progress::-webkit-progress-bar {
 background-color: #0091EA;
  border-radius: 2px;
}
progress::-webkit-progress-value {
   border-radius: 2px; 
}
progress::-moz-progress-bar {
  /* style rules */
}

div#body1{
    border: solid 1px;
    text-align: center;
    padding: 9px;
}
a#upload {
    width: 200px;
}

<style>
body{width:610px;}
#uploadForm {border-top:#F0F0F0 2px solid;background:#FAF8F8;padding:10px;}
#uploadForm label {margin:2px; font-size:1em; font-weight:bold;}
.demoInputBox{padding:5px; border:#F0F0F0 1px solid; border-radius:4px; background-color:#FFF;}
#progress-bar {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
.btnSubmit{background-color:#09f;border:0;padding:10px 40px;color:#FFF;border:#F0F0F0 1px solid; border-radius:4px;}
#progress-div {border:#0FA015 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
#targetLayer{width:100%;text-align:center;}

</style>


</style>
<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>



<div class="row">
	<div class="container">
		<div class="row">
			<div class="col-md-12"> 
				<!-- Nav tabs -->
				<div class="card">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active" id="home12" class="" onclick="liActiveHome();"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-home"></i>  <span>Catalog Listing</span></a></li>
						<li role="presentation" onclick="liActiveProfile();" id="profile12"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-user"></i>  <span>Landing Page</span></a></li>
						<!-- <li role="presentation" onclick="liActiveMessage();" id="message12"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i>  <span>Video Assets</span></a></li> -->
					</ul>


		<form method="post" action="" id="moviePublisherUpload" onsubmit="return false" enctype="multipart/form-data">

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="home">
							<!-- <form role="form" action="" method="post" class="registration-form"> -->
						<input type="hidden" name="movie_id" value="<?= (!empty($movie_id) && $movie_id != '') ? $movie_id : ''; ?>">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="form-group">
												<label>Title</label>
												<label class="sr-only" for="form-movie-title">Movie Title</label>
												<input type="text" name="movietitle" placeholder="Movie Title..." class="form-movie-title form-control" id="movietitle" value="<?= (!empty($movie_title) && $movie_title != '') ? $movie_title : ''; ?>">
											</div>
										</div>	
									</div>	
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
											<div class="form-group">
												<label for="description_long">Synopsis</label>
												<textarea class="form-control" id="description_long" name="description_long" rows="6" spellcheck="false"></textarea>
											</div>
										</div>	
									</div>	
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
											<div class="form-group">
												<label for="description_short">Log Line</label>
												<textarea class="form-control" id="description_short" name="description_short" rows="6" spellcheck="false"></textarea>
											</div>
										</div>	
									</div>	
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
										<label for="genre_id">Genre</label>
										<span class="help">- genre must be selected</span>
										<select class="form-control js-example-responsive" id="genre_id" name="genre_id">
											<!-- <option value="-1">Select</option> -->
											<?php
												$genres =   $this->crud_model->get_genres();
													foreach ($genres as $row2):?>
													<option value="<?php echo $row2['genre_id'];?>">
													<?php echo $row2['name'];?>
												</option>
											<?php endforeach;?>
										</select>
									</div>
									</div>	
								</div>
								<br>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
										<label for="rating">Rating</label>
				                        <span class="help">- star rating of the movie</span>
					                        <select class="form-control js-example-responsive" id="rating" name="rating">
					                            <?php for ($i = 0; $i <= 5 ; $i++):?>
					                                <option value="<?php echo $i;?>">
					                                    <?php echo $i;?>
					                                </option>
					                            <?php endfor;?>
					                        </select>
									</div>
									</div>	
								</div>
								<br>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
										<label for="year">Publishing Year</label>
                        					<span class="help">- year of publishing time</span>
					                        <select class="form-control js-example-responsive" id="year" name="year">
					                            <?php for ($i = date("Y"); $i > 2000 ; $i--):?>
					                                <option value="<?php echo $i;?>">
					                                    <?php echo $i;?>
					                                </option>
					                            <?php endfor;?>
					                        </select>
									</div>
									</div>	
									
								</div>
<input type="hidden" name="year_diff" id="year_diff">
								<br>
								<div class="row" id="price_field">
									<div class="col-md-12">
										<div class="col-md-8">
										<label for="year">Enter Price For new Movie</label>
                        					<span class="help">$8 -- $12</span>
					                        <input type="number" value="" name="price" id="price" class="form-control">
									</div>
									</div>	
									
								</div>

								<br>
	
								<div class="row" id="rent_price_field">
									<div class="col-md-12">
	
										<div class="col-md-8">
										<label for="year">Enter Rent Price For new Movie(if Applicable)</label>
                        					<span class="help">$8 -- $12</span>
					                        <input type="number" value="" name="rent_price" id="rent_price" class="form-control">
									</div>
									</div>	
									
								</div>


								<br>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-8">
											<label for="image">Graphic Assets</label>
				                        	<span class="help">- The Image should be 1920*1080 (16:9 ratio) .jpg or .png file</span>
				                        </div>
									</div>	
								</div>
								<div class="row" style="margin-top: 10px;">
									<div class="col-md-12">
										<div class="col-md-6">
											<label>Thumbnail </label>
				                        	<span class="help">- icon image of the movie</span>
											<input type="file" id="movie_thumb" class="form-control" name="movie_thumb">
				                        </div>
				                       	<div class="col-md-6">
											<label>Poster </label>
				                        	<span class="help">- large banner image of the movie</span>
											<input type="file" class="form-control" name="movie_poster" id="movie_poster">
				                        </div>
									</div>	
								</div>

							<!-- Footer -->
							<footer class="page-footer font-small blue">
								<div class="col-md-1 col-md-offset-11 py-3">
									<div class="nav nav-tabs" role="tablist">
										<div role="presentation" class="btn btn-warning" onclick="liActiveProfile();" id="profile12"><a href="#profile" aria-controls="profile" style="text-decoration: none;color: white" role="tab" data-toggle="tab"><i class="fa fa-user"></i>  <span>Next</span></a></div>
									</div>
								</div>
							  <!-- Copyright -->
							  <div class="footer-copyright text-center py-3">© 2020 Copyright:
							    <a href="#"> HMI Film Studio</a>
							  </div>
							  <!-- Copyright -->

							</footer>
						</div>
						<div role="tabpanel" class="tab-pane" id="profile">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-md-2">
											<label >Select Actor</label>
										</div>							
										<div class="col-md-5">
											<select class="js-example-responsive form-control select2" multiple="multiple" id="actors" name="actors[]">
												
											</select>
										</div>
										<div class="col-md-1">
											<button type="button" onclick="AddActor()" class="btn btn-info btn-md"><span class="glyphicon glyphicon-plus"></span></button>
										</div>
									</div>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-md-2">
											<label >Select Director</label>
										</div>							
										<div class="col-md-5">
											<select class="js-example-responsive form-control select2" multiple="multiple" id="directors" name="directors[]">
											</select>
										</div>
										<div class="col-md-1">
											<button type="button" onclick="AddDirector()" class="btn btn-info btn-md"><span class="glyphicon glyphicon-plus"></span></button>
										</div>
									</div>
								</div>
							</div><br>
							<div id="body1" class="mb-3">
								<div id="filelist-tl"></div>
								<!-- trailer upload -->

								<div id="container-tl">
									<div class="form-group">
										<a id="" name="" href="javascript:;" style="text-decoration: none;">Click Here To Upload Trailer</a>
									</div>

									<progress max="100" value="0" id="p-bar-tl">
									</progress>
									<div class="form-group">
										<a id="uploadFile-tl" name="uploadFile_tl" href="javascript:;" class="btn btn-danger">Upload file</a>
									</div>
								</div>

								<!-- Trailer Upload -->
								
								<div id="filelist"></div>


								<!-- <div id="container">
									<div class="form-group">
										<a id="" name="" href="javascript:;" style="text-decoration: none;">Click Here To Upload Movie</a>
									</div>

									<progress max="100" value="0" id="p-bar"></progress>
									<div class="form-group">
										<a id="uploadFile" name="uploadFile" href="javascript:;" class="btn btn-danger">Upload file</a>
									</div>
								</div> -->


								

							</form>
							<form id="uploadForm" action="localhost/hmifilm/index.php?PublishMovies/uploadtoserver" method="post">
							<div>

							<label>Upload Image File:</label>

						File: <input type="file" name="fup" id="fup" /><br>
								Duration: <input type="text" name="f_du" id="f_du" size="5" /> seconds<br>
								<input type="submit" value="Upload" />
							</form>
							<audio id="audio"></audio>

								</div>
								<div>
									</div>
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>



								<input type="hidden" id="file_ext" name="file_ext" value="<?=substr( md5( rand(10,100) ) , 0 ,10 )?>">
								<div id="console"></div>

								<div id="status"></div>
							</div>
                    			<input type="submit" id="create_movie" class="btn btn-warning col-md-3 col-md-offset-9" value="Publish Movie">
							
						</div>
							<div role="tabpanel" class="tab-pane" id="messages">
								<div class="row" style="margin:20px 60px;">
									<h4>mldmlvkdfmvklmlkfvm</h4>
								</div>
							</div>
						</div>
					</form>

					</div>
				</div>
			</div>
		</div>
	</div>
<div id="ActorPopUp" data-backdrop="" class="modal" role="dialog" style="">
	<form method="POST" action="javascript:void(0)" id="AddActors">
		<div class="modal-dialog modal-xl" style="width:80%">
		<!-- Modal content-->
		<div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">Create actor</h4>
	        <button type="button" class="close" data-dismiss="modal" onclick="closePopUp()">&times;</button>
	      </div>
			<div class="modal-body" style="">
							<div class="form-group mb-3">

			                    <label for="name">Actor Name</label>

								<span class="help">e.g. "Leonardo di Caprio"</span>

			                    <input type="text" class="form-control" id="name" name="name" required>

			                </div>
							<div class="form-group mb-3">

			                    <label for="name">Image</label>

			                    <input type="file" class="form-control" name="thumb" required>

			                </div>
			</div>

			<div class="modal-footer">
				<button id="cnclOrderBtn" type="button" class="btn btn-danger" onclick="closePopUp();" data-dismiss="modal">Cancel</button>
				<input type="submit" id="actrbtn" class="btn btn-success" style="width:70px" value="Save">

<!-- <button type="button" id="addOrderBtn" class="btn btn-success" style="width:70px">Save</button> -->
			</div>

		</div>
	</div>
</form>
</div>
<div id="DirPopUp" data-backdrop="" class="modal" role="dialog" style="">
	<form method="POST" action="javascript:void(0)" id="DirectorAdd">
		<div class="modal-dialog modal-xl" style="width:80%">
		<!-- Modal content-->
		<div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">Create Director/Producer</h4>
	        <button type="button" class="close" data-dismiss="modal" onclick="closePopUp()">&times;</button>
	      </div>
			<div class="modal-body" style="">
							<div class="form-group mb-3">

			                    <label for="name">Director Name</label>

								<span class="help">e.g. "Leonardo di Caprio"</span>

			                    <input type="text" class="form-control" id="name" name="name" required>

			                </div>
							<div class="form-group mb-3">

			                    <label for="name">Image</label>

			                    <input type="file" class="form-control" name="thumb" required>

			                </div>
			</div>

			<div class="modal-footer">
				<button id="cnclOrderBtn" type="button" class="btn btn-danger" onclick="closePopUp();" data-dismiss="modal">Cancel</button>
				<input type="submit" id="dirbtn" class="btn btn-success" style="width:70px" value="Save">

<!-- <button type="button" id="addOrderBtn" class="btn btn-success" style="width:70px">Save</button> -->
			</div>

		</div>
	</div>
</form>
</div>

<!-- <script src="http://malsup.github.com/jquery.form.js"></script>  -->


<script type="text/javascript">

$(document).ready(function() { 
	 $('#uploadForm').submit(function(e) {	
		if($('#fup').val()) {
			e.preventDefault();
			$('#loader-icon').show();
			$(this).ajaxSubmit({ 
				target:   '#targetLayer', 
				beforeSubmit: function() {
				  $("#progress-bar").width('0%');
				},
				uploadProgress: function (event, position, total, percentComplete){	
					$("#progress-bar").width(percentComplete + '%');
					$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
				},
				success:function (){
					$('#loader-icon').hide();
				},
				resetForm: true 
			}); 
			return false; 
		}
	});
});

// Code to get duration of audio /video file before upload - 
  
  //register canplaythrough event to #audio element to can get duration
  var f_duration =0;  //store duration
  document.getElementById('audio').addEventListener('canplaythrough', function(e){
    //add duration in the input field #f_du
    f_duration = Math.round(e.currentTarget.duration);
    document.getElementById('f_du').value = f_duration;
    URL.revokeObjectURL(obUrl);
  });
  
  //when select a file, create an ObjectURL with the file and add it in the #audio element
  var obUrl;
  document.getElementById('fup').addEventListener('change', function(e){
    var file = e.currentTarget.files[0];
    //check file extension for audio/video type
    if(file.name.match(/\.(avi|mp3|mp4|mpeg|ogg)$/i)){
      obUrl = URL.createObjectURL(file);
      document.getElementById('audio').setAttribute('src', obUrl);
    }
  });



$("#year").on('change',function(){
	// alert();
 var currentYear =	new Date().getFullYear()
	var movieYear =  $('#year').val();
	if(currentYear - movieYear > 1)
	{
		$('#price').val('');
		$('#price_field').css("display","none");
		$('#rent_price').val('');
		$('#rent_price_field').css("display","none");
		$('#year_diff').val(currentYear - movieYear);
	}
	else{
		$('#price_field').css("display","block");
		$('#rent_price_field').css("display","block");

	}

});

function closePopUp() {
	$("#DirPopUp").hide();
	$("#ActorPopUp").hide();
}
function AddActor(){
	$("#ActorPopUp").show();
	$("#AddActors")[0].reset();
}
function AddDirector(){
	$("#DirPopUp").show();
	$("#DirectorAdd")[0].reset();
}
function showActor(){
	$.ajax({
		url:'<?= base_url() ?>'+'index.php?PublisherAws/showActor',
		type: 'POST',
		data: {data:1},
		success: function(data){
			$("#actors").html(data);
			$("#series_actors").html(data);
		}
	})
}
function showDirector(){
$.ajax({
	url:'<?= base_url() ?>'+'index.php?PublisherAws/showDirector',
	type: 'POST',
	data: {data:1},
	success: function(data){
		console.log(data);
		$("#directors").html(data);
		$("#series_directors").html(data);
	}
})
}
$(document).ready(function(){
	showActor();
	showDirector();
	$("#AddActors").submit(function(e){
		e.preventDefault();
		var formData = new FormData(this);
		$("#actrbtn").attr('disabled',true);

		$.ajax({
			url:'<?= base_url() ?>'+'index.php?PublisherAws/addActor',
			type: 'POST',
			data: formData,
			success: function(data){
				$("#actrbtn").attr('disabled',false);
				$("#SubmitMovie").show();
				$("#ActorPopUp").hide();
		        $.toast({
		            heading: 'Field',
		            text: 'Actor Added Successfully.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'success',
		            // hideAfter: -1,
		            // timeout: -1
		            hideAfter: false,
		        });
				showActor();
			},
		cache: false,
		contentType: false,
		processData: false
		})
	})
	$("#DirectorAdd").submit(function(e){
		e.preventDefault();
		var formData = new FormData(this);
		$("#dirbtn").attr('disabled',true);
		$.ajax({
			url:'<?= base_url() ?>'+'index.php?PublisherAws/addDirector',
			type: 'POST',
			data: formData,
			success: function(data){
				$("#dirbtn").attr('disabled',false);
				$("#SubmitMovie").show();
				$("#DirPopUp").hide();
		        $.toast({
		            heading: 'Field',
		            text: 'Director Added Successfully.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'success',
		            // hideAfter: -1,
		            // timeout: -1
		            hideAfter: false,
		        });
				showDirector();
			},
		cache: false,
		contentType: false,
		processData: false
		})
	})

	$("#movie_thumb").on('change', function(){
		var name = document.getElementById("movie_thumb").files[0].name;
		var form_data = new FormData();
		var ext = name.split('.').pop().toLowerCase();

		if (jQuery.inArray(ext, ['jpg']) == -1) {
			alert("Invalid Image File");
			return false;
		}
	  	var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("movie_thumb").files[0]);
		var f = document.getElementById("movie_thumb").files[0];
		var fsize = f.size||f.fileSize;
		// console.log(fsize);
   		form_data.append("file", document.getElementById('movie_thumb').files[0]);
   		form_data.append("movie_id", '<?= $movie_id ?>');
		$.ajax({
	    url:"upload_image.php",
	    method:"POST",
	    data: form_data,
	    contentType: false,
	    cache: false,
	    processData: false,
	    beforeSend:function(){
	     $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
	    },   
	    success:function(data)
	    {
			if ($.trim(data) == 'Wrong_Image_Size') {
				$.toast({
		            heading: 'Field',
		            text: 'Your Image Should be 4:3.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'warning',
		            // hideAfter: -1,
		            // timeout: 900,
		            hideAfter: false
		        });
		        return false;
			}
			if ($.trim(data) == 1) {
				$.toast({
		            heading: 'Field',
		            text: 'Thumbnail Image Uploaded.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'success',
		            hideAfter: -1,
		            hideAfter: false
		        });
		        return false;
			}
	     $('#uploaded_image').html(data);
	    }
	   });
	})

	$("#movie_poster").on('change', function(){
		var name = document.getElementById("movie_poster").files[0].name;
		var form_data = new FormData();
		var ext = name.split('.').pop().toLowerCase();

		if (jQuery.inArray(ext, ['jpg']) == -1) {
			alert("Invalid Image File");
			return false;
		}
	  	var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("movie_poster").files[0]);
		var f = document.getElementById("movie_poster").files[0];
		var fsize = f.size||f.fileSize;
		// console.log(fsize);
   		form_data.append("file", document.getElementById('movie_poster').files[0]);
   		form_data.append("movie_id", '<?= $movie_id ?>');
		$.ajax({
	    url:"upload_imagePoster.php",
	    method:"POST",
	    data: form_data,
	    contentType: false,
	    cache: false,
	    processData: false,
	    beforeSend:function(){
	     $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
	    },   
	    success:function(data)
	    {
			if ($.trim(data) == 'Wrong_Image_Size') {
				$.toast({
		            heading: 'Field',
		            text: 'Your Image Should be 4:3.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'warning',
		            // hideAfter: -1,
		            // timeout: 900,
		            hideAfter: false
		        });
		        return false;
			}
			if ($.trim(data) == 1) {
				$.toast({
		            heading: 'Field',
		            text: 'Poster Image Uploaded.!',
		            position: 'top-right',
		            showHiddenTranscition: 'plain',
		            icon: 'success',
		            hideAfter: -1,
		            hideAfter: false
		        });
		        return false;
			}
	     $('#uploaded_image').html(data);
	    }
	   });
	})

			})
			function liActiveHome() {
				$('#home12').addClass('active', true);
				$("#profile12").removeClass('active', false);
				$("#message12").removeClass("active",false);
			}
			function liActiveProfile() {
				$('#home12').removeClass('active', false);
				$("#profile12").addClass('active', true);
				$("#message12").removeClass("active",false);
				// $("#profile12").children('a').addClass('active',true);
			}
			function liActiveMessage() {
				$('#home12').removeClass('active', false);
				$("#profile12").removeClass('active', false);
				$("#message12").addClass("active",true);
			}
			$(document).mousemove(function(){
				$('.modal-backdrop').remove();
			})

		</script>
		<script>
			$.fn.expose = function(options) {

				var $modal = $(this),
				$trigger = $("a[href=" + this.selector + "]");

				$modal.on("expose:open", function() {

					$modal.addClass("is-visible");
					$modal.trigger("expose:opened");
				});

				$modal.on("expose:close", function() {

					$modal.removeClass("is-visible");
					$modal.trigger("expose:closed");
				});

				$trigger.on("click", function(e) {

					e.preventDefault();
					$modal.trigger("expose:open");
				});

				$modal.add( $modal.find(".close") ).on("click", function(e) {

					e.preventDefault();

// if it isn't the background or close button, bail
if( e.target !== this )
	return;

$modal.trigger("expose:close");
});

				return;
			}

			$("#Popup").expose();
			$("#SubmitMovie").expose();
			$("#approveMovie").expose();
			$("#ReviewMovie").expose();
			$("#totalEpisode").expose();
			$("#approveEpisode").expose();
			$("#underReviewEpisode").expose();

			$(".cancel").on("click", function(e) {

				e.preventDefault();
				$(this).trigger("expose:close");
			});

			$(".js-example-responsive").select2({
width: 'resolve', // need to override the changed default
theme: "classic"
});
$('#moviePublisherUpload').submit(function () {
    var movie_title         = $("#movietitle").val();
    var movie_thumb         = $("#movie_thumb").val();
    var movie_poster        = $("#movie_poster").val();
    var description_long    = $("#description_long").val();
    var description_short   = $("#description_short").val();
    var actors              = $("#actors").val();
    var genre_id            = $("#genre_id option:selected").text();
    var year                = $("#year option:selected").text();
    var rating              = $("#rating option:selected").text();
	
    if (movie_title == "" || movie_title == " " || movie_title == "-"){
        $.toast({
            heading: 'Field',
            text: 'Title is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (description_long == "" || description_long == " " || description_long == "-"){
        $.toast({
            heading: 'Field',
            text: 'Synopsis is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (description_short == "" || description_short == " " || description_short == "-"){
        $.toast({
            heading: 'Field',
            text: 'Log Line is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (genre_id == "" || genre_id == " " || genre_id == "-"){
        $.toast({
            heading: 'Field',
            text: 'Genre is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (rating == "" || rating == " " || rating == "-"){
        $.toast({
            heading: 'Field',
            text: 'Rating is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (year == "" || year == " " || year == "-"){
        $.toast({
            heading: 'Field',
            text: 'Publishing Year is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (movie_thumb == "" || movie_thumb == " " || movie_thumb == "-"){
        $.toast({
            heading: 'Field',
            text: 'Movie Thumbnail is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
    if (movie_poster == "" || movie_poster == " " || movie_poster == "-"){
        $.toast({
            heading: 'Field',
            text: 'Movie Poster is Required.!',
            position: 'top-right',
            showHiddenTranscition: 'plain',
            icon: 'info',
            // hideAfter: -1,
            // timeout: -1
            hideAfter: false,
        });
        return false;
    }
	var formData = new FormData(this);
            $.ajax({
                type: 'Post',
                data: formData,
                url: '<?= base_url('index.php?PublisherAws/movie_upload') ?>',
                success: function (response) {
                    if (response == "202"){
                        $.toast({
                    heading: 'Movie',
                    text: 'Thank you your movie have been submitted we will contact you as soon as possible <a href="localhost/hmifilm/index.php?PublishMovies/index" class="btn btn-primary" style="position: absolute;top: 0;right: 7px;width: 61px;background-color: black;" >OK</a>',
                    position: 'top-right',
                    showHiddenTranscition: 'plain',
                    icon: 'success',
                    loaderBg: '#8de98d',
                    allowToastClose: false,
                    // 
                    hideAfter: false
                });
				// window.location.href = encodeURI(base_url+'index.php?PublishMovies/index');
                    // $('#create_movie').prop('disabled', false);
                        // toast.fire({
                        //     type: 'success',
                        //     title: 'Thank you your movie have been submitted we will contact you within two weeks <a href="#">OK</a>',
                        //     hideAfter: false

                        // });
                        // setTimeout(function () {
                        //     // window.location.href = encodeURI(base_url+'index.php?PublishMovies/movies');
                        //     // location.reload();
                        // },6000)
                    }else if (response == 'MovieName_Already_Exists') {
						alert('MovieName_Already_Exists');
					}
					else{
                        // toast.fire({
                        //     type: 'error',
                        //     title: 'Something Wrong.!'
                        // })
						alert('Something_Wrong.!');
                    }
                },
                processData: false,
                contentType: false
            })
})
// function movieValidation(){
// 
// }
</script>
<script src="<?php echo base_url();?>assets/js/plupload/plupload.full.min.js"></script>

<script src="<?= base_url().'assets/form_ajaxSubmit.js' ?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/application_trailer.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/application.js"></script>


<!-- <script src="<?php echo base_url();?>assets/js/jquery.form.min.js"></script>  -->