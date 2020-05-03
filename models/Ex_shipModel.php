<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
require_once dirname(__FILE__) . '/sql/ship.php';
class Ex_shipModel extends Main_Model
{
    var $query_str;
    var $loct = array();
    var $objship;
    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
      
        $this->objship = new SHIPSYSTEM();
        //$this->fal_load();
        //$this->load->libraries('Pro_query','qr');  
    }

    public function ship_item()
    {
        $this->ship_load();
        $sql_ship = array();
        if($_POST)
        {
            $sql_ship["im"] = $this->objship->MYSQL_GETDATA_ITEMSHIP($_POST["st"], $_POST['en']);
            $sql_ship["iv"] = $this->objship->MYSQL_GETDATA_INVSHIP($_POST["st"], $_POST['en']);
            $sql_ship["ct"] = $this->objship->MYSQL_GETDATA_CUSTSHIP($_POST["st"], $_POST['en']);

        } 
        else 
        {
            $sql_ship["im"] = $this->objship->MYSQL_GETDATA_ITEMSHIP('2020/02/01', '2020/02/04');            
            $sql_ship["iv"] = $this->objship->MYSQL_GETDATA_INVSHIP('2020/02/01', '2020/02/04');
            $sql_ship["ct"] = $this->objship->MYSQL_GETDATA_CUSTSHIP('2020/02/01', '2020/02/04');    
        }//echo $sql_ship; exit;
        $exp_resu = array(
                            "itm" => $this->exec($this->ship, $sql_ship["im"]),
                            "inv" => $this->exec($this->ship, $sql_ship["iv"]),
                            "cus" => $this->exec($this->ship, $sql_ship["ct"])
                            //,$this->exec($this->ship, $sql_ship["sl"])
                         );




        return $exp_resu;    
    }
    public function ship_data()
    {
        $this->ship_load();
        $str_ship_data = array();

        if($_POST)
        {
            $str_ship_data["dt"] = $this->objship->MYSQL_GETDATA_SHIPPING( $_POST["st"], $_POST['en'], $_POST["cn"]);

        } 
        else 
        {
            $str_ship_data["dt"] = $this->objship->MYSQL_GETDATA_SHIPPING('2020/02/01', '2020/02/04');            
        }//echo $sql_ship; exit;

        return   $this->exec($this->ship, $str_ship_data["dt"]);
    }

}
?> 
