<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Facade\PrettyPrint;

use SakuraSamurai\Gismeteo\Exception\GismeteoException;
use SakuraSamurai\Gismeteo\Exception\InvalidArgumentException;
use SakuraSamurai\Gismeteo\Gismeteo;
use SakuraSamurai\Gismeteo\GismeteoInterface;
use SakuraSamurai\Gismeteo\Response\SearchResponse\SearchResponse;
use SakuraSamurai\Gismeteo\Response\WeatherResponse\WeatherResponse;

class GismeteoPrettyPrintApi implements GismeteoInterface
{
    private Gismeteo $gismeteo;

    public function __construct()
    {
        $this->gismeteo = new Gismeteo();
    }

    private function returnPrettyPrintedWeatherString(WeatherResponse $result): string
    {
        $precipitationIntensityInt = $result->precipitation->intensity;
        switch ($precipitationIntensityInt) {
            case 0:
                $precipitationIntensityString = 'Нет осадков';
                break;
            case 1:
                $precipitationIntensityString = 'Небольшой дождь / снег';
                break;
            case 2:
                $precipitationIntensityString = 'Дождь / снег';
                break;
            case 3:
                $precipitationIntensityString = 'Сильный дождь / снег';
                break;
            default:
                $precipitationIntensityString = 'Неизвестный';
        }
        $precipitationTypeInt = $result->precipitation->type;
        switch ($precipitationTypeInt) {
            case 0: //
                $precipitationTypeString = 'Нет осадков☀️';
                break;
            case 1:
                $precipitationTypeString = 'Дождь🌧';
                break;
            case 2:
                $precipitationTypeString = 'Снег🌨';
                break;
            case 3:
                $precipitationTypeString = 'Смешанные осадки🌦';
                break;
            default:
                $precipitationTypeString = 'Неизвестный';
        }
        $precipitation =
            "- Тип: $precipitationTypeString \n" .
            "- Интенсивность: $precipitationIntensityString \n" .
            "- Количество: {$result->precipitation->amount} мм. \n" .
            "- Длительность: {$result->precipitation->duration} \n";

        $geomagneticFieldInt = $result->geomagneticField;
        switch ($geomagneticFieldInt) {
            case 1:
                $geomagneticFieldString = 'Нет заметных возмущений';
                break;
            case 2:
                $geomagneticFieldString = 'Небольшие возмущения';
                break;
            case 3:
                $geomagneticFieldString = 'Слабая геомагнитная буря';
                break;
            case 4:
                $geomagneticFieldString = 'Малая геомагнитная буря';
                break;
            case 5:
                $geomagneticFieldString = 'Умеренная геомагнитная буря';
                break;
            case 6:
                $geomagneticFieldString = 'Сильная геомагнитная буря';
                break;
            case 7:
                $geomagneticFieldString = 'Жесткий геомагнитный шторм';
                break;
            case 8:
                $geomagneticFieldString = 'Экстремальный шторм';
                break;
            default:
                $geomagneticFieldString = 'Неизвестно';
        }
        $windDirectionScale_8Int = $result->wind->directionScale_8;
        switch ($windDirectionScale_8Int) {
            case 0:
                $windDirectionScale_8String = 'Штиль';
                break;
            case 1:
                $windDirectionScale_8String = 'Северный';
                break;
            case 2:
                $windDirectionScale_8String = 'Северо-восточный';
                break;
            case 3:
                $windDirectionScale_8String = 'Восточный';
                break;
            case 4:
                $windDirectionScale_8String = 'Юго-восточный';
                break;
            case 5:
                $windDirectionScale_8String = 'Южный';
                break;
            case 6:
                $windDirectionScale_8String = 'Юго-западный';
                break;
            case 7:
                $windDirectionScale_8String = 'Западный';
                break;
            case 8:
                $windDirectionScale_8String = 'Северо-западный';
                break;
            default:
                $windDirectionScale_8String = 'Неизвестно';
        }
        $wind =
            "- Скорость: {$result->wind->speedM_s} м/с \n" .
            "- Направление: $windDirectionScale_8String \n";
        $cloudinessTypeInt = $result->cloudiness->type;
        switch ($cloudinessTypeInt) {
            case 0:
                $cloudinessTypeString = 'Ясно☀️';
                break;
            case 1:
                $cloudinessTypeString = 'Малооблачно🌤';
                break;
            case 2:
                $cloudinessTypeString = 'Облачно🌥️';
                break;
            case 3:
                $cloudinessTypeString = 'Пасмурно☁';
                break;
            case 101:
                $cloudinessTypeString = 'Переменная облачность🌤';
                break;
            default:
                $cloudinessTypeString = 'Неизвестно';
        }
        $cloudiness =
            "- Процент: {$result->cloudiness->percent} % \n" .
            "- Тип: $cloudinessTypeString \n";
        $kind = ($result->kind === 'Obs')
            ? 'Наблюдение' // True
            : (($result->kind === 'Frc')
                ? 'Прогноз'
                : 'Неизвестный тип');

        $storm = $result->storm ? 'Сейчас шторм!🧨' . "\n" : '';

        $temperature =
            "- Температура воздуха(C): {$result->temperature->airC} ({$result->temperature->comfortC}) \n" .
            "- Температура воды(C): {$result->temperature->waterC} \n";

        return (
            "Осадки: \n $precipitation \n" .
            "Давление: {$result->pressure->mm_hg_atm} мм.рт. столба \n" .
            "Влажность: {$result->humidity->percent} % \n" .
            "Геомагнитное поле: $geomagneticFieldString \n" .
            "Ветер: \n $wind \n" .
            "Облачность: \n $cloudiness \n" .
            "Дата измерений: $result->measurementDate \n" .
            "Тип данных: $kind \n" .
            "$storm" .
            "Температура(Температура 'по ощущению'): \n $temperature \n" .
            "Описание погоды: $result->fullDescription \n"
        );
    }

