@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet"/>
@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading">{{ __("Import AliExpress Products") }}</h4>
				<ul class="links">
					<li>
						<a href="{{ route('admin.dashboard',$storename) }}">{{ __("Dashboard") }} </a>
					</li>

				</ul>
			</div>
		</div>
	</div>
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">	
						<form>
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">Product ID* </h4>
										<p class="sub-heading">(https://www.aliexpress.com/item/ProductID.html)</p>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="number" class="input-field" placeholder="Enter ProductID" name="product_id" id="product_id" required="">
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<button type="button" id="fetch" class="addProductSubmit-btn" style="float: right;">Fetch</button>
								</div>
							</div>
						</form>
						<br/>
						<div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
						<form id="geniusform" action="{{route('admin-prod-store',$storename)}}" method="POST" enctype="multipart/form-data">
							{{csrf_field()}}

							@include('includes.admin.form-both')
							<input type="hidden" name="ali_express_product" value="ali_express_product">
							<input type="hidden" name="previous_price" value="0">
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Product Name') }}* </h4>
										<p class="sub-heading">(In Any Language)</p>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" placeholder="{{ __('Enter Product Name') }}" name="name" id="name" required="">
								</div>
							</div>
							<input type="hidden" name="featured_image" id="featured_image">
							<div id="product_image">
								
							</div>
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Product Sku') }}* </h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" placeholder="{{ __('Enter Product Sku') }}" name="sku" required="" value="{{ str_random(3).substr(time(), 6,8).str_random(3) }}">

								</div>
							</div>
							


							<div class="showbox">

								<div class="row">
									<div class="col-lg-4">
										<div class="left-area">
											<h4 class="heading">{{ __("Product Measurement") }}*</h4>
										</div>
									</div>
									<div class="col-lg-3">
										<select id="product_measure">
											<option value="">{{ __("None") }}</option>
											<option value="Gram">{{ __("Gram") }}</option>
											<option value="Kilogram">{{ __("Kilogram") }}</option>
											<option value="Litre">{{ __("Litre") }}</option>
											<option value="Pound">{{ __("Pound") }}</option>
											<option value="Custom">{{ __("Custom") }}</option>
										</select>
									</div>
									<div class="col-lg-1"></div>
									<div class="col-lg-3 hidden" id="measure">
										<input name="measure" type="text" id="measurement" class="input-field" placeholder="{{ __("Enter Unit") }}">
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">
											{{ __('Product Current Price') }}*
										</h4>
										<p class="sub-heading">
											({{ __('In') }} {{$sign->name}})
										</p>
									</div>
								</div>
								<div class="col-lg-7">
									<input name="price" id="price" type="number" class="input-field" placeholder="{{ __('e.g 20') }}" step="0.01" required="" min="0">
								</div>
							</div>
							<div class="row" id="stckprod">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Product Stock') }}*</h4>
										<p class="sub-heading">{{ __('(Leave Empty will Show Always Available)') }}</p>
									</div>
								</div>
								<div class="col-lg-7">
									<input name="stock" id="stock" type="text" class="input-field" placeholder="{{ __('e.g 20') }}">
									<div class="checkbox-wrapper">

									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Category') }}*</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<select id="cat" name="category_id" required="">
										<option value="">{{ __('Select Category') }}</option>
										@foreach($cats as $cat)
										<option data-href="{{ route('admin-subcat-load',[$storename,$cat->id]) }}" value="{{ $cat->id }}">{{$cat->name}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Sub Category') }}*</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<select id="subcat" name="subcategory_id" disabled="">
										<option value="">{{ __('Select Sub Category') }}</option>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('Child Category') }}*</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<select id="childcat" name="childcategory_id" disabled="">
										<option value="">{{ __('Select Child Category') }}</option>
									</select>
								</div>
							</div>


							<div id="catAttributes"></div>
							<div id="subcatAttributes"></div>
							<div id="childcatAttributes"></div>
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">
											{{ __('Product Images') }}*
										</h4>

									</div>
								</div>
								<div class="col-lg-7" id="images">

								</div>
							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">
											{{ __("Product Description") }}*
										</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<div class="text-editor">
										<textarea class="form-control" name="details" id="details" rows="5"></textarea> 
									</div>
								</div>
							</div>
							


							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">
											{{ __("Product Buy/Return Policy") }}*
										</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<div class="text-editor">
										<textarea class="nic-edit-p" name="policy"></textarea> 
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __("Youtube Video URL") }}*</h4>
										<p class="sub-heading">{{ __("(Optional)") }}</p>
									</div>
								</div>
								<div class="col-lg-7">
									<input  name="youtube" type="text" class="input-field" placeholder="{{ __("Enter Youtube Video URL") }}">
									<div class="checkbox-wrapper">
										<input type="checkbox" name="seo_check" value="1" class="checkclick" id="allowProductSEO" value="1">
										<label for="allowProductSEO">{{ __("Allow Product SEO") }}</label>
									</div>
								</div>
							</div>



							<div class="showbox">
								<div class="row">
									<div class="col-lg-4">
										<div class="left-area">
											<h4 class="heading">{{ __("Meta Tags") }} *</h4>
										</div>
									</div>
									<div class="col-lg-7">
										<ul id="metatags" class="myTags">
										</ul>
									</div>
								</div>  

								<div class="row">
									<div class="col-lg-4">
										<div class="left-area">
											<h4 class="heading">
												{{ __("Meta Description") }} *
											</h4>
										</div>
									</div>
									<div class="col-lg-7">
										<div class="text-editor">
											<textarea name="meta_description" class="input-field" placeholder="{{ __("Meta Description") }}"></textarea> 
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">

									</div>
								</div>
								<div class="col-lg-7">
									<div class="featured-keyword-area">
										<div class="heading-area">
											<h4 class="title">{{ __("Feature Tags") }}</h4>
										</div>

										<div class="feature-tag-top-filds" id="feature-section">
											<div class="feature-area">
												<span class="remove feature-remove"><i class="fas fa-times"></i></span>
												<div class="row">
													<div class="col-lg-6">
														<input type="text" name="features[]" class="input-field" placeholder="Enter Your Keyword">
													</div>

													<div class="col-lg-6">
														<div class="input-group colorpicker-component cp">
															<input type="text" name="colors[]" value="#000000" class="input-field cp"/>
															<span class="input-group-addon"><i></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>

										<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i class="icofont-plus"></i> {{ __("Add More Field") }}</a>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __("Tags") }} *</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<ul id="tags" class="myTags">
									</ul>
								</div>
							</div>
							<input type="hidden" name="type" value="Physical">
							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">
										
									</div>
								</div>
								<div class="col-lg-7 text-center">
									<button class="addProductSubmit-btn" type="submit">{{ __("Create Product") }}</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">{{ __("Image Gallery") }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="top-area">
					<div class="row">
						<div class="col-sm-6 text-right">
							<div class="upload-img-btn">
								<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i>{{ __("Upload File") }}</label>
							</div>
						</div>
						<div class="col-sm-6">
							<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> {{ __("Done") }}</a>
						</div>
						<div class="col-sm-12 text-center">( <small>{{ __("You can upload multiple Images.") }}</small> )</div>
					</div>
				</div>
				<div class="gallery-images">
					<div class="selected-image">
						<div class="row">


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')

