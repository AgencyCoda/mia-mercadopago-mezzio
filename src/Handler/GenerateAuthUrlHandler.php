<?php

namespace Mia\Mercadopago\Handler;

use Mia\Mercadopago\Helper\MercadopagoHelper;

/**
 * Description of FetchHandler
 *
 * @author matiascamiletti
 */
class GenerateAuthUrlHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * @var MercadopagoHelper
     */
    protected $service;

    public function __construct(MercadopagoHelper $service)
    {
        $this->service = $service;
    }
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get Current User
        $user = $this->getUser($request);
        // Generate Auth URL
        $data = $this->service->generateAuthUrl('', getenv('MERCADOPAGO_REDIRECT_URL') ? getenv('MERCADOPAGO_REDIRECT_URL') : '/mercadopago/connect');
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }
}