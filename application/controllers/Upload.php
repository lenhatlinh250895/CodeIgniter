<?php 
/**
 * 
 */
class Upload extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('encryption');
		$this->load->model('Account_M','m');
	}

	public function index()
	{
		$this->load->view('upload/upload');
	}

	public function do_upload()
	{
		// $config['upload_path'] = './uploads/';
		// $config['allowed_types'] = 'gif|jpg|png';
		// $config['encrypt_name'] = true;
		// $this->load->library('upload',$config);
		// //echo $this->encryption->encrypt('zxcasd');
		// echo $this->encryption->decrypt('f15598f9fe49ac5dbcfe0d65bdf692280911d16f19c91072978f169174283090bbc4571129a86c4db51f4ba4934022027a6c51e1fdbb1d12f9c714f76a16e665LbwB4U5S+n+qgTeObQa/yHOl1SkajvhqJbJicd4RtFY=');
		// if($this->upload->do_upload('image'))
		// {
		// 	$fi = $this->upload->data();
		// 	print_r($fi['file_name']);
		// }
		// else
		// {
		// 	print_r($this->upload->display_errors());
		// }
		$result = $this->m->asd();
		echo $result;
	}
}
?>