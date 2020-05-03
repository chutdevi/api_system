<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_receiveinmanage extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //header('Content-Type: application/json');
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
        $tz_object = new DateTimeZone('+0700');
        $datetime  = new DateTime();
        $datetime->setTimezone($tz_object);
 		$this->load->model('Ex_lismodel','md');

    }

	public function index()
	{
		
	}



	public function list_acpt(){ echo json_encode(  $this->md->lis_acpt()   ); }


    public function list_atag(){ echo json_encode(  $this->md->lis_atag()   ); }


    public function list_mtag(){ echo json_encode(  $this->md->lis_mtag()   ); }







}
