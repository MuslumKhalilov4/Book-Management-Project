<?php

namespace App\Services\Interfaces;

use App\Models\Rating;

interface RatingServiceInterface
{
    public function rate(array $request): Rating;   

    public function updateRating(array $request, int $id): Rating;
}