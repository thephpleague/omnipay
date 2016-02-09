<?php namespace Omnipay\Common;

interface ParameterizedInterface
{
    function setParameter($key, $value);

    /**
     * Get one parameter.
     *
     * @return mixed A single parameter value.
     */
    function getParameter($key);

    /**
     * Get all parameters.
     *
     * @return array An associative array of parameters.
     */
    function getParameters();
}
