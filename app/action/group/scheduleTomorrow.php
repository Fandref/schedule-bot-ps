<?php


class ScheduleTomorrowAction extends Action implements ActionInterface{
    public function handler(){
        $this->getScheduleTomorrow($this->group);
    }

    protected function getScheduleTomorrow(string $group_name){

        $couples = $this->schedule->groupDay($group_name, date('d.m.Y', strtotime("+1 day")));
    
        $this->printSchedule($couples);
        
    }

}