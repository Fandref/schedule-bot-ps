<?php


class CurrentWeekScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->getCurrentWeekSchedule($this->name);
        
        
    }

    protected function getCurrentWeekSchedule(string $teacher_name){
        $couples = $this->schedule->teacherWeek($teacher_name);

        $this->printSchedule($couples); 
    }

}