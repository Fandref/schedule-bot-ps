<?php


class MainMenuAction extends Action implements ActionInterface{
    public function handler(){
        $this->bot->sendAnswer($this->id, [
            'text' => "Головне меню 📚",
            'disable_notification' => true,
            'reply_markup' => self::main_menu
        ]);
        

        $form = $this->form ?? $this->load->component('form/form', [$this->id]);

        if($form->isInit()){
            $form->deleteForm();
        }
    
            
            
    }
}