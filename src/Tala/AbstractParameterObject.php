<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Tala\Exception\MissingParameterException;

/**
 * Abstract Parameter Object class
 */
abstract class AbstractParameterObject
{
    protected $parameters;

    public function __construct($parameters = array())
    {
        $this->initialize($parameters);
    }

    public function initialize($parameters)
    {
        foreach ((array) $parameters as $key => $value) {
            if ($key) {
                $this->setParameter($key, $value);
            }
        }
    }

    /**
     * Dynamically retrieve properties.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getParameter($key);
    }

    /**
     * Dynamically set properties
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setParameter($key, $value);
    }

    /**
     * Dynamically generate property getters and setters
     */
    public function __call($name, $arguments)
    {
        $type = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        if ($type == 'get') {
            return $this->getParameter($property);
        } elseif ($type == 'set') {
            $value = isset($arguments[0]) ? $arguments[0] : null;

            return $this->setParameter($property, $value);
        }

        throw new \BadMethodCallException("Undefined method: $name");
    }

    public function getParameter($key)
    {
        if ($this->isValidParameter($key)) {
            // check for accessor method
            if (method_exists($this, 'get'.ucfirst($key))) {
                return $this->{'get'.ucfirst($key)}();
            }

            return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
        } else {
            throw new \BadMethodCallException("Invalid parameter: $key");
        }
    }

    public function setParameter($key, $value)
    {
        if ($this->isValidParameter($key)) {
            // check for mutator method
            if (method_exists($this, 'set'.ucfirst($key))) {
                $this->{'set'.ucfirst($key)}($value);
            } else {
                $this->parameters[$key] = $value;
            }
        } else {
            throw new \BadMethodCallException("Invalid parameter: $key");
        }
    }

    /**
     * Return a list of valid parameter names. If empty, all parameter names are valid.
     * Can be overridden by a subclass.
     */
    public function getValidParameters()
    {
        return null;
    }

    /**
     * True if the specified parameter name is valid.
     */
    public function isValidParameter($key)
    {
        if (empty($key)) return false;

        $validParameters = $this->getValidParameters();

        return empty($validParameters) || in_array($key, $validParameters);
    }

    /**
     * Validate that the specified parameters have been supplied.
     * If any parameters are missing, an exception is thrown.
     */
    public function validateRequired($parameters)
    {
        if ( ! is_array($parameters)) {
            $parameters = array($parameters);
        }

        foreach ($parameters as $key) {
            if ('' == $this->getParameter($key)) {
                throw new MissingParameterException($key);
            }
        }
    }
}