<script src="{{asset('assets/admin/js/jquery.Jcrop.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.SimpleCropper.js')}}"></script>

<script type="text/javascript">
	
// Gallery Section Insert

$(document).on('click', '.remove-img' ,function() {
	var id = $(this).find('input[type=hidden]').val();
	$('#galval'+id).remove();
	$(this).parent().parent().remove();
});

$(document).on('click', '#prod_gallery' ,function() {
	$('#uploadgallery').click();
	$('.selected-image .row').html('');
	$('#geniusform').find('.removegal').val(0);
});


$("#uploadgallery").change(function(){
	var total_file=document.getElementById("uploadgallery").files.length;
	for(var i=0;i<total_file;i++)
	{
		$('.selected-image .row').append('<div class="col-sm-6">'+
			'<div class="img gallery-img">'+
			'<span class="remove-img"><i class="fas fa-times"></i>'+
			'<input type="hidden" value="'+i+'">'+
			'</span>'+
			'<a href="'+URL.createObjectURL(event.target.files[i])+'" target="_blank">'+
			'<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
			'</a>'+
			'</div>'+
			'</div> '
			);
		$('#geniusform').append('<input type="hidden" name="galval[]" id="galval'+i+'" class="removegal" value="'+i+'">')
	}

});

// Gallery Section Insert Ends	

</script>

<script type="text/javascript">

	$('#imageSource').on('change', function () {
		var file = this.value;
		if (file == "file"){
			$('#f-file').show();
			$('#f-link').hide();
			$('#f-link').find('input').prop('required',false);
		}
		if (file == "link"){
			$('#f-file').hide();
			$('#f-link').show();
			$('#f-link').find('input').prop('required',true);
		}
	});
	
</script>

<script type="text/javascript">
	
	$('.cropme').simpleCropper();
	$('#crop-image').on('click',function(){
		$('.cropme').click();
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#fetch').click(function(){
			$('.gocover').show();

			var product_id = $('#product_id').val();

			$.ajax({
				url: '{{route("products-ali-express-fetch",$storename)}}',
				type: 'POST',
				data: {product_id:product_id},
				success:function(data)
				{
					$('#images').empty();
					$('#product_image').empty();
					if(data.title)
					{
						var details = data.description;
						details = details.replace(/(<([^>]+)>)/ig,"");
						$('#name').val(data.title);
						$('#details').val(details);
						$('#stock').val(data.totalAvailableQuantity);
						$('#featured_image').val(data.images[0]);
						for(var i = 0; i < 1; i++)
						{
							var image = data.images[i];
							$('#images').append('<img src="'+image+'" style="width: 200px;height: 200px;" />');
							$('#product_image').append('<input type="hidden" name="product_image" value="'+image+'" />');
						}
					}
					console.log(data);
					$('.gocover').hide();
				}

			});
		});
	});
</script>
<script src="{{asset('assets/admin/js/product.js')}}"></script>
@endsection