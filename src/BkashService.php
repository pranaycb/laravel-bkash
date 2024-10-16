<?php

/**
 * A laravel package for bkash payment gateway
 * @author Pranay Chakraborty <pranaycb.ctg@gmail.com>
 * @link https://github.com/pranaycb
 */

namespace PranayCb\LaravelBkash;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Exception;

class BkashService
{
    /**
     * Bkash config
     * @var array
     */
    private array $bkash_config;

    /**
     * Bkash request base url
     * @var string
     */
    private string $base_url;

    /**
     * Set configuration
     * @param array $config
     * @throws Exception
     */
    public function setConfig(array $config)
    {
        if (!array_key_exists('environment', $config)) {
            throw new Exception('Environment parameter is required');
        }

        switch ($config['environment']) {
            case 'sandbox':
                $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/';
                break;

            case 'production':
                $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/';
                break;

            default:
                throw new Exception('Environment ' . $config['environment'] . ' is not allowed. Allowed environments are: sandbox, production');
        }

        unset($config['environment']);
        $this->bkash_config = $config;
    }

    /**
     * Grant token
     * @return string
     * @throws Exception
     */
    public function getToken(): string
    {
        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'password' => $this->bkash_config['password'],
                'username' => $this->bkash_config['username'],
            ])->post($this->base_url . 'token/grant', [
                'app_key' => $this->bkash_config['app_key'],
                'app_secret' => $this->bkash_config['app_secret'],
            ]);

            if ($response->status() !== 200) {
                throw new Exception('Failed to generate token. Check credentials');
            }

            $contentsObject = $response->json();

            if ($contentsObject['statusCode'] !== '0000') {
                throw new Exception("Code: {$contentsObject['statusCode']}; {$contentsObject['statusMessage']}");
            }

            Cookie::queue('id_token', $contentsObject['id_token'], 0);

            return $contentsObject['id_token'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create payment
     * @param array $paymentData
     * @return object
     * @throws Exception
     */
    public function createPayment(array $paymentData): object
    {
        $authToken = $this->getToken();

        try {
            $response = Http::withHeaders([
                'authorization' => $authToken,
                'x-app-key' => $this->bkash_config['app_key'],
                'content-type' => 'application/json',
            ])->post($this->base_url . 'create', $paymentData);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Execute payment
     * @param string $paymentId
     * @return object
     * @throws Exception
     */
    public function executePayment(string $paymentId): object
    {
        $authToken = Cookie::get('id_token');

        try {
            $response = Http::withHeaders([
                'authorization' => $authToken,
                'x-app-key' => $this->bkash_config['app_key'],
                'content-type' => 'application/json',
            ])->post($this->base_url . 'execute', [
                'paymentID' => $paymentId,
            ]);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Query payment
     * @param string $trxId
     * @return object
     * @throws Exception
     */
    public function queryPayment(string $trxId): object
    {
        $authToken = Cookie::get('id_token');

        try {
            $response = Http::withHeaders([
                'authorization' => $authToken,
                'x-app-key' => $this->bkash_config['app_key'],
                'content-type' => 'application/json',
            ])->get($this->base_url . 'general/searchTransaction', [
                'trxID' => $trxId,
            ]);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Handle response
     * @param $response
     * @return object
     * @throws Exception
     */
    private function handleResponse($response): object
    {
        if ($response->status() === 200 && $response->json()['statusCode'] === '0000') {
            return $response->json();
        }

        throw new Exception($response->json()['statusCode'] . ': ' . $response->json()['statusMessage']);
    }
}
