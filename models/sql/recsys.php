<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
ini_set('mssql.charset', 'UTF-8');
class RECEIVE
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

/* @ ORACLE @  */
	public function ORACLE_GETDATA_PLANRECEIVE( $d, $c="" )
		{
			$d = date('Y/m/d', strtotime($d) );
			$c = ($c != "") ? sprintf( "AND %s", $c ) : $c ;
			$str_sql = sprintf(
			"SELECT
			 AP.PLANT_CD 
			,AP.VEND_CD
			,B.VEND_ANAME
			,B.VEND_NAME
			,AP.ITEM_CD  AS ITEM_CD
			,MI.ITEM_NAME
			,MP.MODEL 
			,AP.PUCH_ODR_CD AS PONUMBER 
			,AP.CONFIRM_DLV_DATE AS RDATE
			,AP.PUCH_ODR_DLV_DATE AS SDATE
			,AP.PUCH_ODR_QTY AS QTY
			,AP.PUCH_ODR_STS_TYP AS ODR_STATUS
			,AP.ACPT_INSPC_TYP   AS ODR_TYPE
			,AP.UPDATED_BY
			FROM 
				T_RLSD_PUCH_ODR AP 
			,M_ITEM MI
			,M_PLANT_ITEM MP
			,M_VEND_CTRL B
			WHERE
				AP.ITEM_CD   = MI.ITEM_CD(+) 
			AND AP.VEND_CD   = B.VEND_CD(+)
			AND AP.PLANT_CD  = MP.PLANT_CD
			AND AP.ITEM_CD   = MP.ITEM_CD(+) 
			AND TO_CHAR(AP.PUCH_ODR_DLV_DATE,'YYYY/MM/DD') = '$d'-- OR TO_CHAR(AP.CONFIRM_DLV_DATE,'YYYY/MM/DD') = TO_CHAR(SYSDATE,'YYYY/MM/DD')
			AND AP.CONFIRM_DLV_DATE IS NULL
			%s
			UNION ALL 
			SELECT
				AP.PLANT_CD 
			,AP.VEND_CD
			,B.VEND_ANAME
			,B.VEND_NAME
			,AP.ITEM_CD  AS ITEM_CD
			,MI.ITEM_NAME
			,MP.MODEL 
			,AP.PUCH_ODR_CD AS PONUMBER
			,AP.PUCH_ODR_DLV_DATE AS RDATE  
			,AP.CONFIRM_DLV_DATE AS SDATE 
			,AP.PUCH_ODR_QTY AS QTY 
			,AP.PUCH_ODR_STS_TYP AS ODR_STATUS
			,AP.ACPT_INSPC_TYP   AS ODR_TYPE
			,AP.UPDATED_BY
			FROM 
				T_RLSD_PUCH_ODR AP 
			,M_ITEM MI
			,M_PLANT_ITEM MP
			,M_VEND_CTRL B
			WHERE
				AP.ITEM_CD   = MI.ITEM_CD(+) 
			AND AP.VEND_CD   = B.VEND_CD(+)
			AND AP.PLANT_CD  = MP.PLANT_CD
			AND AP.ITEM_CD   = MP.ITEM_CD(+) 
			AND TO_CHAR(AP.CONFIRM_DLV_DATE,'YYYY/MM/DD') = '$d'-- OR TO_CHAR(AP.CONFIRM_DLV_DATE,'YYYY/MM/DD') = TO_CHAR(SYSDATE,'YYYY/MM/DD') 
			%s
			ORDER BY 1,2" 
			,$c
			,$c
			); 
			return	$str_sql;
		}
	public function ORACLE_GETDATA_PLANRECEIVE_COUNT( $d, $pt="" )
		{
			$str_sql = sprintf(
			" SELECT
				COUNT(B.PONUMBER) AMPONT_PO 
				,COUNT(DISTINCT B.VEND_CD) AMPONT_VEND
			FROM (%s) B WHERE B.PLANT_CD = '%s' " 
			,$this->ORACLE_GETDATA_PLANRECEIVE($d)
			,$pt
			);

			//echo $str_sql; exit;
			return	$str_sql;

		}
}


// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */
 