<?php 
/**
 * 
 */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Employee extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Employee_M');
	}

	public function index(){
		$data['page'] = 'export-excel';
		$data['title'] = 'Export excel data';
		$data['employeedata'] = $this->Employee_M->employeeList();
		$this->load->view('employee/employee',$data);
	}

	public function createExcel()
	{
		$filename = 'Employee.xlsx';
		$employeedata = $this->Employee_M->employeeList();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','ID');
		$sheet->setCellValue('B1','User Name');
		$sheet->setCellValue('C1','Full Name');
		$sheet->setCellValue('D1','Gioi Tinh');
		$sheet->setCellValue('E1','Role Id');
		$row = 2;
		foreach ($employeedata as $value) {
			$sheet->setCellValue('A'.$row,$value['id']);
			$sheet->setCellValue('B'.$row,$value['username']);
			$sheet->setCellValue('C'.$row,$value['fullname']);
			$sheet->setCellValue('D'.$row,$value['gioitinh']);
			$sheet->setCellValue('E'.$row,$value['roleid']);
			$row++;
		}
		$write = new Xlsx($spreadsheet);
		$write->save('uploads/'.$filename);
		//header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."Account"); 
	}

	public function import()
	{
		$data = array();
		$data['title'] = 'Import Excel Sheet';
		$data['breadcrumb'] = array('Home' => '#');
		$this->load->view('employee/index',$data);
	}

	public function upload()
	{
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($_FILES['fileURL']['tmp_name']);
		$alldatainsheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

		$arraycount = count($alldatainsheet);
		$flag = 0;
		$createarray = array('ID','User Name','Full Name','Gioi Tinh','Role Id');
		$makearray = array('ID'=>'ID','UserName'=>'UserName','FullName'=>'FullName','GioiTinh'=>'GioiTinh','RoleId'=>'RoleId');
		$sheetdatakey = array();
		foreach($alldatainsheet as $datainsheet)
		{
			foreach($datainsheet as $key => $value)
			{
				if (in_array(trim($value),$createarray)) {
					$value = preg_replace('/\s+/', '', $value);
					$sheetdatakey[trim($value)] =  $key;
				}
			}
		}
		$datadiff = array_diff_key($makearray, $sheetdatakey);
		if(empty($datadiff))
			$flag = 1;

		print_r($sheetdatakey['ID']);
	}

}
?>