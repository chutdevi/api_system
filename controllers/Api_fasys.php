<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_fasys extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
        $tz_object = new DateTimeZone('+0700');
        $datetime  = new DateTime();
        $datetime->setTimezone($tz_object);
 		$this->load->model('FasysModel','md');
        header("Content-Type: application/json;charset=utf-8");
    }

	public function index()
	{
		
	}

    public function fasys_errordate(){  echo json_encode( $this->md->fasys_derror()  ); }

    public function fasys_errordate_f(){  echo json_encode( $this->md->fasys_derror_false()  ); }

    public function fasys_errordate_up(){  echo  $this->md->fasys_derror_update(); }


    public function fasys_prodbypd(){  echo  json_encode( $this->md->fasys_production_pd() ); }

    public function fasys_prodbycr(){  echo  json_encode( $this->md->fasys_production_cr() ); }

    public function fasys_prodbyal(){  echo  json_encode( $this->md->fasys_production_al() ); }

    public function ejsys_prod(){  echo  json_encode( $this->md->ejsys_production() ); }

    public function fasys_prodbyseqln(){  echo  json_encode( $this->md->fasys_production_seqln() ); }

    public function fasys_prodbyln(){  echo  json_encode( $this->md->fasys_production_ln() ); }

    public function fasys_prodbreak(){  echo  json_encode( $this->md->fasys_production_break() ); }    

    public function ejsys_ctime(){  echo  json_encode( $this->md->ejsys_cycletime() ); }


    public function fasys_prodcnt(){  echo  json_encode( $this->md->fasys_production_cnt() ); }
    public function fasys_prodallcnt(){  echo  json_encode( $this->md->fasys_production_linecnt() ); }

    public function alsys_prodbyln(){  echo  json_encode( $this->md->alsys_productionline() ); }

    

}
 