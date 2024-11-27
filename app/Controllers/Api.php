<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Api extends BaseController
{
    private $apiKey = "9e797da3165609b77e870967e50a8e12";

    public function proxy($endpoint = '')
    {
        if (!$endpoint) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Endpoint is required.'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $url = "https://api.rajaongkir.com/starter/" . $endpoint;

        $client = \Config\Services::curlrequest();
        $options = [
            'headers' => [
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'http_errors' => false,
            'verify' => false 
        ];
        

        // Metode POST atau GET
        if ($this->request->getMethod() === 'POST') {
            $options['form_params'] = $this->request->getPost();
        }

        try {
            $response = $client->request($this->request->getMethod(), $url, $options);
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
