<?php

namespace App\ModelFilters;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class TransactionFilter extends ModelFilter
{
    public $relations = [];

    protected $blacklist = [];

    public function id($search)
    {
        return $this->where(__FUNCTION__, $search);
    }

    public function description($search)
    {
        return $this->where(__FUNCTION__, 'like', "%$search%");
    }

    public function userId($search)
    {
        return $this->where("user_id", $search);
    }

    public function isValue($search)
    {
        return $this->where(__FUNCTION__, $search);
    }

    public function dueDate($search)
    {
        $date = Carbon::create($search)->format("Y-m-d");
        return $this->where("due_date", "like","%$date%");
    }

    public function paymentDate($search)
    {
        $date = Carbon::create($search)->format("Y-m-d");
        return $this->where("payment_date", "like","%$date%");
    }

    public function type($search)
    {
        return $this->where(__FUNCTION__, $search);
    }

    public function typePayment($search)
    {
        return $this->where("type_payment", $search);
    }
}
