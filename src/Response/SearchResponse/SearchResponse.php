<?php

declare(strict_types=1);

namespace SakuraSamurai\Gismeteo\Response\SearchResponse;

class SearchResponse
{
    public int $id;
    public ?string $districtName;
    public ?string $subDistrictName;
    public string $name;
    public string $kind;
    public ?string $countryName;

    public static function fromResponse(array $response): self
    {
        $self = new self();

        /**
         *  Тип географического объекта:
         *  T - Город
         *  C - Мегаполис
         *  A - Аэропорт
         *  M - Метеостанция
         */
        $self->kind = $response['kind'];

        $self->countryName = $response['country']['name'] ?? null;

        $self->districtName = $response['district']['name'] ?? null;

        $self->subDistrictName = $response['sub_district']['name'] ?? null;

        $self->id = $response['id'];

        $self->name = $response['name'];


        return $self;
    }
}
