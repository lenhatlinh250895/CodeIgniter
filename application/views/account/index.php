<div class="container">
	<div class="input-group">
		<span class="input-group-addon">Search</span>
		<input type="text" name="search" id="search" placeholder="Search by User Name" class="form-control">
	</div>
	<div id="result"></div>


	<h3>User Lists <?php echo $this->session->userdata('user'); ?></h3>
	<div class="alert alert-success" style="display: none;">
		
	</div>
	<button id="btnadd" class="btn btn-success">Add New</button>
	<div class="table-responsive" id="showAllUser"></div>
	<div id="link" align="right"></div>
</div>

<div class="modal fade" id="mymodal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="mess_succ" id="message"></div>
				<div class="mess_error" id="message"></div>
				<form action="" method="post" id="myform" class="form-horizontal" enctype="multipart/form-data">
					<input type="hidden" name="id" value="0">
					<div class="alert alert-danger" id="alert" style="display: none;">
	
					</div>
					<div class="form-group">
						<label for="username" class="label-control col-md-4">User Name</label>
						<div class="col-md-8">
							<input type="text" name="username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="label-control col-md-4">Pass Word</label>
						<div class="col-md-8">
							<input type="password" name="password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="repassword" class="label-control col-md-4">Re-Pass</label>
						<div class="col-md-8">
							<input type="password" name="repassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="fullname" class="label-control col-md-4">Full Name</label>
						<div class="col-md-8">
							<input type="text" name="fullname" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="image" class="label-control col-md-4">Image</label>
						<div class="col-md-8">
							<input type="file" name="image" id="image">
						</div>
					</div>
					<div class="form-group">
						<label for="gioitinh" class="label-control col-md-4">Gioi Tinh</label>
						<div class="col-md-8">
							<select name="gioitinh" id="gioitinh">
								<option id="nam" value="Nam" selected>Nam</option>
								<option id="nu" value="Nu">Nữ</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="level" class="label-control col-md-4">Level</label>
						<div class="col-md-8">
							<select name="level" id="level">
								<option id="member" value="1" selected>Member</option>
								<option id="admin" value="2">Admin</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnsave" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="deletemodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        	Do you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="btndelete" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
	function load_data(page)
	{
		$.ajax({
			url: "<?php echo base_url(); ?>Account/pagination/"+page,
			method: "GET",
			dataType: "json",
			success: function(data){
				$('#showAllUser').html(data.data_table);
				$('#link').html(data.pagination_link);
			}
		});
	}
	load_data(1);

	//chuyen tab pagination
	$(document).on("click",".pagination li a",function(even){
		even.preventDefault();
		var page = $(this).data("ci-pagination-page");
		load_data(page);
	});

	//hien thi popup them user
	$('#btnadd').on('click',function(){
		$('#mymodal').modal('show');
		$('#mymodal').find('.modal-title').text('Add New User');
		$('#myform').attr('action','http://localhost/account/Account/addNewUser');
	});
	//them moi va update user
	$('#btnsave').on('click',function(){
		var url = $('#myform').attr('action');
		var data = $('#myform').serialize();
		$.ajax({
			type: 'ajax',
			url: url,
			data: data,
			method: 'post',
			dataType: 'json',
			success: function(response){
				if(response.success)
				{
					$('#mymodal').modal('hide');
					$('#myform')[0].reset();
					if(response.type == 'add')
						var type = 'Added';
					if(response.type == 'update')
						var type = 'Update';
					$('.alert-success').html('User '+type+' Successfully').fadeIn().delay(4000).fadeOut('slow');
					var page = $('.active').find('a').html();
					if($('.active').length)
						load_data(page);
					else
						load_data(1);
				}
				else
					$('#alert').html(response).fadeIn().delay(4000).fadeOut('slow');
			}
		});
	});

	//xoa user
	$('#showAllUser').on('click','.btndel',function(){
		var id = $(this).attr('data');
		var lastid = $('.tableeee').children().children().children(":last").children().attr('data');
		console.log(lastid);
		console.log(id);
		$('#deletemodal').modal('show');
		$('#btndelete').unbind().click(function(){
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: 'http://localhost/account/Account/deleteUser',
				async: false,
				data: {id : id},
				dataType: 'json',
				success: function(response)
				{
					if(response.success)
					{
						$('#deletemodal').modal('hide');
						$('.alert-success').html('User Deleted Successfully').fadeIn().delay(4000).fadeOut('slow');
						var page = "";
						page = $('.active').find('a').html();
						if($('.active').length)
							if(id == lastid)
								load_data(page-1);
							else
								load_data(page);
						else
							load_data(1);
					}
					else
					{
						alert('Error');
					}
				},
				error: function()
				{
					alert('Error Deleting')
				}
			});
		});
	});
	//hien thi popup edit user
	$('#showAllUser').on('click','.btnedit',function(){
		var id = $(this).attr('data');
		$('#mymodal').modal('show');
		$('#mymodal').find('.modal-title').text('Edit User');
		$('#myform').attr('action','http://localhost/account/Account/updateUser/'+id);
		$.ajax({
			type: 'ajax',
			method: 'get',
			url: '<?php echo base_url(); ?>Account/editUser',
			data: {id : id},
			async: false,
			dataType: 'json',
			success: function(data)
			{
				$('input[name=id]').val(data.id);
				$('input[name=username]').val(data.username);
				$('input[name=fullname]').val(data.fullname);
				$('input[name=password]').val(data.password);
				$('input[name=repassword]').val(data.password);
				if(data.gioitinh == 'Nam')
				{
					$('#nam').attr('selected','true');
					$('#nu').removeAttr('selected');
				}
				else
				{
					$('#nu').attr('selected','true');
					$('#nam').removeAttr('selected');
				}
				if(data.level == 1)
				{
					$('#member').attr('selected','true');
					$('#admin').removeAttr('selected');
				}
				else
				{
					$('#admin').attr('selected','true');
					$('#member').removeAttr('selected');
				}
			},
			error: function()
			{
				alert('Could not Edit Data')
			}
		});
	});

	//search theo user name
	$('#search').keyup(function(){
		var var_text = $(this).val();
		if(var_text != '')
		{
			$.ajax({
				url: 'http://localhost/account/Account/search',
				method: 'post',
				data: {var_text : var_text},
				dataType: 'text',
				success: function(data){
					$('#result').html(data);
				},
			});
		}
		else
		{
			$('#result').html('');
		}
	});
});
</script>

