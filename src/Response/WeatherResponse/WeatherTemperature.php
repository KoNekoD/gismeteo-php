<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherTemperature
{
    /** @var float $comfortC "по ощущению" */
    public float $comfortC;
    /** @var float $comfortF "по ощущению" */
    public float $comfortF;
    public float $waterC;
    public float $waterF;
    public float $airC;
    public float $airF;

    public function __construct(float $comfortC, float $comfortF, float $waterC, float $waterF, float $airC, float $airF)
    {
        $this->comfortC = $comfortC;
        $this->comfortF = $comfortF;
        $this->waterC = $waterC;
        $this->waterF = $waterF;
        $this->airC = $airC;
        $this->airF = $airF;
    }
}
