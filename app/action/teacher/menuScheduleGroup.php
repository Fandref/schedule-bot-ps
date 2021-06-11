<?php

class MenuScheduleGroupAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);

        $form->init(['group']);
        
        $this->bot->sendAnswer($this->id, [
            'text' => "Студенський 👨‍🎓",
            'disable_notification' => true,
            'reply_markup' => $this->renderScheduleMenu(true)
        ]);
    }

}