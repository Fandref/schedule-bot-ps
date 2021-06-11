<?php

include_once DIR_ACTION."/teacher/scheduleCertainDate.php";


class ScheduleCertainDateOppositeAction extends ScheduleCertainDateAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->name){
            $this->bot->sendMessage($this->id, [
                'text' => "Як звати викладача? 🧑‍🏫",
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
                $this->bot->sendMessage($this->id,
                    [
                        "text" => "На яку дату вам потрібний розклад?\n\n<code>В форматі: {$this->date_now}</code>"
                    ]
                );
                $form->initField('date');
               

            }
        }
        else{
            $this->getScheduleCertainDate($form->name);
        } 
        
    }

}