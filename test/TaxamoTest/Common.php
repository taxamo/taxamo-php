<?php
abstract class TaxamoTestCase extends UnitTestCase {
   function getApi() {
        $swagger = new APIClient('SamplePrivateTestKey1', 'https://api.taxamo.com');
        return new Taxamo($swagger);
   }
}
?>
