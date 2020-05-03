<?php
header("Content-Type:text/html; charset=UTF-8");
require_once dirname(__FILE__) . '/sql/prdsys.php';
date_default_timezone_set("Asia/Bangkok");
//ini_set('mssql.charset', 'UTF-8');
class PrdsysModel extends Main_Model
{
    var $RECSYS;
    public function __construct()
        {
            parent::__construct();
            ## asset config
            //session_destroy();
            ob_clean();
            flush();
            $this->PRDSYS = new PRODUCTIONSYSTEM();

        }

    public function prdsys_prdline_model($d="", $p="")
        { 
            if( $d == "" && $p == ""){ 
                 $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ; 
                 $p = ( $this->input->get("p") ) ?  $this->input->get("p") : 'PD02' ;
             } 
            $p = ( $p == "PL00" ) ? "LG00" : $p;
            $ex   = $this->expk_load();
            $exp  = $this->exec( $ex, $this->PRDSYS->ORACLE_GETDATA_PRODLINE( $d, $p ) ); 
            return $exp;
        }
    public function prdsys_prdloss_model($d="", $p="")
        {
            if( $d == "" && $p == ""){ 
                $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ; 
                $p = ( $this->input->get("p") ) ?  $this->input->get("p") : 'PD02' ;
             } 
            $fa   = ( $p == "PD06" || $p ==  "PL22") ? $this->fab_load() : $this->fak_load();
            $exp  = $this->exec( $fa, $this->PRDSYS->DB2_GETDATA_FALOSS( $d, $p ) ); 
            return $exp;
        }
    public function prdsys_prdtime_model($d="", $p="")
        { 
            if( $d == "" && $p == ""){ 
                $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ; 
                $p = ( $this->input->get("p") ) ?  $this->input->get("p") : 'PD02' ;
            } 
            $fa   = ( $p == "PD06" || $p ==  "PL22") ? $this->fab_load() : $this->fak_load();
            $exp  = $this->exec( $fa, $this->PRDSYS->DB2_GETDATA_PRODTIME( $d, $p ) ); 
            return $exp;
        }   
    public function prdsys_prdlinecy_model($d="")
        { 
            if( $d == "" ){ 
                $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ;  
            } 
            $ex   = $this->expk_load(); 
            $exp  = $this->exec( $ex, $this->PRDSYS->ORA_GETDATA_LINECYCLETIME( $d ) ); 
            return $exp;
        }   
    public function prdsys_prdhist_model($d="")
        { 
            if( $d == "" ){ 
                $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ;  
            } 
            $ex   = $this->expk_load(); 
            $exp  = $this->exec( $ex, $this->PRDSYS->ORA_GETDATA_PRODHISTORY( $d ) ); 
            return $exp;
        }  

