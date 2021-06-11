<?php

include_once DIR_ACTION."/teacher/dateToCertainDateSchedule.php";


class DateToCertainDateScheduleOppositeAction extends DateToCertainDateScheduleAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->name){
            $this->bot->sendMessage($this->id, [
                'text' => "На ім'я якого викладача будемо шукати розклад?",
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
                $this->bot->sendMessage($this->id, [
                    'text' => "Так-так 🤖 \nЗараз тобі потрібно ввести першу та останню дату періоду ✏️"
                ]);
                $this->bot->sendMessage($this->id, [
                    'text' => "З якої дати починати? 🤓 \n\n<code>В форматі: {$this->date_now}</code>"
                ]);
    
                $form->init(['start_date', 'end_date']);

                $form->name = $name;
            }
                
        }
        else{
            $this->getTodayToCertainDateSchedule($form->name);
           
        } 
    }


    
}



    