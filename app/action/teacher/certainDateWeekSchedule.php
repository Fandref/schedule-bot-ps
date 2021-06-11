<?php


class CertainDateWeekScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id, [
                'text' => "Зрозуміло 😌 Від якої дати вам відрахувати тиждень?\n\n<code>Потрібно в форматі: {$this->date_now}</code>",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->init(['date']);
            $this->form = $form;
        } 
        else{
        
            $this->getCertainDateWeekSchedule($this->name);
        } 
        
    }

    protected function getCertainDateWeekSchedule(string $teacher_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;
            $couples = $this->schedule->teacherWeek($teacher_name, date('d.m.Y', strtotime($message)));
            $this->printSchedule($couples); 
            var_dump("ф: ", $this);
            $this->form->deleteForm();
        }
    }

}