    /**
     * @throws GismeteoException
     */
    public function getCurrentWeatherByCoordinates(float $latitude, float $longitude): string
    {
        $result = $this->gismeteo->getCurrentWeatherByCoordinates($latitude, $longitude);
        return $this->returnPrettyPrintedWeatherString($result);
    }

    /**
     * @throws GismeteoException
     */
    public function getCurrentWeatherByGeographicObject(int $id): string
    {
        $result = $this->gismeteo->getCurrentWeatherByGeographicObject($id);
        return $this->returnPrettyPrintedWeatherString($result);
    }

    /**
     * @return string[]
     * @throws GismeteoException
     */
    public function getForecastDailyStepByCoordinates(float $latitude, float $longitude, int $days, int $step_count): array
    {
        $strings = [];
        $result = $this->gismeteo->getForecastDailyStepByCoordinates($latitude, $longitude, $days, $step_count);
        foreach ($result as $resultItem) {
            $strings[] = $this->returnPrettyPrintedWeatherString($resultItem);
        }

        return $strings;
    }

    /**
     * @return string[]
     * @throws InvalidArgumentException
     * @throws GismeteoException
     */
    public function getForecastDailyStepByGeographicObject(int $id, int $days, int $step_count): array
    {
        $result = $this->gismeteo->getForecastDailyStepByGeographicObject($id, $days, $step_count);
        $strings = [];
        foreach ($result as $resultItem) {
            $strings[] = $this->returnPrettyPrintedWeatherString($resultItem);
        }
        return $strings;
    }

    private function returnPrettyPrintedGeographicObject(SearchResponse $search): string
    {
        switch ($search->kind) {
            case'T':
                $kindValue = 'Город';
                break;
            case'C':
                $kindValue = 'Мегаполис';
                break;
            case'A':
                $kindValue = 'Аэропорт';
                break;
            case'M':
                $kindValue = 'Метеостанция';
                break;
            default:
                $kindValue = 'Неизвестный';
        }
        $name = $search->name;
        $id = $search->id;
        $kind = $kindValue;
        $country = ($search->countryName === null ? 'Неизвестно' : $search->countryName);
        $region = $search->districtName;
        $subRegion = $search->subDistrictName;

        return (
            "Название: $name \n" .
            "Идентификатор: $id \n" .
            "Тип географического объекта: $kind \n" .
            "Страна: $country \n" .
            "Регион: $region \n" .
            "Подрегион: $subRegion \n"

        );
    }

    /**
     * @throws GismeteoException
     */
    public function searchGeographicObjectByCoordinates(float $latitude, float $longitude, int $limit): array
    {
        $result = $this->gismeteo->searchGeographicObjectByCoordinates($latitude, $longitude, $limit);
        $searchStrings = [];
        foreach ($result as $resultItem) {
            $searchStrings[] = $this->returnPrettyPrintedGeographicObject($resultItem);
        }

        return $searchStrings;
    }

    /**
     * @throws GismeteoException
     */
    public function searchGeographicObjectByIP(string $ip): array
    {
        $result = $this->gismeteo->searchGeographicObjectByIP($ip);
        $searchStrings = [];
        foreach ($result as $resultItem) {
            $searchStrings[] = $this->returnPrettyPrintedGeographicObject($resultItem);
        }

        return $searchStrings;
    }

    /**
     * @throws GismeteoException
     */
    public function searchGeographicObjectByQuery(string $query): array
    {
        $result = $this->gismeteo->searchGeographicObjectByQuery($query);
        $searchStrings = [];
        foreach ($result as $resultItem) {
            $searchStrings[] = $this->returnPrettyPrintedGeographicObject($resultItem);
        }

        return $searchStrings;
    }
}
