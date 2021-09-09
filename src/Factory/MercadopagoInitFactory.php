<?php

namespace Mia\Mercadopago\Factory;

use Mia\Mercadopago\Helper\MercadopagoHelper;
use Psr\Container\ContainerInterface;

class MercadopagoInitFactory
{
    public function __invoke(ContainerInterface $container, $requestName)
    {
        // Get service
        $service = $container->get(MercadopagoHelper::class);
        // Generate class
        return new $requestName($service);
    }
}