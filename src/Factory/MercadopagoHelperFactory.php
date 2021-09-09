<?php 

declare(strict_types=1);

namespace Mia\Mercadopago\Factory;

use Mia\Mercadopago\MercadopagoHelper;
use Psr\Container\ContainerInterface;

class MercadopagoHelperFactory 
{
    public function __invoke(ContainerInterface $container) : MercadopagoHelper
    {
        // Obtenemos configuracion
        $config = $container->get('config')['mercadopago'];
        // creamos libreria
        return new MercadopagoHelper($config['client_id'], $config['client_secret']);
    }
}