<?php

namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasConfiguration;
use Illuminate\Database\Eloquent\Model;

abstract class SiteComponent extends Model
{
    use HasConfiguration;
}
