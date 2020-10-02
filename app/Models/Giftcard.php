<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giftcard extends Model
{
    protected $table        = 'giftcards';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_name', 'email','message','company_logo','details_json'
    ];

    public function getDetailsJsonAttribute($value)
    {
        return !empty($value) ? json_decode($value, true) : [];
    }
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
        } else {
            return null;
        }
    }
}
