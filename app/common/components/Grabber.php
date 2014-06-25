<?php
/**
 * Created by PhpStorm.
 * User: komrakov
 * Date: 6/25/14
 * Time: 8:21 PM
 */

class Grabber
{

    public static function grab($link)
    {
        $cookie = 'sessionid=OTE1NDMyNTU%3D; Steam_Language=russian; recentlyVisitedAppHubs=221410; steamLogin=76561198032255024%7C%7C206D72EE094F069EDC4213C1C73B9D97FA411D79; webTradeEligibility=%7B%22allowed%22%3A0%2C%22reason%22%3A2048%2C%22allowed_at_time%22%3A1404143342%2C%22steamguard_required_days%22%3A15%2C%22sales_this_year%22%3A452%2C%22max_sales_per_year%22%3A-1%2C%22forms_requested%22%3A0%2C%22new_device_cooldown_days%22%3A7%7D; steamCC_95_79_105_224=RU; timezoneOffset=14400,0; __utma=268881843.2095460869.1403287496.1403538691.1403542708.4; __utmb=268881843.0.10.1403542708; __utmc=268881843; __utmz=268881843.1403538691.3.3.utmcsr=yandex|utmccn=(organic)|utmcmd=organic';
        $curlInstance = curl_init();
        curl_setopt($curlInstance, CURLOPT_URL, $link);
        curl_setopt($curlInstance, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInstance, CURLOPT_COOKIE, $cookie);
        $result = curl_exec($curlInstance);
        curl_close($curlInstance);
        return $result;
    }

}