<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Content-Type: application/json;charset=utf-8");
date_default_timezone_set("Asia/Bangkok");
class Api_event_reportaccess extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        ob_clean();
        flush(); 
 		$this->load->model('EventModel','md');

    }

	public function index()
	{
		
	}

    public function evtsys_eventlogin(){  echo json_encode( $this->md->getdata_user()  ); }   

    public function evtsys_getmenugp(){  echo json_encode( $this->md->getdata_menu_group()  ); }

    public function evtsys_eventadd(){  echo json_encode( $this->md->insert_event_list()  ); }

    public function evtsys_manage_memb(){ echo json_encode( $this->md->getdata_memb_group()  ); } 

    public function evtsys_login_member(){ echo json_encode( $this->md->update_system_login() ); }

    public function evtsys_uses_member(){ echo json_encode( $this->md->update_system_uses()  ); }

    public function evtsys_session_set(){ return $this->md->setdata_session(); }

    public function evtsys_session_get(){ echo json_encode( $this->md->getdata_session()  ); }

    public function evtsys_session_del(){ return $this->md->deldata_session(); }

    public function evtsys_session_del_name(){ return $this->md->deldata_session(); }

}
 