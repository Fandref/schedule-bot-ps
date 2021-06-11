<?php

include_once DIR_ACTION."/teacher/currentToCertainDateSchedule.php";


class CurrentToCertainDateScheduleOppositeAction extends CurrentToCertainDateScheduleAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->name){
            $this->bot->sendMessage($this->id, [
                'text' => "На ім'я якого викладача будемо шукати розклад? 🤖",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->name = true;
        }
        else if($form->name === true){
            $name = $this->getTeacherName();
            if($name){
                $form->name = $name;
                $this->bot->sendMessage($this->id, [
                    'text' => "До якої дати потрібний розклад?\n\n<code>Потрібно в форматі: {$this->date_now}</code>"
                ]);
                
                $form->initField('start_date');
                $form->initField('end_date');
            }
                

            
        }

        else{
        
            $this->getCurrentToCertainDateSchedule($form->name);
           
        } 
    }



    
}



    