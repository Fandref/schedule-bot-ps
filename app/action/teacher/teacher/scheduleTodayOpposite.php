<?php

include_once DIR_ACTION."/group/scheduleToday.php";


class ScheduleTodayOppositeAction extends scheduleTodayAction{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
        $this->form = $form;
        if(!$form->group){
            $this->bot->sendMessage($this->id, [
                'text' => "Введіть назву групи 🧑‍🎓",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->group = true;
        }
        else{  
            $group = $this->getGroup();
            if($group){
                $form->group = $group;
                $this->getScheduleToday($form->group);
                $form->deleteForm();
            }
        }
        
    }

}