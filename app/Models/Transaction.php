<?php

namespace App\Models;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Filterable;

    protected $fillable = [
        "user_id",
        "description",
        "value",
        "due_date",
        "payment_date",
        "type",
        "type_payment",
    ];

    /**
     * Relations
     */

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
