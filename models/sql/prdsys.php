<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
ini_set('mssql.charset', 'UTF-8');
class PRODUCTIONSYSTEM
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;
   }

/* @ ORACLE @  */
	public function ORACLE_GETDATA_PRODLINE( $d, $p='K1PD02' )
		{
			$dy = date('Y/m/d' , strtotime($d) );
			$gd = ( date('t' , strtotime("+ 0 day", strtotime($d)) ) == $d ) ? $dy : date('Y/m/d' , strtotime("- 1 day", strtotime($d)) );
			$y1 = date('Y/m/d' , strtotime("- 1 day", strtotime($d)) );
			$y2 = date('Y/m/d' , strtotime("- 2 day", strtotime($d)) );
			$t1 = date('Y/m/d' , strtotime("+ 1 day", strtotime($d)) );
			$t2 = date('Y/m/d' , strtotime("+ 2 day", strtotime($d)) ); 
			$sd = date('Y/m/01', strtotime("+ 0 day", strtotime($d)) );
			$ed = date('Y/m/t' , strtotime("+ 0 day", strtotime($d)) );
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$p = ($p == "PD06" || $p == "PL00") ? sprintf( "K2%s", $p ) : sprintf( "K1%s", $p );
			$str_sql = sprintf(
			"SELECT
		    VC.PARENT_SEC_CD AS PD
		   ,A.LINE_CD
		   ,VM.SEC_NM
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y2' THEN A.QTY END ),0) AS  AGOPLAN
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y2' THEN A.QTY END ),0) AS  AGOACTU
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y2' THEN A.QTY END ),0)- 
			NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y2' THEN A.QTY END ),0) AS  AGOREMN 
		   ,NVL(SUM(CASE WHEN A.TYPE = 4 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y2' THEN A.QTY END ),0) AS  AGODEFC 
		   ,0 AGOTOTLTIME
		   ,0 AGOTOTLBAKE
		   ,0 AGOTOTLLOSS 
		   ,NULL AGOACTUTIME 
		   ,0 AGOTOTLCYTM
		   ,NULL AGOEFF		   

		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y1' THEN A.QTY END ),0) AS  YSDPLAN
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y1' THEN A.QTY END ),0) AS  YSDACTU
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y1' THEN A.QTY END ),0) - 
			NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y1' THEN A.QTY END ),0) AS  YSDREMN 
		   ,NVL(SUM(CASE WHEN A.TYPE = 4 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '$y1' THEN A.QTY END ),0) AS  YSDDEFC 
		   ,0 YSDTOTLTIME
		   ,0 YSDTOTLBAKE
		   ,0 YSDTOTLLOSS 
		   ,NULL YSDACTUTIME 
		   ,0 YSDTOTLCYTM
		   ,NULL YSDEFF	
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') =  '$dy' THEN A.QTY END ),0) AS  TODPLAN 
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') =  '$t1' THEN A.QTY END ),0) AS  NX1PLAN 
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') =  '$t2' THEN A.QTY END ),0) AS  NX2PLAN  
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$gd' THEN A.QTY END ),0) AS ACCUM_PLAN
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$gd' THEN A.QTY END ),0) AS ACCUM_ACTU
		   ,NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$gd' THEN A.QTY END ),0) - 
			NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$gd' THEN A.QTY END ),0) AS ACCUM_REMN
		   ,NVL(SUM(CASE WHEN A.TYPE = 4 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$ed' THEN A.QTY END ),0) AS ACCUM_DEFC
		   ,NULL  DEFCPC 
		   ,'%%'  PRECN 	   
		   ,0 ACCM_TIME
		   ,0 ACCM_BRKE 
		   ,0 ACCM_LOSS 
		   ,NULL ACCM_ACTM 
		   ,0 ACCM_CYTM
		   ,NULL EFF	 
		   ,NVL(SUM(CASE WHEN A.TYPE = 3 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '$ed' THEN A.QTY END ),0) AS PLAN_THIS_MONTH 
		   FROM
		   (SELECT
			1 AS TYPE
		   ,TW.PLANT_CD AS PLANT_CD
		   ,TW.WS_CD AS LINE_CD
		   ,TW.WORK_ODR_DLV_DATE AS D_DATE
		   ,TW.OPR_INST_QTY AS QTY
		   FROM
			T_WORK_IN_PROC_BY_PROC TW
		   ,(SELECT OPR_INST_CD ,SUM(ACPT_QTY) AS QTY FROM T_OPR_RSLT GROUP BY OPR_INST_CD) TR
		   WHERE 
			   TW.OPR_INST_CD = TR.OPR_INST_CD(+)
		   AND NOT(TW.WORK_STS_TYP = 9 AND TR.QTY = 0)
		   AND TO_CHAR(TW.WORK_ODR_DLV_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(TW.WORK_ODR_DLV_DATE,'YYYY/MM/DD') <= '$ed'
		   UNION ALL
		   
		   SELECT
		   2
		   ,PLANT_CD AS PLANT_CD
		   ,WS_CD AS LINE_CD
		   ,OPR_DATE AS D_DATE
		   ,ACPT_QTY AS QTY
		   FROM
		   T_OPR_RSLT
		   WHERE
		      TO_CHAR(OPR_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(OPR_DATE,'YYYY/MM/DD') <= '$ed'
		   UNION ALL
		   
		   SELECT
			3 AS TYPE
		   ,TD.PLANT_CD AS PLANT_CD
		   ,TD.SOURCE_CD AS LINE_CD
		   ,TD.PRD_DUE_DATE AS D_DATE
		   ,TD.ODR_QTY AS QTY
		   FROM
		   (SELECT * FROM T_OD WHERE OD_TYP = 2 AND OUTSIDE_TYP = 1 AND NOT (ODR_STS_TYP = 9 AND TOTAL_RCV_QTY = 0 AND ODR_STS_TYP != 1) ) TD 
		   WHERE
		   	  TO_CHAR(TD.PRD_DUE_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(TD.PRD_DUE_DATE,'YYYY/MM/DD') <= '$ed'
		   
		   UNION ALL
		   
		   SELECT
			 4 AS STATUS
		   , DF.PLANT_CD AS PLANT_CD
		   , DF.WS_CD AS LINE_CD
		   , DF.RSLT_DATE AS D_DATE
		   , SUM(DF.QTY) AS QTY
		   
		   FROM
			   UT_DEFECT_DISPOSAL DF
		   WHERE
				 DF.DEL_FLG = 0
		   AND DF.VEND_CD IS NULL
		   AND TO_CHAR(DF.RSLT_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(DF.RSLT_DATE,'YYYY/MM/DD') <= '$ed'
		   GROUP BY
			 DF.PLANT_CD
		   , DF.WS_CD
		   , DF.RSLT_DATE
		   ) A
		   
		   ,VM_DEPARTMENT_CLASS VC
		   ,VM_DEPARTMENT VM
		   
		   WHERE
			   A.LINE_CD = VC.COMP_SEC_CD(+)
		   AND A.LINE_CD = VM.SEC_CD(+)
		   AND VC.PARENT_SEC_CD IS NOT NULL 
		   AND VC.PARENT_SEC_CD = '$p'
		   GROUP BY
			A.LINE_CD
		   ,VC.PARENT_SEC_CD
		   ,VM.SEC_NM
		   ORDER BY
			 1,2"
			); 
		    //echo $str_sql;exit;
			return	$str_sql;
		} 
	public function DB2_GETDATA_FALOSS( $d="2020-02-01" , $p="PD02")
		{
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$dy = date('Y-m-d' , strtotime($d) ); 
			$sy = date('Y-m-d' , strtotime("- 1 day" , strtotime($d)) );			
			$tm = date('Y-m-d' , strtotime("+ 1 day" , strtotime($d)) ); 


			$sd = date('Y-m-01', strtotime("+ 0 day", strtotime($d)) );
			$ed = date('Y-m-01' , strtotime("+ 1 month", strtotime($sd)) );
			$datec = date('Ym01', strtotime( "- 0 month", strtotime($sd) ));		
			$str_sql = sprintf(
				"SELECT 
				LM.SYOZK_CD AS PD
			   ,LT.LINE_CD
			   ,SUM( CASE  WHEN TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') >= '$sy 08:00:00' 
				   	        AND TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') <= '$dy 08:00:00'
				      	   THEN TIMESTAMPDIFF (4, CHAR(TIMESTAMP( LT.KYUSI_ED||LT.KYUSI_ET ,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP( LT.KYUSI_SD||LT.KYUSI_ST,'YYYY.MM.DD.HH.MI.SS')))
				     END 
				   ) AS ALOSS			   
			   ,SUM( CASE  WHEN TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') >= '$dy 08:00:00' 
				   	        AND TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') <= '$tm 08:00:00'
				      	   THEN TIMESTAMPDIFF (4, CHAR(TIMESTAMP( LT.KYUSI_ED||LT.KYUSI_ET ,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP( LT.KYUSI_SD||LT.KYUSI_ST,'YYYY.MM.DD.HH.MI.SS')))
				     END 
				   ) AS YLOSS
				,SUM( CASE  WHEN TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') >= '$sd 08:00:00' 
				   		     AND TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') <= '$tm 08:00:00'
				  		    THEN TIMESTAMPDIFF (4, CHAR(TIMESTAMP( LT.KYUSI_ED||LT.KYUSI_ET ,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP( LT.KYUSI_SD||LT.KYUSI_ST,'YYYY.MM.DD.HH.MI.SS')))
				      END 
				   ) AS LOSS_ACCM			   
			   FROM KYUSIJIT_F LT INNER JOIN LINE_MST LM ON LT.LINE_CD = LM.LINE_CD
			   
			   WHERE   
					 LT.KYUSI_SD > '$datec'
				   AND KYUSI_RY NOT IN 'W__'
				   AND TO_CHAR(TO_DATE(LT.KYUSI_SD||LT.KYUSI_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') BETWEEN '$sd 08:00:00' AND '$ed 08:00:00'
				   AND LM.SYOZK_CD = '$p'
			   GROUP BY
				LM.SYOZK_CD
			   ,LT.LINE_CD 
			   ORDER BY LM.SYOZK_CD ASC ;"
			);

			//echo $str_sql; exit;
			return $str_sql;
		} 

	public function ORA_GETDATA_PRODHISTORY( $d="2020-02-01")
		{
			 
			$sd = date('Y/m/01', strtotime( "- 0 day", strtotime($d) ));
			$ed = date('Y/m/t' , strtotime( "- 0 day", strtotime($d) ));
			$ld = date('t' , strtotime($d) );
			$hi = "";
			foreach(range(1, $ld) as $fd  ){ $hi .= sprintf(",NVL(SUM(CASE WHEN A.TYPE = 2 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') = '%s' THEN A.QTY END ),0) AS \"%s\" \n", date( 'Y/m/d', strtotime(date('Y-m-'.$fd,strtotime($d) ) ) ), date( 'dS', strtotime(date('Y-m-'.$fd,strtotime($d) ) ) )  );  }
			$sql_str = sprintf("SELECT
			CASE WHEN PARENT_SEC_CD LIKE '%%LG%%' THEN '53' ELSE A.PLANT_CD END PLANT
		   ,VC.PARENT_SEC_CD AS PD
		   ,A.LINE_CD
		   ,VM.SEC_NM
		   ,A.ITEM_CD
		   ,MI.ITEM_NAME
		   ,MP.MODEl
  		   ,MP.PRODUCT_TYP 	 
		   ,NVL(SUM(CASE WHEN A.TYPE = 1 AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') >= '%s' AND TO_CHAR(A.D_DATE,'YYYY/MM/DD') <= '%s' THEN A.QTY END ),0) AS TOTAL_PLAN
		   ,NULL TOTAL_ACTU
		   ,NULL TOTAL_DIFF
		   %s 
		   FROM
		   (SELECT
		   1 AS TYPE
		   ,TW.PLANT_CD AS PLANT_CD
		   ,TW.WS_CD AS LINE_CD
		   ,TW.ITEM_CD
		   ,TW.WORK_ODR_DLV_DATE AS D_DATE
		   ,TW.OPR_INST_QTY AS QTY
		   FROM
		   T_WORK_IN_PROC_BY_PROC TW
		   ,(SELECT OPR_INST_CD ,SUM(ACPT_QTY) AS QTY FROM T_OPR_RSLT GROUP BY OPR_INST_CD) TR
		   WHERE 
			   TW.OPR_INST_CD = TR.OPR_INST_CD(+)
		   AND NOT(TW.WORK_STS_TYP = 9 AND TR.QTY = 0)
		   AND TW.OPR_INST_QTY > 0
		   AND TO_CHAR(TW.WORK_ODR_DLV_DATE,'YYYY/MM/DD') BETWEEN '%s' AND '%s'
				  
		   UNION ALL
				  
		   SELECT
		   2
		   ,PLANT_CD AS PLANT_CD
		   ,WS_CD AS LINE_CD
		   ,ITEM_CD
		   ,OPR_DATE AS D_DATE
		   ,ACPT_QTY AS QTY
		   FROM
		   T_OPR_RSLT
		   WHERE
		   	   ACPT_QTY > 0
		   AND TO_CHAR(OPR_DATE,'YYYY/MM/DD') BETWEEN '%s' AND '%s'
			) A
				  
		   ,VM_DEPARTMENT_CLASS VC
		   ,VM_DEPARTMENT VM
		   ,M_PLANT_ITEM MP
		   ,M_ITEM MI       
		   WHERE
		   A.LINE_CD = VC.COMP_SEC_CD(+)
		   AND A.LINE_CD  = VM.SEC_CD(+)
		   AND A.ITEM_CD = MI.ITEM_CD(+)
		   AND A.PLANT_CD = MP.PLANT_CD
		   AND A.ITEM_CD  = MP.ITEM_CD(+)
		   AND VC.PARENT_SEC_CD IS NOT NULL 
		   GROUP BY
		    A.PLANT_CD
		   ,VC.PARENT_SEC_CD
		   ,A.LINE_CD
		   ,VM.SEC_NM
		   ,A.ITEM_CD
		   ,MP.MODEl
		   ,MP.PRODUCT_TYP
		   ,MI.ITEM_NAME
		   ORDER BY
		   1, 2, 3"
		   ,$sd, $ed
		   ,$hi
		   ,$sd, $ed
		   ,$sd, $ed
			);
			//echo $sql_str; exit;
			return $sql_str;			
		}

	public function ORA_GETDATA_LINECYCLETIME( $d = "2020-03-27" )
		{	 
			$dy = date('Y/m/d' , strtotime($d) );
			$sd = date('Y/m/01', strtotime("+ 0 day", strtotime($d)) );
			$ed = date('Y/m/t' , strtotime("+ 0 day", strtotime($d)) );
			$y1 = date('Y/m/d' , strtotime("- 1 day", strtotime($d)) );
			$y2 = date('Y/m/d' , strtotime("- 2 day", strtotime($d)) );
			$sql_str = sprintf(
		   "SELECT
			B.PLANT_CD
		   ,B.LINE_CD
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y2' THEN B.QTY     END ) AGOQTY
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y2' THEN B.CYCELTM END ) AGOCYCELTM
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y2' THEN B.USTIME  END ) AGOLINE_USETM
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y1' THEN B.QTY     END ) YSDQTY
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y1' THEN B.CYCELTM END ) YSDCYCELTM
		   ,SUM( CASE WHEN TO_CHAR(B.D_DATE,'YYYY/MM/DD') = '$y1' THEN B.USTIME  END ) YSDLINE_USETM
		   ,SUM( B.QTY )     QTY
		   ,SUM( B.CYCELTM ) CYCELTM
		   ,SUM( B.USTIME)   LINE_USETM
		   FROM
		   	   ( SELECT
		   		   PR.PLANT_CD AS PLANT_CD
		   		  ,PR.WS_CD AS LINE_CD
		   	  	  ,PR.ITEM_CD 
		   		  ,PR.OPR_DATE AS D_DATE
		   		  ,PR.ACPT_QTY AS QTY
		   	  ,CASE WHEN MS.REMARK IS NULL THEN 999  ELSE CAST( MS.REMARK AS FLOAT ) END  CYCELTM 
		   	  ,CASE WHEN MS.REMARK IS NULL THEN 999  ELSE CAST( MS.REMARK AS FLOAT ) END * PR.ACPT_QTY  USTIME
		   		  FROM
		   			T_OPR_RSLT PR
		   	   ,M_SOURCE   MS
		   		  WHERE
		   		   PR.WS_CD   = MS.SOURCE_CD
		   	   AND PR.ITEM_CD = MS.ITEM_CD(+) 
		   		   AND TO_CHAR(OPR_DATE,'YYYY/MM/DD') >= '$sd' AND TO_CHAR(OPR_DATE,'YYYY/MM/DD') <= '$ed'  ) B 
		   GROUP BY
		   	B.PLANT_CD
		     ,B.LINE_CD 
		   ORDER BY 2" 
			);
								//echo $sql_str; exit;
			return $sql_str;			
		}

	public function DB2_GETDATA_PRODOFSEQLINE( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			//$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf("SELECT
						 LE.SYOZK_CD PD
						,SH.LINE_CD
						,SH.PLAN_HI PLANDATE
						,SH.PLAN_JUN SEQ
						,SH.HINBAN ITEM_CD
						,SH.PLAN_SU PLANPROD
						,SH.JITU_SU ACTUPROD
						,SH.JITU_SU - SH.PLAN_SU REMNPROD
						,CASE WHEN SH.KOTEI LIKE 'M%%' THEN 'FW' ELSE SH.KOTEI END TYPELINE
						,SH.DENSO_F STATUSCD
						,CASE 
						  WHEN SH.DENSO_F = '00' THEN 'Production'
						  WHEN SH.DENSO_F = '01' THEN 'Complete'
						  WHEN SH.DENSO_F = '02' THEN 'Transfer'
						 END STATUSNM
						,SH.LINE_CD||LPAD(SH.HINBAN,25,'0' ) INX
						,TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS'),'YYYY/MM/DD HH24:MI:SS') SDATE
						,TO_CHAR(TO_DATE(JITU_ED||JITU_ET,'YYYYMMDDHH24MISS'),'YYYY/MM/DD HH24:MI:SS') EDATE
						,TO_CHAR( SYSDATE ,'YYYY/MM/DD HH24:MI:SS') CDATE
						,SH.CYOKU_K SHIF
						,SH.JITU_LOT LOT
						,SH.SAGYO_SIJI_NO WI
						,SUM(TIMESTAMPDIFF (2, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODSEC
						,SUM(TIMESTAMPDIFF (4, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODMIN
						,SUM(TIMESTAMPDIFF (8, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODHOU
						,SUM(TIMESTAMPDIFF (2, CHAR(TIMESTAMP(TO_CHAR(SYSDATE, 'YYYYMMDDHHMISS'),'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS') ))) AS CURTSEC
						,SUM(TIMESTAMPDIFF (4, CHAR(TIMESTAMP(TO_CHAR(SYSDATE, 'YYYYMMDDHHMISS'),'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS') ))) AS CURTMIN
						,SUM(TIMESTAMPDIFF (8, CHAR(TIMESTAMP(TO_CHAR(SYSDATE, 'YYYYMMDDHHMISS'),'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS') ))) AS CURTHOU
						,%s	BREAKTM				
						FROM
						SEISAN_H SH
						,LINE_MST LE
						
						WHERE 
							SH.LINE_CD = LE.LINE_CD
						AND SH.JITU_SD >= '%s'
						AND LE.SYOZK_CD = '%s' 
						AND SH.JITU_LOT = '%s'
						AND SH.DENSO_F  = '00'
						GROUP BY 
						 LE.SYOZK_CD 
						,SH.LINE_CD
						,SH.PLAN_HI 
						,SH.PLAN_JUN 
						,SH.HINBAN
						,SH.PLAN_SU 
						,SH.JITU_SU 
						,SH.KOTEI
						,SH.DENSO_F
						,SH.JITU_SD
						,SH.JITU_ST
						,SH.JITU_ED
						,SH.JITU_ET
						,SH.CYOKU_K
						,SH.JITU_LOT
						,SH.SAGYO_SIJI_NO			
						ORDER BY 9,2"
						,$this->DB2_GETQURY_PRODBREAK( $d, $p )
						,$datec
						,$p
						,$lot
						);
			return $sql_str; 
		}


	public function DB2_GETDATA_PRODOFLINE( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			//$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf("SELECT
								  LE.SYOZK_CD PD
								 ,SH.LINE_CD
								 ,SUM( SH.PLAN_SU ) TOTALPN
								 ,SUM( SH.JITU_SU ) TOTALAC 
								 ,SUM( SH.JITU_SU ) - SUM( SH.PLAN_SU ) TOTALRM 
								 ,SUM(TIMESTAMPDIFF (2, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODSEC
								 ,SUM(TIMESTAMPDIFF (4, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODMIN
								 ,SUM(TIMESTAMPDIFF (8, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODHOU								  
								 ,SH.JITU_LOT
								 ,SUM( %s ) BREAKTM
								FROM
								 SEISAN_H SH
								,LINE_MST LE
								
								WHERE 
								  SH.LINE_CD = LE.LINE_CD
								 AND SH.JITU_SD >= '%s'
								 AND LE.SYOZK_CD = '%s' 
								 AND SH.JITU_LOT = '%s'
								GROUP BY 
								  LE.SYOZK_CD 
								 ,SH.LINE_CD
								 ,SH.JITU_LOT	
								ORDER BY 1,2"
							   ,$this->DB2_GETQURY_PRODBREAK( $d, $p )
							   ,$datec
							   ,$p
							   ,$lot
								);
			//echo $sql_str; exit;
			return $sql_str;			
		 }
	public function DB2_GETDATA_PRODBREAK( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );
			$dateysday = date('Ymd' , strtotime($d) );	
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$formq1 = "SH.CYOKU_K <> 'S' AND TO_CHAR ( TO_DATE((SH.JITU_SD||SH.JITU_ST ), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq2 = "TO_CHAR( TO_DATE( ( SH.JITU_ED||SH.JITU_ET), 'YYYYMMDDHH24MISS' ), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq3 = "SH.CYOKU_K = 'S' AND TO_CHAR( TO_DATE((SH.JITU_SD||SH.JITU_ST), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$sql_str = sprintf(
			    "SELECT
					 LE.SYOZK_CD PD
					,SH.LINE_CD
					,SH.JITU_LOT LOT
					,SH.CYOKU_K SHIFT
					,SH.PLAN_JUN SEQ
					,SH.HINBAN ITEM_CD
					,SH.LINE_CD||LPAD(SH.HINBAN,25,'0' ) INX				 
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'100000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'101000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END BREAK10
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'120000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'124000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END BREAK12
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'150000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'151000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END BREAK15
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'170000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'173000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END BREAK17
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'220000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'221000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END BREAK22
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'004000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END BREAK00
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'030000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'031000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END BREAK03
					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'050000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'053000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END BREAK05

					,CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'190000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'191000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END S1
					,CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'210000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'214000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END S2
					,CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'001000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END S3		

					,CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'100000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'101000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'120000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'124000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'150000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'151000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'170000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'173000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'220000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'221000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'004000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'030000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'031000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
					 CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'050000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'053000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
					 CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'190000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'191000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
					 CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'210000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'214000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
					 CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'001000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END BREAKTM
		       	   ,TO_CHAR(SYSDATE ,'YYYY-MM-DD HH24:MI:SS') WDATE 
				FROM 
				   SEISAN_H SH
				  ,LINE_MST LE
				WHERE 
					SH.LINE_CD = LE.LINE_CD
					AND SH.JITU_SD >  '%s'
					AND LE.SYOZK_CD = '%s' 
					AND SH.JITU_LOT = '%s'
				GROUP BY					 
				     LE.SYOZK_CD
					,SH.LINE_CD
					,SH.JITU_LOT
					,SH.CYOKU_K
					,SH.PLAN_JUN
					,SH.HINBAN
					,SH.JITU_SD
					,SH.JITU_ST
					,SH.JITU_ED
					,SH.JITU_ET
					"							
					,$datec
					,$p
					,$lot
				);
			//	echo $sql_str; exit;
			return $sql_str;			
		}
	public function DB2_GETDATA_PRODTIME( $d="2020-02-01", $p="PD06" )
		{
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$yd = date('Y-m-d'  , strtotime("- 1 day",   strtotime($d)  ) );
			$sd = date('Y-m-01' , strtotime("+ 0 day",   strtotime($d)  ) );
			$ed = date('Y-m-01' , strtotime("+ 1 month", strtotime($sd) ) );
			$datec = date('Ym01', strtotime( "- 0 month", strtotime($sd) ));
			$tdlot = $this->GETLOT_TBKKFATHAILAND($d);
			$ydlot = $this->GETLOT_TBKKFATHAILAND($yd);
			//$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf(
			"SELECT
			 LE.SYOZK_CD PD
			,SH.LINE_CD
			,CEILING( AVG( CASE WHEN SH.JITU_LOT = '%s' THEN  CAST(SH.JININ_SU AS FLOAT ) END)) YDTOTALMN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.PLAN_SU  END) YDTOTALPN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.JITU_SU  END) YDTOTALAC 
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.JITU_SU  -  SH.PLAN_SU   END)  YDTOTALRM 
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (2, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS YDPRODSEC
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (4, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS YDPRODMIN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (8, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS YDPRODHOU

			,CEILING( AVG( CASE WHEN SH.JITU_LOT = '%s' THEN  CAST(SH.JININ_SU AS FLOAT ) END)) TDTOTALMN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.PLAN_SU  END) TDTOTALPN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.JITU_SU  END) TDTOTALAC 
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  SH.JITU_SU  -  SH.PLAN_SU   END)  TDTOTALRM 
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (2, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS TDPRODSEC
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (4, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS TDPRODMIN
			,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN  TIMESTAMPDIFF (8, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS') - TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS'))) END ) AS TDPRODHOU
 
			,CEILING( AVG( CAST(SH.JININ_SU AS FLOAT ) )) AVGMA_ACCM
			,SUM( SH.PLAN_SU  ) TOTALPN_ACCM
			,SUM( SH.JITU_SU  ) TOTALAC_ACCM 
			,SUM( SH.JITU_SU  ) - SUM( SH.PLAN_SU ) TOTALRM_ACCM 
			,SUM(TIMESTAMPDIFF (2, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODSEC_ACCM
			,SUM(TIMESTAMPDIFF (4, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODMIN_ACCM
			,SUM(TIMESTAMPDIFF (8, CHAR(TIMESTAMP(SH.JITU_ED||SH.JITU_ET,'YYYY.MM.DD.HH.MI.SS')- TIMESTAMP(SH.JITU_SD||SH.JITU_ST,'YYYY.MM.DD.HH.MI.SS')))) AS PRODHOU_ACCM
		    ,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN
					%s 
				  END
				) YDBREAKTM 
		    ,SUM( CASE WHEN SH.JITU_LOT = '%s' THEN
					%s 
				  END
				) TDBREAKTM
			,SUM( 
			        %s  
			    )  BREAKTMACCM
		    FROM
			SEISAN_H SH
			-- ,(
			-- 	SELECT
			-- 	SM.LINE_CD
			-- 	,SUM(SM.JININ_SU) MANS
			-- 	FROM
			-- 	(
			-- 		SELECT  
			-- 		LINE_CD 
			-- 		,AVG(JININ_SU) JININ_SU
			-- 		,JITU_LOT 
			-- 		FROM SEISAN_H 
			-- 		WHERE 
			-- 		JITU_SD >= '%s'  
			-- 		AND TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') BETWEEN '%s 08:00:00' AND '%s 08:00:00' 
			-- 		GROUP BY  LINE_CD, JITU_LOT
			-- 	)SM
			-- 	GROUP BY SM.LINE_CD 
			--  ) B
			,LINE_MST LE 					
			WHERE 
				--SH.LINE_CD = B.LINE_CD
				SH.LINE_CD = LE.LINE_CD
				AND SH.DENSO_F = '02'
				AND SH.JITU_SD >= '%s'
				AND LE.SYOZK_CD = '%s' 
				AND TO_CHAR(TO_DATE(SH.JITU_SD||SH.JITU_ST,'YYYYMMDDHH24MISS'),'YYYY-MM-DD HH24:MI:SS') BETWEEN '%s 08:00:00' AND '%s 08:00:00'
			GROUP BY 
				LE.SYOZK_CD 
				,SH.LINE_CD
				--,B.MANS 	
			ORDER BY 1,2"
			,$ydlot ,$ydlot ,$ydlot ,$ydlot ,$ydlot, $ydlot, $ydlot 
			,$tdlot ,$tdlot ,$tdlot ,$tdlot ,$tdlot, $tdlot, $tdlot
			,$ydlot ,$this->DB2_GETQURY_PRODBREAK( $yd, $p )
			,$tdlot ,$this->DB2_GETQURY_PRODBREAK( $d, $p )
			,$this->DB2_GETQURY_PRODBREAKACCM( $d, $p )
			,$datec
			,$sd, $ed
			,$datec
			,$p
			,$sd, $ed 
			);
			// echo $sql_str; exit;
			return $sql_str;			
		 }	
	
	
	private function DB2_GETQURY_PRODBREAK( $d="2020-02-01", $p="PD06" )
		{
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$sd = date('Ym01' , strtotime($d) );
			$datec = date('Ym01', strtotime( "- 0 month", strtotime($sd) ));	
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime("+ 0 day", strtotime( $d ) ) );	
			$dateysday = date('Ymd' , strtotime("+ 1 day", strtotime( $d ) ) );		
			
			$formq1 = "SH.CYOKU_K <> 'S' AND TO_CHAR ( TO_DATE((SH.JITU_SD||SH.JITU_ST ), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq2 = "TO_CHAR( TO_DATE( ( SH.JITU_ED||SH.JITU_ET), 'YYYYMMDDHH24MISS' ), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq3 = "SH.CYOKU_K = 'S' AND TO_CHAR( TO_DATE((SH.JITU_SD||SH.JITU_ST), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$sql_str = sprintf(
				" 
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'100000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'101000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'120000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'124000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'150000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'151000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'170000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'173000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'220000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'221000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'004000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'030000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'031000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'050000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'053000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'190000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'191000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'210000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'214000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'001000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END 
 				"							
				,$datec
				,$p
				,$lot
				);
			//	echo $sql_str; exit;
			return $sql_str;			
		}	
	private function DB2_GETQURY_PRODBREAKACCM( $d="2020-02-01", $p="PD06" )
		{
			$p = ($p == "PL22") ? sprintf( "PL00" )   : $p ;
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			// $datetoday = date('Ymd' , strtotime($d) );
			$sd = date('Ym01' , strtotime($d) );
			$ls = date('t'    , strtotime($sd));	
			$sql_str = "";
			$datec = date('Ym01', strtotime( "- 0 month", strtotime($sd) ));
			$formq1 = "SH.CYOKU_K <> 'S' AND TO_CHAR ( TO_DATE((SH.JITU_SD||SH.JITU_ST ), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq2 = "TO_CHAR( TO_DATE( ( SH.JITU_ED||SH.JITU_ET), 'YYYYMMDDHH24MISS' ), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq3 = "SH.CYOKU_K =  'S' AND TO_CHAR ( TO_DATE((SH.JITU_SD||SH.JITU_ST ), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			foreach(range( 1, $ls ) as $g){
			$datetoday = date('Ymd', strtotime("+0 day", strtotime( date('Y-m-'.$g , strtotime($sd) )) ) );
			$dateysday = date('Ymd', strtotime("+1 day", strtotime( date('Y-m-'.$g , strtotime($sd) )) ) );	
 
			$sql_str .= sprintf(
				" 
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'100000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'101000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'120000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'124000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'150000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'151000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'170000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'173000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'220000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'221000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'004000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'030000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'031000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$dateysday'||'050000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'053000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 30 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'190000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'191000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$datetoday'||'210000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'214000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 40 ELSE 0 END +
				  CASE WHEN $formq3 < TO_CHAR(TO_DATE( '$dateysday'||'000000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$dateysday'||'001000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
				  "							
				,$datec
				,$p
				,$lot
				);
			}
 
			//	echo $sql_str; exit;
			return substr( $sql_str , 0 ,-9);			
		 }		
	public function ORACLE_GETDATE_HOLIDAYS_FM($m)
		 {
			 return sprintf("
				 SELECT CAL_DATE
				 , LOWER( TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'DAY' ) ) FND
				 , TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'Dy' ) ND
				 , LOWER( TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'DDTH' ) ) DD 
				  FROM M_CAL 
				  WHERE CAL_NO = 1 
				  AND HOLIDAY_FLG = 1 
				  AND TO_CHAR( TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'YYYY/MM') = '%s' "
				  ,$m);
		 }
	 
	 public function ORACLE_GETDATE_SATURDAY_WD($m) 
		 {
			 return sprintf("SELECT 
							 CAL_DATE
							 , LOWER( TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'DAY' ) ) FND
							 , TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'Dy' ) ND
							 , LOWER( TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'DDTH' ) ) DD 
 
							 FROM 
							 M_CAL WHERE CAL_NO = 1 
							 AND HOLIDAY_FLG = 0 
							 AND TO_CHAR( TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'YYYY/MM') = '%s' 
							 AND TO_CHAR( TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'Dy') = 'Sat' 
							 "
							 ,$m);
		 }
	 public function ORACLE_GETDATE_CALENDA_WD($m, $s, $e) 
		 {
			 $sq="";
			 foreach( range($s, $e) as $x => $nx  )
			 {
				 $dc  = date('Ym', strtotime( $nx . "month", strtotime($m) ) );
				 $sq .= sprintf(",COUNT ( CASE WHEN TO_CHAR(TO_DATE(CAL_DATE,'YYYY-MM-DD'), 'YYYYMM') = '%s' THEN CAL_DATE END ) M%s \n", $dc, ($x+1) );
			 }
			 return sprintf("SELECT 
							 %s
							 FROM M_CAL WHERE CAL_NO = 1 AND HOLIDAY_FLG = 0"
							 ,substr($sq,1)
						   );
		 }	

/* @ MYSQL SUBPORT @  */
	public function MYSQL_GETSEQ_REPORTLIST($fid)
	{
		$sq=sprintf( "SELECT  LPAD( ( MAX(FILE_SEQ)+ 1 ), 3, '0') NSEQ FROM report_list WHERE FILE_ID = '%s' ", $fid );
		return $sq;
	}
	public function MYSQL_GETNDATA_REPORTLIST($fid, $seq)
	{
		$sq=sprintf( "SELECT  COUNT(FILE_SEQ) NSEQ FROM report_list WHERE FILE_ID = '%s' AND FILE_SEQ = '%s'", $fid, $seq );
		return $sq;
	}
	public function MYSQL_INSERT_REPORTLIST($ins)
	{
		$sq = sprintf("INSERT INTO report_list ( FILE_ID ,FILE_NAME, FILE_SEQ, FILE_SIZE, FILE_TYPE ,FILE_PATH  ,FILE_CREATED  ,FILE_OWNER ,FILE_PARENT_PATH, FILE_GROUP, CREATED_DATE ) VALUES ( %s )", $ins);
		return $sq;
	}
	public function MYSQL_GETDATA_REPORTLIST($gst='202004', $gen='202004')
	{
		//$sq = sprintf("SELECT * FROM report_list WHERE DATE_FORMAT(FILE_CREATED,'%%Y-%%m-%%d') BETWEEN '%s' AND '%s' ORDER BY FILE_ID DESC ,FILE_CREATED DESC", $gst, $gen);
		$sq = sprintf("SELECT * FROM report_list ORDER BY FILE_ID DESC ,FILE_CREATED DESC", $gst, $gen);
		return $sq;
	}




/* @ GET LOT */
	private function GETLOT_TBKKFATHAILAND($lot_dt)
		{
			$_YEARS = array ('J', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
			$_MONTH = array ('L', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');

			$D = date('d',strtotime($lot_dt));
			$M = date('m',strtotime($lot_dt));
			$Y = date('y',strtotime($lot_dt));

			//echo $_YEARS[($Y % 10)].$_MONTH[($M % 12)].$D; exit();
			return $_YEARS[($Y % 10)].$_MONTH[($M % 12)].$D;
		 }	


		}


// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */
