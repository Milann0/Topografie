<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'user_id',
        'score',
        'total_questions',
        'game_type',
        'percentage'
    ];
    
    protected $casts = [
        'score' => 'integer',
        'total_questions' => 'integer',
        'percentage' => 'decimal:2'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}