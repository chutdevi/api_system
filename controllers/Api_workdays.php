<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_workdays extends CI_Controller {

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
 		$this->load->model('Ex_daysModel','md');

    }

	public function index()
	{
		
	}

    public function workdays_pdmonth(){ echo json_encode( $this->md->days_pdwork( $this->input->post('dets') ) ); }
    public function workdays_lnmonth(){ echo json_encode( $this->md->days_lnwork( $this->input->post('dets'), $this->input->post('pd') ) ); }
    public function workdays_olmonth(){ echo json_encode( $this->md->days_olwork( $this->input->post('dets'), $this->input->post('pd') ) ); }

    public function workdays_lnmonthac(){ echo json_encode( $this->md->days_lnworkac( $this->input->post('dets'), $this->input->post('pd') ) ); }
    public function workdays_olmonthac(){ echo json_encode( $this->md->days_olworkac( $this->input->post('dets'), $this->input->post('pd') ) ); }


    public function workdays_acmonth(){ echo json_encode( $this->md->days_acwork()  ); }

}
