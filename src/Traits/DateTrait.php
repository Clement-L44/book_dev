<?php

namespace App\Traits;

use Symfony\Component\Clock\DatePoint;

/**
 * Use DatePoint - https://symfony.com/blog/new-in-symfony-6-4-datepoint
 */
trait DateTrait {

    public function now()
    {
        return new DatePoint(datetime: 'now', timezone: new \DateTimeZone('Europe/Paris'));
    }

}