

<?php

class TodayToCertainDateScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id, [
                'text' => "До якої дати брати розклад?📆\n\n<code>Потрібно в форматі: {$this->date_now}</code>",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->init(['date']);
        } 
        else{
        
            $this->getTodayToCertainDateSchedule($this->group);
        } 
    }

    public function getTodayToCertainDateSchedule(string $group_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;
            $couples = $this->schedule->groupPeriod($group_name, date('d.m.Y', strtotime($message)), date('d.m.Y'));
            $this->printSchedule($couples); 

            $this->form->deleteForm();
        } 
    }

    
}



    