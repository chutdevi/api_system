<?php
header("Content-Type:text/html; charset=UTF-8");
require_once dirname(__FILE__) . '/sql/evnsys.php';
date_default_timezone_set("Asia/Bangkok");
//ini_set('mssql.charset', 'UTF-8');
class EventModel extends Main_Model
{
    var $RECSYS;
    public function __construct()
        {
            parent::__construct();
            ## asset config
            //session_destroy();
            ob_clean();
            flush();
            $this->PRDSYS = new EVENTSYSTEM(); 
        }
    public function insert_event_list()
        {
            $evn_file = ( $this->input->get("f") )  ? $this->input->get("f") : "none";
            $user     = ( $this->input->get("u") )  ? $this->input->get("u") : "none";
            $evn_name = ( $this->input->get("n") )  ? $this->input->get("n") : "none";
            $evn_id   = ( $this->input->get("id") ) ? $this->input->get("id"): "none";  
            $mysql    = $this->user_load(); 
                  
                $str_result = sprintf("%s,%s,%s,%s,'%s'"
                                        , $evn_id
                                        , $evn_name
                                        , $evn_file
                                        , $user 
                                        ,date('Y-m-d H:i:s')
                                        ); 
                
                $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_INSERT_EVENTLIST($str_result) );  

                return "complete" ;
        }
    public function getdata_report_prd_list()
        {
            $gst = ( $this->input->get("s") ) ? $this->input->get("s") : date('Y-m-01');
            $gen = ( $this->input->get("e") ) ? $this->input->get("e") : date('Y-m-t');
            $mysql = $this->user_load();
            $cfile = $this->exec( $mysql, $this->PRDSYS->MYSQL_GETDATA_USER(   ) )  ; 
            return $cfile;
        }
    public function getdata_user()
        { 
            $content = $this->serverget(); 
            $mysql   = array( $this->user_load(), $this->user_load() ) ;
            $oracl   = $this->expk_load(); 
            $user_step = $this->exec( $mysql[0], $this->PRDSYS->MYSQL_GETDATA_USERSYSTEM( $content["user"], $content["pass"] ) )  ;
            if( empty( $user_step ) ){
                $user_step = $this->exec( $mysql[0], $this->PRDSYS->MYSQL_GETDATA_USEROLD( $content["user"], $content["pass"] ) )  ;
                if( empty( $user_step ) ){
                    $user_step = $this->exec( $oracl, $this->PRDSYS->ORACL_GETDATA_USEEXPK( $content["user"], $content["pass"] ) );
                    if( empty( $user_step ) ){ 
                         return array("msg" => "0", "group" => null );
                    }else{
                         $this->exec_odbc( $mysql[0], $this->PRDSYS->MYSQL_INSERT_USERSYSTEM( sprintf("'%s'", implode("','",$user_step[0])), $content["user"] ) )  ;
                         $group = ( empty( $user_step[0]["GROUP_CD"]) ) ?  $this->exec( $mysql[1], $this->PRDSYS->MYSQL_GETDATA_USERGROUP( $content["user"] )) : $user_step ;
                         $this->exec_odbc( $mysql[1], $this->PRDSYS->MYSQL_UPDATE_GROUP( $group[0]["GROUP_CD"], $content["user"] ) ); 
                         $this->update_system_login($content["user"]);
                        return array("msg" => "1", "name" => $user_step[0]["USER_NAME"], "address" => $user_step[0]["ADDRESS"], "group" => $group[0]["GROUP_CD"] );
                    } 
                }else{
                    $this->exec_odbc( $mysql[0], $this->PRDSYS->MYSQL_INSERT_USERSYSTEM( sprintf("'%s'", implode("','",$user_step[0])), $content["user"] ) );
                    $group = $user_step[0]["GROUP_CD"];
                    $this->update_system_login($content["user"]);
                    return array("msg" => "1", "name" => $user_step[0]["USER_NAME"], "address" => $user_step[0]["ADDRESS"], "group" => $group );
                }
            }else{
                $this->update_system_login($content["user"]);
                return array("msg" => "1", "name" => $user_step[0]["USER_NAME"], "address" => $user_step[0]["ADDRESS"], "group" => $user_step[0]["GROUP_CD"] );
            }  
            //return array();
        }
    
    public function getdata_menu_group()
        {
            $g = ( $this->input->get("g") ) ? $this->input->get("g") : "";
            $mysql = $this->user_load();
            $menu = $this->exec( $mysql, $this->PRDSYS->MYSQL_GETMENU_BYGROUP($g) )  ; 
            $subm = $this->exec( $mysql, $this->PRDSYS->MYSQL_GETSUBMENU_BYGROUP($g) )  ; 
            return array("menu"=>$menu, "sub" => $subm);
        }
    public function getdata_memb_group()
        {
            $g = ( $this->input->get("g") ) ? $this->input->get("g") : "";
            $mysql = $this->user_load();
            $memb = $this->exec( $mysql, $this->PRDSYS->MYSL_GETMEMBER_GROUP() );
            return $memb;
        } 
    public function setdata_session()
        {
            $content = $this->serverget(); 
            $i = ( $this->input->get("i") ) ? $this->input->get("i") : $content["i"];
            $n = ( $this->input->get("n") ) ? $this->input->get("n") : $content["n"];
            $v = ( $this->input->get("v") ) ? $this->input->get("v") : $content["v"];
            $arr_set = array($i,$n,$v);
            $mysql = $this->user_load();
            $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_SESSION_SET($arr_set) );
            return 0;
        }  
    public function getdata_session()
        {
            $content = $this->serverget(); 
            $i = ( $this->input->get("i") ) ? $this->input->get("i") : $content["i"];
            // $n = ( $this->input->get("n") ) ? $this->input->get("n") : $content["n"]; 
            // $arr_set = array($i,$n);
            $mysql = $this->user_load();
            $memb  = $this->exec( $mysql, $this->PRDSYS->MYSQL_SESSION_GET($i) );
            return $memb;
        }  
    public function deldata_session()
        {
            $content = $this->serverget(); 
            $n = ( $this->input->get("n") ) ? $this->input->get("n") : $content["n"];
            // $n = ( $this->input->get("n") ) ? $this->input->get("n") : $content["n"]; 
            // $arr_set = array($i,$n);
            $mysql = $this->user_load();
            $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_SESSION_DEL($n) );
            return 0;
        }                                
    public function update_system_uses()
        {
            $content = $this->serverget(); 
            $g = ( $this->input->get("user") ) ? $this->input->get("user") : $content["user"];
            $t = ( $this->input->get("stat") ) ? $this->input->get("stat") : $content["stat"];
            $update = array( "STATUS_ONLINE"=>$t 
                            ,"LOGOUT_DATE"=>sprintf("'%s'", date('Y-m-d H:i:s') )
                            ,"LAST_USE"=>sprintf("'%s'", date('Y-m-d H:i:s') )
                            ,"USER_CD" => $g
                           ); 
            $mysql = $this->user_load();
            $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_UPDATE_USES($update) ); 
            return array("test" => "Please", "todo" => $g);
        }     
    public function update_system_login($u)
        {
            $update = array( "LOGIN_DATE" => sprintf("'%s'", date('Y-m-d H:i:s') ) 
                            ,"USER_CD"    => $u
                           ); 
            $mysql = $this->user_load();
            $this->exec_odbc( $mysql, $this->PRDSYS->MYSQL_UPDATE_LOGIN($update) );             
        }   
}
?> 
