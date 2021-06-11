<?php


class TodayWeekScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->getTodayWeekScheduleAction($this->group);
        
        
    }

    protected function getTodayWeekScheduleAction(string $group_name){
        $couples = $this->schedule->groupWeek($group_name, date('d.m.Y'));

        $this->printSchedule($couples); 
    }

}