    public function prdsys_prdsumy_model( $d="", $p="")
        {
            if( $d == "" && $p == ""){ 
                $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ; 
                $p = ( $this->input->get("p") ) ?  $this->input->get("p") : 'PD02' ;
            } 
			$yd = date('Y/m/d' , strtotime("- 1 day", strtotime($d)) );        


            $exd = $this->prdsys_prdline_model($d , $p);
            $fat = $this->prdsys_prdtime_model($yd, $p);
            $fal = $this->prdsys_prdloss_model($yd, $p);
            $cyt = $this->prdsys_prdlinecy_model($d);
            $smd = $exd;
            foreach($exd as $i => $v) {
                $smd[$i] = $this->prdsys_setprd_time($smd[$i], $fat, $v["LINE_CD"]);
                $smd[$i] = $this->prdsys_setprd_loss($smd[$i], $fal, $v["LINE_CD"]);
                $smd[$i] = $this->prdsys_setprd_cytm($smd[$i], $cyt, $v["LINE_CD"]);
            }
             
            return $smd;
        }
    public function prdsys_prdreport_model( $d="" )
        {
 
            $d = ( $this->input->get("d") ) ?  $this->input->get("d") : date('Y/m/d') ;  
            $rpt = array( "data" => array( "PD01" => $this->prdsys_prdsumy_model($d, "PD01")
                                          ,"PD02" => $this->prdsys_prdsumy_model($d, "PD02")
                                          ,"PD03" => $this->prdsys_prdsumy_model($d, "PD03")
                                          ,"PD04" => $this->prdsys_prdsumy_model($d, "PD04")
                                          ,"PD05" => $this->prdsys_prdsumy_model($d, "PD05")
                                          ,"PD06" => $this->prdsys_prdsumy_model($d, "PD06")
                                          ,"LG00" => $this->prdsys_prdsumy_model($d, "PL00")
                                          ,"PL00" => $this->prdsys_prdsumy_model($d, "PL22")  ),
                          "hist" => $this->prdsys_prdhist_model($d)
                        );
            return $rpt;
        }        
    private function prdsys_setprd_time($dset, $cdata, $key)
        { 
            foreach($cdata as $k => $val) {
                if( $val["LINE_CD"] == $key ) {

                    $dset["AGOTOTLTIME"]  =  ( $val["YDPRODMIN"] != null )? $val["YDPRODMIN"] : "0";
                    $dset["AGOTOTLBAKE"]  =  ( $val["YDBREAKTM"] != null )? $val["YDBREAKTM"] : "0"; 

                    $dset["YSDTOTLTIME"]  =  ( $val["TDPRODMIN"] != null )? $val["TDPRODMIN"] : "0";
                    $dset["YSDTOTLBAKE"]  =  ( $val["TDBREAKTM"] != null )? $val["TDBREAKTM"] : "0"; 

                    $dset["ACCM_TIME"] =  ( $val["PRODMIN_ACCM"] != null )? $val["PRODMIN_ACCM"] : "0";
                    $dset["ACCM_BRKE"] =  ( $val["BREAKTMACCM"]  != null )? $val["BREAKTMACCM"]  : "0"; 

                    return  $dset;
                } 
            } 

            return $dset;
        }

    private function prdsys_setprd_loss($dset, $cdata, $key)
        {
            foreach($cdata as $k => $val)
            {
                foreach($cdata as $k => $val) {
                    if( $val["LINE_CD"] == $key ) {
                        $dset["AGOTOTLLOSS"]  =  ( $val["ALOSS"] != null )? $val["ALOSS"] : "0";
                        $dset["YSDTOTLLOSS"]  =  ( $val["YLOSS"] != null )? $val["YLOSS"] : "0";
                        $dset["ACCM_LOSS"]    =  ( $val["LOSS_ACCM"] != null )?$val["LOSS_ACCM"] : "0"; 
                    }
                }                
            } 
            return $dset;       
        }
    private function prdsys_setprd_cytm($dset, $cdata, $key)
        {
            foreach($cdata as $k => $val)
            {
                foreach($cdata as $k => $val) {
                    if( $val["LINE_CD"] == $key ) {
    
                        $dset["AGOTOTLCYTM"]  =  ( $val["AGOLINE_USETM"] != null && $val["CYCELTM"] < 998 )? $val["AGOLINE_USETM"] : "0";
                        $dset["YSDTOTLCYTM"]  =  ( $val["YSDLINE_USETM"] != null && $val["CYCELTM"] < 998 )? $val["YSDLINE_USETM"] : "0";
                        $dset["ACCM_CYTM"]    =  ( $val["LINE_USETM"]    != null && $val["CYCELTM"] < 998 )? $val["LINE_USETM"]    : "0";
    
                    }
                }                
            } 
            return $dset;       
        }
    public function holidays_data_frommonth( $d="", $c="" )
        {

            $d = ( $this->input->get("d") && $d == "" ) ?  $this->input->get("d") : date('Y/m/d') ;
            $c = ( $this->input->get("c") && $c == "" ) ?  $this->input->get("c") : '+ 0 day' ; 
 
            $ex  = $this->expk_load(); 
            $mth = date('Y/m', strtotime($c,  strtotime( $d ) )) ;
            $sql_expk   =  $this->exec( $ex, $this->PRDSYS->ORACLE_GETDATE_HOLIDAYS_FM( $mth) );  
 
            return $sql_expk;             
        }
    public function workdays_data_saturday($d="", $c="")
        {

            $d = ( $this->input->get("d") && $d == "" ) ?  $this->input->get("d") : date('Y/m/d') ;
            $c = ( $this->input->get("c") && $c == "" ) ?  $this->input->get("c") : '+ 0 day' ; 
 
            $ex  = $this->expk_load(); 
            $mth = date('Y/m', strtotime($c,  strtotime( $d ) )) ;
            $sql_expk   =  $this->exec( $ex, $this->PRDSYS->ORACLE_GETDATE_SATURDAY_WD( $mth ) );  
 
            return $sql_expk;               
        }

