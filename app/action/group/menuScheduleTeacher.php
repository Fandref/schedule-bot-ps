<?php

class MenuScheduleTeacherAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);

        $form->init(['name']);
        $this->bot->sendAnswer($this->id, [
            'text' => 'Викладацький 🧑‍🏫',
            'disable_notification' => true,
            'reply_markup' => $this->renderScheduleMenu(true)
        ]);
    }

}