<?php


class ScheduleTodayAction extends Action implements ActionInterface{
    public function handler(){
        $this->getScheduleToday($this->name);
    }

    protected function getScheduleToday(string $teacher_name){

        $couples = $this->schedule->teacherDay($teacher_name, date('d.m.Y'));
    
        $this->printSchedule($couples);
    }

}