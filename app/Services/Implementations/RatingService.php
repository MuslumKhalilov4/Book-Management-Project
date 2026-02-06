<?php

namespace App\Services\Implementations;

use App\Exceptions\AlreadyRatedException;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Models\Rating;
use App\Services\Interfaces\RatingServiceInterface;


class RatingService implements RatingServiceInterface
{
    public function rate(array $request): Rating
    {
        $user_id = auth()->user()->id;

        if (Rating::where('user_id', $user_id)->where('book_id', $request['book_id'])->exists()) {
            throw new AlreadyRatedException();
        }

        $rating = Rating::create([
            'rating' => $request['rating'],
            'comment' => $request['comment'],
            'book_id' => $request['book_id'],
            'user_id' => $request['user_id']
        ]);

        return $rating;
    }

    public function updateRating(array $request, int $id): Rating
    {
        $user_id = auth()->user()->id;
        $old_rating = Rating::findOrFail($id);

        if ($old_rating->rating == $request['rating'])
        $updated = $old_rating->update([
            'rating' => $request['rating'],
            'comment' => $request['comment']
        ]);

        return $updated;
    }
}
