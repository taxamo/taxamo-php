<?php
/*
 def test_ops(self):





        resp = self.api.getTransaction(resp.transaction.key)

        self.assertFalse(resp.transaction.key is None)
        self.assertEqual(resp.transaction.buyer_name, "Python tester #2")
        self.assertEqual(resp.transaction.invoice_address.street_name, "Test street #4")

        self.assertEqual(resp.transaction.status, 'N')
        self.assertEqual(resp.transaction.amount, 70)
        self.assertEqual(resp.transaction.tax_amount, 8.68)
        self.assertEqual(resp.transaction.total_amount, 78.68)
        self.assertEqual(resp.transaction.evidence.by_billing.resolved_country_code, "IE")
        self.assertEqual(resp.transaction.evidence.by_ip.resolved_country_code, "IE")
        self.assertEqual(resp.transaction.evidence.forced.resolved_country_code, "FR")

        resp = self.api.confirmTransaction(resp.transaction.key, {
            'transaction':
                {
                    'buyer_name': 'Python tester',
                    'invoice_place': 'Pythontown',
                    'currency_code': 'EUR',
                    'transaction_lines': [{'amount': 300,
                                           'custom_id': 'line1'},
                                          {'amount': 400,
                                           'product_type': 'e-book',
                                           'custom_id': 'line2'}]}})

        self.assertFalse(resp.transaction.key is None)
        self.assertEqual(resp.transaction.status, 'C')
        self.assertEqual(resp.transaction.amount, 700)
        self.assertEqual(resp.transaction.tax_amount, 86.8)
        self.assertEqual(resp.transaction.total_amount, 786.8)

        resp = self.api.getTransaction(resp.transaction.key)

        self.assertFalse(resp.transaction.key is None)
        self.assertEqual(resp.transaction.status, 'C')
        self.assertEqual(resp.transaction.amount, 700)
        self.assertEqual(resp.transaction.tax_amount, 86.8)
        self.assertEqual(resp.transaction.total_amount, 786.8)
        self.assertEqual(resp.transaction.evidence.by_billing.resolved_country_code, "IE")
        self.assertEqual(resp.transaction.evidence.by_ip.resolved_country_code, "IE")
        self.assertEqual(resp.transaction.evidence.forced.resolved_country_code, "FR")

        resp = self.api.listTransactions(statuses="C",
                                         order_date_from="2014-01-01",
                                         order_date_to="2099-12-31",
                                         currency_code='EUR',
                                         sort_reverse='true',
                                         limit=100)

        self.assertTrue(len(resp.transactions) > 0)
        self.assertTrue(len(resp.transactions) <= 100)

        for transaction in resp.transactions:
            self.assertEqual(transaction.status, 'C')
            self.assertEqual(transaction.currency_code, 'EUR')

        resp = self.api.listTransactions(statuses="N",
                                         order_date_from="2099-12-01",
                                         order_date_to="2099-12-31",
                                         currency_code='EUR',
                                         sort_reverse='true',
                                         limit=10)

        self.assertTrue(len(resp.transactions) == 0)


    def test_cancel(self):
        resp = self.api.createTransaction(
            {
                'transaction': {
                    'currency_code': 'USD',
                    'buyer_ip': '127.0.0.1',
                    'billing_country_code': 'IE',
                    'force_country_code': 'FR',
                    'transaction_lines': [{'amount': 200,
                                           'custom_id': 'line1'},
                                          {'amount': 100,
                                           'product_type': 'e-book',
                                           'custom_id': 'line2'}]
                }})
        self.assertFalse(resp.transaction.key is None)
        self.assertEqual(resp.transaction.status, 'N')

        resp = self.api.cancelTransaction(resp.transaction.key)

        self.assertEqual(resp.success, True)

*/
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

    $transaction = new Input_transaction();
    $transaction->currency_code = 'USD';
    $transaction->buyer_ip = '127.0.0.1';
    $transaction->billing_country_code = 'IE';
    $transaction->force_country_code = 'FR';
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);

    $resp = $this->getApi()->createTransaction(array('transaction' => $transaction));

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->countries->detected->code, "IE");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 300);
    $this->assertEqual($resp->transaction->tax_amount, 46.2);
    $this->assertEqual($resp->transaction->total_amount, 346.2);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 19.6);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 39.2);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 7);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 7);

    $resp = $this->getApi()->getTransaction($resp->transaction->key);

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->evidence->by_billing->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->by_ip->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->forced->resolved_country_code, "FR");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 300);
    $this->assertEqual($resp->transaction->tax_amount, 46.2);
    $this->assertEqual($resp->transaction->total_amount, 346.2);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 19.6);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 39.2);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 7);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 7);

    $transaction_line1 = new Input_transaction_line();
    $transaction_line1->amount = 30;
    $transaction_line1->custom_id = 'line1';

    $transaction_line2 = new Input_transaction_line();
    $transaction_line2->amount = 40;
    $transaction_line2->custom_id = 'line2';
    $transaction_line2->product_type = 'e-book';

    $transaction = new Input_transaction();
    $transaction->currency_code = 'CHF';
    $transaction->buyer_name = 'Python tester #2';
    $transaction->transaction_lines = array($transaction_line1, $transaction_line2);

    $resp = $this->getApi()->updateTransaction($resp->transaction->key, array('transaction' => $transaction));

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 70);
    $this->assertEqual($resp->transaction->tax_amount, 8.68);
    $this->assertEqual($resp->transaction->total_amount, 78.68);
    $this->assertEqual($resp->transaction->status, "N");


    $resp = $this->getApi()->getTransaction($resp->transaction->key);

    $this->assertNotNull($resp->transaction->key);

    $this->assertEqual($resp->transaction->evidence->by_billing->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->by_ip->resolved_country_code, "IE");
    $this->assertEqual($resp->transaction->evidence->forced->resolved_country_code, "FR");
    $this->assertEqual($resp->transaction->tax_country_code, "FR");
    $this->assertEqual($resp->transaction->amount, 70);
    $this->assertEqual($resp->transaction->tax_amount, 8.68);
    $this->assertEqual($resp->transaction->total_amount, 78.68);
    $this->assertEqual($resp->transaction->status, "N");

    $this->assertEqual(resp.transaction.buyer_name, "Python tester #2")
    $this->assertEqual(resp.transaction.invoice_address.street_name, "Test street #4")

    $this->assertEqual($resp->transaction->transaction_lines[0]->custom_id, 'line1');
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_rate, 19.6);
    $this->assertEqual($resp->transaction->transaction_lines[0]->tax_amount, 5.88);
    $this->assertEqual($resp->transaction->transaction_lines[1]->custom_id, 'line2');
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_rate, 7);
    $this->assertEqual($resp->transaction->transaction_lines[1]->tax_amount, 2.8);
  }
}


?>