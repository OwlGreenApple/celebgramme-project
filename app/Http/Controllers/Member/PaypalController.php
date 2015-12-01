<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Order;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Package;
use Celebgramme\Models\VeritransModel;
use Celebgramme\Veritrans\Veritrans;

use Illuminate\Http\Request;
use View, Input, Paypalpayment;

class PaypalController extends Controller
{
	public function __construct(){   
		$this->_apiContext = Paypalpayment::apiContext(config('paypal_payment.Account.ClientId'), config('paypal_payment.Account.ClientSecret'));
	}
  
  /*
  * Process payment using credit card
  */
  public function store()
  {
      // ### Address
      // Base Address object used as shipping or billing
      // address in a payment. [Optional]
      $addr= Paypalpayment::address();
      $addr->setLine1("3909 Witmer Road");
      $addr->setLine2("Niagara Falls");
      $addr->setCity("Niagara Falls");
      $addr->setState("NY");
      $addr->setPostalCode("14305");
      $addr->setCountryCode("US");
      $addr->setPhone("716-298-1822");

      // ### CreditCard
      $card = Paypalpayment::creditCard();
      $card->setType("visa")
          ->setNumber("4758411877817150")
          ->setExpireMonth("05")
          ->setExpireYear("2019")
          ->setCvv2("456")
          ->setFirstName("Joe")
          ->setLastName("Shopper");

      // ### FundingInstrument
      // A resource representing a Payer's funding instrument.
      // Use a Payer ID (A unique identifier of the payer generated
      // and provided by the facilitator. This is required when
      // creating or using a tokenized funding instrument)
      // and the `CreditCardDetails`
      $fi = Paypalpayment::fundingInstrument();
      $fi->setCreditCard($card);

      // ### Payer
      // A resource representing a Payer that funds a payment
      // Use the List of `FundingInstrument` and the Payment Method
      // as 'credit_card'
      $payer = Paypalpayment::payer();
      $payer->setPaymentMethod("credit_card")
          ->setFundingInstruments(array($fi));

      $item1 = Paypalpayment::item();
      $item1->setName('Ground Coffee 40 oz')
              ->setDescription('Ground Coffee 40 oz')
              ->setCurrency('USD')
              ->setQuantity(1)
              ->setTax(0.3)
              ->setPrice(7.50);

      $item2 = Paypalpayment::item();
      $item2->setName('Granola bars')
              ->setDescription('Granola Bars with Peanuts')
              ->setCurrency('USD')
              ->setQuantity(5)
              ->setTax(0.2)
              ->setPrice(2);


      $itemList = Paypalpayment::itemList();
      $itemList->setItems(array($item1,$item2));


      $details = Paypalpayment::details();
      $details->setShipping("1.2")
              ->setTax("1.3")
              //total of items prices
              ->setSubtotal("17.5");

      //Payment Amount
      $amount = Paypalpayment::amount();
      $amount->setCurrency("USD")
              // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
              ->setTotal("20")
              ->setDetails($details);

      // ### Transaction
      // A transaction defines the contract of a
      // payment - what is the payment for and who
      // is fulfilling it. Transaction is created with
      // a `Payee` and `Amount` types

      $transaction = Paypalpayment::transaction();
      $transaction->setAmount($amount)
          ->setItemList($itemList)
          ->setDescription("Payment description")
          ->setInvoiceNumber(uniqid());

      // ### Payment
      // A Payment Resource; create one using
      // the above types and intent as 'sale'

      $payment = Paypalpayment::payment();

      $payment->setIntent("sale")
          ->setPayer($payer)
          ->setTransactions(array($transaction));

      try {
          // ### Create Payment
          // Create a payment by posting to the APIService
          // using a valid ApiContext
          // The return object contains the status;
          $payment->create($this->_apiContext);
      } catch (\PPConnectionException $ex) {
          return  "Exception: " . $ex->getMessage() . PHP_EOL;
          exit(1);
      }

      dd($payment);
  } 
  
    /*
        Use this call to get details about payments that have not completed, 
        such as payments that are created and approved, or if a payment has failed.
        url:payment/PAY-3B7201824D767003LKHZSVOA
    */

    public function show($payment_id)
    {
       $payment = Paypalpayment::getById($payment_id,$this->_apiContext);

       dd($payment);
    }  

   /*
        Use this call to get a list of payments. 
        url:payment/
    */
    public function index()
    {
        echo "<pre>";

        $payments = Paypalpayment::getAll(array('count' => 1, 'start_index' => 0), $this->_apiContext);

        dd($payments);
    }    

    public function test()
    {
      // Get the payment Object by passing paymentId
      // payment id and payer ID was previously stored in database in
      // create() fuction , this function create payment using "paypal" method
      $paymentId = 'PAY-9H753279GJ775144HKZICHPI';
      $PayerID = '206';
      $payment = Paypalpayment::getById($paymentId, $this->_apiContext);

      // PaymentExecution object includes information necessary 
      // to execute a PayPal account payment. 
      // The payer_id is added to the request query parameters
      // when the user is redirected from paypal back to your site
      $execution = Paypalpayment::PaymentExecution();
      $execution->setPayerId($PayerID);

      //Execute the payment
      $payment->execute($execution,$this->_apiContext);    
    }
    
}
