<?php 
/**
 * 
 */
class Employee_M extends CI_Model
{
	
	public function employeeList()
	{
		$this->db->select('id,username,fullname,gioitinh,roleid');
		$this->db->from('user');
		$query = $this->db->get();
		return $query->result_array();
	}
}
?>