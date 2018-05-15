<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model {
    
    protected $fillable = [
        'body', 'user_id', 'priority', 'complete'
    ];
    
    public function user() 
    {
        return $this->belongsTo('App\User');
    }
}