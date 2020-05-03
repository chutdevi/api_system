<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
date_default_timezone_set("Asia/Bangkok");
class SHIPSYSTEM 
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;

   }
		
/* @MySQL@  */
	public function MYSQL_GETDATA_SHIPPING($datSt, $datEn , $codi="")
		{
			$sql_str = sprintf( "SELECT 
								  td.ITEM_CD    
								, ts.LOT_NUMBER 
								, ts.INVOICE_NO
								, td.DESINATED_DLV_DATE AS \"DELIVERY_DATE\"
								, td.SHIP_PLAN_DATE    
								, ROUND(ts.SHIP_QTY,0)  SHIP_QTY
								, ts.SLIP_CD  
								, ts.CREATED_DATE 
								, td.CUST_ODR_NO  
								, td.CUST_CD 
								, ROUND(td.SHIP_INDICATION_QTY,0) TOTAL
								#, ROUND( SUM( ts.SHIP_QTY ) ) QTY
								FROM 
									t_invoice_ship_odr td LEFT OUTER JOIN t_ship ts ON td.INVOICE_NO = ts.INVOICE_NO  AND td.SLIP_CD = ts.SLIP_CD
								WHERE 
								-- td.ITEM_CD = '1300A057' AND								 
									DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s'
								%s	
								"
							    ,date('Y/m/d', strtotime($datSt ) )
							    ,date('Y/m/d', strtotime($datEn ) )
							    ,$codi
							  );

			//echo $sql_str; exit;
			return $sql_str;
		}

	public function MYSQL_GETDATA_ITEMSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.ITEM_CD FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.ITEM_CD" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}
	public function MYSQL_GETDATA_SLIPSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.SLIP_CD FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.SLIP_CD" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}
	public function MYSQL_GETDATA_POSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.CUST_ODR_NO FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.CUST_ODR_NO" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}
	public function MYSQL_GETDATA_INVSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.INVOICE_NO INV FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.INVOICE_NO" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}
	public function MYSQL_GETDATA_CUSTSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.CUST_CD FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.CUST_CD" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}		
	public function MYSQL_GETDATA_LOTSHIP($datSt, $datEn)
		{
			$sql_str = sprintf( "SELECT td.INVOICE_NO FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') BETWEEN '%s' AND '%s' GROUP BY td.INVOICE_NO" 
							   ,date('Y/m/d', strtotime($datSt ) )
							   ,date('Y/m/d', strtotime($datEn ) )
							  );

			return $sql_str;
		}									
	public function MYSQL_GETDATA_ITEMSHIP_YEAR()
		{
			$sql_str = sprintf( "SELECT td.ITEM_CD FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') >= '%s' GROUP BY td.ITEM_CD" 
							   ,date('Y/m/d', strtotime("- 1 year", strtotime( date('Y/m/01') ) ) )
							  );

			return $sql_str;
		}
	public function MYSQL_GETDATA_SLIPSHIP_YEAR()
		{
			$sql_str = sprintf( "SELECT td.SLIP_CD FROM t_invoice_ship_odr td  WHERE DATE_FORMAT(td.DESINATED_DLV_DATE,'%%Y/%%m/%%d') >= '%s' GROUP BY td.SLIP_CD" 
							   ,date('Y/m/d', strtotime("- 1 year", strtotime( date('Y/m/01') ) ) )
							  );

			return $sql_str;
		}
}
// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */