{% extends "layouts/html.volt" %}
{% block title %}
<title>
	{{pageName}}
</title>
{% endblock %}
{% block content %}
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header" style="margin-bottom:5px;">
			<i class="fa fa-{{pageName}}"></i> {{pageName}}
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>

<div class="row">
	<div class="col-lg-12 no-padding">
		<div class="">
			<!-- /.panel-heading -->
			<div class="panel-body no-padding">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- /.panel -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-clock-o fa-fw">
							</i> Dashboard
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="col-lg-3 col-md-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-3">
												<i class="fa fa-Client fa-3x">
												</i>
											</div>
											<div class="col-xs-9 text-right">
												<div class="huge">
													{{ dashboard.customers_count }}
												</div>
												<div>
													Clients
												</div>
											</div>
										</div>
									</div>
									<a href="{{ url('superadmin/Customer') }}">
										<div class="panel-footer">
										<span class="pull-left">
											View Details
										</span>
										<span class="pull-right">
											<i class="fa fa-arrow-circle-right">
											</i>
										</span>
											<div class="clearfix">
											</div>
										</div>
									</a>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="panel panel-green">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-3">
												<i class="fa fa-archive  fa-3x">
												</i>
											</div>
											<div class="col-xs-9 text-right">
												<div class="huge">
													{{ dashboard.packages_count }}
												</div>
												<div>
													Packages
												</div>
											</div>
										</div>
									</div>
									<a href="{{ url('superadmin/Package') }}">
										<div class="panel-footer">
										<span class="pull-left">
											View Details
										</span>
										<span class="pull-right">
											<i class="fa fa-arrow-circle-right">
											</i>
										</span>
											<div class="clearfix">
											</div>
										</div>
									</a>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="panel panel-red">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-3">
												<i class="fa fa-percent fa-3x">
												</i>
											</div>
											<div class="col-xs-9 text-right">
												<div class="huge">
													{{ dashboard.vouchers_count }}
												</div>
												<div>
													Vouchers
												</div>
											</div>
										</div>
									</div>
									<a href="{{ url('superadmin/Voucher') }}">
										<div class="panel-footer">
										<span class="pull-left">
											View Details
										</span>
										<span class="pull-right">
											<i class="fa fa-arrow-circle-right">
											</i>
										</span>
											<div class="clearfix">
											</div>
										</div>
									</a>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="panel panel-yellow">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-3">
												<i class="fa fa-shopping-cart fa-3x">
												</i>
											</div>
											<div class="col-xs-9 text-right">
												<div class="huge">
													{{ dashboard.spent_vocuhers_count }}
												</div>
												<div>
													Vouchers spent
												</div>
											</div>
										</div>
									</div>
									<a href="{{ url('superadmin/VoucherSpent') }}">
										<div class="panel-footer">
										<span class="pull-left">
											Vouchers spent
										</span>
										<span class="pull-right">
											<i class="fa fa-arrow-circle-right">
											</i>
										</span>
											<div class="clearfix">
											</div>
										</div>
									</a>
								</div>
							</div>

						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
	<!-- /.panel -->
</div>
<!-- /.col-lg-12 -->

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
{% endblock %}
