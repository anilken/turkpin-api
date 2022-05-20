<?php

namespace Anilken\Turkpin;

use Anilken\Turkpin\Exceptions\Exception;
use Anilken\Turkpin\Exceptions\InvalidArgumentException;

class Turkpin
{
    protected $api;

    /**
     * Turkpin constructor
     * Set username password for api
     *
     * @param string $username Turkpin Api Username
     * @param string $password Turkpin Api Password
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __construct($username, $password, $is_test = false)
    {
        if (! function_exists('curl_version')) {
            throw new Exception('CURL not installed');
        }

        $this->api = new Base($username, $password, $is_test);
    }

    /**
     * Get List of games
     *
     * @return array
     * @throws Exception
     */
    public function epinList(): array
    {
        $result = $this->api->make('epinOyunListesi');

        if (! isset($result['oyunListesi']['oyun'])) {
            throw new Exception('Failed to get game list', 101);
        }

        return $result['oyunListesi']['oyun'];
    }

    /**
     * Get List of products
     *
     * @param $epin_id
     * @return array
     * @throws Exception
     */
    public function epinProducts($epin_id): array
    {
        $result = $this->api->make('epinUrunleri', ['oyunKodu' => $epin_id]);

        if (! isset($result['epinUrunListesi']['urun'])) {
            throw new Exception('Failed to get products list', 102);
        }

        return $result['epinUrunListesi']['urun'];
    }

    /**
     * Create new order
     *
     * @param int $epin_id epinList() id
     * @param int $product_id epinProducts() id
     * @param int $qty order qty
     * @param string $character optional character to be delivered
     * @return array
     * @throws Exception
     */
    public function epinOrder($epin_id, $product_id, $qty = 1, $character = ''): array
    {
        $result = $this->api->make('epinSiparisYarat', ['oyunKodu' => $epin_id, 'urunKodu' => $product_id, 'adet' => $qty, 'character' => $character]);

        $r = [
            'status' => $result['siparisSonuc'],
            'order_no' => $result['siparisNo'],
            'total_amount' => $result['siparisTutari'],
        ];


        if (isset($result['epin_list']['epin'])) {
            $r['list'] = $result['epin_list']['epin'];
        }

        //fix array
        if (isset($result['epin_list']['epin']) && isset($result['epin_list']['epin']['code']) == 1) {
            $r['list'] = [$result['epin_list']['epin']];
        }

        return $r;
    }

    /**
     * Check in stock product
     *
     * @param int $epin_id epinList() id
     * @param int $product_id epinProducts() id
     * @return int
     * @throws Exception
     */
    public function epinCheckStock($epin_id, $product_id): int
    {
        $products = $this->epinProducts($epin_id);

        $stock = 0;
        foreach ($products as $product) {
            if ($product['id'] === $product_id) {
                $stock = $product['stock'];

                break;
            }
        }

        return $stock;
    }

    /**
     * Check order Status
     *
     * @param int|string $order_no epinOrder() order_no
     * @return array
     * @throws Exception
     */
    public function checkStatus($order_no): array
    {
        $result = $this->api->make('siparisDurumu', ['siparisNo' => $order_no]);

        $r = [
            'status_code' => $result['DURUM_KODU'],
            'order_no' => $result['SIPARIS_NO'],
            'order_code' => $result['SIPARIS_DURUMU'],
            'order_status_description' => $result['SIPARIS_DURUMU_ACIKLAMA'],
            'check_date' => $result['KONTROL_TARIHI'],
        ];

        if (isset($result['EKSTRA'])) {
            $r['extra'] = $result['EKSTRA'];
        }

        return $r;
    }

    /**
     * Check account balance
     *
     * @return mixed
     * @throws Exception
     */
    public function checkBalance()
    {
        $result = $this->api->make('balance');

        if (! isset($result['balanceInformation'])) {
            throw new Exception('Failed to get balance', 103);
        }

        return $result['balanceInformation'];
    }
}
