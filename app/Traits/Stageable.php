<?php

namespace App\Traits;

use App\Stage;

trait Stageable
{
    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id')->orderBy('id', 'asc');
    }
}
