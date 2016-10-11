<?php
/**
 * Interface PerfectMoney
 */
interface PerfectMoneyInterface {

    public function __construct();
	
	public function getBalance();
	
	public function sendMoney($account, $amount, $descripion = '', $payment_id = '');
	
	public static function render($data = [], $view = 'perfectmoney');
	
	public function getHistory($start_day = null, $start_month = null, $start_year = null, $end_day = null, $end_month = null, $end_year = null, $data = []);

}