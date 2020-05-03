<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type:text/html; charset=UTF-8");
//ini_set('mssql.charset', 'UTF-8');
date_default_timezone_set("Asia/Bangkok");
class FASYSTEM
{
   


   public function __construct()
   {
   		//echo "Load Complete!"; exit;

   }
		
/* @DB2@  */
	public function DB2_GETDATA_ERRORDATEPRO( $d="2020-02-01" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );
			$sql_str = sprintf( "SELECT
			 LINE_CD 
			,PLAN_HI  PLAN_DATE
			,PLAN_JUN SEQ
			,HINBAN ITEM_CD
			,PLAN_SU PLAN_QTY
			,JITU_SU ACTU_QTY
			,TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS'),'YYYY/MM/DD HH24:MI:SS') SDATE
			,TO_CHAR(TO_DATE(JITU_ED||JITU_ET,'YYYYMMDDHH24MISS'),'YYYY/MM/DD HH24:MI:SS') EDATE
			,SAGYO_SIJI_NO WINUMBER
			,JITU_LOT LOT
			,CASE WHEN TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS') < TO_DATE('%s'||'080000', 'YYYYMMDDHH24MISS')
					THEN 'FALSE'
					ELSE 'TRUE'
				END FCHECK
			,CASE WHEN TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS') < TO_DATE('%s'||'080000', 'YYYYMMDDHH24MISS')
					THEN TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS')+ 1 DAY, 'YYYYMMDD')
					ELSE TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS')+ 0 DAY, 'YYYYMMDD')
				END MD_SDATE
			,JITU_ST MD_STIME
			,CASE WHEN TO_DATE(JITU_ED||JITU_ET,'YYYYMMDDHH24MISS') < TO_DATE('%s'||'080000', 'YYYYMMDDHH24MISS')
					THEN TO_CHAR(TO_DATE(JITU_ED||JITU_ET,'YYYYMMDDHH24MISS')+ 1 DAY, 'YYYYMMDD')
					ELSE TO_CHAR(TO_DATE(JITU_ED||JITU_ET,'YYYYMMDDHH24MISS')+ 0 DAY, 'YYYYMMDD')
				END MD_EDATE
			,JITU_ET MD_ETIME
			FROM 
			
			SEISAN_H
			
			WHERE
				JITU_SD > '%s'
			AND JITU_SU > 0
			--AND	TO_CHAR(TO_DATE(JITU_SD||JITU_ST,'YYYYMMDDHH24MISS'),'YYYY/MM/DD HH24:MI:SS') >= TO_CHAR(SYSDATE - 1 DAY,'YYYY/MM/DD HH24:MI:SS')
			AND	JITU_LOT = '%s'
			
			
			ORDER BY 10"
			,$datetoday
			,$datetoday
			,$datetoday
			,$datec
			,$lot
			);

