<?php


class ScheduleTomorrowAction extends Action implements ActionInterface{
    public function handler(){
        $this->getScheduleTomorrow($this->name);
    }

    protected function getScheduleTomorrow(string $teacher_name){

        $couples = $this->schedule->teacherDay($teacher_name, date('d.m.Y', strtotime("+1 day")));
    
        $this->printSchedule($couples);
        
    }

}