<?php

namespace SakuraSamurai\Gismeteo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SakuraSamurai\Gismeteo\Exception\GismeteoException;
use SakuraSamurai\Gismeteo\Exception\InvalidArgumentException;
use SakuraSamurai\Gismeteo\Response\SearchResponse\SearchResponse;
use SakuraSamurai\Gismeteo\Response\WeatherResponse\WeatherResponse;

class Gismeteo implements GismeteoInterface
{
    protected const API_URL = 'https://api.gismeteo.net';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::API_URL,
            'headers' => [
                'X-Gismeteo-Token' => $_SERVER['GISMETEO_TOKEN'],
                'Accept-Encoding' => 'gzip'
            ]
        ]);
    }

    /**
     * Получение погодных данных "за окном" по координатам
     * @param float $latitude Широта. Диапазон допустимых значений от −90 до 90
     * @param float $longitude Долгота. Диапазон допустимых значений от −180 до 180
     * @return WeatherResponse Погодные данные
     * @throws GismeteoException
     */
    public function getCurrentWeatherByCoordinates(float $latitude, float $longitude): WeatherResponse
    {
        try {
            $responseInterface = $this->client->request('GET', '/v2/weather/current/', [
                'query' => ['latitude' => $latitude, 'longitude' => $longitude],
            ]);
            $response = json_decode($responseInterface->getBody()->getContents(), true)['response'];
            return WeatherResponse::fromResponse($response);
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Получение погодных данных по Географическому Обьекту
     * @param int $id ID географического объекта ищется через поиск.
     * @return WeatherResponse Погодные данные
     * @throws GismeteoException
     */
    public function getCurrentWeatherByGeographicObject(int $id): WeatherResponse
    {
        try {
            $responseInterface = $this->client->request('GET', "/v2/weather/current/$id/");
            $response = json_decode($responseInterface->getBody()->getContents(), true)['response'];
            return WeatherResponse::fromResponse($response);
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

    }

    /**
     * Получение прогноза погоды по координатам
     * @param float $latitude Широта. Диапазон допустимых значений от −90 до 90
     * @param float $longitude Долгота. Диапазон допустимых значений от −180 до 180
     * @param int $days Количество дней, от 1 до 10
     * @param int $step_count Количиство шагов измерения за день. Допустимые значения: 1|4|8
     * @return WeatherResponse[] Прогнозы погоды
     * @throws GismeteoException
     */
    public function getForecastDailyStepByCoordinates(float $latitude, float $longitude, int $days, int $step_count): array
    {
        $query = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'days' => $days,
        ];

        // Количество сроков за сутки:
        switch ($step_count) {
            case 1:
                $uri = '/v2/weather/aggregate/';
                break;
            case 4:
                $uri = '/v2/weather/forecast/by_day_part/';
                break;
            case 8:
                $uri = '/v2/weather/forecast/';
                break;
            default:
                throw new InvalidArgumentException('$step_count can be 1 or 4 or 8');
        }

        try {
            $responseInterface = $this->client->request('GET', $uri, ['query' => $query,]);
            $result = [];

            $responseArray = json_decode($responseInterface->getBody()->getContents(), true)['response'];
            foreach ($responseArray as $response) {
                $result[] = WeatherResponse::fromResponse($response);
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

    }

    /**
     * Получение прогноза погоды по Географичесому Обьекту
     * @param int $id ID географического объекта ищется через поиск.
     * @param int $days Количество дней от 1 до 10
     * @param int $step_count Количиство шагов измерения за день. Допустимые значения: 1|4|8
     * @return WeatherResponse[] Прогнозы погоды
     * @throws InvalidArgumentException
     * @throws GismeteoException
     */
    public function getForecastDailyStepByGeographicObject(int $id, int $days, int $step_count): array
    {
        $query = [
            'days' => $days,
        ];

        // Количество сроков за сутки:
        switch ($step_count) {
            case 1:
                $uri = "/v2/weather/aggregate/$id";
                break;
            case 4:
                $uri = "/v2/weather/by_day_part/$id";
                break;
            case 8:
                $uri = "/v2/weather/forecast/$id";
                break;
            default:
                throw new InvalidArgumentException('Invalid step count');
        }

        try {
            $responseInterface = $this->client->request('GET', $uri, ['query' => $query,]);
            $result = [];

            $responseArray = json_decode($responseInterface->getBody()->getContents(), true)['response'];
            foreach ($responseArray as $response) {
                $result[] = WeatherResponse::fromResponse($response);
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

    }

    /**
     * Поиск географических обьектов по координатам
     * @param float $latitude Широта. Диапазон допустимых значений от −90 до 90
     * @param float $longitude Долгота. Диапазон допустимых значений от −180 до 180
     * @param int $limit Ограничение количества географических объектов. От 1 до 36.
     * @return SearchResponse[] Географические обьекты
     * @throws GismeteoException
     */
    public function searchGeographicObjectByCoordinates(float $latitude, float $longitude, int $limit): array
    {
        try {
            $responseInterface = $this->client->request('GET', '/v2/search/cities/', [
                'query' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'limit' => $limit
                ],
            ]);
            $result = [];

            $responseArray = json_decode($responseInterface->getBody()->getContents(), true)['response']['items'];
            foreach ($responseArray as $response) {
                $result[] = SearchResponse::fromResponse($response);
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Поиск географических обьектов по IP-адресу пользователя
     * @param string $ip IP-адрес пользователя
     * @return SearchResponse[] Географические обьекты
     * @throws GismeteoException
     */
    public function searchGeographicObjectByIP(string $ip): array
    {
        try {
            $responseInterface = $this->client->request('GET', '/v2/search/cities/', [
                'query' => [
                    'ip' => $ip,
                ],
            ]);
            $result = [];

            $responseArray = json_decode($responseInterface->getBody()->getContents(), true)['response']['items'];
            foreach ($responseArray as $response) {
                $result[] = SearchResponse::fromResponse($response);
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Поиск географических обьектов по ключевому слову
     * @param string $query Поиск по городу, району, области, стране, аэропорту
     * @return SearchResponse[] Географические обьекты
     * @throws GismeteoException
     */
    public function searchGeographicObjectByQuery(string $query): array
    {
        try {
            $responseInterface = $this->client->request('GET', '/v2/search/cities/', [
                'query' => [
                    'query' => $query,
                ],
            ]);
            $result = [];

            $responseArray = json_decode($responseInterface->getBody()->getContents(), true)['response']['items'];
            foreach ($responseArray as $response) {
                $result[] = SearchResponse::fromResponse($response);
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new GismeteoException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }


    }

}