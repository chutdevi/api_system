<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
class Ex_lismodel extends Main_Model
{
    var $query_str;
    var $loct = array(); 
    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
      
        $this->fal_load();
        //$this->load->libraries('Pro_query','qr');  
    }


    public function lis_acpt()
    {
        $sub_fal = $this->fal_load();
        $recue = $this->exec( $sub_fal, $this->pro_query->MSSQL_GETDATA_ACPT( $_GET["adate"] ) );
        
        //var_dump(  $recue )     ; exit;
        return $recue;
    }

    public function lis_atag()
    {
        $sub_fal = $this->fal_load();
        $recue = $this->exec( $sub_fal, $this->pro_query->MSSQL_GETDATA_RECEIVETAG( $_GET["po"]) );
        
        //var_dump(  $recue )     ; exit;
        return $recue;
    }
    public function lis_mtag()
    {
        $sub_expk = $this->expk_load();
        //echo($this->pro_query->ORACLE_GETDATA_TITEM( $_GET["vend_cd"], $_GET["item_cd"] )); exit;
        $recue = $this->exec( $sub_expk, $this->pro_query->ORACLE_GETDATA_TITEM( $_GET["vend_cd"], $_GET["item_cd"] ) );
        
        //var_dump(  $recue )     ; exit;
        return $recue;
    }


    
}
?> 
