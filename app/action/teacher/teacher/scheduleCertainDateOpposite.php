<?php

include_once DIR_ACTION."/group/scheduleCertainDate.php";


class ScheduleCertainDateOppositeAction extends ScheduleCertainDateAction{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->group){
            $this->bot->sendMessage($this->id, [
                'text' => "Яка група вас цікавить? 🧑‍🎓",
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
                $this->bot->sendMessage($this->id,
                    [
                        "text" => "На яку дату вам потрібний розклад?\n\n<code>В форматі: {$this->date_now}</code>"
                    ]
                );
                $form->initField('date');
               

            }
        }
        else{
            $this->getScheduleCertainDate($form->group);
        } 
        
    }

}