<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_shipping extends CI_Controller {

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
 		$this->load->model('Ex_shipModel','md');

    }

	public function index()
	{
		
	}


    public function ship_item(){ echo json_encode( $this->md->ship_item() ); }

    public function ship_data(){ echo json_encode( $this->md->ship_data() ); }





}
