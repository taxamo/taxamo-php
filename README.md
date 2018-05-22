## Taxamo PHP bindings

This package provides PHP bindings for the [Taxamo](http://www.taxamo.com/) API.

## Documentation

In this project, Taxamo provides Swagger-codegen generated bindings to its RESTful API.

This project is also used to report Taxamo PHP API related issues: [https://github.com/taxamo/taxamo-php/issues](https://github.com/taxamo/taxamo-php/issues).

Also consult [regression tests](https://github.com/taxamo/taxamo-php/blob/master/test/TaxamoTest.php) for example usage of the API.

## Installation

Taxamo-php bindings are available as a [Composer](https://getcomposer.org/) module, so just add the following entry to the composer.json file:

```json
{
  "require": {
    "taxamo/taxamo-php": "2.*"
  }
}
```

Then run the following command:

```bash
composer.phar install #or composer install on Mac OS X
```

Finally, use the autoload feature from Composer to import the required files:

```php
require_once('vendor/autoload.php');
```
    
Or just load the vendor/taxamo/taxamo-php/Taxamo.php file:

    require_once('vendor/taxamo/taxamo-php/lib/Taxamo.php');

If not using Composer, just clone the [https://github.com/taxamo/taxamo-php](https://github.com/taxamo/taxamo-php) GitHub repository with any git client, for example:

```bash
git clone https://github.com/taxamo/taxamo-php
```

Next refer the Taxamo.php file – the actual path depends on the merchant's project layout:

```php
require_once('path/to/taxamo-php/Taxamo.php');
```

## Setting up Taxamo API

To access the Taxamo API from PHP, initialize it by providing an access token and the endpoint url (tokens are available in the Taxamo dashboard):

```php
$taxamo = new Taxamo\Taxamo(
    new Taxamo\APIClient('your_private_key', 'https://api.taxamo.com'));
```

## Pre-calculating tax

To invoke tax pre-calculation mechanism, the `calculateTax()` function needs to be used:

```php
$transaction_line1 = new Taxamo\Input_transaction_line();
$transaction_line1->amount = 200;
$transaction_line1->custom_id = 'line1';

$transaction_line2 = new Taxamo\Input_transaction_line();
$transaction_line2->amount = 100;
$transaction_line2->custom_id = 'line2';
$transaction_line2->product_type = 'e-book';

$transaction = new Taxamo\Input_transaction();
$transaction->currency_code = 'USD';
 //propagate customer's IP address when calling API server-side
$transaction->buyer_ip = $_SERVER['REMOTE_ADDR'];
$transaction->billing_country_code = 'IE';
$transaction->force_country_code = 'FR';
$transaction->transaction_lines = array($transaction_line1, $transaction_line2);

$resp = $taxamo->calculateTax(array('transaction' => $transaction));

echo "Total amount: ".$resp->transaction->total_amount;
```

*Please note, that when calling the API to calculate tax server-side, it is suggested to propagate the buyer's IP address to Taxamo using the buyer_ip field. Otherwise, the Taxamo API will use a merchant's server's IP address as one of the pieces of evidence, which might not be very feasible.*

## Storing Taxamo transaction

To store a transaction, the `createTransaction()` function needs to be used:

```php
$transaction_line1 = new Taxamo\Input_transaction_line();
$transaction_line1->amount = 200;
$transaction_line1->custom_id = 'line1';

$transaction_line2 = new Taxamo\Input_transaction_line();
$transaction_line2->amount = 100;
$transaction_line2->custom_id = 'line2';
$transaction_line2->product_type = 'e-book';

$transaction = new Taxamo\Input_transaction();
$transaction->currency_code = 'USD';
 //propagate customer's IP address when calling API server-side
$transaction->buyer_ip = $_SERVER['REMOTE_ADDR'];
$transaction->billing_country_code = 'IE';
$transaction->force_country_code = 'FR';
$transaction->transaction_lines = array($transaction_line1, $transaction_line2);

$resp = $taxamo->createTransaction(array('transaction' => $transaction));

echo "Transaction key: ".$resp->transaction->key;
```

*Please note, that when calling the API to store a transaction server-side, it is suggested to propagate the buyer's IP address to Taxamo using the buyer_ip field. Otherwise, the Taxamo API will use the merchant's server's IP address as one of the pieces of evidence, which might not be very feasible.*

## Confirming Taxamo transaction

The `confirmTransaction()` function updates the status of the previously created transaction to Confirmed, signaling that the payment was processed and making it available for settlement reporting:

```php
$taxamo->confirmTransaction($resp->transaction->key, null);
```

It is possible to confirm and update the transaction at the same time:

```php
$transaction = new Taxamo\Input_transaction();
$transaction->currency_code = 'CHF';
$transaction->buyer_name = 'Python tester #2';
$transaction->invoice_place = 'Test street #5';
$transaction->invoice_address = array('street_name' => 'Test street #4');
$transaction->transaction_lines = array($transaction_line1, $transaction_line2);

$taxamo->confirmTransaction($resp->transaction->key, array('transaction' => $transaction)); 
```

The transaction can also be updated without confirming it by using the `updateTransaction()` function.

### Other operations

Please review the [RESTful API documentation](/apidocs/docs.html) for a complete list of operations.
