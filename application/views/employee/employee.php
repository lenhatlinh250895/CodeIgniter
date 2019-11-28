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

<table class="table table-hover tablesorter">
<thead>
	<tr>
		<th class="header">Id.</th>
		<th class="header">User Name</th> 
		<th class="header">Full Name</th>
		<th class="header">Gioi Tinh</th>  
		<th class="header">Role Id</th>                
	</tr>
</thead>
<a class="pull-right btn btn-warning btn-large" style="margin-right:40px" href="<?php echo base_url(); ?>/Employee/createExcel"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
<tbody>
	<?php
	if (isset($employeedata) && !empty($employeedata)) {
		foreach ($employeedata as $key => $emp) {
			?>
			<tr>
				<td><?php echo $emp['id']; ?></td>   
				<td><?php echo $emp['username']; ?></td> 
				<td><?php echo $emp['fullname']; ?></td>
				<td><?php echo $emp['gioitinh']; ?></td> 
				<td><?php echo $emp['roleid']; ?></td>                       
			</tr>
			<?php
		}
	} else {
	?>
		<tr>
			<td colspan="5" class="alert alert-danger">No Records founds</td>    
		</tr>
	<?php } ?>			 
</tbody>
</table>   


</body>
</html>