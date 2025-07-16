<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Favourite;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FavouriteRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FavouriteController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function store(FavouriteRequest $request)
    {
        $this->authorize('create', Favourite::class);

        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        $exists = Favourite::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            return self::error('Already favorited.', 409);
        }

        $favourite = Favourite::create($validated);

        return self::created($favourite, 'Favourite added successfully.');
    }

    public function destroy(FavouriteRequest $request)
    {
        
        $favourite = Favourite::where('user_id', Auth::id())
        ->where('product_id', $request->product_id)
        ->delete();

        return self::deleted('Favourite removed successfully.');

    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin) {
            $data = User::with('favouriteProducts')->get();
            return self::success(['users' => $data]);
        } else {
            $favourites = $user->favouriteProducts()->with('category')->get();
            return self::success(['favourites' => $favourites]);
        }
    }
}
