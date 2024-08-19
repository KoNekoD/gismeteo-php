<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherHumidity
{
    public int $percent;

    public function __construct(int $percent)
    {
        $this->percent = $percent;
    }
}
