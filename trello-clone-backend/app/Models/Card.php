<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['board_list_id', 'title', 'description', 'position', 'due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function list()
    {
        return $this->belongsTo(BoardList::class, 'board_list_id');
    }
}
