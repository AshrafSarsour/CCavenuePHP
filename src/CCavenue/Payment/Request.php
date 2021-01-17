<?php 

namespace CCavenue\Payment;

use CCavenue\Security\Crypto;
use CCavenue\View\RequestForm;
use ReflectionClass;

class Request
{
    public static $paymentUrl = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction&';

	private $working_key;
	private $access_code;
	private $merchant_id;

	public function __construct($working_key,$access_code,$merchant_id)
    {
    	$this->working_key = $working_key;
    	$this->access_code = $access_code;
    	$this->merchant_id = $merchant_id;
    }

	public function getAccessCode()
	{
		return $this->access_code;
	}
	public function setAccessCode($val)
	{
		return $this->access_code = $val;
	}

	private $paymentId;
	public function getPaymentId()
	{
		return $this->paymentId;
	}
	public function setPaymentId($val)
	{
		return $this->paymentId = $val;
	}

	private $orderID;
	public function getOrderId()
	{
		return $this->orderID;
	}
	public function setOrderId($val)
	{
		return $this->orderID = $val;
	}

	private $amount;
	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->amount = $val;
	}

	private $currency;
	public function getCurrency()
	{
		return $this->currency;
	}

	public function setCurrency($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->currency = $val;
	}

	private $prodDesc;
	public function getProdDesc()
	{
		return $this->prodDesc;
	}
	public function setProdDesc($val)
	{
		return $this->prodDesc = $val;
	}

	private $billingName;
	public function getBillingName()
	{
		return $this->billingName;
	}
	public function setBillingName($val)
	{
		return $this->billingName = $val;
	}

	private $billingEmail;
	public function getBillingEmail()
	{
		return $this->billingEmail;
	}
	public function setBillingEmail($val)
	{
		return $this->billingEmail = $val;
	}
 
	private $signature;
	public function getSignature($refresh = false)
	{
		if((!$this->signature) || $refresh)
		{
			$this->setData();
			$this->signature = Crypto::encrypt($this->data,$this->working_key);
		}

		return $this->signature;
	}

	private $responseUrl;
	public function getResponseUrl()
	{
		return $this->responseUrl;
	}
	public function setResponseUrl($val)
	{
		return $this->responseUrl = $val;
	}

	private $redirectUrl;
	public function getRedirectUrl()
	{
		return $this->redirectUrl;
	}
	public function setRedirectUrl($val)
	{
		return $this->redirectUrl = $val;
	}

	protected static $fillable_fields = [
		'working_key','merchant_id','access_code','amount',
		'currency','billingName','billingEmail','redirectUrl'
	];

	public function make($fieldValues)
	{
		$this->setData();
		RequestForm::render($fieldValues, self::$redirectUrl);
	}

	private function setData(){
        $class = new ReflectionClass(__CLASS__); 
        $properties = $class->getProperties(); 
        $this->data =  http_build_query($properties);
    }
}
