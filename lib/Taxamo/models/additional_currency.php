<?php namespace Taxamo;
/**
 *  Copyright 2014-2018 Taxamo
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
class Additional_currency {

  static $swaggerTypes = array(
      'currency_code' => 'string',
      'amount' => 'number',
      'tax_amount' => 'number',
      'fx_rate' => 'number',
      'fx_date' => 'string',
      'total_amount' => 'number'

    );

  /**
  * 3-letter ISO currency code.
  */
  public $currency_code; // string
  /**
  * Amount (w/o TAX) in designated currency.
  */
  public $amount; // number
  /**
  * Tax amount in designated currency.
  */
  public $tax_amount; // number
  /**
  * Foreign exchange rate used in calculation
  */
  public $fx_rate; // number
  /**
  * Date to use when calculating invoice FX rate. Defaults to transaction's order date.
  */
  public $fx_date; // string
  /**
  * Total amount in designated currency.
  */
  public $total_amount; // number
  }

