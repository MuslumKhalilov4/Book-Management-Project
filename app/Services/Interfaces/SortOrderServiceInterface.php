<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface SortOrderServiceInterface{
    public function orderUp($id, Model $model): Model;

    public function orderDown($id, Model $model): Model;
}