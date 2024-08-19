<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherResponse
{
    public WeatherPrecipitation $precipitation;
    public WeatherPressure $pressure;
    public WeatherHumidity $humidity;
    public int $geomagneticField;
    public WeatherWind $wind;
    public WeatherCloudiness $cloudiness;
    /** @var string $measurementDate
     * Local time
     * @example '2022-11-14 19:00:00'
     */
    public string $measurementDate;
    public string $kind;
    public bool $storm;
    public WeatherTemperature $temperature;
    public string $fullDescription;

    public static function fromResponse(array $response): self
    {
        $self = new self();

        $precipitation = $response['precipitation'];
        $self->precipitation = new WeatherPrecipitation(
            $precipitation['type_ext'],
            $precipitation['intensity'],
            $precipitation['correction'],
            $precipitation['amount'],
            $precipitation['duration'],
            $precipitation['type']
        );

        $pressure = $response['pressure'];
        $self->pressure = new WeatherPressure(
            $pressure['h_pa'],
            $pressure['mm_hg_atm'],
            $pressure['in_hg']
        );

        $humidity = $response['humidity'];
        $self->humidity = new WeatherHumidity($humidity['percent']);

        /**
         *  Геомагнитное поле
         *  1 Нет заметных возмущений
         *  2 Небольшие возмущения
         *  3 Слабая геомагнитная буря
         *  4 Малая геомагнитная буря
         *  5 Умеренная геомагнитная буря
         *  6 Сильная геомагнитная буря
         *  7 Жесткий геомагнитный шторм
         *  8 Экстремальный шторм
         */
        $self->geomagneticField = $response['gm'];

        $wind = $response['wind'];
        $self->wind = new WeatherWind(
            $wind['direction']['degree'],
            $wind['direction']['scale_8'],
            $wind['speed']['km_h'],
            $wind['speed']['m_s'],
            $wind['speed']['mi_h']
        );

        $cloudiness = $response['cloudiness'];
        $self->cloudiness = new WeatherCloudiness($cloudiness['type'], $cloudiness['percent']);

        $self->measurementDate = $response['date']['local'];

        /**
         *  Тип погодных данных
         *  Obs - Наблюдение
         *  Frc - Прогноз (представляется, если нет наблюдения)
         */
        $self->kind = $response['kind'];

        $self->storm = $response['storm'];

        $temperature = $response['temperature'];
        $self->temperature = new WeatherTemperature(
            $temperature['comfort']['C'],
            $temperature['comfort']['F'],
            $temperature['water']['C'],
            $temperature['water']['F'],
            $temperature['air']['C'],
            $temperature['air']['F']
        );

        $self->fullDescription = $response['description']['full'];

        return $self;
    }
}