			//echo $sql_str; exit;
			return $sql_str;
		} 
	public function DB2_GETDATA_ERRORDATEPRO_FALSE( $d="2020-02-01" )
		{
			$datec = date('Ym01', strtotime($d) );
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );
			$sql_str = sprintf( "SELECT * FROM (%s) ER WHERE ER.FCHECK = 'FALSE'",$this->DB2_GETDATA_ERRORDATEPRO($d) );
			
			//echo $sql_str; exit;
			return $sql_str;
		}

	public function FASYS_UPDATE_ERRORDATE($d)
        {
			$sql_str = sprintf("UPDATE SEISAN_H
								SET  JITU_SD = '%s', JITU_ED = '%s'
								WHERE
									LINE_CD  = '%s'
								AND	JITU_LOT = '%s'
								AND PLAN_JUN = '%s'
								AND SAGYO_SIJI_NO = '%s'
								" 
								,$d["MD_SDATE"]
								,$d["MD_EDATE"]
								,$d["LINE_CD"]
								,$d["LOT"]
								,$d["SEQ"]
								,$d["WINUMBER"]
								);
			return $sql_str;
        }



	public function GETLOT_TBKKFATHAILAND($lot_dt)
		{
		$_YEARS = array ('J', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
		$_MONTH = array ('L', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');

		$D = date('d',strtotime($lot_dt));
		$M = date('m',strtotime($lot_dt));
		$Y = date('y',strtotime($lot_dt));

		//echo $_YEARS[($Y % 10)].$_MONTH[($M % 12)].$D; exit();
		return $_YEARS[($Y % 10)].$_MONTH[($M % 12)].$D;
		}

	

/* DB2 PRODUCTION DATA */
	public function DB2_GETDATA_PRODOFPD( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf("SELECT
							   	LT.SYOZK_CD PD
							   ,SUM( SH.PLAN_SU ) TOTALPLAN_QTY
							   ,SUM( SH.JITU_SU ) TOTALACTU_QTY
							   ,SUM( SH.JITU_SU ) - SUM( SH.PLAN_SU )  TOTALALDIFF_QTY
							   ,ROUND ( CAST( SUM( SH.JITU_SU  ) AS float ) / CAST ( SUM( SH.PLAN_SU ) AS float ) ,2)  PROGESS_PROD
							   ,SH.JITU_LOT LOT			
							   FROM 
							   	SEISAN_H SH
							   ,LINE_MST LT
							   WHERE
							    	SH.LINE_CD = LT.LINE_CD 
							    AND	JITU_SD > '%s'
							    AND LT.SYOZK_CD = '%s'
							    AND JITU_LOT = '%s'	
							    GROUP BY 
							   	LT.SYOZK_CD, SH.JITU_LOT"
							   ,$datec
							   ,$p
							   ,$lot
								);
			return $sql_str;			
		}
	public function DB2_GETDATA_PRODOFCR( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf("SELECT
							   	LT.SYOZK_CD PD
							   ,SUM( SH.PLAN_SU ) TOTALPLAN_QTY
							   ,SUM( SH.JITU_SU ) TOTALACTU_QTY
							   ,SUM( SH.JITU_SU ) - SUM( SH.PLAN_SU )  TOTALALDIFF_QTY
							   ,ROUND( ( CAST( SUM( SH.JITU_SU  ) AS float ) / CAST ( SUM( SH.PLAN_SU ) AS float)) , 2 ) PROGESS_PROD
							   ,SH.JITU_LOT LOT			
							   FROM 
							   	SEISAN_H SH
							   ,LINE_MST LT
							   WHERE
							    	SH.LINE_CD = LT.LINE_CD 
							    AND	JITU_SD > '%s'
							    AND LT.SYOZK_CD = '%s'
							    AND JITU_LOT = '%s'
								AND SH.DENSO_F = '00'	
								
							    GROUP BY 
							   	LT.SYOZK_CD, SH.JITU_LOT"
							   ,$datec
							   ,$p
							   ,$lot
								);
			return $sql_str;			
		}
	public function DB2_GETDATA_PRODCNT( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime($d) );			
			$sql_str = sprintf("SELECT
							   	LE.SYOZK_CD PD
							   ,SH.LINE_CD   
							   ,SH.JITU_LOT LOT			
							   FROM 
							   	SEISAN_H SH
							   ,LINE_MST LE
							   WHERE
							    	SH.LINE_CD = LE.LINE_CD 
							    AND	JITU_SD > '%s'
							    AND LE.SYOZK_CD = '%s'
							    AND JITU_LOT = '%s'
							    GROUP BY 
							   	LE.SYOZK_CD, SH.JITU_LOT ,SH.LINE_CD"
							   ,$datec
							   ,$p
							   ,$lot
								);
			return $sql_str;			
		}


/* ORACLE PRODUCTION DATA */
	public function ORA_GETDATA_PROD( $d="2020-02-01")
		{
			$datec = date('Y/m/d' , strtotime( "- 5 day", strtotime($d) ));
			$datetoday = date('Y/m/01', strtotime( "- 0 day", strtotime($d) ));
			$datet = date('Y/m/t' , strtotime( "- 0 day", strtotime($d) ));
			$dated = date('Y/m/d' , strtotime($d) );			
			$sql_str = sprintf("SELECT
								 CASE WHEN VM.PARENT_SEC_CD = 'K2PD06' THEN 'K1PD06' ELSE VM.PARENT_SEC_CD END AS PD
								,NVL(SUM(CASE WHEN A.STATUS = 1 AND TO_CHAR(A.PDATE,'YYYY/MM/DD') = '$dated' THEN A.QTY END ),0)  AS PLANDAY 
								,NVL(SUM(CASE WHEN A.STATUS = 2 AND TO_CHAR(A.PDATE,'YYYY/MM/DD') = '$dated' THEN A.QTY END ),0)  AS ACTUDAY
								,NVL(SUM(CASE WHEN A.STATUS = 2 AND TO_CHAR(A.PDATE,'YYYY/MM/DD') = '$dated' THEN A.QTY END ),0) - NVL(SUM(CASE WHEN A.STATUS = 1 AND TO_CHAR(A.PDATE,'YYYY/MM/DD') = '$dated' THEN A.QTY END ),0)  AS DIFFDAY
								,NVL(SUM(CASE WHEN A.STATUS = 1  THEN A.QTY END ),0)  AS PLANACCM 
								,NVL(SUM(CASE WHEN A.STATUS = 2  THEN A.QTY END ),0)  AS ACTUACCM 
								,NVL(SUM(CASE WHEN A.STATUS = 2  THEN A.QTY END ),0) - NVL(SUM(CASE WHEN A.STATUS = 1  THEN A.QTY END ),0)  AS DIFFACCM
								,NULL  AS PROGESS

									FROM
									(
										SELECT
											1 AS STATUS
											,TD.PLANT_CD AS PLANT
											,TD.SOURCE_CD AS LINE_CD
											,VD.SEC_NM AS LINE_NAME
											,TD.ITEM_CD
											,MI.ITEM_NAME
											--,MP.MODEL
											,TD.ACPT_PLAN_DATE AS PDATE
											,TD.ODR_QTY AS QTY
											FROM
												(SELECT * FROM T_OD WHERE OD_TYP = 2 AND OUTSIDE_TYP = 1 AND NOT (ODR_STS_TYP = 9 AND TOTAL_RCV_QTY = 0)) TD
											,VM_DEPARTMENT VD
											,M_ITEM MI
											--,M_PLANT_ITEM MP
											WHERE
												TD.ITEM_CD   = MI.ITEM_CD(+)
											AND TD.SOURCE_CD = VD.SEC_CD(+)
											AND NOT(TD.ODR_STS_TYP = 9 AND TD.TOTAL_RCV_QTY = 0)
											AND TO_CHAR(TD.ACPT_PLAN_DATE,'YYYY/MM/DD') BETWEEN '$datetoday' AND '$dated'

										UNION ALL

										SELECT
											2 AS STATUS
											,TR.PLANT_CD AS PLANT
											,TR.WS_CD AS LINE_CD
											,VD.SEC_NM AS LINE_NAME
											,TR.ITEM_CD AS ITEM_CD
											,MI.ITEM_NAME AS ITEM_NAME
											--,MP.MODEL
											,TR.OPR_DATE AS PDATE
											,TR.ACPT_QTY AS QTY
											FROM
												T_OPR_RSLT TR
											,M_ITEM MI
											,VM_DEPARTMENT VD
											--,M_PLANT_ITEM MP
											WHERE
											TR.ITEM_CD = MI.ITEM_CD(+)
											AND TR.WS_CD = VD.SEC_CD(+)
											AND TO_CHAR(TR.OPR_DATE,'YYYY/MM/DD') BETWEEN '$datetoday' AND '$dated'
										--AND TR.ITEM_CD = MP.ITEM_CD(+)
									) A
										,VM_DEPARTMENT_CLASS VM
										,VM_DEPARTMENT VDD
										,M_PLANT_ITEM MP
										WHERE
										A.LINE_CD = VM.COMP_SEC_CD(+)
										AND A.LINE_CD = VDD.SEC_CD(+)
										AND A.PLANT = MP.PLANT_CD
										AND A.ITEM_CD = MP.ITEM_CD(+)
										AND NOT ( VM.PARENT_SEC_CD IN ( 'K1LG00', 'K2PL00' ) )
										--AND A.ITEM_CD = '5JX371-3590'
										GROUP BY
										VM.PARENT_SEC_CD
									ORDER BY
										1"
								);
			return $sql_str;			
		}

	public function ORA_GETDATA_CYCLETIME( $p="PD06" )
		{	
			$sql_str = sprintf("SELECT 
								 MS.PLANT_CD
								--,CASE WHEN VM.PARENT_SEC_CD = 'K1LG00' THEN 'K1PL00' ELSE VM.PARENT_SEC_CD END PD
								,VM.PARENT_SEC_CD PD  
								,MS.SOURCE_CD LINE_CD
								,MS.ITEM_CD
								,MS.SPLIT_PRIORITY LINE_PRIORITY
								,MS.REMARK
								,SOURCE_CD || LPAD(MS.ITEM_CD,25,'0') INX
								FROM 
									M_SOURCE MS
								,VM_DEPARTMENT_CLASS VM 
								WHERE 
									MS.SOURCE_CD = VM.COMP_SEC_CD(+)
								AND MS.REMARK IS NOT NULL
								AND TO_CHAR( MS.EFF_PHASE_OUT_DATE , 'YYY/MM//DD' )  >= TO_CHAR( SYSDATE , 'YYY/MM//DD' )
								AND MS.OUTSIDE_TYP = 1
								AND VM.PARENT_SEC_CD = '%s'
								ORDER BY 1,2,3,4 "
							   ,$p
								);
								//echo $sql_str; exit;
			return $sql_str;			
		}

	public function DB2_GETDATA_PRODOFSEQLINE( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
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
			$datetoday = date('Ymd' , strtotime("+ 0 day", strtotime( $d ) ) );	
			$dateysday = date('Ymd' , strtotime("+ 1 day", strtotime( $d ) ) );	
			
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
	private function DB2_GETQURY_PRODBREAK( $d="2020-02-01", $p="PD06" )
		{
			$datec = date('Ymd', strtotime( "- 5 day", strtotime($d) ));
			$lot = $this->GETLOT_TBKKFATHAILAND($d);
			$datetoday = date('Ymd' , strtotime("+ 0 day", strtotime( $d ) ) );	
			$dateysday = date('Ymd' , strtotime("+ 1 day", strtotime( $d ) ) );		
			
			$formq1 = "SH.CYOKU_K <> 'S' AND TO_CHAR ( TO_DATE((SH.JITU_SD||SH.JITU_ST ), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq2 = "TO_CHAR( TO_DATE( ( SH.JITU_ED||SH.JITU_ET), 'YYYYMMDDHH24MISS' ), 'YYYY/MM/DD HH24:MI:SS' )";
			$formq3 = "SH.CYOKU_K = 'S' AND TO_CHAR( TO_DATE((SH.JITU_SD||SH.JITU_ST), 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' )";
			$sql_str = sprintf(
			    " CASE WHEN $formq1 < TO_CHAR(TO_DATE( '$datetoday'||'100000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) AND $formq2 > TO_CHAR(TO_DATE( '$datetoday'||'101000', 'YYYYMMDDHH24MISS'), 'YYYY/MM/DD HH24:MI:SS' ) THEN 10 ELSE 0 END +
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





	}

	// END Template Class

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */