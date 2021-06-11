<?php

include_once DIR_ACTION."/group/certainDateWeekSchedule.php";

class CertainDateWeekScheduleOppositeAction extends CertainDateWeekScheduleAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->group){
            $this->bot->sendMessage($this->id, [
                'text' => "Яку групу оберете?",
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
                    'text' => "Зрозуміло 😌 Від якої дати вам відрахувати тиждень?\n\n<code>Потрібно в форматі: {$this->date_now}</code>"
                ]);
    
                $form->initField('date');
                
            }
                

            
        }

        else{
        
            $this->getCertainDateWeekSchedule($form->group);
           
        } 
        
    }

}
