<?php

namespace m4aax16\kinguin;

class Region
{   

    const REGIONS = array("1" => "Europe",
        "2"	 => "United States",
        "3"	 => "Region free",
        "4"	 => "Other",
        "5"	 => "Outside Europe",
        "6"	 => "RU VPN",
        "7"	 => "Russia",
        "8"	 => "United Kingdom",
        "9"	 => "China",
        "10" => "RoW (Rest of World)",
        "11" => "Latin America",
        "12" => "Asia",
        "13" => "Germany",
        "14" => "Australia",
        "15" => "Brazil",
        "16" => "India",
        "17" => "Japan",
        "18" => "North America");
    
    public static function getRegions()
    {
        return $regions = self::REGIONS;
    }

    public static function getRegionById($regionId)
    {
        $regions = self::REGIONS;

        return $regions[$regionId];
    }

}