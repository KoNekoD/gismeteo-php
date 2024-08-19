<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo;

interface GismeteoInterface
{
    public function getCurrentWeatherByCoordinates(float $latitude, float $longitude);

    public function getCurrentWeatherByGeographicObject(int $id);

    public function getForecastDailyStepByCoordinates(float $latitude, float $longitude, int $days, int $step_count);

    public function getForecastDailyStepByGeographicObject(int $id, int $days, int $step_count);

    public function searchGeographicObjectByCoordinates(float $latitude, float $longitude, int $limit);

    public function searchGeographicObjectByIP(string $ip);

    public function searchGeographicObjectByQuery(string $query);
}
