<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
ini_set('mssql.charset', 'UTF-8');
class del_plan
{
   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

   public function que_exp( $str_date ,$end_date )
   {
       $sql_str = "SELECT
                    SO.SLIP_CD AS SLIP_CD,
                    TO_CHAR(OD.DESINATED_DLV_DATE,'DD-MM-YYYY') AS DELIVERY_DATE,
                    OD.ITEM_CD AS PART_NO,
                    IT.MODEL AS MODEL,
                    OD.ODR_QTY AS QTY,
                    OD.REMARKS AS KANBAN_LIST_NO,
                    CASE 
                        WHEN TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') = '09:00' THEN '1'
                        WHEN TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') = '10:00' THEN '1'
                        WHEN TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') = '16:00' THEN '2'
                        WHEN TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') = '22:00' THEN '3'
                        WHEN TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') = '04:00' THEN '4'
                    END AS TRIP,
                    TO_CHAR(OD.DESINATED_DLV_DATE,'HH24:SS') AS ROUND_TIME,
                    OD.TOTAL_SHIP_QTY AS ACTUAL_QTY,
                    NVL( OD.TOTAL_SHIP_QTY - OD.ODR_QTY , 0 ) AS DIFF,
                    SP.INVOICE_NO AS INVOICE_NO,
                    OD.CUST_ODR_NO,
                    CASE 
                        WHEN SUBSTR(SP.INVOICE_NO,1,2) = '90' THEN 'INV.90'
                        WHEN SUBSTR(SP.INVOICE_NO,1,2) = '92' THEN 'INV.92'
                    END AS BOI
                    
                    FROM
                    T_ODR OD,
                    M_PLANT_ITEM IT,
                    T_SHIP SP,
                    T_SHIP_ODR SO
                    
                    WHERE
                    OD.ITEM_CD = IT.ITEM_CD (+)
                    AND OD.ODR_CTL_NO = SP.ODR_CTL_NO (+)
                    AND OD.ODR_CTL_NO = SO.ODR_DEPOT_CTL_NO (+)
                    AND OD.CUST_CD = 'D20410'
                    AND OD.DEL_FLG = '0'
                    AND TO_CHAR(OD.DESINATED_DLV_DATE,'YYYY/MM/DD') BETWEEN '$str_date' AND '$end_date'
                    
                    ORDER BY DELIVERY_DATE , PART_NO , TRIP ASC ";

        return $sql_str;
   }

   public function que_ship( $slip_cd )
   {
        $sql_str = "SELECT INVOICE_NO FROM t_invoice_ship_odr WHERE SLIP_CD = '$slip_cd'";
        return $sql_str;
   }
}
// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */