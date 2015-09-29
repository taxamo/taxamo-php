<?php

class Taxamo_ExceptionTest extends TaxamoTestCase {
  public function testValidation() {
    try {
        $resp = $this->getApi()->calculateTax(array());
        $this->fail('TaxamoValidationException not thrown');
    } catch (Taxamo\TaxamoValidationException $e) {
        $this->assertEqual("missing-required-key", $e->validation_failures->transaction);
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoValidationException was thrown');
    }
  }

  public function testAuthError() {
    try {
        $swagger = new Taxamo\APIClient('SamplePrivateTestKey1!!!!', 'https://api.taxamo.com');
        $api = new Taxamo\Taxamo($swagger);
        $resp = $api->calculateTax(array());
        $this->fail('TaxamoAuthenticationException not thrown');
    } catch (Taxamo\TaxamoAuthenticationException $e) {
        $this->assertEqual($e->response, "{\"errors\":[\"Please provide correct public token.\"]}");
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoAuthenticationException was thrown');
    }
  }

 public function testConnectionError() {
    try {
        $swagger = new Taxamo\APIClient('SamplePrivateTestKey1', 'https://api.taxamo.com.nonexistent');
        $api = new Taxamo\Taxamo($swagger);
        $resp = $api->calculateTax(array());
        $this->fail('TaxamoAuthenticationException not thrown');
    } catch (Taxamo\TaxamoAPIException $e) {
        $this->assertEqual($e->response['http_code'], 0);
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoAPIException was thrown');
    }
  }

}


?>