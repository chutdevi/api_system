<?php
header("Content-Type:text/html; charset=UTF-8");
require_once dirname(__FILE__) . '/sql/recsys.php';
date_default_timezone_set("Asia/Bangkok");
//ini_set('mssql.charset', 'UTF-8');
class RecsysModel extends Main_Model
{
    var $RECSYS;
    public function __construct()
        {
            parent::__construct();
            ## asset config
            //session_destroy();
            ob_clean();
            flush();
            $this->RECSYS = new RECEIVE();

        }

    public function recsys_planreceive_model()
        {
            $ex = $this->expk_load();
            $codi  = ( $this->input->post("d") ) ?  $this->input->post("d") : date('Y/m/d') ;
            $plant = ( $this->input->post("plant") ) ? sprintf("AP.PLANT_CD = '%s'", $this->input->post("plant") ) : "" ; 
            $far = $this->exec( $ex, $this->RECSYS->ORACLE_GETDATA_PLANRECEIVE($codi, $plant) ); 
            return $far;
        }
    public function recsys_cntplanreceive_model()
        {
            $ex = $this->expk_load();
            $codi = ( $this->input->post("d") ) ? $_POST["d"] : date('Y/m/d') ; 
            $far = array( "51" =>  $this->exec( $ex, $this->RECSYS->ORACLE_GETDATA_PLANRECEIVE_COUNT($codi, 51 ) )[0]
                        , "52" =>  $this->exec( $ex, $this->RECSYS->ORACLE_GETDATA_PLANRECEIVE_COUNT($codi, 52 ) )[0]
                        );
            return $far;
        }        

    private function dirfile_create($dr, $fn, $ty , $d)
        {
			if( is_dir($dr) === false ) mkdir($dr);
			$dr = sprintf("%s/%s/", $dr, date('Ym') );
			if( is_dir($dr) === false ) mkdir($dr);
			$filename = sprintf("%s%s.%s", $dr, $fn ."-". $d , $ty ); 
            return $filename;
        }

    

}
?> 
