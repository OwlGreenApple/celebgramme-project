<?php

namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAffiliate extends Model
{
    protected $connection = 'mysql_affiliate';
    protected $table = 'orders';
}
