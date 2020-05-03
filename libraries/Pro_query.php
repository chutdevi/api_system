<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
class Pro_query 
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

/* @ORACLE@ */
	public function ORACLE_GETDATA_MOVESTOCK( $im_cd, $po_cd )
		{
			$str_sql = sprintf("
            SELECT 
             ITEM_CD
            ,PLANT_CD
            ,WH_CD
            ,RCV_ISSUE_BEFORE_QTY BEFORE_QTY
            ,RCV_ISSUE_QTY CURENT_QTY
            ,RCV_ISSUE_AFTER_QTY AFTER_QTY
            ,PUCH_ODR_CD
            ,RCV_ISSUE_DATE
            FROM 
             T_RCV_ISSUE 
            WHERE 
            ITEM_CD = '%s' AND PUCH_ODR_CD = '%s' ",$im_cd, $po_cd);
			//echo $str_sql; exit;			
			return $str_sql	;
		}
	public function ORACLE_CHECK_MOVESTOCK( $po_cd )
		{
			$str_sql = "
						SELECT 
					     TI.RCV_ISSUE_CTRL_CD
						,TI.ITEM_CD
						,TI.PLANT_CD
						,TI.WH_CD
						,TI.RCV_ISSUE_BEFORE_QTY BEFORE_QTY
						,TI.RCV_ISSUE_QTY CURENT_QTY
						,TI.RCV_ISSUE_AFTER_QTY AFTER_QTY
						,TI.PUCH_ODR_CD
						,TI.RCV_ISSUE_DATE
						FROM 
						 T_RCV_ISSUE TI
						,( SELECT RCV_ISSUE_CTRL_CD, ITEM_CD FROM T_RCV_ISSUE WHERE PUCH_ODR_CD = '$po_cd' ) RC
						WHERE
							TI.ITEM_CD = RC.ITEM_CD(+)
						AND RC.RCV_ISSUE_CTRL_CD <= TI.RCV_ISSUE_CTRL_CD
	          			AND TI.WH_CD LIKE '%QC'
						--AND TI.WH_CD IS NOT NULL
	          
			          GROUP BY
			            TI.RCV_ISSUE_CTRL_CD
					   ,TI.ITEM_CD
					   ,TI.PLANT_CD
					   ,TI.WH_CD
					   ,TI.RCV_ISSUE_BEFORE_QTY 
					   ,TI.RCV_ISSUE_QTY 
					   ,TI.RCV_ISSUE_AFTER_QTY 
					   ,TI.PUCH_ODR_CD
					   ,TI.RCV_ISSUE_DATE   					
						ORDER BY 1
			";
			return $str_sql	;
		}
	public function ORACLE_GETDATA_LOCATION()
		{
		 	$str_sql = "SELECT ITEM_CD, CLASIFICATION_CD, REMARK1 FROM M_PLANT_ITEM WHERE PLANT_CD = '51' AND REMARK1 IS NULL";
		 	return $str_sql;
		}
	public function ORACLE_GETDATA_LOCATION_CK($loc)
		{
		 	//$str_sql = "SELECT PLANT_CD, ITEM_CD, MODEL, CLASIFICATION_CD, PKG_UNIT_QTY, REMARK1 FROM M_PLANT_ITEM WHERE CLASIFICATION_CD LIKE 'H%' AND ( REMARK1 IS NULL OR NOT REMARK1 LIKE 'EX%')";
		 	$str_sql = "SELECT PLANT_CD, ITEM_CD, MODEL, CLASIFICATION_CD LOCT, PKG_UNIT_QTY SNP FROM M_PLANT_ITEM WHERE CLASIFICATION_CD = '$loc' ";
		 	return $str_sql;

		}
	public function ORACLE_GETDATA_ITEM_LOCATION_CK($imt)
		{
		 	//$str_sql = "SELECT PLANT_CD, ITEM_CD, MODEL, CLASIFICATION_CD, PKG_UNIT_QTY, REMARK1 FROM M_PLANT_ITEM WHERE CLASIFICATION_CD LIKE 'H%' AND ( REMARK1 IS NULL OR NOT REMARK1 LIKE 'EX%')";
		 	$str_sql = "SELECT PLANT_CD, ITEM_CD, MODEL, CLASIFICATION_CD LOCT, PKG_UNIT_QTY SNP FROM M_PLANT_ITEM WHERE ITEM_CD = '$imt' ";
		 	return $str_sql;

		}				
	public function ORACLE_GETDATA_MITEM()
		{
			$str_sql =  "
						SELECT 
						 MI.ITEM_CD
						,MI.ITEM_NAME
						,MP.PKG_UNIT_QTY
						,MI.STOCK_UNIT
						,MP.MODEL
						,MP.CLASIFICATION_CD
						,MV.VEND_CD
						,MV.VEND_ANAME
						,MV.VEND_NAME
						FROM
						 M_ITEM MI
						,M_PLANT_ITEM MP
						,M_SOURCE MS
						,M_VEND_CTRL MV
						WHERE 
						    MI.ITEM_CD = MP.ITEM_CD(+)
						AND MI.ITEM_CD = MS.ITEM_CD(+)
						AND MS.SOURCE_CD = MV.VEND_CD(+)
						AND (MP.REMARK1 IS NULL OR MP.REMARK1 != 'EXPIRED')
						AND MP.OUTSIDE_TYP = 2
						AND MV.VEND_CD IS NOT NULL
						";
			return	$str_sql;
		}	

	public function ORACLE_GETDATA_TITEM($vend_cd, $item_cd)
		{
			$str_sql =  "
						SELECT 
						 MI.ITEM_CD
						,MI.ITEM_NAME
						,MP.PKG_UNIT_QTY
						,MI.STOCK_UNIT
						,MP.MODEL
						,MP.CLASIFICATION_CD
						,MV.VEND_CD
						,MV.VEND_ANAME
						,MV.VEND_NAME
						FROM
						 M_ITEM MI
						,M_PLANT_ITEM MP
						,M_SOURCE MS
						,M_VEND_CTRL MV
						WHERE 
						    MI.ITEM_CD = MP.ITEM_CD(+)
						AND MI.ITEM_CD = MS.ITEM_CD(+)
						AND MS.SOURCE_CD = MV.VEND_CD(+)
						AND (MP.REMARK1 IS NULL OR MP.REMARK1 != 'EXPIRED')
						AND MP.OUTSIDE_TYP = 2
						AND MV.VEND_CD IS NOT NULL
						AND MV.VEND_CD = '$vend_cd' AND MI.ITEM_CD = '$item_cd'
						";
			//echo $str_sql; exit;
			return	$str_sql;
		}			

/* @MSSQL@  */
	public function MSSQL_GETDATA_EVENQC( )
		{
			$str_sql = "SELECT PUCH_ODR_CD ,ITEM_CD, EVEN_FLG, INSPC_FLG FROM FA_RECEIVEIN WHERE EVEN_FLG = 1";
			return	$str_sql;
		}
	public function MSSQL_GETDATA_PROCZERO( )
		{
			$str_sql = "SELECT PUCH_ODR_CD ,ITEM_CD, EVEN_FLG, INSPC_FLG, LOT_RECEIVE FROM FA_RECEIVEIN WHERE PROC_FLG = 0 ";
			return	$str_sql;
		}
	public function MSSQL_GETDATA_QCINCOMING( )
		{
			$str_sql = "SELECT
						 FR.PUCH_ODR_CD 
						,FR.ITEM_CD
						,FR.SEQ
						,FT.LOCATION_PART
						FROM 

						FA_RECEIVEIN FR 

						LEFT OUTER JOIN 

						( SELECT PUCH_ODR_CD, LOCATION_PART, SEQ FROM FA_RECEIVE_TAG GROUP BY PUCH_ODR_CD, LOCATION_PART, SEQ ) FT

						ON FR.PUCH_ODR_CD = FT.PUCH_ODR_CD AND FR.SEQ = FT.SEQ

						WHERE  FT.LOCATION_PART = 'QC-INCOMING' AND FR.EVEN_FLG = '2'
						";
			return	$str_sql;
		}
	public function MSSQL_GETDATA_ACPT( $acpt_date )
		{
			$str_sql = "SELECT  
			FN.PUCH_ODR_CD
		  , FN.ITEM_CD
		  , CONCAT(FN.VEND_CD,'0') VEND_CD
		  , FN.RECEIVE_QTY
		  , FN.INVOICE_CD
		  , FN.ACTP_UPDATE_DATED DLV_DATE
		  , FORMAT (FN.DLV_DATE , 'yyyyMMdd') RECEIVE_DATE
		  , FN.LOT_RECEIVE
		  , CASE WHEN FN.INSPC_FLG = 3 THEN 'QC INSPECTION'
				   WHEN FN.INSPC_FLG = 4 THEN 'DIRECT TO LINE'
				   ELSE ''
			END INSPC_FLG
		  , FN.STATUS_FLG
		  , FT.MAX_TAG
		  , FT.TAG_QTY
		  FROM FA_RECEIVEIN FN LEFT OUTER JOIN ( SELECT DISTINCT PUCH_ODR_CD, TAG_QTY, MAX_TAG  FROM FA_RECEIVE_TAG ) FT ON FN.PUCH_ODR_CD = FT.PUCH_ODR_CD
		  WHERE FN.DLV_DATE = '$acpt_date' AND FN.EVEN_FLG = 1
			";
			return	$str_sql;
		}
	public function MSSQL_GETDATA_READQR()
		{
			//$str_sql = "SELECT  READ_QR FROM FA_RECEIVE_TAG WHERE READ_QRCTL IS NULL";
			$str_sql = "SELECT  READ_QR FROM FA_RECEIVE_TAG WHERE RIGHT(READ_QRCTL,3) = '_00'";
			return	$str_sql;
		}
	public function MSSQL_GETDATA_RECEIVETAG($po)
		{
			//$str_sql = "SELECT  READ_QR FROM FA_RECEIVE_TAG WHERE READ_QRCTL IS NULL";
			$str_sql = "SELECT
						 PUCH_ODR_CD
						,RECEIVE_TOTAL
						,TAG_QTY
						,LOT_RECEIVE
						,RIGHT(READ_QR, 3) TAG_SEQ
						,READ_QR
						,EVEN_STATUS
						,ITEM_CD
						,MAX_TAG
						,VEND_CD
						,LOCATION_PART
						FROM
						FA_RECEIVE_TAG
						WHERE PUCH_ODR_CD = '$po'

						ORDER BY 1,5";
			return	$str_sql;
		}
	public function MSSQL_UPDATED_RCTL( $read_qr )
		{
			$read_qrctl = $read_qr.'_000';
			$str_sql = "UPDATE  FA_RECEIVE_TAG SET READ_QRCTL = '$read_qrctl' WHERE DLV_DATE = '$read_qr'";
			return	$str_sql;
		}
	public function MSSQL_LOCATION_DETAIL( $parm )
		{
			$str_sql = sprintf(
						"SELECT 
						 LOCATION_PART LOCT
						--,ITEM_CD
						,SUM(TAG_QTY) QTY
						,COUNT(LOCATION_PART) TAG_BOX
						FROM  FA_RECEIVE_TAG
						WHERE LOCATION_PART = '%s'

						GROUP BY LOCATION_PART ---,ITEM_CD"
						,$parm);
			//echo $str_sql; exit;
			return	$str_sql;
		}
	public function MSSQL_GETSEQ_TAG( )
		{
			$sql_str = "SELECT
						 *
						FROM 
						(
							SELECT 
							  PUCH_ODR_CD
							, ITEM_CD
							, COUNT( TAG_SEQ ) TAG_SEQ
							, LOCATION_PART 
							, MAX_TAG

							FROM 
								FA_RECEIVE_TAG 
							GROUP BY   
								PUCH_ODR_CD
							, ITEM_CD
							, LOCATION_PART 
							, MAX_TAG
						) TH

						WHERE TH.TAG_SEQ != TH.MAX_TAG
				";

			return $sql_str;
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