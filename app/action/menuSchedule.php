<?php

class MenuScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->bot->sendAnswer($this->id, [
            'text' => "Розклад 🗒",
            'disable_notification' => true,
            'reply_markup' => $this->renderScheduleMenu()
        ]);
        if($this->expansion){
            $form = $this->load->component('form/form');
            $form->deleteForm();
        }
    }

}