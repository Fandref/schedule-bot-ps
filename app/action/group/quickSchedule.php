<?php


class QuickScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $couples = $this->schedule->groupDay($this->group);
        $this->printSchedule($couples);
    }

}