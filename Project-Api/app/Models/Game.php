<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'dice1',
        'dice2',
        'total',
        'result',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
        'id',
    ];

   public function UserGame()
   {
    return $this->belongsTo(Game::class);
   }
}
