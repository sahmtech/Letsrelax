<?php

namespace Modules\Service\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Database\factories\FavoriteFactory;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
        protected $fillable = ['service_id', 'user_id'];

    protected $casts = [

        'service_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');

    }

}