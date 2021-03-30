<?php

namespace FireKeeper\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reminders';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    protected $dateFormat = 'U';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'remind_date' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    // protected $attributes = [
    //     'alias' => 'unkindled',
    // ];
}
