<?php

echo("Running Taxamo API client tests...");

$testURI = '/simpletest/autorun.php';
$ok = @include_once(dirname(__FILE__).$testURI);
if (!$ok) {
  $ok = @include_once(dirname(__FILE__).'/../vendor/simpletest'.$testURI);
}
if (!$ok) {
  echo "MISSING DEPENDENCY: The Taxamo API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

// Throw an exception on any error
// @codingStandardsIgnoreStart
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
// @codingStandardsIgnoreEnd
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/Taxamo.php');
require_once(dirname(__FILE__) . '/TaxamoTest/Common.php');

require_once(dirname(__FILE__) . '/TaxamoTest/TestTaxApi.php');
require_once(dirname(__FILE__) . '/TaxamoTest/TestTransactionsApi.php');
?>