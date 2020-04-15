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

    public static function getProgressColor($percentage)
    {
        $label='';
        if($percentage<=15){
            $label = 'bg-danger';
        }else if ($percentage > 15 && $percentage <= 33) {
            $label = 'bg-warning';
        } else if ($percentage > 33 && $percentage <= 70) {
            $label = 'bg-primary';
        } else {
            $label = 'bg-success';
        }

        return $label;
    }
}
