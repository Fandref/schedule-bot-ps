<?php


class TodayWeekScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->getTodayWeekScheduleAction($this->name);
        
        
    }

    protected function getTodayWeekScheduleAction(string $teacher_name){
        $couples = $this->schedule->teacherWeek($teacher_name, date('d.m.Y'));

        $this->printSchedule($couples); 
    }

}