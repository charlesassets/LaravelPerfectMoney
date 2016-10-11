<?php

namespace charlesassets\LaravelPerfectMoney;

use Carbon\Carbon;

/**
 * Class PerfectMoney
 */
class PerfectMoney implements PerfectMoneyInterface {

    /**
     * @var string
     */
    protected $account_id;
	
    /**
     * @var string
     */
    protected $passphrase;
	
    /**
     * @var string
     */
    protected $alt_passphrase;
	
    /**
     * @var string
     */
    protected $marchant_id;
	
    /**
     * @var array
     */
    protected $ssl_fix = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
	
	
    public function __construct()
	{
		$this->account_id = config('perfectmoney.account_id');
		$this->passphrase = config('perfectmoney.passphrase');
		$this->marchant_id = config('perfectmoney.marchant_id');
    }
	
    /**
     * get the balance for the wallet
     *
     * @return array
     */
	public function getBalance()
	{
		
		// Get data from the server
		$url = file_get_contents('https://perfectmoney.is/acct/balance.asp?AccountID=' . $this->account_id . '&PassPhrase=' . $this->passphrase, false, stream_context_create($this->ssl_fix));
		if(!$url)
		{
		   return ['status' => 'error', 'message' => 'Connection error'];
		}

		// searching for hidden fields
		if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $url, $result, PREG_SET_ORDER))
		{
		   return ['status' => 'error', 'message' => 'Invalid output'];
		}
		
		// putting data to array (return error, if have any)
		$data = [];
		foreach($result as $item)
		{
			if($item[1] == 'ERROR')
			{
				return ['status' => 'error', 'message' => $item[2]];
			}
			else
			{
				$data[] = [
					'currency' 	=> $item[1],
					'balance'	=> $item[2]
				];
			}
		}

		$data['status'] = 'success';
		
		return $data;
		
		
	}
	
    /**
     * Send Money
     *
	 * @param   string        $account
	 * @param   double        $amount
	 * @param   string        $descripion
	 * @param   string        $payment_id
	 *
     * @return array
     */
	public function sendMoney($account, $amount, $descripion = '', $payment_id = '')
	{
		
		// trying to open URL to process PerfectMoney Spend request
		$url = file_get_contents('https://perfectmoney.is/acct/confirm.asp?AccountID=' . urlencode(trim($this->account_id)) . '&PassPhrase=' . urlencode(trim($this->passphrase)) . '&Payer_Account=' . urlencode(trim($this->marchant_id)) . '&Payee_Account=' . urlencode(trim($account)) . '&Amount=' . $amount .  (empty($descripion) ? '' : '&Memo=' . urlencode(trim($descripion))) . (empty($payment_id) ? '' : '&PAYMENT_ID=' . urlencode(trim($payment_id))), false, stream_context_create($this->ssl_fix));

		if(!$url){
		   return ['status' => 'error', 'message' => 'Connection error'];
		}

		// searching for hidden fields
		if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $url, $result, PREG_SET_ORDER))
		{
		   return ['status' => 'error', 'message' => 'Invalid output'];
		}

		// putting data to array (return error, if have any)
		$data = [];
		foreach($result as $item)
		{
			if($item[1] == 'ERROR')
			{
				return ['status' => 'error', 'message' => $item[2]];
			}
			else
			{
				$data[$item[1]] = $item[2];
			}
		}
		
		
		$data['status'] = 'success';
		
		return $data;
		
	}
	
    /**
     * Render form
     *
	 * @param   array        $data
	 *
     * @return \Illuminate\View\View
     */
	public static function render($data = [], $view = 'perfectmoney')
	{
		
		if(view()->exists('perfectmoney::' . $view)){
			return view($view)->render();
		}
		
		return view('perfectmoney::perfectmoney', compact('data'));
	}
	
	
    /**
     * This script demonstrates querying account history
	 * using PerfectMoney API interface.
     *
	 * @param   int        $start_day
	 * @param   int        $start_month
	 * @param   int        $end_year
	 * @param   int        $end_day
	 * @param   int        $end_month
	 * @param   int        $end_year
	 *
     * @return array
     */
	public function getHistory($start_day = null, $start_month = null, $start_year = null, $end_day = null, $end_month = null, $end_year = null, $data = [])
	{
		
		$start_day = ($start_day ? $start_day : Carbon::now()->subYear(1)->day);
		$start_month = ($start_month ? $start_month : Carbon::now()->subYear(1)->month);
		$start_year =  ($start_year ? $start_year : Carbon::now()->subYear(1)->year);
		$end_day = ($end_day ? $end_day : Carbon::now()->day);
		$end_month = ($end_month ? $end_month : Carbon::now()->month);
		$end_year = ($end_year ? $end_year : Carbon::now()->year);
		
		
		$url = 'https://perfectmoney.is/acct/historycsv.asp?startmonth=' . $start_month . '&startday=' . $start_day . '&startyear=' . $start_year . '&endmonth=' . $end_month . '&endday=' . $end_day . '&endyear=' . $end_year . '&AccountID=' . urlencode(trim($this->account_id)) . '&PassPhrase=' . urlencode(trim($this->passphrase));
		
		// Custom Filters
		if(isset($data['payment_id']))
		{
			$url .= '&payment_id=' . $data['payment_id'];
		}
		
		// Custom Filters
		if(isset($data['batchfilter']))
		{
			$url .= '&batchfilter=' . $data['batchfilter'];
		}
		
		if(isset($data['counterfilter']))
		{
			$url .= '&counterfilter=' . $data['counterfilter'];
		}
		
		if(isset($data['metalfilter']))
		{
			$url .= '&metalfilter=' . $data['metalfilter'];
		}
		
		if(isset($data['payment_id']))
		{
			$url .= '&payment_id=' . $data['payment_id'];
		}
		
		if(isset($data['oldsort']) && in_array(strtolower($data['oldsort']), ['tstamp', 'batch_num', 'metal_name', 'counteraccount_id', 'amount ']) )
		{
			$url .= '&oldsort=' . $data['oldsort'];
		}
		
		if(isset($data['paymentsmade']) && $data['paymentsmade'] == true)
		{
			$url .= '&paymentsmade=1';
		}
		
		if(isset($data['paymentsmade']) && $data['paymentsmade'] == true)
		{
			$url .= '&paymentsmade=1';
		}
		
		if(isset($data['paymentsreceived']) && $data['paymentsreceived'] == true)
		{
			$url .= '&paymentsreceived=1';
		}
		
		// Get data from the server
		$url = file_get_contents($url, false, stream_context_create($this->ssl_fix));
		if(!$url)
		{
		   return ['status' => 'error', 'message' => 'Connection error'];
		}
		
		if (substr($url, 0, 63) == 'Time,Type,Batch,Currency,Amount,Fee,Payer Account,Payee Account') { 
			
			$lines = explode("\n", $url);
			
			// Getting table names (Time,Type,Batch,Currency,Amount,Fee,Payer Account,Payee Account)
			$rows = explode(",", $lines[0]);
			
			$return_data = [];
			
			// Fetching history
			$return_data['history'] = [];
			for($i=1; $i < count($lines); $i++){
			
				$item = explode(',', $lines[$i]);
				
				foreach($items as $key => $value)
				{
					$return_data['history'][] = [
						str_replace(' ', '_', strtolower($rows[$key]))	=> $value
					];
				}
				
			
			}
			
			$return_data['status'] = 'success';
			
			return $return_data;
			
		}
		else
		{
			return ['status' => 'error', 'message' => 'Invalid output'];
		}
		
		
	}

}

