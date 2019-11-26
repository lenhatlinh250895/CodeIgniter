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
		$this->perPage = 3;
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
		if($this->form_validation->run() == FALSE)
		{
			echo json_encode(validation_errors());
		}
		else
		{
			$result = $this->m->addNewUser();
			$mess['type'] = 'add';
			if($result == true)
				$mess['success'] = true;
			echo json_encode($mess);
		} 
	}

	//xoa user
	public function deleteUser()
	{
		$result = $this->m->deleteUser();
		$mess['success'] = false;
		if($result)
			$mess['success'] = true;
		echo json_encode($mess);
	}

	//lay thong tin user dc chon
	public function editUser()
	{
		$result = $this->m->editUser();
		echo json_encode($result);
	}

	//cap nhat user
	public function updateUser()
	{
		$mess['success'] = false;
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','User name','required|min_length[5]|max_length[60]|callback_check_user');
		$this->form_validation->set_rules('password','Pass Word','required|min_length[6]|max_length[60]');
		$this->form_validation->set_rules('repassword','Re Pass','required|min_length[6]|max_length[60]|matches[password]');
		$this->form_validation->set_rules('fullname','Full Name','required|min_length[6]|max_length[60]');
		if($this->form_validation->run() == TRUE)
		{
			$result = $this->m->updateUser();
			$mess['type'] = 'update';
			if($result)
				$mess['success'] = true;
			echo json_encode($mess);
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

	public function countAll()
	{
		$items = (int)$_GET["items"];
		$totalitem = $this->m->countAll();
		$page = $totalitem/$items;
		$array_total = array("total" => 0);
		$tam = explode(".", $page);
		if(count($tam) > 1)
			$page = $tam[0]+1;
		else
			$page = $tam[0];
		$array_total["total"] = $page;
		echo json_encode($array_total);
	}

	public function list()
	{
		$items = (int)$_POST["items"];
		$currenpage = (int)$_POST["currentPage"];
		$offset = ($currenpage - 1) * $items;
		$result = $this->m->listAll($items,$offset);
		echo json_encode($result);
		// echo "<pre>";
		// echo print_r($result);
		// echo "</pre>";
	}

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

		$output = array(
			"pagination_link" => $this->pagination->create_links(),
			'data_table'	  => $this->m->listAll($config["per_page"],$start)
		);
		echo json_encode($output);
	}

	public function isLoggedIn()
	{
		$isLoggedIn = $this->session->userdata('user');
		if(!isset($isLoggedIn)){
			redirect(base_url().'Account/login');
		}
	}
	public function isLoggedOut()
	{
		$isLoggedIn = $this->session->userdata('user');
		if(isset($isLoggedIn)){
			redirect(base_url().'Account');
		}
	}

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

	public function logout()
	{
		$this->session->unset_userdata('user');
		echo 1;
	}
}
?>