<?php namespace League\Omnipay\Common;

interface ParameterizedInterface
{
    public function setParameter($key, $value);

    /**
     * Get one parameter.
     *
     * @return mixed A single parameter value.
     */
    public function getParameter($key);

    /**
     * Get all parameters.
     *
     * @return array An associative array of parameters.
     */
    public function getParameters();
}
