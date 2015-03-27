<?php
class Taxamo_TransactionsTest extends TaxamoTestCase
{
  public function testStandardFlow()
  {

    $transaction_line1 = new Input_transaction_line();
    $transaction_line1->amount = 200;
    $transaction_line1->custom_id = 'line1';

    $transaction_line2 = new Input_transaction_line();
    $transaction_line2->amount = 100;
    $transaction_line2->custom_id = 'line2';
    $transaction_line2->product_type = 'e-book';

    $custom_field1 = new Custom_fields();
    $custom_field1->key = 'test1';
    $custom_field1->value = 'test2';

    $custom_field2 = new Custom_fields();
    $custom_field2->key = 'test12';
    $custom_field2->value = 'test22';

    $transaction_line2->custom_fields = array($custom_field2);

    $transaction = new Input_transaction();
    $transaction->currency_code = 'USD';
    $transaction->buyer_ip = '127.0.0.1';
    $transaction->billing_country_code = 'IE';
    $transaction->force_country_code = 'FR';
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);
    $transaction->custom_fields = array($custom_field1);

    $resp = $this->getApi()->createTransaction(array('transaction' => $transaction));

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->countries->detected->code, "IE");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 300);
    $this->assertEqual($resp->transaction->tax_amount, 45.5);
    $this->assertEqual($resp->transaction->total_amount, 345.5);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 20);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 40);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 5.5);


    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->key, 'test12');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->value, 'test22');

    $this->assertEqual($resp->transaction->custom_fields[0]->key, 'test1');
    $this->assertEqual($resp->transaction->custom_fields[0]->value, 'test2');

    $resp = $this->getApi()->getTransaction($resp->transaction->key);

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->evidence->by_billing->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->by_ip->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->forced->resolved_country_code, "FR");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 300);
    $this->assertEqual($resp->transaction->tax_amount, 45.5);
    $this->assertEqual($resp->transaction->total_amount, 345.5);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 20);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 40);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->key, 'test12');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->value, 'test22');

    $this->assertEqual($resp->transaction->custom_fields[0]->key, 'test1');
    $this->assertEqual($resp->transaction->custom_fields[0]->value, 'test2');

    $transaction_line1 = new Input_transaction_line();
    $transaction_line1->amount = 30;
    $transaction_line1->custom_id = 'line1';

    $transaction_line2 = new Input_transaction_line();
    $transaction_line2->amount = 40;
    $transaction_line2->custom_id = 'line2';
    $transaction_line2->product_type = 'e-book';

    $custom_field1 = new Custom_fields();
    $custom_field1->key = 'test31';
    $custom_field1->value = 'test32';

    $custom_field2 = new Custom_fields();
    $custom_field2->key = 'test412';
    $custom_field2->value = 'test422';

    $transaction_line1->custom_fields = array($custom_field1, $custom_field2);
    $transaction_line2->custom_fields = array($custom_field2, $custom_field1, $custom_field2);

    $transaction = new Input_transaction();
    $transaction->currency_code = 'CHF';
    $transaction->buyer_name = 'Python tester #2';
    $transaction->invoice_place = 'Test street #5';
    $transaction->invoice_address = array('street_name' => 'Test street #4');
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);

    $transaction->custom_fields = array($custom_field2, $custom_field1);

    $resp = $this->getApi()->updateTransaction($resp->transaction->key, array('transaction' => $transaction));

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 70);
    $this->assertEqual($resp->transaction->tax_amount, 8.2);
    $this->assertEqual($resp->transaction->total_amount, 78.2);
    $this->assertEqual($resp->transaction->status, "N");


    $resp = $this->getApi()->getTransaction($resp->transaction->key);

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->evidence->by_billing->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->by_ip->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->forced->resolved_country_code, "FR");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 70);
    $this->assertEqual($resp->transaction->tax_amount, 8.2);
    $this->assertEqual($resp->transaction->total_amount, 78.2);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual($resp->transaction->buyer_name, "Python tester #2");
    $this->assertEqual($resp->transaction->invoice_address->street_name, "Test street #4");

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_fields[0]->key, 'test31');
    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_fields[0]->value, 'test32');
    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_fields[1]->key, 'test412');
    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_fields[1]->value, 'test422');

    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->key, 'test412');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[0]->value, 'test422');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[1]->key, 'test31');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[1]->value, 'test32');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[2]->key, 'test412');
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_fields[2]->value, 'test422');

    $this->assertEqual($resp->transaction->custom_fields[1]->key, 'test31');
    $this->assertEqual($resp->transaction->custom_fields[1]->value, 'test32');
    $this->assertEqual($resp->transaction->custom_fields[0]->key, 'test412');
    $this->assertEqual($resp->transaction->custom_fields[0]->value, 'test422');

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 20);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 6);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 5.5);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 2.2);

    time_nanosleep(1, 0);

    $resp = $this->getApi()->confirmTransaction($resp->transaction->key, array());
    $this->assertEqual("C", $resp->transaction->status);
    $resp = $this->getApi()->getTransaction($resp->transaction->key);
    $this->assertEqual("C", $resp->transaction->status);

    time_nanosleep(1, 0);

    $this->getApi()->unconfirmTransaction($resp->transaction->key, array());
    $resp = $this->getApi()->getTransaction($resp->transaction->key);
    $this->assertEqual("N", $resp->transaction->status);

    time_nanosleep(1, 0);
    $resp = $this->getApi()->confirmTransaction($resp->transaction->key, array());
    $this->assertEqual("C", $resp->transaction->status);
    $resp = $this->getApi()->getTransaction($resp->transaction->key);
    $this->assertEqual("C", $resp->transaction->status);
    $this->assertNotNull($resp->transaction->invoice_image_url);

    time_nanosleep(1, 0);

    $this->getApi()->unconfirmTransaction($resp->transaction->key, array());
    $resp = $this->getApi()->getTransaction($resp->transaction->key);
    $this->assertEqual("N", $resp->transaction->status);

