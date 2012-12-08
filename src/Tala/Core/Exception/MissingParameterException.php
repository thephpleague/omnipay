<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core\Exception;

use Tala\Core\Exception;

/**
 * Missing Parameter Exception.
 *
 * Thrown when a credit card or request parameter is missing.
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class MissingParameterException extends \RuntimeException implements Exception
{
    protected $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
        parent::__construct("The $parameter parameter is required");
    }
}
