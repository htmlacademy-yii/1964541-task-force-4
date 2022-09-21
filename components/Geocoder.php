<?php

namespace app\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use TaskForce\exceptions\BadRequestException;
use TaskForce\exceptions\WrongAnswerFormatException;
use yii\base\Component;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class Geocoder extends Component
{
    public string $address;
    public string $lat;
    public string $long;
    public string $apiKey;
    public string $apiUrl;
    const RESPONSE_CODE_OK = 200;
    const GEOCODE_BASE_URL = 'https://geocode-maps.yandex.ru/';
    const GEOCODE_API_KEY = 'e666f398-c983-4bde-8f14-e3fec900592a';
    const GEOCODE_COORDINATES_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    const GEOCODE_LONGITUDE = 0;
    const GEOCODE_LATITUDE = 1;

    public function getLocation($address)
    {
        $this->address = $address;
        $client = new Client(['base_uri' => self::GEOCODE_BASE_URL]);
        $request = new Request('GET', '1.x');
        $response = $client->send($request,
            ['query' => ['apikey' => self::GEOCODE_API_KEY, 'geocode' => $this->address, 'format' => 'json']]);

        if ($response->getStatusCode() !== self::RESPONSE_CODE_OK) {
            throw new BadRequestException('Ошибка запроса');
        }
        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new WrongAnswerFormatException('Ошибка формата ответа');
        }

        $location = explode(' ', ArrayHelper::getValue($responseData, self::GEOCODE_COORDINATES_KEY));

        $this->long = $location[self::GEOCODE_LONGITUDE];
        $this->lat = $location[self::GEOCODE_LATITUDE];
    }
}