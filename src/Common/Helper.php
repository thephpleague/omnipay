<?php
/**
 * Helper class
 */

namespace League\Omnipay\Common;

use InvalidArgumentException;
use League\Omnipay\Common\Exception\RuntimeException;

/**
 * Helper class
 *
 * This class defines various static utility functions that are in use
 * throughout the Omnipay system.
 */
class Helper
{
    /**
     * Convert a string to camelCase. Strings already in camelCase will not be harmed.
     *
     * @param  string  $str The input string
     * @return string camelCased output string
     */
    public static function camelCase($str)
    {
        $str = self::convertToLowercase($str);
        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * Convert strings with underscores to be all lowercase before camelCase is preformed.
     *
     * @param  string $str The input string
     * @return string The output string
     */
    protected static function convertToLowercase($str)
    {
        $explodedStr = explode('_', $str);

        if (count($explodedStr) > 1) {
            foreach ($explodedStr as $value) {
                $lowercasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowercasedStr);
        }

        return $str;
    }

    /**
     * Validate a card number according to the Luhn algorithm.
     *
     * @param  string  $number The card number to validate
     * @return boolean True if the supplied card number is valid
     */
    public static function validateLuhn($number)
    {
        $str = '';
        foreach (array_reverse(str_split($number)) as $i => $c) {
            $str .= $i % 2 ? $c * 2 : $c;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }

    /**
     * Initialize an object with a given array of parameters
     *
     * Parameters are automatically converted to camelCase. Any parameters which do
     * not match a setter on the target object are ignored.
     *
     * @param ParameterizedInterface $target     The object to set parameters on
     * @param array $parameters An array of parameters to set
     */
    public static function initializeParameters(ParameterizedInterface $target, array $parameters = [])
    {
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $method = 'set'.ucfirst(static::camelCase($key));
                if (method_exists($target, $method)) {
                    $target->$method($value);
                } else {
                    $target->setParameter($key, $value);
                }
            }
        }
    }

    /**
     * Resolve a gateway class to a short name.
     *
     * The short name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of a gateway.
     */
    public static function getGatewayShortName($className)
    {
        if (0 === strpos($className, '\\')) {
            $className = substr($className, 1);
        }

        if (0 === strpos($className, 'League\\Omnipay\\')) {
            return trim(str_replace('\\', '_', substr($className, 15, -7)), '_');
        }

        return '\\'.$className;
    }

    /**
     * Resolve a short gateway name to a full namespaced gateway class.
     *
     * Class names beginning with a namespace marker (\) are left intact.
     * Non-namespaced classes are expected to be in the \Omnipay namespace, e.g.:
     *
     *      \Custom\Gateway     => \Custom\Gateway
     *      \Custom_Gateway     => \Custom_Gateway
     *      Stripe              => \League\Omnipay\Stripe\Gateway
     *      PayPal\Express      => \League\Omnipay\PayPal\ExpressGateway
     *      PayPal_Express      => \League\Omnipay\PayPal\ExpressGateway
     *
     * @param  string  $shortName The short gateway name
     * @return string  The fully namespaced gateway class name
     */
    public static function getGatewayClassName($shortName)
    {
        if (0 === strpos($shortName, '\\')) {
            return $shortName;
        }

        // replace underscores with namespace marker, PSR-0 style
        $shortName = str_replace('_', '\\', $shortName);
        if (false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }

        return '\\League\\Omnipay\\'.$shortName.'Gateway';
    }

    /**
     * Convert an amount into a float.
     * The float datatype can then be converted into the string
     * format that the remote gateway requies.
     *
     * @var string|int|float $value The value to convert.
     * @throws InvalidArgumentException on a validation failure.
     * @return float The amount converted to a float.
     */

    public static function toFloat($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new InvalidArgumentException('Data type is not a valid decimal number.');
        }

        if (is_string($value)) {
            // Validate generic number, with optional sign and decimals.
            if (!preg_match('/^[-]?[0-9]+(\.[0-9]*)?$/', $value)) {
                throw new InvalidArgumentException('String is not a valid decimal number.');
            }
        }

        return (float)$value;
    }

    /**
     * Parse the JSON response body and return an array
     *
     * Copied from Response->json() in Guzzle3 (copyright @mtdowling)
     * @link https://github.com/guzzle/guzzle3/blob/v3.9.3/src/Guzzle/Http/Message/Response.php
     *
     * @param  string $body
     * @throws RuntimeException if the response body is not in JSON format
     * @return array|string|int|bool|float
     */
    public static function jsonDecode($body)
    {
        $data = json_decode((string) $body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }
        return $data === null ? [] : $data;
    }

    /**
     * Parse the XML response body and return a \SimpleXMLElement.
     *
     * In order to prevent XXE attacks, this method disables loading external
     * entities. If you rely on external entities, then you must parse the
     * XML response manually by accessing the response body directly.
     *
     * Copied from Response->xml() in Guzzle3 (copyright @mtdowling)
     * @link https://github.com/guzzle/guzzle3/blob/v3.9.3/src/Guzzle/Http/Message/Response.php
     *
     * @param  string $body
     * @return \SimpleXMLElement
     * @throws RuntimeException if the response body is not in XML format
     * @link http://websec.io/2012/08/27/Preventing-XXE-in-PHP.html
     *
     */
    public static function xmlDecode($body)
    {
        $errorMessage = null;
        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        libxml_clear_errors();
        try {
            $xml = new \SimpleXMLElement((string) $body ?: '<root />', LIBXML_NONET);
            if ($error = libxml_get_last_error()) {
                $errorMessage = $error->message;
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);
        if ($errorMessage) {
            throw new RuntimeException('Unable to parse response body into XML: ' . $errorMessage);
        }
        return $xml;
    }
}
