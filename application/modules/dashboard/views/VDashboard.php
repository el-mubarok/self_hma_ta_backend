<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">

<!-- head -->
<?= $this->load->view('../../elements/head'); ?>

<style>
	.main{display:flex;padding:2em;height:90vh;justify-content:center;align-items:middle}.clockbox,#clock{width:100%}.circle{fill:none;stroke:#fff;stroke-width:9;stroke-miterlimit:10}.mid-circle{fill:#fff}.hour-marks{fill:none;stroke:#fff;stroke-width:9;stroke-miterlimit:10}.hour-arm{fill:none;stroke:#fff;stroke-width:17;stroke-miterlimit:10}.minute-arm{fill:none;stroke:#fff;stroke-width:11;stroke-miterlimit:10}.second-arm{fill:none;stroke:#fff;stroke-width:4;stroke-miterlimit:10}.sizing-box{fill:none}#hour,#minute,#second{transform-origin:300px 300px;transition:transform .5s ease-in-out}
</style>
<style>
	.info-card-desc{
		font-size: 12px;
	}
</style>

<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">

	<!-- navbar-fixed-top-->
	<?= $this->load->view('../../elements/navbar'); ?>

	<!-- ////////////////////////////////////////////////////////////////////////////-->

	<!-- main menu / sidebar-->
	<?= $this->load->view('../../elements/sidebar'); ?>
	<!-- / main menu / sidebar-->

	<div class="app-content content container-fluid" style="min-height: 100%;">
		<div class="content-wrapper">

			<div class="content-body">

				<!-- <div class="row">
					<div class="col-xl-3 col-lg-3 col-sm-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-body text-xs-left">
											<h3 class="pink">278</h3>
											<span class="info-card-desc">Total Warga</span>
										</div>
										<div class="media-right media-middle">
											<i class="icon-users pink font-large-2 float-xs-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-body text-xs-left">
											<h3 class="teal">Rp150K</h3>
											<span class="info-card-desc">Pemasukan Bulan Ini</span>
										</div>
										<div class="media-right media-middle">
											<i class="icon-arrow78 teal font-large-2 float-xs-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-body text-xs-left">
											<h3 class="deep-orange">Rp0</h3>
											<span class="info-card-desc">Pengeluaran Bulan Ini</span>
										</div>
										<div class="media-right media-middle">
											<i class="icon-arrow79 deep-orange font-large-2 float-xs-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-body text-xs-left">
											<h3 class="cyan">Rp2.500K</h3>
											<span class="info-card-desc">Total Saldo</span>
										</div>
										<div class="media-right media-middle">
											<i class="icon-gold2 cyan font-large-2 float-xs-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<div class="row">
					<div class="col-xs-12">
						<div class="card">
							<div class="card-body" 
								style="
									background-image: url(https://images.pexels.com/photos/2157404/pexels-photo-2157404.jpeg);
									background-size: cover;
									background-repeat: no-repeat;
									background-position: center center;
								">
								<div class="card-block">
									<div class="widget-dark-layer" 
										style="
											position: absolute;
											width: 100%;
											height: 100%;
											top: 0;
											left: 0;
											background: linear-gradient(to right,
												rgba(0,0,0,.8),
												rgba(0,0,0,.7),
												rgba(0,0,0,0));
											z-index: 1;
										"></div>
									<div style="position: relative; z-index: 10; color: #ffffff;">
										<div class="clockbox" style="width: 100px">
											<svg id="clock" xmlns="http://www.w3.org/2000/svg" width="300" viewBox="0 0 600 600">
												<g id="face">
													<circle class="circle" cx="300" cy="300" r="253.9" />
													<path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6" />
													<circle class="mid-circle" cx="300" cy="300" r="16.2" />
												</g>
												<g id="hour">
													<path class="hour-arm" d="M300.5 298V142" />
													<circle class="sizing-box" cx="300" cy="300" r="253.9" />
												</g>
												<g id="minute">
													<path class="minute-arm" d="M300.5 298V67" />
													<circle class="sizing-box" cx="300" cy="300" r="253.9" />
												</g>
												<g id="second">
													<path class="second-arm" d="M300.5 350V55" />
													<circle class="sizing-box" cx="300" cy="300" r="253.9" />
												</g>
											</svg>
										</div>
										<div>
											<h1>
												<?= date("d M Y")?>
											</h1>
											<h3>Perumahan Mentari Village, Balikpapan</h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- ////////////////////////////////////////////////////////////////////////////-->

	<!-- footer -->
	<?= $this->load->view('../../elements/footer'); ?>

	<!-- script -->
	<?= $this->load->view('../../elements/script'); ?>
	<script src="https://rawcdn.githack.com/el-mubarok/javascript-analog-clock/cd3110fab824034178be68d10ea433e62921e26b/script.js"></script>
	<script>
		console.log(date.getHours());
	</script>
</body>

</html>