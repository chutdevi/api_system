<?php
header("Content-Type:text/html; charset=UTF-8");
ini_set('mssql.charset', 'UTF-8');
class Ex_recModel extends Main_Model
{
    var $query_str;
    var $loct = array();
    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean(); 
        flush();
      
        $this->fal_load();
        //$this->load->libraries('Pro_query','qr');  
    }

    public function rec_in()
    {
        $this->ship_load();
        $sql_ship = "CALL ACEPT_LIST10(0,0)";

        $recue = $this->exec($this->ship, $sql_ship);

        $sql_ins = "";
        $count_ins = 0;
        foreach ($recue as $ind => $value) 
        {
            $sql_ins = "EXEC INSFA_RECEIVEIN 
             '{$value['PLANT_CD']}'
            ,'{$value['PUCH_ODR_CD']}'
            ,'{$value['SEQ']}'
            ,'{$value['ITEM_CD']}'
            ,'{$value['INSTRUCT_QTY']}'
            ,'{$value['QTY']}'
            ,'{$value['DLV_DATE']}'
            ,'{$value['INSPC_FLG']}'
            ,'{$value['STATUS_FLG']}'
            ,'{$value['UPDATED_DATE']}'
            ,'{$value['VEND_CD']}'
            ,'{$value['INVOICE_NO']}'
            ,'{$value['COMP_FLG']}'
            ,'{$value['KEY_UP']}'

            ,'{$this->datetime->format('Y-m-d H:i:s')}'
            ,'{$this->datetime->format('Y-m-d H:i:s')}'
            ,'0'
            ,'0'
            ,'{$value['VEND_CD']}{$value['EXEC_DATE']}'
            ,'0'
            ,'0'   
            ";
          $flg_ins = $this->exec($this->fal, $sql_ins )[0];
          //echo  ("STATUS NO : " . $flg_ins['FLG'] . " Message : " . $flg_ins['MSG']); 
          //echo "<br>";
          if( $flg_ins['FLG'] > 0 ) $count_ins++;
        }
        $this->ship->close();
        //var_dump($recue); exit;
        $up =  $this->rec_even();
        $this->rec_move();
        //$this->rec_ptag();

        return "Count Data insert : " . $count_ins . " Count Data update : " . $up; 

    }
    public function rec_even()
    {
        $this->expk_load();

        $recue = $this->exec($this->fal, "SELECT * FROM FA_RECEIVEIN WHERE EVEN_FLG = 0");
        $update_succ = 0;
        //var_dump($recue); exit;
        foreach( $recue  as $ind => $value)
        {
            $sql_expk = $this->pro_query->ORACLE_GETDATA_MOVESTOCK( $value['ITEM_CD'], $value['PUCH_ODR_CD'] );                                         
            $recue = $this->exec($this->expk, $sql_expk);
            if( count( $recue ) > 0 )
            {

                $sql_up = "EXEC UPDFA_RECEIVEINEVENFLG '1', '{$value['PUCH_ODR_CD']}', '{$value['ITEM_CD']}', 0 ";

                $mes = $this->exec($this->fal, $sql_up);

                $update_succ++; 
            }
        }
        return $update_succ;

    }
    
    public function rec_move()
    {
        $this->expk_load();

        $sql_fal = $this->pro_query->MSSQL_GETDATA_EVENQC();
        $exe_fal = $this->exec($this->fal, $sql_fal);

        //var_dump($exe_fal); exit;
        //echo $this->datetime->format('Y\-m\-d\ H:i:s') . "<br>";
        $nm = 1;
        foreach ($exe_fal as $r => $value) 
        {
            # code...`
        
            $sql_expk = $this->pro_query->ORACLE_CHECK_MOVESTOCK($value["PUCH_ODR_CD"]);
            $recue    = $this->exec($this->expk, $sql_expk);

            if($value["INSPC_FLG"] == '4')  $mes = $this->exec($this->fal, "EXEC UPDFA_RECEIVEINEVENFLG '2', '{$value["PUCH_ODR_CD"]}', '{$value['ITEM_CD']}', '1' ");

            $instock = 0; 
            $after_index = 0;
            $index_st = 0;
            //var_dump($recue); exit;  
        
            for ($ind = 0; $ind < count($recue); $ind++)
            {
                if ( $recue[$ind]["AFTER_QTY"] > 0 )
                {
                    $instock  += $recue[$ind]["CURENT_QTY"];
                    //$ind++;
                }
                else
                {

                    //echo $instock . ' : INSTOCK ' . "<hr>";
                    foreach (range($index_st  , $ind-1) as $key)
                    {
                        $sql_up = "EXEC UPDFA_RECEIVEINEVENFLG '2', '{$recue[$key]["PUCH_ODR_CD"]}', '{$recue[$key]['ITEM_CD']}', '2' ";

                        $mes = $this->exec($this->fal, $sql_up);
                        // echo ($nm++) . " : " . $mes[0]["MSG"];
                        // echo "<br>";
                    }
                    //$ind++;
                    $index_st =  $ind+1; 
                    $instock = 0;                  
                    //echo "<hr>";
                }
                //echo $index_st . " " . $instock. "<br>" ;
            }

            //exit;
        }
    }

    public function rec_get_tag()
    {
        $po_cd = '0';
        if( !empty($_GET) ) $po_cd = $_GET["po_cd"];
            $exe_fal = $this->exec($this->fal, "SELECT * FROM FA_RECEIVE_TAG WHERE PUCH_ODR_CD = '$po_cd'");
        return $exe_fal;
    }

    public function rec_ptag()
    {
        $db_ship = $this->ship_load();
        $this->set_location();
        $sql_fal  = $this->pro_query->MSSQL_GETDATA_PROCZERO();
        $us_id = 'SYSTEM';
        $ins_msg = "";
        $upd_msg = "";
        if( !empty($_GET) ) $us_id = $_GET["user_id"];
        
        //echo $this->datetime->format('Y-m-d H:i:s'); exit;
        //echo( $us_id ); exit;


        $exe_fal = $this->exec($this->fal, $sql_fal);

        //var_dump($exe_fal); exit;

        foreach ($exe_fal as $kfal => $kval) 
        {
            $sql_ship = "CALL ACEPT_TAGLIST10('{$kval['PUCH_ODR_CD']}') ;";
            $exe_shp = $this->exec($db_ship, $sql_ship);
              
            foreach( $exe_shp as $ind_sh => $val_sh)
            {
                $str_ins = "EXEC INSFA_RECEIVE_TAG ";
                foreach( $val_sh as $ind_ins => $val_ins)
                {
                    $str_ins .= "'$val_ins', ";
                }
                
                //$str_ins = substr($str_ins, 0, -2);
                
                if( $kval["EVEN_FLG"] == 2 )
                    $str_ins .= " '{$this->loct[$kval["ITEM_CD"]]}' , '{$this->datetime->format('Y-m-d H:i:s')}', '$us_id', '2', '{$kval["LOT_RECEIVE"]}', '{$this->datetime->format('Y-m-d H:i:s')}', '$us_id', 1 ";
                else
                    $str_ins .= " 'QC-INCOMING' , '{$this->datetime->format('Y-m-d H:i:s')}', '$us_id', '0', '{$kval["LOT_RECEIVE"]}', '{$this->datetime->format('Y-m-d H:i:s')}', '$us_id', 1";
                //echo $str_ins; exit;

                $ins_msg  = $this->exec($this->fal, $str_ins);
                //echo $ins_msg; //exit;
                //$upd_msg  = $this->exec($this->fal, "EXEC UPDFA_RECEIVEINPROCFLG '1', '{$kval["PUCH_ODR_CD"]}' , '{$kval["ITEM_CD"]}' ");
            }   

            $db_ship->close();
            //var_dump($ins_msg); 
            //            exit;
        }

        return $this->rec_loca( count($exe_fal) );

       
        //var_dump($exe_fal); exit;
        //$exe_shp = $this->exec($this->ship, $sql_ship);        
    }
    public function rec_loca($cut)
    {
        $sub_fal = $this->fal_load();
        $recue = $this->exec($sub_fal, $this->pro_query->MSSQL_GETDATA_QCINCOMING());
        
        foreach( $recue as $ind => $val)
        {
            $sql_up = "EXEC UPDFA_RECEIVE_TAGLOCATIONPART '{$this->loct[$val["ITEM_CD"]]}', '{$val["PUCH_ODR_CD"]}', '{$val['ITEM_CD']}', '{$val['SEQ']}', 2";
            $this->exec_odbc( $sub_fal, $sql_up );   
            $sub_fal->close();       
        //    echo "$sql_up
        //    ";
        }


        return "Count Data insert : " . $cut . " Count Data update : " . count($recue); 
        //$sub_fal->close();
        //var_dump($mes);
        //exit;      5100092582
    }
    public function rec_upd_qrctl()
    {
        $sub_fal = $this->fal_load();
        $cut = 0;
        //echo $this->pro_query->MSSQL_GETDATA_READQR(); exit;
        $recue = $this->exec( $sub_fal, $this->pro_query->MSSQL_GETDATA_READQR() );
        
        //var_dump(   $recue ); exit;

        foreach( $recue as $ind => $val)
        {
            $sql_up = "EXEC UPDFA_RECEIVE_TAGREADQRCTL '{$val["READ_QR"]}'";
            $exe_fal = $this->exec( $sub_fal, $sql_up );   
      
            if( $exe_fal[0]['MSG'] == 'Update Data Successfully' )
            {
                    $cut++;

            }

            //if( $cut == 1000) break;
                    //    echo "$sql_up
        //    ";
        }
        $sub_fal->close(); 

        return "Count Data insert : " . $cut . " Count Data update : " . count($recue); 
        //$sub_fal->close();
        //var_dump($mes);
        //exit;      
    }



    private function set_location()
    {
        $this->expk_load();
        $sql_expk = $this->pro_query->ORACLE_GETDATA_LOCATION();
        $exp_resu = $this->exec($this->expk, $sql_expk);
        
        foreach ($exp_resu as $ind => $value) 
        {
            $this->loct[$value["ITEM_CD"]]   = $value["CLASIFICATION_CD"];
        }

        //var_dump($this->loct);
    }
    public function rec_upd_flgpro()
    {
        //$this->expk_load();
        $sql_fal = $this->pro_query->MSSQL_GETSEQ_TAG();
        $exp_resu = $this->exec($this->fal, $sql_fal);
        
        foreach ($exp_resu as $ind => $value) 
        {
            $this->exec_odbc($this->fal, "EXEC UPDFA_RECEIVEINPROCFLG 1, '{$value["PUCH_ODR_CD"]}', '{$value["ITEM_CD"]}'" );
            //$this->loct[$value["ITEM_CD"]]   = $value["CLASIFICATION_CD"];
            echo $value["PUCH_ODR_CD"] . "<br>";
        }
         echo Count($exp_resu); 
        exit;

        //var_dump($this->loct);5100092573
    }
    public function test_function()
    {
        //$this->expk_load();
        $sql_fal  = $this->pro_query->MSSQL_GETDATA_PROCZERO();
        $exp_resu = $this->exec($this->fal, $sql_fal);
        
        var_dump($exp_resu); 

        // foreach ($exp_resu as $ind => $value) 
        // {
        //     $this->exec_odbc($this->fal, "EXEC UPDFA_RECEIVEINPROCFLG 1, '{$value["PUCH_ODR_CD"]}', '{$value["ITEM_CD"]}'" );
        //     //$this->loct[$value["ITEM_CD"]]   = $value["CLASIFICATION_CD"];
        //     echo $value["PUCH_ODR_CD"] . "<br>";
        // }
        //  echo Count($exp_resu); 
        exit;

        //var_dump($this->loct);5100092573
    }


}
?> 
