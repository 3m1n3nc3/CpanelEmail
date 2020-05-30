<?php
    
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . '/third_party/Paystack/src/autoload.php'; 

class Paystack {

    public $private;
    public $public;
    public $reference;

    public function __construct() 
    { 
        $this->CI = & get_instance();
        $this->secret = my_config('paystack_secret');
        $this->init = new Yabacon\Paystack($this->secret);
    }

    public function pay($reference)
    {
        $trx = $this->init->transaction->verify( [ 'reference' => $reference ] );
        return $trx;
    }

}
