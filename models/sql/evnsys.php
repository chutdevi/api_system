<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
ini_set('mssql.charset', 'UTF-8');
class EVENTSYSTEM
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

/* @ MYSQL SUBPORT @  */
	public function MYSQL_GETDATA_USERSYSTEM($user, $pass)
	 {
		$sq = sprintf("SELECT * FROM user_system where USER_CD = '%s' AND PASSWORD = '%s'", $user, $pass );
		return $sq;
	 }
	public function MYSQL_GETDATA_USEROLD($user, $pass)
	 {
		$sq = sprintf(
			"SELECT 
			  USER_CD
			, USER_NAME
			, PASSWORD
			, ADDRESS
			, GROUP_CD
			, LOGIN_DATE
			, LOGOUT_DATE
			, LAST_USE
			, CREATE_DATE
			, 'SYSTEM' CREATE_BY
			, DATE_FORMAT(NOW(),'%%Y-%%m-%%d %%H:%%i:%%s') UPDATE_DATE
			, UPDATE_BY FROM user_sys where USER_CD = '%s' AND PASSWORD = '%s' AND NOT ISNULL(GROUP_CD)", $user, $pass ); 
		return $sq;
	 }
	 public function MYSQL_GETDATA_USERGROUP($user)
	  {
		$sq = sprintf( "SELECT DISTINCT GROUP_CD  FROM user_sys where USER_CD = '%s' AND NOT ISNULL(GROUP_CD) AND GROUP_CD <> '' ", $user );
		return $sq;
	  }	 
	public function ORACL_GETDATA_USEEXPK($user, $pass)
	 {
		$sq = sprintf(
			"SELECT 
			  USER_CD
			, USER_NAME
			, PASSWORD
			, ADDRESS
			, null GROUP_CD
			, TO_CHAR(SYSDATE-(2/24), 'YYYY-MM-DD HH24:MI:SS') LOGIN_DATE
			, TO_CHAR(SYSDATE-(2/24), 'YYYY-MM-DD HH24:MI:SS') LOGOUT_DATE
			, TO_CHAR(SYSDATE-(2/24), 'YYYY-MM-DD HH24:MI:SS') LAST_USE
			, TO_CHAR(SYSDATE-(2/24), 'YYYY-MM-DD HH24:MI:SS') CREATE_DATE
			, 'SYSTEM' CREATE_BY
			, TO_CHAR(SYSDATE-(2/24), 'YYYY-MM-DD HH24:MI:SS') UPDATE_DATE
			, 'SYSTEM' UPDATE_BY FROM USER_MST where USER_CD = '%s' AND PASSWORD = '%s'", $user, $pass );
		//echo $sq; exit;
		return $sq;
	 }	 	  

	
	 public function MYSQL_INSERT_USERSYSTEM($ins, $id)
	 {
		$sq = sprintf("CALL  INSERT_PRDREPORT_LIST (%s) ", $ins);
		return $sq;
	 }


	public function MYSQL_INSERT_EVENTLIST($ins)
	 {
		$sq = sprintf("INSERT INTO event_list ( EVENT_ID ,EVENT_NAME,EVENT_FILE,EVENT_USER,EVENT_CREATED ) VALUES ( %s )", $ins);
		return $sq;
	 } 
	public function MYSQL_GETMENU_BYGROUP($g="")
	 {
		 $sql = sprintf(
		   "SELECT DISTINCT mns.MENU_NAME 
			FROM menu_sys mns LEFT OUTER JOIN menu_mst mst ON mns.SUB_MENU_CD = mst.SUB_MENU_CD   
			WHERE  mns.GROUP_CD = %s AND mst.STATUSD = 1
			ORDER BY mst.MENU_CD"
			, $g);
			//echo $sql;exit;
			return $sql;
	 }
	public function MYSQL_GETSUBMENU_BYGROUP($g="")
	 {
		 $sql = sprintf(
			 "SELECT 
			  mns.MENU_NAME
			, mst.SUB_MENU_NAME
			, mst.LINK  
			FROM menu_sys mns LEFT OUTER JOIN menu_mst mst ON mns.SUB_MENU_CD = mst.SUB_MENU_CD   
			WHERE  mns.GROUP_CD = %s AND mst.STATUSD = 1
			ORDER BY 1, 2"
			, $g);
			//echo $sql;exit;
			return $sql;
	 }	 


	public function MYSL_GETMEMBER_GROUP(){
		$sql = sprintf(
		"SELECT 
		  u.NUM
		, u.USER_CD
		, SUBSTR(u.USER_CD,3) IMAGE_ID
		, u.USER_NAME
		, CASE WHEN u.ADDRESS    = '' THEN 'Please update'   ELSE u.ADDRESS END  ADDRESS
		, g.GROUP_CD
		, CASE WHEN g.GROUP_NAME = '' OR ISNULL(g.GROUP_NAME)THEN 'No group' ELSE g.GROUP_NAME END GROUP_NAME 
		, DATE_FORMAT(u.LOGIN_DATE,'%%Y-%%m-%%d %%H:%%i:%%s') LOGIN_DATE
		, DATE_FORMAT(u.LOGOUT_DATE,'%%Y-%%m-%%d %%H:%%i:%%s') LOGOUT_DATE
		, DATE_FORMAT(u.LAST_USE,'%%Y-%%m-%%d %%H:%%i:%%s') LAST_USE
		, u.STATUS_ONLINE
		FROM user_system u LEFT OUTER JOIN g_user g ON u.GROUP_CD = g.GROUP_CD ORDER BY 10 DESC");

	  return $sql;
	}

	public function MYSQL_SESSION_SET($dset){
		$sql = sprintf("INSERT INTO session_report_access  VALUES ( '%s', '%s', '%s', '%s' ) ", $dset[0], $dset[1], $dset[2], date('Y-m-d H:i:s') );

		return $sql;
	}
	public function MYSQL_SESSION_GET($sess_name){
		$sql = sprintf("SELECT * FROM session_report_access WHERE sess_id = '%s' ", $sess_name );

		return $sql;
	}
	public function MYSQL_SESSION_DEL($sess_name){
		$sql = sprintf("DELETE FROM session_report_access WHERE sess_name='%s'", $sess_name );

		return $sql;
	}
	public function MYSQL_UPDATE_GROUP($gp, $id)
	 {
		$sq = sprintf("UPDATE user_system SET GROUP_CD = '%s' WHERE USER_CD = '%s' ", $gp, $id);
		return $sq;
	 } 
	public function MYSQL_UPDATE_LOGIN($up)
	  {
	  	$sq = sprintf(
	  	"UPDATE user_system  SET LOGIN_DATE = %s  WHERE USER_CD = '%s'" 
	  	 ,$up["LOGIN_DATE"] 
	  	 ,$up["USER_CD"]
	  	);
	  	// echo $sq; exit;
	  	return $sq;
	  } 	  
	 public function MYSQL_UPDATE_USES($up)
	  {
	  	$sq = sprintf(
	  	"UPDATE user_system  SET  STATUS_ONLINE = %s ,LOGOUT_DATE = %s ,LAST_USE = %s WHERE USER_CD = '%s'"
	  	 ,$up["STATUS_ONLINE"]
	  	 ,$up["LOGOUT_DATE"]
	  	 ,$up["LAST_USE"]
	  	 ,$up["USER_CD"]
	  	);
	  	// echo $sq; exit;
	  	return $sq;
	  }  	 

} //END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */
