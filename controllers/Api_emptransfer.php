
<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_emptransfer extends CI_Controller {

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
 		$this->load->model('Ex_empModel','md');


    }

	public function index()
	{
		
	}



	public function user_ck()
	{

		$data = $this->input->get('user');

        echo json_encode(  $this->md->emp_center($data) );

	}

    public function usersys(){ echo json_encode( $this->md->empsys( ) ); }

    public function user_pin(){ echo $this->md->emp_regispin( $this->input->get('user'), $this->input->get('upin') ) ; }

    public function user_pinlogin(){ echo $this->md->emp_loginpin( $this->input->get('user'), $this->input->get('upin') ) ; }





}
