<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="bmottag">
	<meta name="baseurl" content="<?php echo base_url() ?>" />

	<title>VCI</title>
	<link rel="icon" type="image/png" href="<?php echo base_url("images/favicon.png"); ?>" />

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo base_url("assets/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet">
	<!-- MetisMenu CSS -->
	<link href="<?php echo base_url("assets/bootstrap/metisMenu/metisMenu.min.css"); ?>" rel="stylesheet">
	<!-- Social Buttons CSS -->
	<link href="<?php echo base_url("assets/bootstrap/bootstrap-social/bootstrap-social.css"); ?>" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?php echo base_url("assets/bootstrap/dist/css/sb-admin-2_calendar.css"); ?>" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="<?php echo base_url("assets/bootstrap/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" type="text/css">
	<!-- DataTables CSS -->
	<link href="<?php echo base_url("assets/bootstrap/datatables-plugins/dataTables.bootstrap.css"); ?>" rel="stylesheet">
	<!-- DataTables Responsive CSS -->
	<link href="<?php echo base_url("assets/bootstrap/datatables-responsive/dataTables.responsive.css"); ?>" rel="stylesheet">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<!-- jQuery validate-->
	<script type="text/javascript" src="<?php echo base_url("assets/js/general/general.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/general/jquery.validate.js"); ?>"></script>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<div id="wrapper">

        <?php 
			if (session()->get('rol')) {
				echo view('template/menu', ['topMenu' => $topMenu]); 
			}
		?>

		<!-- Start of content -->
		<?php
		if (isset($view) && ($view != '')) {
			echo view($view, $data ?? []);
		}
		?>
		<!-- End of content -->
	</div>

	<!-- Bootstrap Core JavaScript -->
	<script src="<?php echo base_url("assets/bootstrap/js/bootstrap.min.js"); ?>"></script>
	<!-- Metis Menu Plugin JavaScript -->
	<script src="<?php echo base_url("assets/bootstrap/metisMenu/metisMenu.min.js"); ?>"></script>
	<!-- Custom Theme JavaScript -->
	<script src="<?php echo base_url("assets/bootstrap/dist/js/sb-admin-2.js"); ?>"></script>
	<!-- DataTables JavaScript -->
	<script src="<?php echo base_url("assets/bootstrap/datatables/js/jquery.dataTables.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/bootstrap/datatables-plugins/dataTables.bootstrap.min.js"); ?>"></script>
	<script src="<?php echo base_url("assets/bootstrap/datatables-responsive/dataTables.responsive.js"); ?>"></script>
	<script src="<?= base_url('assets/signature_pad/js/signature_pad.js') ?>"></script>

</body>

</html>