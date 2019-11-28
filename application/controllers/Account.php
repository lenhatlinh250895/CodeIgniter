<?php 
/**
 * 
 */
class Account extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Account_M','m');
		$this->load->library('encryption');
	}

	//trang hien thi
	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('account/index');
		$this->load->view('layout/footer');
		$this->isLoggedIn();
	}

	public function login()
	{
		$this->load->view("layout/header");
		$this->load->view("login/login");
		$this->load->view("layout/footer");
		$this->isLoggedOut();
	}

	//hien thi toan bo user
	public function showAllUser()
	{
		$page = $this->input->post('page');
		$config['per_page'] = $this->perPage;
		$data['info'] = $this->m->listAll($config['per_page'],$page);
		echo json_encode($data);
	}

	//kiem tra user ton tai hay chua
	public function check_user($username)
	{
		$id = $this->uri->segment(3);
		$result = $this->m->checkUser($username,$id);
		if($result == false)
		{
			$this->form_validation->set_message("check_user","Your username has been registed, please try again!");
			return false;
		}
		else
			return true;
	}

	//them moi user
	public function addNewUser()
	{
		$mess['success'] = false;
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','User name','required|min_length[5]|max_length[60]|callback_check_user');
		$this->form_validation->set_rules('password','Pass Word','required|min_length[6]|max_length[60]|trim');
		$this->form_validation->set_rules('repassword','Re Pass','required|min_length[6]|max_length[60]|matches[password]|trim');
		$this->form_validation->set_rules('fullname','Full Name','required|min_length[6]|max_length[60]|trim');
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'jpg|png|gif';
		$config['encrypt_name'] = true;
		$this->load->library('upload',$config);
		//echo json_encode($this->encryption->encrypt('mypassword'));
		if($this->upload->do_upload('file'))
		{
			$fi = $this->upload->data();
			$array_newuser = array(
				'username' => $_POST['username'],
				'password' => $this->encryption->encrypt($_POST['password']),
				'fullname' => $_POST['fullname'],
				'gioitinh' => $_POST['gioitinh'],
				'level'    => $_POST['level'],
				'image'    => $fi['file_name'],
				'roleid'   => $_POST['level']
			);
		}
		else
			$array_newuser = array(
				'username' => $_POST['username'],
				'password' => $this->encryption->encrypt($_POST['password']),
				'fullname' => $_POST['fullname'],
				'gioitinh' => $_POST['gioitinh'],
				'level'    => $_POST['level'],
				'image'    => '',
				'roleid'   => $_POST['level']
			);
		if($this->form_validation->run() == FALSE)
		{
			echo json_encode(validation_errors());
		}
		else
		{
			$result = $this->m->addNewUser($array_newuser);
			$mess['type'] = 'add';
			if($result == true)
				$mess['success'] = true;
			echo json_encode($mess);
		} 
	}

	//xoa user
	public function deleteUser()
	{
		$id = $this->input->get('id');
		$row = $this->m->searchUser($id);
		$path_file = 'uploads/'.$row->image;
		unlink($path_file);
		$result = $this->m->deleteUser($id);
		$mess['success'] = false;
		if($result)
		{
			$mess['success'] = true;
		}
		echo json_encode($mess);
	}

	//lay thong tin user dc chon
	public function editUser()
	{
		$result = $this->m->editUser();
		$result->password = $this->encryption->decrypt($result->password);
		echo json_encode($result);
	}

	//cap nhat user
	public function updateUser()
	{
		$id = $this->input->post('id');
		$mess['success'] = false;
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','User name','required|min_length[5]|max_length[60]|callback_check_user');
		$this->form_validation->set_rules('password','Pass Word','required|min_length[6]|max_length[60]');
		$this->form_validation->set_rules('repassword','Re Pass','required|min_length[6]|max_length[60]|matches[password]');
		$this->form_validation->set_rules('fullname','Full Name','required|min_length[6]|max_length[60]');
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'jpg|png|gif';
		$config['encrypt_name'] = true;
		if($this->form_validation->run() == TRUE)
		{
			if(isset($_FILES['file']['name']))
			{
				$this->load->library('upload',$config);
				if($this->upload->do_upload('file'))
				{
					$fi = $this->upload->data();
					$array_edit_user = array(
						'username' => $_POST['username'],
						'password' => $this->encryption->encrypt($_POST['password']),
						'fullname' => $_POST['fullname'],
						'gioitinh' => $_POST['gioitinh'],
						'level'    => $_POST['level'],
						'image'	   => $fi['file_name'],
						'roleid'   => $_POST['level']

					);
					$row = $this->m->searchUser($id);
					$path_file = 'uploads/'.$row->image;
					unlink($path_file);
					$result = $this->m->updateUser($array_edit_user);
					$mess['type'] = 'update';
					if($result)
						$mess['success'] = true;
					echo json_encode($mess);
				}
			}
			else
			{
				$array_edit_user = array(
					'username' => $_POST['username'],
					'password' => $this->encryption->encrypt($_POST['password']),
					'fullname' => $_POST['fullname'],
					'gioitinh' => $_POST['gioitinh'],
					'level'    => $_POST['level'],
					'roleid'   => $_POST['level']
				);
				$result = $this->m->updateUser($array_edit_user);
				$mess['type'] = 'update';
				if($result)
					$mess['success'] = true;
				echo json_encode($mess);
			}
		}
		else
		{
			echo json_encode(validation_errors());
		}
	}

	//search theo user name
	public function search()
	{
		$result = $this->m->search();
		$output = '';
		if($result != false)
		{
			$output .= '<div class="table-responsive">
					   <table class="table table bordered">
					   	   <tr>
					   	       <th>User Name</th>
					   	       <th>Full Name</th>
					   	       <th>Image</th>
					   	       <th>Gioi Tinh</th>
					   	       <th>Level</th>
					   	   </tr>';
			foreach ($result as $row) {
				if($row["level"] == 1)
					$lev = "Member";
				else
					$lev = "Admin";
				$output .= '<tr>
					   	       <td>'.$row["username"].'</td>
					   	       <td>'.$row["fullname"].'</td>
					   	       <td><img src="'.base_url().'uploads/'.$row["image"].'" height="100px"></td>
					   	       <td>'.$row["gioitinh"].'</td>
					   	       <td>'.$lev.'</td>
					   	   </tr>';
			}
			$output .= '</table>
					</div>';
		}
		else
			$output .= 'Data not Found';
		
		echo $output;
	}

	// public function countAll()
	// {
	// 	$items = (int)$_GET["items"];
	// 	$totalitem = $this->m->countAll();
	// 	$page = $totalitem/$items;
	// 	$array_total = array("total" => 0);
	// 	$tam = explode(".", $page);
	// 	if(count($tam) > 1)
	// 		$page = $tam[0]+1;
	// 	else
	// 		$page = $tam[0];
	// 	$array_total["total"] = $page;
	// 	echo json_encode($array_total);
	// }

	// public function list()
	// {
	// 	$items = (int)$_POST["items"];
	// 	$currenpage = (int)$_POST["currentPage"];
	// 	$offset = ($currenpage - 1) * $items;
	// 	$result = $this->m->listAll($items,$offset);
	// 	echo json_encode($result);
	// 	// echo "<pre>";
	// 	// echo print_r($result);
	// 	// echo "</pre>";
	// }

	public function pagination()
	{
		$page = $this->uri->segment(3);
		$this->load->library("pagination");
		$config = array();
		$config["base_url"] = "#";
		$config["total_rows"] = $this->m->countAll();
		$config["per_page"] = 4;
		$config["uri_segment"] = 3;
		$config["use_page_numbers"] =	true;
		$config["full_tag_open"] = "<ul class='pagination'>";
		$config["full_tag_close"] = "</ul>";
		$config["first_tag_open"] = "<li>";
		$config["first_tag_close"] = "</li>";
		$config["last_tag_open"] = "<li>";
		$config["last_tag_close"] = "</li>";
		$config["next_link"] = "&gt;";
		$config["next_tag_open"] = "<li>";
		$config["next_tag_close"] = "</li>";
		$config["prev_link"] = "&lt;";
		$config["prev_tag_open"] = "<li>";
		$config["prev_tag_close"] = "</li>";
		$config["cur_tag_open"] = "<li class='active'><a href='#'>";
		$config["cur_tag_close"] = "</a></li>";
		$config["num_tag_open"] = "<li>";
		$config["num_tag_close"] = "</li>";
		$config["num_links"] = 1;

		$this->pagination->initialize($config);

		
		$start = ($page-1)*$config["per_page"];

		$result = $this->m->listAll($config["per_page"],$start);
		$html = "";
		$html .= '
		<table class="tableeee table table-bordered table-responsive" style="margin-top: 20px;">
			<tr>
				<th>STT</th>
				<th>Name</th>
				<th>Full Name</th>
				<th>Image</th>
				<th>Gioi Tinh</th>
				<th>Level</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		';
		$stt = $start;
		foreach ($result as $row) {
			$stt++;
			$html .= ' 
			<tr>
				<td>'.$stt.'</td>
				<td>'.$row->username.'</td>
				<td>'.$row->fullname.'</td>
				<td><img src="'.base_url().'uploads/'.$row->image.'" height="100px"></td>
				<td>'.$row->gioitinh.'</td>
				<td>'.$row->role.'</td>
				<td><a class="btn btn-info btnedit" data-toggle="modal" id="btnedit" data="'.$row->id.'" href="">Edit</a></td>
				<td><a class="btndel btn btn-danger" data-toggle="modal" id="btndel" data="'.$row->id.'" href="">Delete</a>
				</td>
			</tr>
			';
		}
		$html .= '</table>';
		$output = array(
			"pagination_link" => $this->pagination->create_links(),
			'data_table'	  => $html
		);
		echo json_encode($output);
	}

	//chuyen huong khi da dang nhap
	public function isLoggedIn()
	{
		$isLoggedIn = $this->session->userdata('user');
		if(!isset($isLoggedIn)){
			redirect(base_url().'Account/login');
		}
	}

	//chuyen huong khi chua dang nhap
	public function isLoggedOut()
	{
		$isLoggedIn = $this->session->userdata('user');
		if(isset($isLoggedIn)){
			redirect(base_url().'Account');
		}
	}

	//kiem tra dang nhap
	public function checkLogin()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','User Name','required');
		$this->form_validation->set_rules('password','Pass Word','required');
		if($this->form_validation->run() == FALSE)
			echo validation_errors();
		else
		{
			$result = $this->m->checkLogin();
			if($result == true)
			{
			 	echo 1;
			}
			else
			 	echo 0;
		}
	}

	//dang xuat
	public function logout()
	{
		$this->session->unset_userdata('user');
		echo 1;
	}

	//load level
	public function loadLevel()
	{
		$result = $this->m->loadLevel();
		$html = "";
		foreach($result as $row)
		{
			$html .= '<option id="'.$row->role.'" value="'.$row->roleid.'" >'.$row->role.'</option>';
		}
		echo json_encode($html);
	}
}
?>