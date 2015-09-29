<?php namespace Taxamo;
/**
 *  Copyright 2014 Taxamo, Ltd.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 * $model.description$
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 *
 */
class Summary {

  static $swaggerTypes = array(
      'quarter' => 'string',
      'tax_amount' => 'number',
      'currency_code' => 'string',
      'indicative' => 'bool',
      'fx_rate_date' => 'string',
      'tax_entity_name' => 'string'

    );

  /**
  * Quarter that this summary applies to.
  */
  public $quarter; // string
  /**
  * Tax amount due in this quarter.
  */
  public $tax_amount; // number
  /**
  * In which currency code the settlement was calculated.
  */
  public $currency_code; // string
  /**
  * If the quarter isn't closed yet, tax amount is indicative, as we cannot determine FX rate or all transactions yet.
  */
  public $indicative; // bool
  /**
  * Date of ECB FX rate used for conversions in yyyy-MM-dd'T'hh:mm:ss'Z' format.
  */
  public $fx_rate_date; // string
  /**
  * Tax entity that the tax is due.
  */
  public $tax_entity_name; // string
  }

