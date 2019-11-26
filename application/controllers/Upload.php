<?php 
/**
 * 
 */
class Upload extends CI_Controller
{
	
	public function index()
	{
		$this->load->view('upload/upload');
	}

	public function do_upload()
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;
		$this->load->library('upload',$config);
		if($this->upload->do_upload('image'))
		{
			$fi = $this->upload->data();
			print_r($fi['file_name']);
		}
		else
		{
			print_r($this->upload->display_errors());
		}
	}
}
?>