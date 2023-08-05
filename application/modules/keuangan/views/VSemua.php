<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">

<!-- head -->
<?= $this->load->view('../../elements/head'); ?>

<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">

	<!-- navbar-fixed-top-->
	<?= $this->load->view('../../elements/navbar'); ?>
	<!-- main menu / sidebar-->
	<?= $this->load->view('../../elements/sidebar'); ?>
	<!-- / main menu / sidebar-->

	<!-- content -->
	<div class="app-content content container-fluid" style="min-height: 100%;">
		<div class="content-wrapper">

			<div class="content-header row">
				<div class="content-header-left col-md-6 col-xs-12 mb-1">
					<h2 class="content-header-title">Semua Tagihan</h2>
				</div>
				<!-- <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
					<div class="breadcrumb-wrapper col-xs-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="index.html">Warga</a>
							</li>
							<li class="breadcrumb-item active">
								Warga tetap
							</li>
						</ol>
					</div>
				</div> -->
			</div>

			<div class="content-body">

				<div class="row">
					<div class="col-xs-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title" style="margin-bottom: 4px;">Record Seluruh Tagihan</h4>
								<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
								<div class="heading-elements">
									<ul class="list-inline mb-0">
										<li><a data-action="expand"><i class="icon-expand2"></i></a></li>
									</ul>
								</div>
							</div>
							<div class="card-body collapse in">
								<div class="card-block card-dashboard">

									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="width: 100%" id="tbliuranbulanan">
											<thead class="thead-inverse">
												<tr>
													<th style="width: 1%">#</th>
													<th>Tanggal</th>
													<th>Nama</th>
													<th>Alamat</th>
													<th>Metode</th>
													<th>Status</th>
													<th>Jumlah</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- end content -->

	<!-- footer -->
	<?= $this->load->view('../../elements/footer'); ?>
	<!-- script -->
	<?= $this->load->view('../../elements/script'); ?>
	<?= $this->load->view('script.vsemua.php'); ?>
</body>

</html>