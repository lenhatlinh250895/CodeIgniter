<!-- <div class="btn btn-primary" id="btnlogin">
	Login	
</div> -->
<div class="alert alert-danger" id="alert" style="display: none;">
	
</div>
<div id="loginmodal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
				<div class="modal-title">Login</div>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="myform" class="form-horizontal">
					<div class="form-group">
						<label class="label-control col-md-4">User name</label>
						<div class="col-md-8">
							<input type="text" name="username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-md-4">Pass Word</label>
						<div class="col-md-8">
							<input type="password" name="password" class="form-control">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="login" class="btn btn-success">Login</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function(){

		//show form login
		$('#btnlogin').on('click',function(){
			$('#loginmodal').modal('show');
			$('#myform').attr('action','<?php echo base_url(); ?>Account/checkLogin');
		});

		//login
		$('#login').on('click',function(){
			var data = $('#myform').serialize();
			var url = $('#myform').attr('action');
			console.log(data);
			$.ajax({
				url: url,
				method: 'POST',
				data: data,
				success: function(data){
					console.log(data);
					$('#loginmodal').modal('hide');
					$('#myform')[0].reset();
					if(data == 1)
						window.location.replace('<?php echo base_url(); ?>Account');
					else if(data == 0)
						$('#alert').html("Đăng nhập thất bại!").fadeIn().delay(4000).fadeOut('slow');
						else
							$('#alert').html(data).fadeIn().delay(4000).fadeOut('slow');
				}
			});
		});
	});

</script>