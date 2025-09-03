<?php

namespace Modules\Service\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Service\Models\Favorite;
use Modules\Service\Models\Service;
use Modules\Service\Transformers\FavoriteResource;

class FavoriteController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $favorites = Favorite::with('service')->where('user_id', $user_id)->get();

        return $this->sendResponse(
            FavoriteResource::collection($favorites),
            "Favorites retrieved successfully"
        );
    }

    public function store($id)
    {
        $user_id = Auth::id();

        $service = Service::find($id);
        if (!$service) {
            return $this->sendError(
                "Service not found",
                ["id" => "No service exists with ID: $id"],
                404
            );
        }

        $existingFavorite = Favorite::where('user_id', $user_id)
            ->where('service_id', $id)
            ->first();

        if ($existingFavorite) {
            return $this->sendResponse(
                new FavoriteResource($existingFavorite),
                "Service is already in favorites"
            );
        }

        $favorite = Favorite::create([
            'user_id' => $user_id,
            'service_id' => $id,
        ]);

        return $this->sendResponse(
            new FavoriteResource($favorite),
            "Service added to favorites successfully"
        );
    }

    public function destroy($id)
    {
        $favorite = Favorite::find($id);

        if (!$favorite) {
            return $this->sendError(
                "Favorite item not found",
                ["id" => "No favorite item exists with ID: $id"],
                404
            );
        }

        if ($favorite->user_id !== Auth::id()) {
            return $this->sendError(
                "Unauthorized",
                ["auth" => "You don't have permission to delete this item"],
                403
            );
        }

        $favorite->delete();

        return $this->sendResponse(
            null,
            "Service removed from favorites successfully"
        );
    }
}