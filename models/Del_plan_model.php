<?php
header("Content-Type:text/html; charset=UTF-8");
require_once dirname(__FILE__) . '/sql/del_plan.php';
date_default_timezone_set("Asia/Bangkok");
//ini_set('mssql.charset', 'UTF-8');
class Del_plan_model extends Main_Model
{
    var $exp_sys;
    public function __construct()
    {
        parent::__construct();
        ## asset config
        //session_destroy();
        ob_clean();
        flush();
        $this->exp_sys = new del_plan();

    }

    public function get_exp()
	{	
		$get_year = $this->input->get('year');
        $get_month = $this->input->get('month');
        $exp = $this->expk_load();

        $str_date = $get_year."/".$get_month."/01";
        $str_date = date( "Y/m/d" , strtotime( $str_date ));
        $end_date = date( "Y/m/t" , strtotime( $str_date ));
        // $ship = $this->ship_load();
        $exp_data = $this->exec( $exp, $this->exp_sys->que_exp($str_date , $end_date) );

        return $exp_data;
    }	
    
    public function get_ship( $slip = "" )
	{	
		$get_slip = $slip;
        $ship = $this->ship_load();
        $ship_data = $this->exec( $ship, $this->exp_sys->que_ship($get_slip) );

        if( !empty($ship_data) )
        {
            return $ship_data[0]['INVOICE_NO'];
        }
        else
        {
            return 0;
        }
    }

    public function sum_del_data()
    {
        $exp = $this->get_exp();
        //$ship = $this->get_ship();

        foreach ($exp as $key => $value) 
        {
            if($value['INVOICE_NO'] == null)
            {
                //echo $value['SLIP_CD'];
                $inv = $this->get_ship($value['SLIP_CD']);

                if( $inv != 0 )
                {
                    $exp[$key]['INVOICE_NO'] = $inv;

                    $chk_inv = substr( $inv , 0 , 2 );

                    if( $chk_inv == '90' )
                    {
                        $exp[$key]['BOI'] = 'INV.90';
                    }
                    else if( $chk_inv == '92' )
                    {
                        $exp[$key]['BOI'] = 'INV.92';
                    }
                }
                else
                {
                    $exp[$key]['INVOICE_NO'] = '';
                    $exp[$key]['BOI'] = '';
                }
            }
        }

        return($exp);
    }

}
?> 
