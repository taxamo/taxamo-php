<?php
class Taxamo_ExceptionTest extends TaxamoTestCase {
  public function testValidation() {
    try {
        $resp = $this->getApi()->calculateTax(array());
        $this->fail('TaxamoValidationException not thrown');
    } catch (TaxamoValidationException $e) {
        $this->assertEqual("missing-required-key", $e->validation_failures->transaction);
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoValidationException was thrown');
    }
  }

  public function testAuthError() {
    try {
        $swagger = new APIClient('SamplePrivateTestKey1!!!!', 'https://api.taxamo.com');
        $api = new Taxamo($swagger);
        $resp = $api->calculateTax(array());
        $this->fail('TaxamoAuthenticationException not thrown');
    } catch (TaxamoAuthenticationException $e) {
        $this->assertEqual($e->response, "{\"errors\":[\"Please provide correct public token.\"]}");
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoAuthenticationException was thrown');
    }
  }

 public function testConnectionError() {
    try {
        $swagger = new APIClient('SamplePrivateTestKey1', 'https://api.taxamo.com.nonexistent');
        $api = new Taxamo($swagger);
        $resp = $api->calculateTax(array());
        $this->fail('TaxamoAuthenticationException not thrown');
    } catch (TaxamoAPIException $e) {
        $this->assertEqual($e->response['http_code'], 0);
    } catch (Exception $e) {
        $this->fail('Exception other than TaxamoAPIException was thrown');
    }
  }

}


?>