<?php

namespace App\Services\Implementations;

use App\Exceptions\OrderDownException;
use App\Exceptions\OrderUpException;
use App\Helpers\Helper;
use App\Services\Interfaces\SortOrderServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SortOrderService implements SortOrderServiceInterface
{

    public function orderUp($id, Model $model): Model
    {
        DB::beginTransaction();

        try {
            $data = $model->findOrFail($id);

            $upper = $model->where('order', $data->order - 1)->first();

            if (!$upper) {
                throw new OrderUpException(class_basename($model));
            }

            $current_order = $data->order;

            $upper_order = $upper->order;

            $data->update(['order' => $upper_order]);

            $upper->update(['order' => $current_order]);

            DB::commit();

            return $data;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function orderDown($id, Model $model): Model
    {
        DB::beginTransaction();

        try {
            $data = $model->findOrFail($id);
            $lower = $model->where('order', $data->order + 1)->first();

            if (!$lower) {
                throw new OrderDownException(class_basename($model));
            }

            $current_order = $data->order;
            $lower_order = $lower->order;

            $data->update(['order' => $lower_order]);
            $lower->update(['order' => $current_order]);

            DB::commit();

            return $data;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }
}
