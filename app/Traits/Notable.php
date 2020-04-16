<?php

namespace App\Traits;

use App\Note;

trait Notable
{
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable')->orderByDesc('id');
    }
}
