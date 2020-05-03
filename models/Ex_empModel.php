<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
require_once dirname(__FILE__) . '/sql/emp.php';
class Ex_empModel extends Main_Model
{
    var $obj;
    public function __construct()
        {
            parent::__construct();
            ## asset config
            //session_destroy();
            ob_clean(); 
            flush();
            $this->obj = new MEMBERSYS();
        
        }

    public function emp_center($user_cd)
        {
            $tz_object = new DateTimeZone('+0700');
            $this->Emp_load();

                $excue = $this->emp->query( $this->pro_query->MYSQL_GETDATA_EMP($user_cd) );
                $recue = $excue->result_array();                
                    //}
            return $recue; 

            ///var_dump($recue); exit;
        }
    public function empsys( )
        {
            $tz_object = new DateTimeZone('+0700');
            $f = $this->fal_load();
            $u = ($_GET) ? $_GET["user"] : "00000";
            $this->img_mem($u);
            $excue = $f->query( $this->obj->MSSQL_GETDATA_USERSYS($u) );
            $recue = $excue->result_array();                
            if ( count( $recue ) < 1 ){
                $g = $this->emp_center($u);
                $this->exec_odbc( $f,  $this->obj->MSSQL_ADDDATA_USERSYS ( $g[0] ) ) ;
                return $g;
            }
            return $recue;  
        }

    public function emp_regispin($user_cd, $user_pin)
        {

            $tz_object = new DateTimeZone('+0700');
            $this->fal_load();
            $user_pin = base64_encode(base64_encode($user_pin));
            $data_user = $this->emp_center($user_cd)[0];
            

            if ( $_GET["id"] == 1)
            {
                $usr =  $data_user["USER_TNAME"];
                //$str = $this->pro_query->MSSQL_ADDDATA_USERPIN(" '{$data_user["USER_CD"]}', '{$data_user["USER_ENAME"]}', N'$usr', '$user_pin', '{$this->datetime->format('Y-m-d H:i:s')}', '{$this->datetime->format('Y-m-d H:i:s')}' ");         
                //echo $usr; exit;
                //$excue = $this->fal->query( $str );
            //  $excue = $this->fal->query($str);            
                $excue = $this->fal->query( "EXEC INSFA_USERPIN '{$data_user["USER_CD"]}', '{$data_user["USER_ENAME"]}', N'$usr', '$user_pin', '{$this->datetime->format('Y-m-d H:i:s')}', '{$this->datetime->format('Y-m-d H:i:s')}' " ); 

                $recue = $excue->result_array(); 

                return $recue[0]["FLG"]; 
            }
            //$excue = $this->fal->query( "EXEC INSFA_USERPIN '{$data_user["USER_CD"]}', '{$data_user["USER_ENAME"]}', N'{$data_user["USER_TNAME"]}', '$user_pin', '{$this->datetime->format('Y-m-d H:i:s')}', '{$this->datetime->format('Y-m-d H:i:s')}' " );
            else
            {
                $excue = $this->fal->query("SELECT * FROM FA_USERPIN");     
                $recue = $excue->result_array(); 

                return $recue;      
            }

            
            //echo  "EXEC INSFA_USERPIN '{$data_user["USER_CD"]}', '{$data_user["USER_ENAME"]}', N'$usr', '$user_pin', '{$this->datetime->format('Y-m-d H:i:s')}', '{$this->datetime->format('Y-m-d H:i:s')}' " . "<br>" ;//exit;
                
            //   exit;
            //echo $user_pin . "   =.=   " . base64_decode(base64_decode($user_pin)); exit;
            //var_dump( $recue ); exit;
            

        }
    public function emp_loginpin($user_cd, $user_pin="")
        {

            $tz_object = new DateTimeZone('+0700');
            $this->fal_load();
            $user_pin = base64_encode(base64_encode($user_pin));
            $data_user = $this->emp_center($user_cd)[0];
            
                $usr =  $data_user["USER_TNAME"];

                $excue = $this->fal->query( "SELECT COUNT(IND) IND FROM FA_USERPIN WHERE USER_CD = '$user_cd' AND USER_PIN = '$user_pin' " ); 

                $recue = $excue->result_array(); 

                return $recue[0]["IND"]; 
        }
    public function emp_login($user_cd)
        {
          $tz_object = new DateTimeZone('+0700');
          $this->fal_load();

          $excue = $this->fal->query( "SELECT * FROM FA_USERPIN WHERE USER_CD = '$user_cd' " ); 
          $recue = $excue->result_array(); 
          return $recue; 
        }
    public function img_mem($img)
        {
     
            $file_pointer = $img;
    
            //var_dump(   ! file_exists('img_mem\\' . $file_pointer . '.jpg') ); exit;
    
                if (! file_exists('img_mem\\' . $file_pointer . '.jpg') )  
                { 
                    $this->save_image('http://192.168.82.23/member/photo/'. $file_pointer . '.jpg','img_mem\\' . $file_pointer  . '.jpg');
                    //echo json_encode( array( "img" => 1) );
                } 
                else 
                {               
                    //echo json_encode( array( "img" => 0) );                                   
                }       
    
        }
    public function save_image($inPath,$outPath)
        { //Download images from remote server
            $in  =   fopen($inPath, "rb");
            $out =   fopen($outPath, "wb");
            while ($chunk = fread($in,8192))
            {
                    fwrite($out, $chunk, 8192);
            }
            fclose($in);
            fclose($out);
        }         

}
?> 
