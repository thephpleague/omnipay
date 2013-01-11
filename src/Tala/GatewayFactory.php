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

class GatewayFactory
{
    public function createGateway($type, $parameters = array())
    {
        $type = $this->resolveType($type);

        $gateway = new $type;
        $gateway->initialize($parameters);

        return $gateway;
    }

    protected function resolveType($type)
    {
        $ns = strpos($type, '\\');
        if ($ns === 0) {
            return $type;
        } elseif ($ns === false) {
            $type .= '\\';
        }

        return '\\Tala\\'.$type.'Gateway';
    }
}
