<?php


class CurrentWeekScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->getCurrentWeekSchedule($this->group);
        
        
    }

    protected function getCurrentWeekSchedule(string $group_name){
        $couples = $this->schedule->groupWeek($group_name);

        $this->printSchedule($couples); 
    }

}