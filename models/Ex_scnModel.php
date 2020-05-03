<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
class Ex_scnModel extends Main_Model
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

    public function scn_loc()
    {
        $this->expk_load();
        $sql_expk = $this->pro_query->ORACLE_GETDATA_LOCATION_CK($_GET["loc"]);

        $exp_resu = $this->exec($this->expk, $sql_expk);
        return $exp_resu;    
    }
    public function scn_imt()
    {
        $this->expk_load();
        $sql_expk = $this->pro_query->ORACLE_GETDATA_ITEM_LOCATION_CK($_GET["imt"]);

        $exp_resu = $this->exec($this->expk, $sql_expk);
        return $exp_resu;    
    }    
    public function scn_loc_full()
    {
        $this->expk_load();
        $sql_expk = $this->pro_query->ORACLE_GETDATA_LOCATION_CK($_GET["loc"]);

        $exp_resu = $this->exec($this->expk, $sql_expk);
        return $exp_resu;    
    } 
    public function scn_loc_mssql()
    {

        $sql_fal  = $this->pro_query->MSSQL_LOCATION_DETAIL($_GET["loc"]);
        $exp_resu = $this->exec($this->fal, $sql_fal);

        return $exp_resu;
    }
    public function upd_loc_mssql()
    {
        //echo sprintf("EXEC UPDFA_RECEIVE_MOVE_LOCATIONPART '%s', '%s','%s','%s'", $_GET["lc"], $_GET["po"], $_GET["im"], $_GET["sq"]); exit;
        $exp_resu = $this->exec($this->fal, sprintf("EXEC UPDFA_RECEIVE_MOVE_LOCATIONPART '%s', '%s','%s','%s'", $_GET["lc"], $_GET["po"], $_GET["im"], $_GET["sq"]) );
        return $exp_resu;
    }

    private function set_location()
    {
        $this->expk_load();
        $sql_expk = $this->pro_query->ORACLE_GETDATA_LOCATION_CK();
        $exp_resu = $this->exec($this->expk, $sql_expk);
        
        foreach ($exp_resu as $ind => $value) 
        {
            $this->loct[$value["ITEM_CD"]]   = $value["CLASIFICATION_CD"];
        }

        //var_dump($this->loct);
    }

}
?> 
