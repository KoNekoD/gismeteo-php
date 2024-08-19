<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherPrecipitation
{

    public ?int $type_ext;
    /** @var int $intensity
     *  Интенсивность осадков
     *  0 Нет осадков
     *  1 Небольшой дождь / снег
     *  2 Дождь / снег
     *  3 Сильный дождь / снег
     */
    public int $intensity;
    public ?string $correction;
    /** @var float|null $amount Количество осадков в мм. */
    public ?float $amount;
    public int $duration;
    /** @var int $type
     *  Тип осадков
     *  0 Нет осадков
     *  1 Дождь
     *  2 Снег
     *  3 Смешанные осадки
     */
    public int $type;

    public function __construct(
        ?int    $type_ext,
        int     $intensity,
        ?string $correction,
        ?float  $amount,
        int     $duration,
        int     $type
    )
    {
        $this->type_ext = $type_ext;
        $this->intensity = $intensity;
        $this->correction = $correction;
        $this->amount = $amount;
        $this->duration = $duration;
        $this->type = $type;
    }
}
