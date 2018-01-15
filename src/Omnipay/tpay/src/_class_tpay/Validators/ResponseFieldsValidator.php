<?php

/*
 * Created by tpay.com.
 * Date: 20.06.2017
 * Time: 17:49
 */

namespace Omnipay\Tpay\_class_tpay\Validators;


use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Utilities\Util;
use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

trait ResponseFieldsValidator
{
    /**
     * Check all variables required in response
     * Parse variables to valid types
     *
     * @param object $paymentType
     *
     * @return array
     * @throws TException
     */
    public function getResponse($paymentType)
    {
        $ready = array();
        $missed = array();

        $responseFields = $paymentType->getResponseFields();

        foreach ($responseFields as $fieldName => $field) {
            if (Util::post($fieldName, FieldsConfigDictionary::STRING) === false) {
                if ($field[FieldsConfigDictionary::REQUIRED] === true) {
                    $missed[] = $fieldName;
                }
            } else {
                $val = Util::post($fieldName, FieldsConfigDictionary::STRING);
                switch ($field[FieldsConfigDictionary::TYPE]) {
                    case FieldsConfigDictionary::STRING:
                        $val = (string)$val;
                        break;
                    case FieldsConfigDictionary::INT:
                        $val = (int)$val;
                        break;
                    case FieldsConfigDictionary::FLOAT:
                        $val = (float)$val;
                        break;
                    case FieldsConfigDictionary::ARR:
                        $val = (array)$val;
                        break;
                    default:
                        throw new TException(sprintf('unknown field type in getResponse - field name= %s', $fieldName));
                }
                $ready[$fieldName] = $val;
            }
        }

        if (count($missed) > 0) {
            throw new TException(sprintf('Missing fields in tpay response: %s', implode(',', $missed)));
        }

        foreach ($ready as $fieldName => $fieldVal) {
            $this->hasValidFields($paymentType, $fieldName, $fieldVal, false);
        }

        return $ready;
    }
}
