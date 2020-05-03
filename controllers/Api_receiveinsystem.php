<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_receiveinsystem extends CI_Controller {

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
 		$this->load->model('Ex_recModel','md');

    }

	public function index()
	{
		
	}



	public function rece_in(){ echo json_encode(  array( $this->md->rec_in()   )   ); }
    public function rece_ev(){ echo json_encode(  array( $this->md->rec_even() )   ); }


    public function rece_mv(){ echo json_encode(  array( $this->md->rec_move() )   ); }


    public function rece_tg(){ echo json_encode(  array( $this->md->rec_ptag() )   ); }


    public function rece_lo(){ $this->md->rec_loca(); }


    public function get_tag(){ echo json_encode(   array( $this->md->rec_get_tag() )   ); }


    public function upd_rct(){ echo json_encode(   array( $this->md->rec_upd_qrctl() )   ); }

    public function upd_pro(){ echo json_encode(   array( $this->md->rec_upd_flgpro() )   ); }

    public function test_fn(){ echo json_encode(   array( $this->md->test_function() )   ); }

}
