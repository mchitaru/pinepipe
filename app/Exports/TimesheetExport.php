<?php

namespace App\Exports;

use App\Timesheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TimesheetExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $project_id;
    protected $user_id;
    protected $filter;
    protected $from;
    protected $until;

    public function __construct($project_id, $user_id, $from, $until, $filter)
    {
        $this->project_id = $project_id;
        $this->user_id = $user_id;
        $this->filter = $filter;
        $this->from = $from;
        $this->until = $until;
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('User'),
            __('Project'),
            __('Task'),
            __('Hours'),
        ];
    }    

    public function query()
    {
        $project_id = $this->project_id;
        $user_id = $this->user_id;
        $filter = $this->filter;

        return Timesheet::with(['project', 'task', 'user'])
                            ->where(function ($query) use ($filter) {
                                $query->whereHas('user', function ($query) use($filter) {

                                    $query->where('name','like','%'.$filter.'%');
                                })
                                ->orWhereHas('project', function ($query) use($filter) {

                                    $query->where('name','like','%'.$filter.'%');
                                })
                                ->orWhereHas('task', function ($query) use($filter) {

                                    $query->where('title','like','%'.$filter.'%');
                                });
                            })        
                            ->whereBetween('date', [$this->from, $this->until])
                            ->where(function($query) use($project_id) {
                              if(!empty($project_id)){
                                  
                                    $query->where('project_id', $project_id);

                              }
                            })
                            ->where(function($query) use($user_id) {
                                if(!empty($user_id)){
                                    
                                    $query->where('user_id', $user_id);

                                }
                            })
                            ->orderBy('date', 'asc')
                            ->orderBy('user_id', 'asc')
                            ->orderBy('project_id', 'asc');
  }

    public function map($timesheet): array
    {
        return [
            $timesheet->date,
            $timesheet->user->name,
            $timesheet->project?$timesheet->project->name:null,
            $timesheet->task?$timesheet->task->title:null,
            \Helpers::ceil($timesheet->computeTime()/3600.0),
        ];
    }
}
