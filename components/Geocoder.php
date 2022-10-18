<?php

namespace app\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use TaskForce\exceptions\BadRequestException;
use TaskForce\exceptions\WrongAnswerFormatException;
use Yii;
use yii\base\Component;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class Geocoder extends Component
{
    public string $apiKey;
    public string $baseUri;
    public Client $client;
    const RESPONSE_CODE_OK = 200;
    const GEOCODE_COORDINATES_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    const GEOCODER_ADDRESS_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
    const GEOCODE_LONGITUDE = 0;
    const GEOCODE_LATITUDE = 1;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    /** Извлекает из ответа Геокодера долготу
     * @param $address string Адрес, по которому ищутся координаты
     * @return string Longitude
     * @throws BadRequestException Ошибка запроса к серверу
     * @throws WrongAnswerFormatException Неверный формат ответа
     */
    public function getLong($address, $city): string
    {
        $address = $city . ', ' . $address;
        $location = explode(' ', ArrayHelper::getValue($this->loadLocation($address), self::GEOCODE_COORDINATES_KEY));

        return $location[self::GEOCODE_LONGITUDE];
    }

    /** Извлекает из ответа Геокодера широту
     * @param $address string Адрес, по которому ищутся координаты
     * @return string Longitude
     * @throws BadRequestException Ошибка запроса к серверу
     * @throws WrongAnswerFormatException Неверный формат ответа
     */
    public function getLat($address, $city): string
    {
        $address = $city . ', ' . $address;
        $location = explode(' ', ArrayHelper::getValue($this->loadLocation($address), self::GEOCODE_COORDINATES_KEY));

        return $location[self::GEOCODE_LATITUDE];
    }

    /** Извлекает из ответа Геокодера адрес
     * @param $address string Координаты, по которым ищется адрес
     * @return mixed Адрес
     * @throws BadRequestException Ошибка запроса к серверу
     * @throws WrongAnswerFormatException Неверный формат ответа
     */
    public function getAddress($address): string
    {
        return ArrayHelper::getValue($this->loadLocation($address), self::GEOCODER_ADDRESS_KEY);
    }

    /** Geocoder ApiKey
     * @return string Возвращает АПИ ключ из конфига
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**Связывается с ЯндексГеокодер и возаращает ответ
     * @param $address string Адрес|координаты, которые передаются в API Геокодера
     * @return mixed возвращает массив данных от Геокодера
     * @throws BadRequestException Ошибка запроса к серверу
     * @throws WrongAnswerFormatException Неверный формат ответа
     */
    public function loadLocation($address): array
    {
        $response = $this->client->request('GET', '1.x',
            ['query' => ['apikey' => $this->apiKey, 'geocode' => $address, 'format' => 'json']]);

        if ($response->getStatusCode() !== self::RESPONSE_CODE_OK) {
            throw new BadRequestException('Ошибка запроса');
        }
        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new WrongAnswerFormatException('Ошибка формата ответа');
        }

        return $responseData;

    }

    /**
     * Извлекает из ответа геокодера похожие адреса
     * @param string $address Вводимый пользователем адрес
     * @param string $city Город пользователя
     * @return array Массив с данными для отправки в autocomplete
     * @throws BadRequestException
     * @throws WrongAnswerFormatException
     */
    public function getAutocompleteData($address, $city): array
    {
        $address = $city . ', ' . $address;
        $data = $this->loadLocation($address);
        $feature_members = ArrayHelper::getValue($data,'response.GeoObjectCollection.featureMember');
        foreach ($feature_members as $feature_member) {
            $autoComplete[] = ArrayHelper::getColumn($feature_member, 'name')['GeoObject'];
        }

        return $autoComplete;
    }
}