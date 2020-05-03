<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Content-Type: application/json;charset=utf-8");
date_default_timezone_set("Asia/Bangkok");
class Api_recsys extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        ob_clean();
        flush(); 
 		$this->load->model('RecsysModel','md');

    }

	public function index()
	{
		
	}

    public function recsys_planreceive(){  echo json_encode( $this->md->recsys_planreceive_model()  ); } 

    public function recsys_cntplanreceive(){  echo json_encode( $this->md->recsys_cntplanreceive_model()  ); } 

    

}
 