<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherWind
{
    public ?int $directionDegree;
    /** @var int $directionScale_8
     *  По шкале от 1 до 8
     *  0 Штиль
     *  1 Северный
     *  2 Северо-восточный
     *  3 Восточный
     *  4 Юго-восточный
     *  5 Южный
     *  6 Юго-западный
     *  7 Западный
     *  8 Северо-западный
     */
    public int $directionScale_8;
    public int $speedKm_h;
    public float $speedM_s;
    public int $speedMi_h;

    public function __construct(?int $degree, int $scale_8, int $km_h, float $m_s, int $mi_h)
    {
        $this->directionDegree = $degree;
        $this->directionScale_8 = $scale_8;
        $this->speedKm_h = $km_h;
        $this->speedM_s = $m_s;
        $this->speedMi_h = $mi_h;
    }
}
