<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Content-Type: application/json;charset=utf-8");
date_default_timezone_set("Asia/Bangkok");
class Api_del_plan extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        ob_clean();
        flush(); 
 		$this->load->model('Del_plan_model','md');

    }

	public function index()
	{
		echo "wow"; exit();
	}

    public function del_plan_data(){  echo json_encode( $this->md->get_exp()  ); }  

    public function inv_data(){  echo json_encode( $this->md->get_ship()  ); }  

    public function sum_del_data(){  echo json_encode( $this->md->sum_del_data()  ); } 

}
 