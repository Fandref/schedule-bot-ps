<?php

include_once DIR_ACTION."/group/scheduleTomorrow.php";


class ScheduleTomorrowOppositeAction extends ScheduleTomorrowAction{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
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
        else{  
            $group = $this->getGroup();
            if($group){
                $form->group = $group;
                $this->getScheduleTomorrow($form->group);
                $form->deleteForm();
            }
        }
            
    }

}