<?php

class CurrentToCertainDateScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id, [
                'text' => "До якої дати потрібний розклад?🧐\n\n<code>Потрібно в форматі: {$this->date_now}</code>",
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
        
            $this->getCurrentToCertainDateSchedule($this->group);
        } 
    }

    public function getCurrentToCertainDateSchedule(string $group_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;

            $entered_date = new DateTime($message);
            $current_date = new DateTime('now');
            if($entered_date > $current_date){
                $couples = $this->schedule->groupPeriod($group_name, date('d.m.Y', strtotime($message)));
                $this->printSchedule($couples); 
                $this->form->deleteForm();
            }
            else{
                $this->bot->sendMessage($this->id, [
                    'text' => 'Стоп, кінечна дата не може бути менша за поточну 😟'
                ]);
            }

            
        } 
    }

    
}



    