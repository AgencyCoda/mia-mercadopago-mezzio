<?php

namespace Mia\Mercadopago\Helper;

use GuzzleHttp\Psr7\Request;

class MercadopagoHelper
{
    /**
     * URL de la API
     */
    const BASE_URL_API = 'https://api.mercadopago.com/';
    /**
     * URL de la API
     */
    const BASE_URL_AUTH = 'https://auth.mercadopago.com.ar/';
    /**
     * 
     * @var string
     */
    protected $clientId = '';
    /**
     * 
     * @var string
     */
    protected $clientSecret = '';
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;
    /**
     * 
     * @param string $access_token
     */
    public function __construct($client_id, $client_secret)
    {
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
        $this->guzzle = new \GuzzleHttp\Client();
        \MercadoPago\SDK::setAccessToken($client_secret);
    }
    /**
     * Example JSON Sended:
     * 
     * "items": [
     *     {
     *         "title": "Item title",
     *         "description": "Description",
     *         "quantity": 1,
     *         "unit_price": 50,
     *         "currency_id": "ARS",
     *         "picture_url": "https://www.mercadopago.com/org-img/MP3/home/logomp3.gif"
     *     }
     * ],
     * "marketplace_fee": 2.29
     *
     * @param [type] $accessToken
     * @param [type] $items
     * @param integer $fee
     * @return void
     */
    public function createSellerPreference($accessToken, $payerEmail, $items, $fee = 0, $notificationUrl = '')
    {
        $body = json_encode([
            'items' => $items,
            'payer' => ['email' => $payerEmail],
            'marketplace_fee' => $fee,
            'notification_url' => $notificationUrl
        ]);

        $request = new Request(
            'POST', 
            self::BASE_URL_API . 'checkout/preferences', 
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ], $body);

        $response = $this->guzzle->send($request);
        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents());
        }

        return null;
    }

    public function createPreference()
    {
        
    }
    /**
     *
     * @param string $customId
     * @param string $redirectUrl
     * @return string
     */
    public function generateAuthUrl($customId, $redirectUrl)
    {
        return self::BASE_URL_AUTH . 'authorization?client_id=' . $this->clientId . '&response_type=code&platform_id=mp&state=' . $customId . '&redirect_uri=' . $redirectUrl;
    }
    /**
     * Example response:
     * 
     * {
     *      "access_token": "MARKETPLACE_SELLER_TOKEN",
     *         "token_type": "bearer",
     *         "expires_in": 15552000,
     *         "scope": "offline_access read write",
     *         "refresh_token": "TG-XXXXXXXX"
     *     }
     *
     * @param string $code
     * @param string $redirectUrl
     * @return void
     */
    public function validateAuthorizationCode($code, $redirectUrl)
    {
        $response = $this->guzzle->request('POST', self::BASE_URL_API . 'oauth/token', [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUrl
            ]
        ]);

        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents());
        }

        return null;
    }
    /**
     * Example response:
     * 
     * {
     *      "access_token": "MARKETPLACE_SELLER_TOKEN",
     *         "token_type": "bearer",
     *         "expires_in": 15552000,
     *         "scope": "offline_access read write",
     *         "refresh_token": "TG-XXXXXXXX"
     *     }
     *
     * @param string $refreshToken
     * @return void
     */
    public function refreshAccessToken($refreshToken)
    {
        $response = $this->guzzle->request('POST', self::BASE_URL_API . 'oauth/token', [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken
            ]
        ]);

        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents());
        }

        return null;
    }
}