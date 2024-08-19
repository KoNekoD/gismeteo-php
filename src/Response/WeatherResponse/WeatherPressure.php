<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\WeatherResponse;

class WeatherPressure
{
    public float $h_pa;
    public int $mm_hg_atm;
    public float $in_hg;

    public function __construct(
        float $h_pa,
        int   $mm_hg_atm,
        float $in_hg
    )
    {
        $this->h_pa = $h_pa;
        $this->mm_hg_atm = $mm_hg_atm;
        $this->in_hg = $in_hg;
    }
}
