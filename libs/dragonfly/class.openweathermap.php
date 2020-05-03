<?php

class OpenWeatherMap
{
    const HTTPS_URL = 'https://api.openweathermap.org/data/2.5/weather';

    /**
     * Retorna areglo con las caracteristicas del clima
     * @param string $city
     * @param string $api
     * @return array Retorna areglo con las caracteristicas del clima
     */
    public function openWeatherMap($city, $api)
    {
        return json_decode(file_get_contents(self::HTTPS_URL . '?q=' . $city . '&appid=' . $api . '&units=metric'));
    }
}
