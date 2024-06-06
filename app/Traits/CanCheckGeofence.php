<?php
namespace App\Traits;

trait CanCheckGeofence{



    function isWithinCircle($latitude1, $longitude1, $latitude2, $longitude2,$radius) {
        $earth_radius = 6371*1000;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d<$radius;
    }


}
