<?php


class ScheduleCertainDateAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id,
                [
                    "text" => "На яку дату потрібний розклад?\n\n<code>В форматі: {$this->date_now}</code>",
                    'reply_markup' => [
                        'keyboard' =>[
                            self::main_menu_button      
                        ],
                        'resize_keyboard' => true
                    ]
                ]
            );
            $form->init(['date']);
        } 
        else{
        
            $this->getScheduleCertainDate($this->group);
        } 
        
    }

    protected function getScheduleCertainDate(string $group_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;
            $couples = $this->schedule->groupDay($group_name, date('d.m.Y', strtotime($this->form->date)));
            $this->printSchedule($couples); 

            $this->form->deleteForm();
        }
    }

}