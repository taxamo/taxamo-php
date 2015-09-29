<?php

class Taxamo_TaxTest extends \TaxamoTestCase
{
  public function testCalculate()
  {
    $transaction_line1 = new Taxamo\Input_transaction_line();
    $transaction_line1->amount = 200;
    $transaction_line1->custom_id = 'line1';

    $transaction_line2 = new Taxamo\Input_transaction_line();
    $transaction_line2->amount = 100;
    $transaction_line2->custom_id = 'line2';
    $transaction_line2->product_type = 'e-book';

    $transaction = new Taxamo\Input_transaction();
    $transaction->currency_code = 'USD';
    $transaction->buyer_ip = '127.0.0.1';
    $transaction->billing_country_code = 'IE';
    $transaction->force_country_code = 'FR';
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);

    $resp = $this->getApi()->calculateTax(array('transaction' => $transaction));
    
    $this->assertEqual($resp->transaction->countries->detected->code, "IE");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 300);
    $this->assertEqual($resp->transaction->tax_amount, 45.5);
    $this->assertEqual($resp->transaction->total_amount, 345.5);

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 20);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 40);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 5.5);
  }

  public function testSimpleCalculate()
  {

    $resp = $this->getApi()->calculateSimpleTax(null, null, "e-book", "FR", 1, null, null, null, 100, "IE", "USD", null);

    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 100);
    $this->assertEqual($resp->transaction->tax_amount, 5.5);
    $this->assertEqual($resp->transaction->total_amount, 105.5);

    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 5.5);
  }

  public function testValidateTaxNumber() {
    $resp = $this->getApi()->validateTaxNumber("IE", "6388047V");

    $this->assertEqual($resp->tax_deducted, True);
    $this->assertEqual($resp->buyer_tax_number_valid, True);
    $this->assertEqual($resp->billing_country_code, "IE");

    $resp = $this->getApi()->validateTaxNumber(null, "IE6388047V12121");

    $this->assertEqual($resp->tax_deducted, False);
    $this->assertEqual($resp->buyer_tax_number_valid, False);

  }
  
  public function testLocationCalculate() {
    $resp = $this->getApi()->calculateTaxLocation('BE', '424242');
    
    $this->assertEqual($resp->tax_country_code, "BE");
    $this->assertEqual($resp->tax_supported, True);
    $this->assertEqual($resp->countries->detected->code, "BE");
    $this->assertEqual($resp->countries->by_billing->code, "BE");
    $this->assertEqual($resp->countries->by_cc->code, "BE");
  }
}


?>