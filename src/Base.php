<?php

namespace Anilken\Turkpin;

use Anilken\Turkpin\Exceptions\Exception;
use SimpleXMLElement;

class Base
{
    protected $api_url = "http://turkpin.com/api.php";

    /**
     * @var string Turkpin Api Username
     */
    protected $username;

    /**
     * @var string Turkpin Api Password
     */
    protected $password;

    /**
     * @param $username
     * @param $password
     * @param bool $is_test
     */
    public function __construct($username, $password, $is_test = false)
    {
        $this->username = $username;
        $this->password = $password;

        if ($is_test === true) {
            $this->api_url = "https://www.turkpin.net/api.php";
        }
    }

    /**
     * @param $cmd
     * @param array|null $extra
     * @return mixed
     * @throws Exception
     */
    public function make($cmd, ?array $extra = [])
    {
        $api_data = [
            'params' => [
                'username' => $this->username,
                'password' => $this->password,
                'cmd' => $cmd,
            ],
        ];

        foreach ($extra as $k => $v) {
            $api_data['params'][$k] = $v;
        }


        $xml = new SimpleXMLElement('<APIRequest/>');

        $post = $this->array2xml($api_data, $xml)->asXML();

        return $this->request($post);
    }

    /**
     * @param $xml
     * @return mixed
     * @throws Exception
     */
    protected function request($xml)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => ['DATA' => $xml],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        if (! $response || strlen(trim($response)) == 0) {
            throw new Exception('Api empty result', 0);
        }

        $result = $this->xml2array($response)['params'];

        if (isset($result['HATA_NO']) && $result['HATA_NO'] !== '000') {
            throw new Exception($result['HATA_ACIKLAMA'], $result['HATA_NO']);
        }

        if (isset($result['error']) && $result['error'] !== '000') {
            throw new Exception($result['error_desc'], $result['error']);
        }

        return $result;
    }

    /**
     * @param array $data
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    protected function array2xml(array $data, SimpleXMLElement $xml): SimpleXMLElement
    {
        foreach ($data as $k => $v) {
            is_array($v) ? $this->array2xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
        }

        return $xml;
    }

    /**
     * @param $xml_data
     * @return mixed
     */
    protected function xml2array($xml_data)
    {
        $xml = simplexml_load_string($xml_data, "SimpleXMLElement", LIBXML_NOCDATA);

        return json_decode(json_encode($xml), true);
    }
}
