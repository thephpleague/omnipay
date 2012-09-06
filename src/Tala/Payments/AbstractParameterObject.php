<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payments;

use Tala\Payments\Exception\MissingParameterException;

/**
 * Abstract Parameter Object class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
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

    public function getParameter($key)
    {
        // check for accessor method
        if (method_exists($this, 'get'.ucfirst($key))) {
            return $this->{'get'.ucfirst($key)}();
        }

        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    public function setParameter($key, $value)
    {
        // check for mutator method
        if (method_exists($this, 'set'.ucfirst($key))) {
            $this->{'set'.ucfirst($key)}($value);
        } else {
            $this->parameters[$key] = $value;
        }
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
