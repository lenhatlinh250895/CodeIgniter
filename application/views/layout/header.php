<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajax.js"></script> -->
</head>
<body>
<div class="navbar navbar-default">
	<div class="container">
		<h2><span class="glyphicon glyphicon-home"></span>&nbsp;Welcome to my Application No 20k</h2>
		<?php if (!empty($this->session->userdata('user'))): ?>
		<span class="btn btn-primary" id="btnlogout" align="right">
			Logout	
		</span>
		<?php else: ?>
		<span class="btn btn-primary" id="btnlogin" align="right">
			Login
		</span>			
		<?php endif ?>
	</div>
</div>
<div class="container">

<script type="text/javascript">
$(document).ready(function(){
	$('#btnlogout').on('click',function(){
		$.ajax({
			url: '<?php echo base_url(); ?>Account/logout',
			method: 'GET',
			success: function(data){
				window.location.replace('<?php echo base_url(); ?>Account/login');
			},
			error: function(){
				alert(22);
			}
		});
	});
});	
</script>
