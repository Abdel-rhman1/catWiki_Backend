<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularSearchedBeerds extends Model
{
    use HasFactory;
    protected $table = 'popular_searched_beerds';
    protected $fillable = [
        'id' , 'beerd_id' , 'search_count'
    ];
}
