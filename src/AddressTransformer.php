<?php

namespace TaskForce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class AddressTransformer
{
    private $address;
    public $lat;
    public $long;
    const RESPONSE_CODE_OK = 200;

    public function __construct($address)
    {
        $this->address = $address;
    }

    public function getLocation()
    {
        $client = new Client(['base_uri' => 'https://geocode-maps.yandex.ru/']);
        $response = $client->request('GET', '1.x',
            ['query' => ['apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a', 'geocode' => $this->address, 'format' => 'json']]);

        if ($response->getStatusCode() !== self::RESPONSE_CODE_OK) {
            //throw new BadResponseException('MLM');
        }
        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);

        $location = explode(' ', ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos'));

        $this->long = $location[0];
        $this->lat = $location[1];
    }
}