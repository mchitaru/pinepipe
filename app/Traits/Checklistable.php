<?php

namespace App\Traits;

use App\Checklist;

trait Checklistable
{
    public function checklist()
    {
        return $this->morphMany(Checklist::class, 'checklistable')->orderBy('order');
    }

    public function getCompleteChecklistCount()
    {
        $count = 0;
        foreach($this->checklist as $check) {
            if($check->status) $count++;
        }

        return $count;    
    }

    public function getTotalChecklistCount()
    {
        return $this->checklist->count();
    }
}
