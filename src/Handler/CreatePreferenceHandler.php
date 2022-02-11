<?php 

declare(strict_types=1);

namespace Mia\Mercadopago\Handler;

class CreatePreferenceHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface 
    {
        // Get Current User
        $user = $this->getUser($request);
        // Configurar query
        // Return data
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}