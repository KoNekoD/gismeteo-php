<?php

use SakuraSamurai\Gismeteo\Exception\GismeteoException;
use SakuraSamurai\Gismeteo\Exception\InvalidArgumentException;
use SakuraSamurai\Gismeteo\Gismeteo;
use PHPUnit\Framework\TestCase;

class GismeteoTest extends TestCase
{
    private Gismeteo $gismeteo;

    protected function setUp(): void
    {
        $_SERVER['GISMETEO_TOKEN'] = 'TOKEN';
        $this->gismeteo = new Gismeteo();

        parent::setUp();
    }

    /**
     * @throws InvalidArgumentException
     * @throws GismeteoException
     */
    public function test_search_go_and_get_weather(): void
    {
        $searches = $this->gismeteo->searchGeographicObjectByQuery('москва');

        $weather = $this->gismeteo->getCurrentWeatherByGeographicObject($searches[0]->id);
        $forecast = $this->gismeteo->getForecastDailyStepByGeographicObject($searches[0]->id, 10, 8);

        $this->assertTrue(true);
    }
}
