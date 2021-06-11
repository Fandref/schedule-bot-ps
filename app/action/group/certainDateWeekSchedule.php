<?php


class CertainDateWeekScheduleAction extends Action{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id, [
                'text' => "Доообре 😌 Від якої дати відрахувати тиждень?\n\n<code>Потрібно в форматі: {$this->date_now}</code>",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $form->init(['date']);
        } 
        else{
        
            $this->getCertainDateWeekSchedule($this->group);
        } 
        
    }

    protected function getCertainDateWeekSchedule(string $group_name){
        $message = $this->bot->getMessage()['text'];
        if($this->isDate($message)){
            $this->form->date = $message;
            $couples = $this->schedule->groupWeek($group_name, date('d.m.Y', strtotime($message)));
            $this->printSchedule($couples); 

            $this->form->deleteForm();
        }
    }

}
