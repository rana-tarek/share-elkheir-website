<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'type_of_contribution';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image', 'color'];

    public function contributions()
    {
        return $this->hasMany('App\TypeOfContribution');
    }

}
