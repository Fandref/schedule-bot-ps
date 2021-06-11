<?php

include_once DIR_ACTION."/teacher/scheduleTomorrow.php";


class ScheduleTomorrowOppositeAction extends ScheduleTomorrowAction{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
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
        else{  
            $name = $this->getTeacherName();
            if($name){
                $form->name = $name;
                $this->getScheduleTomorrow($form->name);
                $form->deleteForm();
            }
        }
            
    }


}