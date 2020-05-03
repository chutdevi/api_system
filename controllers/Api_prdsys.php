<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Content-Type: application/json;charset=utf-8");
date_default_timezone_set("Asia/Bangkok");
class Api_prdsys extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        ob_clean();
        flush(); 
 		$this->load->model('PrdsysModel','md');

    }

	public function index()
	{
		
	}

    public function prdsys_prodline(){  echo json_encode( $this->md->prdsys_prdline_model()  ); }  

    public function prdsys_prodloss(){  echo json_encode( $this->md->prdsys_prdloss_model()  ); }  

    public function prdsys_prodtime(){  echo json_encode( $this->md->prdsys_prdtime_model()  ); }
    
    public function prdsys_prodlinecy(){  echo json_encode( $this->md->prdsys_prdlinecy_model()  ); }

    public function prdsys_prodhist(){  echo json_encode( $this->md->prdsys_prdhist_model()  ); }

    public function prdsys_prodsumy(){  echo json_encode( $this->md->prdsys_prdsumy_model()  ); }

    public function prdsys_prodrept(){  echo json_encode( $this->md->prdsys_prdreport_model()); }

    public function prdsys_prodholiday(){  echo json_encode( $this->md->holidays_data_frommonth()); }

    public function prdsys_prodsaturdy(){  echo json_encode( $this->md->workdays_data_saturday());  }

    public function prdsys_prodworkday(){  echo json_encode( $this->md->workdays_data_calenda());   } 

    public function prdsys_insert_prd_list(){  echo json_encode( $this->md->insert_report_prd_list()); }

    public function prdsys_getdata_prd_list(){  echo json_encode( $this->md->getdata_report_prd_list()); }

    public function prdsys_getdata_prd_seq(){  echo json_encode( $this->md->getdata_report_prd_seq()); } 
}
 