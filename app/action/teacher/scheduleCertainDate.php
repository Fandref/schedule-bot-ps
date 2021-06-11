<?php


class ScheduleCertainDateAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;

        if(!$form->isInit()){
            $this->bot->sendMessage($this->id,
                [
                    "text" => "На яку дату вам потрібний розклад?\n\n<code>В форматі: {$this->date_now}</code>",
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
        
            $this->getScheduleCertainDate($this->name);
        } 
        
    }

    protected function getScheduleCertainDate(string $teacher_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;
            var_dump($this->form->date);
            $couples = $this->schedule->teacherDay($teacher_name, date('d.m.Y', strtotime($this->form->date)));
            $this->printSchedule($couples); 

            $this->form->deleteForm();
        }
    }

}