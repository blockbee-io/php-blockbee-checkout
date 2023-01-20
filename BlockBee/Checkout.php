<?php

namespace BlockBee;

use Exception;

class Checkout
{
    private static $base_url = 'https://api.blockbee.io';
    private $bb_params = [];
    private $parameters = [];
    private $api_key = null;

    /**
     * @throws Exception
     */
    public function __construct($api_key, $parameters = [], $bb_params = [])
    {
        if (empty($api_key)) {
            throw new Exception('API Key is Empty');
        }

        $this->bb_params = $bb_params;
        $this->parameters = $parameters;
        $this->api_key = $api_key;
    }

    /**
     * Handles request to payments.
     * @return array
     */
    public function payment_request($redirect_url, $value)
    {
        if (empty($redirect_url) || empty($value)) {
            return null;
        }

        if (!empty($this->parameters)) {
            $req_parameters = http_build_query($this->parameters);
            $redirect_url   = "{$redirect_url}?{$req_parameters}";
        }

        $bb_params = array_merge([
            'redirect_url' => $redirect_url,
            'apikey' => $this->api_key,
            'value' => $value
        ], $this->bb_params);

        $response = Checkout::_request('', 'checkout/request', $bb_params);
        if ($response->status == 'success') {
            return [
                'success_token' => $response->success_token,
                'payment_url' => $response->payment_url
            ];
        }

        return null;
    }

    /**
     * Handles deposit requests
     * @return array
     */
    public function deposit_request($notify_url) {
        if (empty($notify_url)) {
            return null;
        }

        if (!empty($this->parameters)) {
            $req_parameters = http_build_query($this->parameters);
            $notify_url   = "{$notify_url}?{$req_parameters}";
        }

        $bb_params = array_merge([
            'notify_url' => $notify_url,
            'apikey' => $this->api_key,
        ], $this->bb_params);

        $response = Checkout::_request('', 'deposit/request', $bb_params);
        if ($response->status == 'success') {
            return [
                'payment_url' => $response->payment_url
            ];
        }

        return null;
    }

    private static function _request($coin, $endpoint, $params = [], $assoc = false)
    {
        $base_url = Checkout::$base_url;
        $coin = str_replace('_', '/', (string)$coin);

        if (!empty($params)) {
            $data = http_build_query($params);
        }

        if (!empty($coin)) {
            $url = "{$base_url}/{$coin}/{$endpoint}/";
        } else {
            $url = "{$base_url}/{$endpoint}/";
        }

        if (!empty($data)) {
            $url .= "?{$data}";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, $assoc);
    }
}
