(function($) {
	$.fn.paging = function(options) {
		console.log("Hi nguoi choi");

		//gia tri mac dinh
		var defaults = {
			"pages" 		: "#pages",
			"items" 		: 3,
			"currentPage" 	: 1,
			"total" 		: 0,
			"btnPrevious" 	: ".goPrevious",
			"btnNext" 		: ".goNext",
			"txtCurrentPage": "#currentPage",
			"pageInfo" 		: ".pageInfo",
			"showalluser"	: ".showAllUser"
		};
		options = $.extend(defaults,options);

		//cac bien se su dung
		var pages 			= $(options.pages);
		var btnPrevious 	= $(options.btnPrevious);
		var btnNext			= $(options.btnNext);
		var txtCurrentPage 	= $(options.txtCurrentPage);
		var lblPageInfo 	= $(options.pageInfo);
		var showalluser 	= $(options.showalluser);

		//khoi tao ham khi su dung
		init();

		//ham khoi dong
		function init(){
			//lay tong so trang
			$.ajax({
				url: "http://localhost/account/Account/countAll",
				data: {items : options.items},
				type: "GET",
				dataType: "json"
			}).done(function(data){
				options.total = data.total;
				pageInfo();
				loadData(options.currentPage);
				console.log(options);
			});
			//gan su kien 
			setCurrentPage(options.currentPage);

			btnPrevious.on("click",function(e){
				goPrevious();
			});

			btnNext.on("click",function(e){
				goNext();
			});

			txtCurrentPage.on("keyup",function(e){
				if(e.keyCode == 13)
				{
					var currentPageValue = parseInt($(this).val());
					options.currentPage = currentPageValue;
					pageInfo();
					if(isNaN(currentPageValue))
						alert("Gia tri khong hop le");
					else
						loadData(currentPageValue);
				
				}
			});

		}

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
						init();
					}
					else
						alert('Error');
				},
				error: function(){
					alert('Could not add data');
				}
			});
		});

		//xoa user
		$('.showAllUser').on('click','.btndel',function(){
			var id = $(this).attr('data');
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
							init();
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
		$('.showAllUser').on('click','.btnedit',function(){
			var id = $(this).attr('data');
			$('#mymodal').modal('show');
			$('#mymodal').find('.modal-title').text('Edit User');
			$('#myform').attr('action','http://localhost/account/Account/updateUser');
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: 'http://localhost/account/Account/editUser',
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

		//su kien khi nhan previous
		function goPrevious(){
			if(options.currentPage != 1)
			{
				p = options.currentPage-1;
				setCurrentPage(p);
				loadData(p);
				options.currentPage=p;
				pageInfo();
			}

		}

		//su kien khi nhan next
		function goNext(){
			if(options.currentPage != options.total)
			{
				p = options.currentPage+1;
				setCurrentPage(p);
				loadData(p);
				options.currentPage=p;
				pageInfo();
			}
		}

		//gan gia tri trang vao text box
		function setCurrentPage(value){
			txtCurrentPage.val(value);
		}

		//thong tin trang
		function pageInfo(){
			lblPageInfo.text("Page "+ options.currentPage +" of "+ options.total);
		}

		//thong tin data
		function loadData(page){
			console.log("loadData");
			$.ajax({
				url: "http://localhost/account/Account/list",
				type: "POST",
				dataType: "json",
				cache: false,
				data: {
					items			: options.items,
					currentPage 	: page
				}
			}).done(function(data){
				console.log(data);
				if(data.length > 0)
				{
					showalluser.empty();
					var a = options.items*page-options.items;
					$.each(data,function(i,val){
						++a;
						var html = "";
						if(val.level == 1)
							var lev = "Member";
						else
							var lev = "Admin";

						html += "<tr>"+
									"<td>"+a+"</td>"+
									"<td>"+val.username+"</td>"+
									"<td>"+val.fullname+"</td>"+
									"<td>"+val.gioitinh+"</td>"+
									"<td>"+lev+"</td>"+
									"<td><a class='btn btn-info btnedit' data-toggle='modal' id='btnedit' data='"+val.id+"' href=''>Edit</a></td>"+
									"<td><a class='btndel btn btn-danger' data-toggle='modal' id='btndel' data='"+val.id+"' href=''>Delete</a></td>"+
								"</tr>";
						//var str = '<li>' + val.id + ' - ' + val.username + '</li>';
						showalluser.append(html);
					});
				}
			});
		}
	}
})(jQuery);

$(document).ready(function(e){
	var obj = {};
	$('.showAllUser').paging(obj);


});
