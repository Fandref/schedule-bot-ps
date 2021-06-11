<?php


class ScheduleTodayAction extends Action implements ActionInterface{
    public function handler(){
        $this->getScheduleToday($this->group);
    }

    protected function getScheduleToday(string $group_name){

        $couples = $this->schedule->groupDay($group_name, date('d.m.Y'));
    
        $this->printSchedule($couples);
    }

}