<?php

namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

class UserAffiliate extends Model
{
    protected $connection = 'mysql_affiliate';
    protected $table = 'users';
}
