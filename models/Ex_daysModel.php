<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
require_once dirname(__FILE__) . '/sql/workdays.php';
class Ex_daysModel extends Main_Model
{
    var $query_str;
    var $loct = array();
    var $obj;
    public function __construct()
        {
            parent::__construct();
            ## asset config
            //session_destroy();
            ob_clean();
            flush();
          
            $this->obj = new WORKDAYSQUERY();
            //$this->fal_load();
            //$this->load->libraries('Pro_query','qr');  
        }

    public function days_pdwork($dats)
        {
            $this->expk_load();
            $sql_expk = "";
            if($_POST)
            {
                $sql_expk = $this->obj->ORACLE_GETDATA_PDWORKDAYS($dats);
            } 
            else 
            {
                $sql_expk = $this->obj->ORACLE_GETDATA_PDWORKDAYS('2020/01/01');            
            }//echo $sql_ship; exit;
            $exp_resu = $this->exec( $this->expk, $sql_expk );

            return array("data" => $exp_resu, "label" => array(date('F',strtotime($dats)), date('F',strtotime("+ 1 month",strtotime($dats))) ) );    
        }
    
    public function days_lnwork($dats, $pds)
        {
            $this->expk_load();
            //$sql_expk;
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_LINEWORKDAYS($dats, $pds) : $this->obj->ORACLE_GETDATA_LINEWORKDAYS('2020/03/01', 'K1PD01');
            $exp_resu = $this->exec( $this->expk, $sql_expk );
            
            return array("data" => $exp_resu);    
            
        }    
    
    public function days_olwork($dats, $pds)
        {
            $this->expk_load();
            //$sql_expk;
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_LINE_WORK($dats, $pds) : $this->obj->ORACLE_GETDATA_LINE_WORK('2020/03/01', 'K1PD01');
            $exp_resu = $this->exec( $this->expk, $sql_expk );
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_MAXLINE_WORK($pds) : $this->obj->ORACLE_GETDATA_MAXLINE_WORK('K1PD01');
            $exp_maxl = $this->exec( $this->expk, $sql_expk );
            $l = date('t',strtotime($dats));
            $a = array();
            foreach ( range( 1, $l ) as $x ){
                $t = date('dS', strtotime("+".($x-1)." day", strtotime($dats) ) );
                array_push( $a, array( "axisX" => $t, "axisY" => null) );
            }
            foreach ( $exp_resu as $i => $v ){
                foreach( $a as $s => $n){
                    
                    if( $a[$s]["axisX"] == $v["ORDDAYS"] )
                    {
                       //echo $a[$s]["axisX"] . " => " .  $v["ORDDAYS"] . "\n";
                       $a[$s]["axisY"] = $v["THIS_MONTH"] ; 
                       break;
                    } 
                }
            }           
            return array( "data" => $a, "ymax" => $exp_maxl[0]['MAXLINES'] );    
        }

    public function days_lnworkac($dats, $pds)
        {
            $this->expk_load();
            //$sql_expk;
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_LINEWORKDAYS_AC($dats, $pds) : $this->obj->ORACLE_GETDATA_LINEWORKDAYS_AC('2020/03/01', 'K1PD01');
            $exp_resu = $this->exec( $this->expk, $sql_expk );
            
            return array("data" => $exp_resu);    
            
        }   



    public function days_olworkac($dats, $pds)
        {
            $this->expk_load();
            //$sql_expk;
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_LINE_WORK_AC($dats, $pds) : $this->obj->ORACLE_GETDATA_LINE_WORK_AC('2020/03/01', 'K1PD01');
            //echo $sql_expk; exit;
            $exp_resu = $this->exec( $this->expk, $sql_expk );
            $sql_expk = ($_POST) ? $this->obj->ORACLE_GETDATA_MAXLINE_WORK($pds) : $this->obj->ORACLE_GETDATA_MAXLINE_WORK('K1PD01');
            $exp_maxl = $this->exec( $this->expk, $sql_expk );
            $l = date('t',strtotime($dats));
            $a = array();
            foreach ( range( 1, $l ) as $x ){
                $t = date('dS', strtotime("+".($x-1)." day", strtotime($dats) ) );
                array_push( $a, array( "axisX" => $t, "axisY" => null, "axisZ" => null) );
            }
            foreach ( $exp_resu as $i => $v ){
                foreach( $a as $s => $n){
                    
                    if( $a[$s]["axisX"] == $v["ORDDAYS"] )
                    {
                       $a[$s]["axisY"] = $v["PLAN"];
                       $a[$s]["axisZ"] = $v["ACTU"] ;  
                       break;
                    } 
                }
            }           
            return array( "data" => $a, "ymax" => $exp_maxl[0]['MAXLINES'] );    
        }

    public function days_acwork()
        {
            $this->expk_load();
            $dats     = ($_POST) ?  $_POST["dates"]  : '2020/01/01' ;
            $sql_expk = $this->obj->ORACLE_GETDATA_PDWORKDAYS_AC( $dats );

            $exp_resu = $this->exec( $this->expk, $sql_expk );
            //var_dump($exp_resu); exit;
            return array( "data" => $exp_resu, "label" => array(date('F',strtotime($dats)), date('F',strtotime("+ 1 month",strtotime($dats))) ) );                 
        }
}
?> 
