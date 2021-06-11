<?php

include_once DIR_ACTION."/teacher/currentWeekSchedule.php";


class CurrentWeekScheduleOppositeAction extends CurrentWeekScheduleAction{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
        $this->form = $form;
        if(!$form->name){
            $this->bot->sendMessage($this->id, [
                'text' => "Для якого викладача будемо шукати розклад?",
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
     
                $this->getCurrentWeekSchedule($form->name);
                $form->deleteForm();
            }
                
        }
        
        
        
    }


}