<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
class Main_Model extends CI_Model 
{


    var $emp;
    var $user;
    var $fal;
    var $ship;
    var $expk;
    var $fak;
    var $fab;
    var $datetime;

    public function __construct()
    {
        parent::__construct();
        //session_destroy();
        ob_clean();
        flush();
        $tz_object = new DateTimeZone('+0700');
        $this->datetime  = new DateTime();
        $this->datetime->setTimezone($tz_object);  
    }


    public function emp_load()
    {
       $this->emp = $this->load->database('emp',true); 
    }
    public function fal_load()
    {
       $this->fal = $this->load->database('fal',true);

       return $this->fal;
    }
    public function ship_load()
    {
       $this->ship = $this->load->database('ship',true);
    //echo $this->ship->platform(); exit;
       return $this->ship;
    }
    public function expk_load()
    {
       $this->expk = $this->load->database('expk',true);
       return $this->expk;
    }
    public function fak_load()
    {
       $this->fak = $this->load->database('fa',true);
       return $this->fak;
    }
    public function fab_load()
    {
       $this->fab = $this->load->database('fa8',true);
       return $this->fab;
    }    
    public function user_load()
    {
       $this->user = $this->load->database('user',true);
       return $this->user;
    }

    public function exec( $exc, $sql)
    { 
        $excue = $exc->query( $sql );
        $recue = $excue->result_array();
        return $recue; 
    }
    public function exec_odbc( $exc, $sql)
    { 
        $excue = $exc->query( $sql );  
    }
    public function apiget( $url="" )
    {  
			$content = file_get_contents($url);
		 	$result  = json_decode($content);
         $recLoad = json_decode(json_encode($result), true); 
         return $recLoad; 
    }    
    public function serverget( $url="" )
     {  
         $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
         if ($contentType === "application/json") {}
            
            $content = trim(file_get_contents("php://input")); 
            $decoded = json_decode($content, true); 
         // if(! is_array($decoded)) {
               return $decoded;
         // } else {
         //   return $decoded;
         // } 
     } 
}
?> 
