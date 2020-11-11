<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Shopypall Create Store</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
	body {
		color: #fff;
		background: #0f78f2;
	}
	.form-control {
        min-height: 41px;
		background: #fff;
		box-shadow: none !important;
		border-color: #e3e3e3;
	}
	.form-control:focus {
		border-color: #70c5c0;
	}
    .form-control, .btn {        
        border-radius: 2px;
    }
	.login-form {
		width: 650px;
		margin: 0 auto;
		padding: 100px 0 30px;		
	}
	.login-form form {
		color: #7a7a7a;
		border-radius: 2px;
    	margin-bottom: 15px;
        font-size: 13px;
        background: #ececec;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;	
        position: relative;	
    }
	.login-form h2 {
		font-size: 22px;
        margin: 35px 0 25px;
    }
	.login-form .avatar {
		position: absolute;
		margin: 0 auto;
		left: 0;
		right: 0;
		top: -50px;
		width: 95px;
		height: 95px;
		border-radius: 50%;
		z-index: 9;
		background: #70c5c0;
		padding: 15px;
		box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
	}
	.login-form .avatar img {
		width: 100%;
	}	
    .login-form input[type="checkbox"] {
        margin-top: 2px;
    }
    .login-form .btn {        
        font-size: 16px;
        font-weight: bold;
		background: #0f78f2;
		border: none;
		margin-bottom: 20px;
    }
	.login-form .btn:hover, .login-form .btn:focus {
		background: #50b8b3;
        outline: none !important;
	}    
	.login-form a {
		color: #fff;
		text-decoration: underline;
	}
	.login-form a:hover {
		text-decoration: none;
	}
	.login-form form a {
		color: #7a7a7a;
		text-decoration: none;
	}
	.login-form form a:hover {
		text-decoration: underline;
	}
</style>
</head>
<body>
<div class="login-form">
    <!--//www.shopypall.com/store/assets/images/1596669134Shopypall logo.png-->
    <center><img src="{{ asset('assets/logo.png') }} " style="width:200px" class="img image-circle" /></center>
    <form action=" {{ url('create_store1') }} " method="post" id="create_store">
        
        <h2 class="text-center">Welcome To Shopypall | Create Your Store</h2>
        {{ csrf_field() }}
        <div class="form-group">
            <p id="storenameMessage"></p>
        	<input type="text" class="form-control" id='sname' name="storename" placeholder="Store Name" required="required">
        	<p>&nbsp please put a unique name as it will be your live subdomain</p>
        </div>
         <div class="form-group">
        	<input type="text" class="form-control" name="phone" placeholder="Phone number" required="required">
        </div>
        <div class="form-group">
        	<input type="email" class="form-control" name="email" placeholder="Email" required="required">
        </div>
		<div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
        </div>        
        <div class="form-group">
        	<input type="text" class="form-control" name="l_key" placeholder="License Key" required="required">
        </div>
        <div class="form-group">
            <button type="submit" id="create-store" class="btn btn-primary btn-lg btn-block">Create My Store</button>
        </div>
    </form>
    
    <div id='verified_div' style="display:none;">
        <div class="alert alert-success">
            Congrates! your store has been created successfully ! <br >
            Pleas Find the Details in Your Email<br><br><br >
            
            Thanks For Your Time
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<script>
    $(document).ready(function(){
       
        $('#sname').change(function(){
           
            var storename = $(this).val().replace(/\s+/g, '');
            var _token = "{{ csrf_token() }}";
            $(this).val(storename);
            $.ajax({
                url:"{{ url('/checkstorename') }}",
                method:"post",
                data:{_token,storename },
                success:function(res){
                    console.log(res);
                    if(res === 'true'){
                        $('#create-store').attr('disabled',true);
                        $('#storenameMessage').text('Storename Not Available');
                        $('#storenameMessage').attr('class','alert alert-danger');
                    }
                    else{
                        $('#create-store').attr('disabled',false);
                        $('#storenameMessage').text('Storename Available');
                        $('#storenameMessage').attr('class','alert alert-success');
                    }
                }
            })
        })
    })
</script>
</body>
</html>