//    $resp = $this->getApi()->emailInvoice($resp->transaction->key, array('buyer_email' => 'phptest@taxamo.com'));
//    $this->assertTrue($resp->success);

    $this->getApi()->cancelTransaction($resp->transaction->key);
  }

  public function testNewEvidenceFields() {

    $transaction_line1 = new Input_transaction_line();
    $transaction_line1->amount = 200;
    $transaction_line1->custom_id = 'line1';

    $transaction_line2 = new Input_transaction_line();
    $transaction_line2->amount = 100;
    $transaction_line2->custom_id = 'line2';
    $transaction_line2->product_type = 'e-book';

    $ocri_evidence = new Evidence_schema();
    $ocri_evidence->evidence_value = 'GR';

    $evidence = new Evidence();
    $evidence->other_commercially_relevant_info = $ocri_evidence;
    $evidence->self_declaration = $ocri_evidence;

    $transaction = new Input_transaction();
    $transaction->currency_code = 'USD';
    $transaction->evidence = $evidence;
    $transaction->billing_country_code = 'GR';
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);

    $resp = $this->getApi()->createTransaction(array('transaction' => $transaction));

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->countries->detected->code, "GR");
    $this->assertEqual($resp->transaction->countries->by_billing->code, "GR");
    $this->assertEqual($resp->transaction->countries->other_commercially_relevant_info->code, "GR");
    $this->assertEqual($resp->transaction->countries->self_declaration->code, "GR");
    $this->assertEqual($resp->transaction->tax_country_code, "GR");
    $this->assertEqual($resp->transaction->status, "N");

    $resp = $this->getApi()->getTransaction($resp->transaction->key);

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->evidence->by_billing->resolved_country_code, "GR");
    $this->assertEqual($resp->transaction->evidence->other_commercially_relevant_info->resolved_country_code, "GR");
    $this->assertEqual($resp->transaction->evidence->self_declaration->resolved_country_code, "GR");
    $this->assertEqual($resp->transaction->tax_country_code, "GR");
    $this->assertEqual($resp->transaction->status, "N");
  }
}


?>