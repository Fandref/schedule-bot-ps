<?php

include_once DIR_ACTION."/group/dateToCertainDateSchedule.php";


class DateToCertainDateScheduleOppositeAction extends DateToCertainDateScheduleAction{
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
                $this->bot->sendMessage($this->id, [
                    'text' => "Так-так 🤖 \nЗараз потрібно ввести першу та останню дату періоду ✏️"
                ]);
                $this->bot->sendMessage($this->id, [
                    'text' => "З якої дати починати? 🤓 \n\n<code>В форматі: {$this->date_now}</code>"
                ]);
    
                $form->init(['start_date', 'end_date']);

                $form->group = $group;
            }
                
        }
        else{
            $this->getTodayToCertainDateSchedule($form->group);
           
        } 
    }


    
}



    