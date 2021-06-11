<?php

include_once DIR_ACTION."/group/currentWeekSchedule.php";


class CurrentWeekScheduleOppositeAction extends CurrentWeekScheduleAction{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
        $this->form = $form;
        if(!$form->group){
            $this->bot->sendMessage($this->id, [
                'text' => "Для якої групи будемо шукати розклад?",
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
     
                $this->getCurrentWeekSchedule($form->group);
                $form->deleteForm();
            }
                
        }
        
        
        
    }


}