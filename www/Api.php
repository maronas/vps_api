<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

require 'vendor/autoload.php';

class Api
{
    function getApiUri(): string
    {
        return 'https://billing.time4vps.com';
    }

    function getCredentials(): string
    {
        $credentials = $_SESSION['credentials'];
        return $credentials;
    }

    protected function getPublicHttpClient(array $headers = []): Client
    {
        return new Client([
            'base_uri' => $this->getApiUri() . '/api/',
            'headers' => [
                ...$headers
            ]
        ]);
    }

    protected function getAuthHttpClient(array $headers = []): Client
    {
        return $this->getPublicHttpClient([
            'Authorization' => 'Basic ' . $this->getCredentials(),
            ...$headers
        ]);
    }

    function signUp($credentials): array
    {
        $json = [
            'json' => [
                "type" => $credentials["type"],
                "email" => $credentials["email"],
                "password" => $credentials["password"],
                "firstname" => $credentials["firstname"],
                "lastname" => $credentials["lastname"],
                "country" => $credentials["country"],
                "address1" => $credentials["address1"],
                "city" => $credentials["city"],
                "currency" => "EUR"
            ]
        ];

        $response = $this->getPublicHttpClient()->post('signup', $json)->getBody()->getContents();
        return json_decode($response, true);
    }

    function fetchCategories()
    {
        try {
            $cat_resp = $this->getAuthHttpClient()->get('category');
            $body = $cat_resp->getBody()->getContents();
            $data = json_decode($body, true);
            if (!empty($data['categories'])) {
                return $data['categories'];
            } else {
                return 'No categories found';
            }
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function fetchProducts($cat_id)
    {
        try {
            $response = $this->getAuthHttpClient()->get('category/' . $cat_id . '/product');
            $data = json_decode($response->getBody()->getContents(), true);
            if (!empty($data['products'])) {
                return $data['products'];
            } else {
                return 'No products found';
            }
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function fetchProductInfo($prod_id)
    {
        try {
            $response = $this->getAuthHttpClient()->get('order/' . $prod_id);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function fetchPayments()
    {
        try {
            $response = $this->getAuthHttpClient()->get('payment');
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function orderProduct($data, $product_id)
    {
        try {
            $response = $this->getAuthHttpClient()->request('POST', 'order/' . $product_id, ['json' => $data]);
            $order_response = json_decode($response->getBody()->getContents(), true);
            $response_order_id = $this->getAuthHttpClient()->get('service/order/' . $order_response['order_num']);
            $order_id = json_decode($response_order_id->getBody()->getContents(), true);
            $response_info = $this->getAuthHttpClient()->get('service/' . $order_id['account_id']);
            $order_info = json_decode($response_info->getBody()->getContents(), true);
            $data_set = [
                "user_id" => "",
                "order_number" => $order_response['order_num'],
                "order_id" => $order_id['account_id'],
                "invoice_id" => $order_response['invoice_id'],
                "product_id" => $order_response['items'][0]['product_id'],
                "service_type" => $order_response['items'][0]['type'],
                "service_name" => $order_info['service']['name'],
                "total_price" => (float)$order_info['service']['total']
            ];
            return $data_set;
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function orderHistory($product_id, $order_id)
    {
        try{
            $response = $this->fetchProductInfo($product_id);
            $config = $response['product']['description'];
            $data_set = [
                'config' => $config,
            ];
            return $data_set;
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

    function orderHistoryDateCreated($order_id){
        try{
            $response_info = $this->getAuthHttpClient()->get('service/' . $order_id);
            $order_info = json_decode($response_info->getBody()->getContents(), true);
            return $order_info['service']['date_created'];
        } catch (ClientException $e) {
            return 'Client Error: ' . $e->getMessage();
        } catch (RequestException $e) {
            return 'Request Error: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'General Error: ' . $e->getMessage();
        }
    }

}
?>