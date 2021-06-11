<?php

include_once DIR_ACTION."/group/currentToCertainDateSchedule.php";


class CurrentToCertainDateScheduleOppositeAction extends CurrentToCertainDateScheduleAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->group){
            $this->bot->sendMessage($this->id, [
                'text' => "Для якої групи будемо шукати розклад? 🤖",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->group = true;
        }
        else if($form->group === true){
            $group = $this->getGroup();
            if($group){
                $form->group = $group;
                $this->bot->sendMessage($this->id, [
                    'text' => "До якої дати потрібний розклад?\n\n<code>Потрібно в форматі: {$this->date_now}</code>"
                ]);
                
                $form->initField('start_date');
                $form->initField('end_date');
            }
                

            
        }

        else{
        
            $this->getCurrentToCertainDateSchedule($form->group);
           
        } 
    }



    
}



    