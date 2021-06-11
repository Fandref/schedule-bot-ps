<?php

include_once DIR_ACTION."/teacher/todayToCertainDateSchedule.php";


class TodayToCertainDateScheduleOppositeAction extends todayToCertainDateScheduleAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        
        if(!$form->name){
            $this->bot->sendMessage($this->id, [
                'text' => "Введи ім'я викладача 🧑‍🏫",
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
                    'text' => "Яка остання дата буде в періоді? 📆\n\n<code>Потрібно в форматі: {$this->date_now}</code>"
                ]);
                $form->initField('date');
            }
        } 
        else{
        
            $this->getTodayToCertainDateSchedule($form->name);

        } 
    }

}



    