<?php 
/**
 * 
 */
class Account_M extends CI_Model
{
	protected $_table = 'user';
	function __construct()
	{
		parent::__construct();
	}
	//hien thi toan bo user
	public function showAllUser()
	{
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	//them moi user
	public function addNewUser($array_newuser)
	{
		$this->db->insert($this->_table,$array_newuser);
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	//kiem tra user ton tai chua
	public function checkUser($username,$id="")
	{
		if($id != "")
		{
			$this->db->where("id !=",$id);
		}
		$this->db->where('username',$username);
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0)
			return false;
		else
			return true;

	}

	//xoa user
	public function deleteUser()
	{
		$id = $this->input->get('id');
		$this->db->where('id',$id);
		$this->db->delete($this->_table);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}

	//lay thong tin user dc chon
	public function editUser()
	{
		$id = $this->input->get('id');
		$this->db->where('id',$id);
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
	}

	//cap nhat user
	public function updateUser()
	{
		$id = $this->input->post('id');
		$array_edit_user = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'fullname' => $this->input->post('fullname'),
			'gioitinh' => $this->input->post('gioitinh'),
			'level' => $this->input->post('level'),
		);
		$this->db->where('id',$id);
		$this->db->update($this->_table,$array_edit_user);
		//if($this->db->affected_rows() > 0)
			return true;
		//return false;
	}

	//search theo user name
	public function search()
	{
		$var_text = $this->input->post('var_text');
		$this->db->select('username,fullname,gioitinh,level');
		$this->db->like('username',$var_text);
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0)
			return $query->result_array();
		else
			return false;
	}

	//dem so luong user
	public function countAll()
	{
		return $this->db->count_all($this->_table);
	}

	public function listAll($offset,$start)
	{
		$this->db->limit($offset,$start);
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function checkLogin()
	{
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$this->db->where("username",$username);
		$this->db->where("password",$password);
		$this->db->where("level","2");
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0)
		{
			$this->session->set_userdata('user',$username);
			return true;
		}
		return false;
	}
}
?>