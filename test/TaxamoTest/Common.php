<?php

abstract class TaxamoTestCase extends UnitTestCase {
   function getApi() {
        $swagger = new Taxamo\APIClient('SamplePrivateTestKey1', 'https://api.taxamo.com');
        $swagger->sourceId = "TaxamoTestPHP/2.0.0";
        return new Taxamo\Taxamo($swagger);
   }
}
?>
