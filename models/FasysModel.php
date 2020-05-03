<?php
header("Content-Type:text/html; charset=UTF-8");
require_once dirname(__FILE__) . '/sql/fasys.php';
date_default_timezone_set("Asia/Bangkok");
//ini_set('mssql.charset', 'UTF-8');
class FasysModel extends Main_Model
{
    var $FASYS;
    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
        $this->FASYS = new FASYSTEM();

    }

    public function fasys_derror()
        {
            $fa = $this->fab_load();
            $codi = ($_POST) ? $_POST["c"] : "2020-02-28" ;
            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_ERRORDATEPRO($codi) );

            return $far;
        }

    public function fasys_derror_false($c)
        {
            $fa = $this->fab_load();
            $codi = $c;
            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_ERRORDATEPRO_FALSE($codi) );

            return $far;
        }
    public function fasys_derror_update()
        {
            $codi = ($_POST) ? $_POST["c"] : ($_GET) ? $_GET["c"] : date('Y-m-d', strtotime("- 1 day", strtotime(date('Y/m/d')))) ;
            $val = $this->fasys_derror_false($codi);
            $f = $this->dirfile_create("log/fasys/","logupdate", "csv", date('Ymd')) ;
            $file = fopen($f,"a");
            $c = 0;
            foreach( $val as $inx => $v)
                {
                   $this->exec_odbc( $this->fab, $this->FASYS->FASYS_UPDATE_ERRORDATE($v) );
                   //echo $this->FASYS->FASYS_UPDATE_ERRORDATE($v);
                   //exit;
                   fputcsv($file, $v);
                   $c++;

                }
            fclose($file);
            $f = $this->dirfile_create("log/fasys/","log", "log", date('Ym') ) ;
            $file = fopen( $f,"a");
            fwrite($file,"update date/time: ". date('Y-m-d H:i:s') . " ,data condition date= " . $codi  . " lot= " . $this->FASYS->GETLOT_TBKKFATHAILAND($codi). " ,data updated: " . str_pad($c, 5, " ", STR_PAD_RIGHT) . " row" . "\r\n" );
            fclose($file);           
            return $f;
        }

    public function fasys_production_pd()
        {

            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;
            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();
            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODOFPD($codi, $copd) );

            return $far;
        }
    public function fasys_production_cr()
        {
            
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();

            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi, $copd) );

            return $far;
        }
    public function fasys_production_al()
        {
            $fk =  $this->fak_load();
            $fb =  $this->fab_load();
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $data=array("total" => array("pd01" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD01") )
                                        ,"pd02" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD02") )
                                        ,"pd03" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD03") )
                                        ,"pd04" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD04") )
                                        ,"pd05" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD05") )
                                        ,"pd06" => $this->exec( $fb, $this->FASYS->DB2_GETDATA_PRODOFPD( $codi,"PD06") )                                                                                                                              
                                        ),
                        "curent"=> array("pd01" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD01") )
                                        ,"pd02" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD02") )
                                        ,"pd03" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD03") )
                                        ,"pd04" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD04") )
                                        ,"pd05" => $this->exec( $fk, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD05") )
                                        ,"pd06" => $this->exec( $fb, $this->FASYS->DB2_GETDATA_PRODOFCR( $codi,"PD06") )                                                                                                                              
                                      )); 
           // var_dump($data);exit;
            return $data;
        }
    public function fasys_production_seqln()
        {
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();

            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODOFSEQLINE( $codi, $copd) );

            return $far;
        }
    public function ejsys_production()
        {
             
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;

            $ex  = $this->expk_load();

            $exp = $this->exec( $ex, $this->FASYS->ORA_GETDATA_PROD( $codi ) );

            return $exp;
        }
    public function fasys_production_ln()
        {
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();

            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODOFLINE( $codi, $copd) );

            return $far;
        }
    public function fasys_production_break()
        {
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();

            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODBREAK( $codi, $copd) );

            return $far;
        }  
    public function fasys_production_cnt()
        {
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            $copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa = ( $copd == "PD06" ) ? $this->fab_load() : $this->fak_load();

            $far = $this->exec( $fa, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, $copd) );

            return $far;
        }          
    public function fasys_production_linecnt()
        {
            $codi = ($_GET) ? $_GET["d"] : "2020-03-13" ;
            //$copd = ($_GET) ? strtoupper($_GET["p"]) : strtoupper("pd06") ;

            $fa10 = $this->fak_load();
            $fa08 = $this->fab_load();

            $far =array(  "PD01" =>$this->exec( $fa10, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD01") ) 
                         ,"PD02" =>$this->exec( $fa10, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD02") ) 
                         ,"PD03" =>$this->exec( $fa10, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD03") ) 
                         ,"PD04" =>$this->exec( $fa10, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD04") ) 
                         ,"PD05" =>$this->exec( $fa10, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD05") ) 
                         ,"PD06" =>$this->exec( $fa08, $this->FASYS->DB2_GETDATA_PRODCNT( $codi, "PD06") ) 
                       );

            return $far;
        }         

    public function ejsys_cycletime()
        {
            
            $codi = ($_GET) ? strtoupper($_GET["p"]) : "PD01" ;
            $codi = ( $codi == "PD06" ) ? "K2".$codi : "K1".$codi;
            $ex  = $this->expk_load();

            $exp = $this->exec( $ex, $this->FASYS->ORA_GETDATA_CYCLETIME( $codi ) );

            return $exp;
        }




    public  function alsys_productionline()
        {
            
            $faseq = $this->fasys_production_seqln();
            $fasum = $this->fasys_production_ln();
            $ejcyc = $this->ejsys_cycletime();
            $arSet = $this->RETURN_ARRAYPROD();
            $datAr = array(); 
            foreach( $fasum as $v)
            {   
                //if( $v["LINE_CD"] == 'K2M066' ) { var_dump($v); exit; }
                $datAr[$v["LINE_CD"]] = $arSet;
                $ln = $this->LINEPRODGET($faseq, $v["LINE_CD"]);
                    if( count( $ln ) > 0 )
                    {   
                        $datAr[$v["LINE_CD"]] = $this->SETPRODLINETOARRAY( $datAr[$v["LINE_CD"]], $ln );
                        $cy = $this->CYCCELTIMEGET( $ejcyc, $ln["INX"] );
                            if( count( $cy )  > 0 )
                            {
                                $datAr[$v["LINE_CD"]] = $this->SETPRODCYCLETIMETOARRAY( $datAr[$v["LINE_CD"]], $cy ); 
                            }
                            $datAr[$v["LINE_CD"]] = $this->SETPRODTOTALTOARRAY( $datAr[$v["LINE_CD"]], $v );
                            $datAr[$v["LINE_CD"]] = $this->SETACTUALSTANDARD( $datAr[$v["LINE_CD"]] );    
                    }
                    else
                    {
                        $datAr[$v["LINE_CD"]] = $this->SETPRODTOTALTOARRAY( $datAr[$v["LINE_CD"]], $v );
                        $datAr[$v["LINE_CD"]] = $this->SETACTUALSTANDARDCOMPLETE( $datAr[$v["LINE_CD"]] );
                    }
                      
            } 
            return $datAr;
           // var_dump( $datAr ); exit;
        }

    private function CYCCELTIMEGET($ar, $k){ $data=array(); foreach( $ar as $v)  if ( $v["INX"] == $k)     { return $v; }  else $data = array(); return $data; } 
    private function LINEPRODGET($ar, $k)  { $data=array(); foreach( $ar as $v)  if ( $v["LINE_CD"] == $k) { return $v; }  else $data = array(); return $data; }
    
    private function SETPRODLINETOARRAY($sto, $ar)
        { 
           $sto["PLANDATE"] = $ar["PLANDATE"];
           $sto["SEQ"     ] = $ar["SEQ"     ];
           $sto["ITEM_CD" ] = $ar["ITEM_CD" ];
           $sto["PLANPROD"] = $ar["PLANPROD"];
           $sto["ACTUPROD"] = $ar["ACTUPROD"];
           $sto["REMNPROD"] = $ar["REMNPROD"];
           $sto["TYPELINE"] = $ar["TYPELINE"];
           $sto["STATUSCD"] = $ar["STATUSCD"];
           $sto["STATUSNM"] = $ar["STATUSNM"];
           $sto["INX"     ] = $ar["INX"     ];
           $sto["SDATE"   ] = $ar["SDATE"   ];
           $sto["EDATE"   ] = $ar["EDATE"   ];
           $sto["CDATE"   ] = $ar["CDATE"   ];
           $sto["SHIF"    ] = $ar["SHIF"    ];
           $sto["LOT"     ] = $ar["LOT"     ];
           $sto["WI"      ] = $ar["WI"      ];
        //    $sto["PRODSEC" ] = $ar["PRODSEC" ];
        //    $sto["PRODMIN" ] = $ar["PRODMIN" ];
        //    $sto["PRODHOU" ] = $ar["PRODHOU" ];
        //    $sto["BREAKTM" ] = $ar["BREAKTM" ];
           return $sto;
        }
    private function SETPRODCYCLETIMETOARRAY($sto, $ar)
        {
          $sto["CYCLETM"] = $ar["REMARK"];
          return $sto;
        }
    private function SETPRODTOTALTOARRAY($sto, $ar)
        {

           $sto["PD"]       = $ar["PD"];
           $sto["LINE_CD"]  = $ar["LINE_CD"];
           $sto["TOTALPN"]  = $ar["TOTALPN"];
           $sto["TOTALAC"]  = $ar["TOTALAC"];
           $sto["TOTALRM"]  = $ar["TOTALRM"];
           $sto["PRODSEC" ] = $ar["PRODSEC" ];
           $sto["PRODMIN" ] = $ar["PRODMIN" ];
           $sto["PRODHOU" ] = $ar["PRODHOU" ];
           $sto["BREAKTM" ] = $ar["BREAKTM" ];      
           
           //if( $ar["LINE_CD"] == 'K2M066' ) { var_dump($sto);  }
           //else var_dump( $ar["LINE_CD"] );           

           return $sto; 
         }
    private function SETACTUALSTANDARD( $sto )
        {
           $sto["STDACTU"]  = ( $sto["CYCLETM"]  > 0 ) ? sprintf('%0.0f', floor( ( $sto["PRODMIN"] - $sto["BREAKTM"] ) / $sto["CYCLETM" ] ) ) : null;
           //$sto["STDACTU"]  = ( $sto["CYCLETM"]  > 0 ) ? sprintf('%0.0f', floor( ( $sto["PRODMIN"] ) / $sto["CYCLETM" ] ) ) : null;
           $sto["STDDIFF"]  = sprintf('%0.0f',  $sto["ACTUPROD"]  - $sto["STDACTU"] );

           $sto["PRGCUNT"]  = ( $sto["PLANPROD"]  > 0 ) ? sprintf('%0.2f', $sto["ACTUPROD"]  / $sto["PLANPROD"]  * 100) : "0"; 
           $sto["PRGTOTA"]  = ( $sto["TOTALPN"]   > 0 ) ? sprintf('%0.2f', $sto["TOTALAC" ]  / $sto["TOTALPN"]   * 100) : "0";
           $sto["PRGCYTM"]  = ( $sto["STDACTU"]   > 0 ) ? sprintf('%0.2f', $sto["ACTUPROD"]  / $sto["STDACTU"]   * 100) : "0";
           return $sto;
         }
    private function SETACTUALSTANDARDCOMPLETE( $sto )
        {
            $sto["PRGCUNT"]  = ( $sto["PLANPROD"]  > 0 ) ? sprintf('%0.2f', $sto["ACTUPROD"]  / $sto["PLANPROD"]  * 100) : "0"; 
            $sto["PRGTOTA"]  = ( $sto["TOTALPN"]   > 0 ) ? sprintf('%0.2f', $sto["TOTALAC" ]  / $sto["TOTALPN"]   * 100) : "0";
            $sto["PRGCYTM"]  = ( $sto["STDACTU"]   > 0 ) ? sprintf('%0.2f', $sto["ACTUPROD"]  / $sto["STDACTU"]   * 100) : "0";
           return $sto;
         }                  
    private function RETURN_ARRAYPROD()
        {
            $arraySet  = array(
                "PD"       => "",
                "LINE_CD"  => "",
                "PLANDATE" => "",
                "SEQ"      => "",
                "ITEM_CD"  => "",
                "PLANPROD" => "0",
                "ACTUPROD" => "0",
                "REMNPROD" => "0",
                "TYPELINE" => "",
                "STATUSCD" => "",
                "STATUSNM" => "",
                "INX"      => "",
                "SDATE"    => "",
                "EDATE"    => "",
                "CDATE"    => "",
                "SHIF"     => "",
                "LOT"      => "",
                "WI"       => "",
                "PRODSEC"  => "0",
                "PRODMIN"  => "0",
                "PRODHOU"  => "0",
                "CURTSEC"  => "0",
                "CURTMIN"  => "0",
                "CURTHOU"  => "0",                
                "CYCLETM"  => "0",
                "STDACTU"  => "0",
                "STDDIFF"  => "0",
                "TOTALPN"  => "0",
                "TOTALAC"  => "0",
                "TOTALRM"  => "0",
                "PRGCUNT"  => "0",
                "PRGTOTA"  => "0",
                "PRGCYTM"  => "0",
                "BREAKTM"  => "0"
             );
            return $arraySet;            
         }
    
    
    
    
    
    
    
    
    

    private function dirfile_create($dr, $fn, $ty , $d)
        {
			if( is_dir($dr) === false ) mkdir($dr);
			$dr = sprintf("%s/%s/", $dr, date('Ym') );
			if( is_dir($dr) === false ) mkdir($dr);
			$filename = sprintf("%s%s.%s", $dr, $fn ."-". $d , $ty ); 
            return $filename;
        }

    

}
?> 