    public function workdays_data_calenda( $d='2020/02/01', $s, $e )
        {
            $d = ( $this->input->get("d") && $d == "" ) ?  $this->input->get("d") : date('Y/m/d') ;
            $s = ( $this->input->get("s") && $s == "" ) ?  $this->input->get("s")  :1 ; 
            $e = ( $this->input->get("e") && $e == "" ) ?  $this->input->get("e") : 2 ; 
 
            $ex  = $this->expk_load();  
            $sql_expk   =  $this->exec( $ex, $this->PRDSYS->ORACLE_GETDATE_CALENDA_WD( $d, $s, $e ) );  
 
            return $sql_expk;           
        }        


    public function insert_report_prd_list()
        {
            $dirfile = ( $this->input->get("f") ) ? $this->input->get("f") : "";
            $str_result="";
            if ( !empty($dirfile) ){
                $mysql = $this->user_load();
                if ( file_exists($dirfile) ){

                  $rf    = (strlen($dirfile) > 71 ) ? substr( basename( $dirfile, ".xlsx" ), 0, -3) :  basename( $dirfile, ".xlsx" ) ;  
                  $fid   = sprintf("SYSTEM00%s",substr( $rf, -8));
                  //echo $fid . "c => " . strlen($dirfile); exit;
                  $cfile =  $this->exec( $mysql, $this->PRDSYS->MYSQL_GETSEQ_REPORTLIST( $fid ) )[0]["NSEQ"] ; 
                  $cfile = ($cfile == null) ? "001" : $cfile; 
                  
                  $str_result = sprintf("'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'"
                                        , $fid
                                        , basename( $dirfile, ".xlsx" )
                                        , $cfile
                                        , round( filesize($dirfile) / 1024 , 0)
                                        , "xlsx"
                                        , $dirfile
                                        , date( 'Y-m-d H:i:s',  fileatime($dirfile) )
                                        ,"SYSTEM00"
                                        , basename( dirname( $dirfile, 1) )
                                        ,"PRODREPORT"
                                        ,date('Y-m-d H:i:s')
                                        );
                }
                
                $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_INSERT_REPORTLIST($str_result) );  

                return $this->exec( $mysql, $this->PRDSYS->MYSQL_GETNDATA_REPORTLIST( $fid, $cfile ) ) ;
            }else{
                return array( array("NSEQ"=>0) );
            } 
        }
    public function getdata_report_prd_seq()
        {
            $dirfile = ( $this->input->get("f") ) ? $this->input->get("f") : ""; 
            $mysql = $this->user_load();
            $fid   = sprintf("SYSTEM00%s",substr( basename( $dirfile, ".xlsx" ),-8));
            $cfile =  $this->exec( $mysql, $this->PRDSYS->MYSQL_GETSEQ_REPORTLIST( $fid ) )[0]["NSEQ"] ; 

            return $cfile;

        }        
    public function getdata_report_prd_list()
        {
            $gst = ( $this->input->get("s") ) ? $this->input->get("s") : date('Y-m-01');
            $gen = ( $this->input->get("e") ) ? $this->input->get("e") : date('Y-m-t');
            $mysql = $this->user_load();
            $cfile = $this->exec( $mysql, $this->PRDSYS->MYSQL_GETDATA_REPORTLIST( $gst, $gen ) )  ; 
            return $cfile;
        }
}
?> 
