<?php


class QuickScheduleAction extends Action implements ActionInterface{
    public function handler(){
    
        $couples = $this->schedule->teacherDay($this->name);
        $this->printSchedule($couples);
    }

}