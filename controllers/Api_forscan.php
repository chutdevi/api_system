<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_forscan extends CI_Controller {

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
 		$this->load->model('Ex_scnModel','md');

    }

	public function index()
	{
		
	}


    public function scna_cklocation(){ echo json_encode( $this->md->scn_loc() ); }

	public function scna_ckitemlocation(){ echo json_encode( $this->md->scn_imt() ); }

    public function scna_cklocation_fa(){ echo json_encode( $this->md->scn_loc_mssql() ); }

    public function scna_mvlocation_fa(){ echo json_encode( $this->md->upd_loc_mssql() ); }




}
