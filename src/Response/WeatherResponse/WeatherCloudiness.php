<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherCloudiness
{

    /** @var int $type
     *  По шкале от 0 до 3
     *  0 Ясно
     *  1 Малооблачно
     *  2 Облачно
     *  3 Пасмурно
     *  101 Переменная облачность
     */
    public int $type;
    public int $percent;

    public function __construct(int $type, int $percent)
    {
        $this->type = $type;
        $this->percent = $percent;
    }
}
