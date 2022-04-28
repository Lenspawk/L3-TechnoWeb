<?php

namespace App\Service;

class MyService
{
    public function sommeTab(array $tab) : int
    {
        return array_sum($tab);
    }
}