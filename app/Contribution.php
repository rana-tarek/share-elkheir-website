<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateDiff;
use Carbon\Carbon;

class Contribution extends Model
{
    
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contributions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type_of_contribution', 'longitude', 'latitude', 'area', 'quantity', 'access', 'covered'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
