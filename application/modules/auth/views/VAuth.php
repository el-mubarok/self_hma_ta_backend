<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">

<!-- head -->
<?= $this->load->view('../../elements/head'); ?>

<style>
	.icon-spin{
		margin-right: 5px;
		-webkit-animation: icon-spinner 1s linear infinite;
		-moz-animation: icon-spinner 1s linear infinite;
		-ms-animation: icon-spinner 1s linear infinite;
		-o-animation: icon-spinner 1s linear infinite;
		animation: icon-spinner 1s linear infinite;
	}
	@keyframes icon-spinner{
		from {
			-ms-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-webkit-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		to {
			-ms-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-webkit-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	body{
		background: linear-gradient(-45deg, #267871, #136a8a, #5f2c82, #49a09d);
		background-size: 400% 400%;
		animation: bg-spin 30s ease infinite;
	}

	@keyframes bg-spin{
		0% {
			background-position: 0% 50%;
		}
		50% {
			background-position: 100% 50%;
		}
		100% {
			background-position: 0% 50%;
		}
	}
</style>

<body data-open="click" data-menu="vertical-menu" data-col="1-column" class="vertical-layout vertical-menu 1-column  blank-page blank-page">

	<div class="app-content content container-fluid">
		<div class="content-wrapper">
			<div class="content-header row">
			</div>
			<div class="content-body">
				<section class="flexbox-container">
					<div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 m-0">
							<div class="card-header no-border">
								<!-- <div class="card-title text-xs-center">
									<div class="p-1"><img src="../../app-assets/images/logo/robust-logo-dark.png" alt="branding logo"></div>
								</div> -->
								<h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>LogIn to continue</span></h6>
							</div>
							<div class="card-body collapse in">
								<div class="card-block">
									<form class="form-horizontal form-simple" action="auth/login" method="post">
										<fieldset class="form-group position-relative has-icon-left mb-0">
											<input type="text" class="form-control form-control-lg input-lg" id="user-name" placeholder="Your Username" name="username" required>
											<div class="form-control-position">
												<i class="icon-head"></i>
											</div>
										</fieldset>
										<fieldset class="form-group position-relative has-icon-left">
											<input type="password" class="form-control form-control-lg input-lg" id="user-password" placeholder="Enter Password" name="password" required>
											<div class="form-control-position">
												<i class="icon-key3"></i>
											</div>
										</fieldset>
										
										<!-- <fieldset class="form-group row">
											<div class="col-md-6 col-xs-12 text-xs-center text-md-left">
												<fieldset>
													<input type="checkbox" id="remember-me" class="chk-remember">
													<label for="remember-me"> Remember Me</label>
												</fieldset>
											</div>
											<div class="col-md-6 col-xs-12 text-xs-center text-md-right">
												<a href="recover-password.html" class="card-link">Forgot Password?</a>
											</div>
										</fieldset> -->
										
										<button type="submit" class="btn btn-primary btn-lg btn-block btn-logins">
											<i class="icon-unlock2"></i> Login
										</button>
									</form>
								</div>
							</div>
							
							<!-- <div class="card-footer">
								<div class="">
									<p class="float-sm-left text-xs-center m-0">
										<a href="recover-password.html" class="card-link">Recover password</a>
									</p>
								</div>
							</div> -->

						</div>
					</div>
				</section>

			</div>
		</div>
	</div>
	<!-- ////////////////////////////////////////////////////////////////////////////-->

	<!-- script -->
	<?= $this->load->view('../../elements/script'); ?>
	<script>

		$("#user-name, #user-password").keypress(function(e){
			if(e.keyCode == 13){
				$(".btn-login").trigger("click");		
			}
		});

		$(".btn-login").click(function(){
			var el = $(this);
			var uname = $("#user-name"), 
			upass = $("#user-password");
			
			if(uname.val() != "" && upass.val() !== ""){
				loading('start',el);
				setTimeout(function(){
					loading('stop',el);
					window.open("http://localhost:3000", "_self");}, 4000);
			}else{
				alert("Please fill all the field!");
			}
		});
		
		function loading(status, el){
			// loader element
			var els = $("<i>").addClass("icon-reload icon-spin");
			switch(status){
				case 'start':
					el.find("i.icon-unlock2").attr("class","icon-spinner icon-spin");
					el.attr("disabled","disabled");
				break;
				case 'stop':
					el.find("i.icon-spin").attr("class","icon-unlock2");
					el.removeAttr("disabled");
				break;
			}
		}
	</script>
</body>

</html>