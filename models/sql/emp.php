<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
ini_set('mssql.charset', 'UTF-8');
class MEMBERSYS
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

/* @MSSQL@  */
	public function MSSQL_GETDATA_USERSYS( $u )
		{
			$str_sql = sprintf(
			"SELECT
			RIGHT('00000' + USER_CD , 5) USER_CD
		    ,USER_TNAME
		    ,USER_ENAME
		    ,USER_EMAIL
		    ,USER_START
		    ,USER_BIRHT
		    ,'None' DEPT_NAME
		    FROM
			FA_USERSYS
			WHERE
			RIGHT('00000' + USER_CD , 5) = '%s'"
			,$u
			);
			return	$str_sql;
		}
	public function MSSQL_ADDDATA_USERSYS( $u )
		{
			
			$s = sprintf(
			"INSERT INTO FA_USERSYS  (USER_CD, USER_ENAME, USER_TNAME, USER_EMAIL, USER_START, USER_BIRHT, DEPT_NAME, UPDATED_DATE, CREATED_DATE, USER_STATUS )  
			 VALUES ( '%s', '%s', N'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )"
			,$u["USER_CD"]
			,$u["USER_ENAME"]
			,$u["USER_TNAME"]
			,$u["USER_EMAIL"]
			,$u["USER_START"]
			,null
			,$u["DEPT_NAME"]
			,date('Y-m-d H:i:s')
			,date('Y-m-d H:i:s')
			,1
			);
			//echo $s;  exit;
			return	$s ;
		}		
/* @MySQL@  */
	public function MYSQL_GETDATA_EMP($uid)
		{
			$sql_str = "SELECT 
				  us.EmpID  USER_CD
				, us.NameEN USER_ENAME
				, us.NameTH USER_TNAME
				, us.Email  USER_EMAIL
				, us.StartDate USER_START
				, us.Birhtday USER_BIRHT 
				, dp.DeptName DEPT_NAME
				FROM 
					user us
					LEFT OUTER JOIN 
					department dp
				  	ON us.DeptID = dp.DeptID
				WHERE EmpID = '$uid' 
				ORDER BY 1 
				";

			return $sql_str;
		}
}
// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */