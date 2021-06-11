<?php

class DayScheduleMenuAction extends Action implements ActionInterface{
    public function handler(){
        $this->bot->sendAnswer($this->id, [
            'text' => "На день",
            'disable_notification' => true,
            'reply_markup' => [
                'keyboard' =>[
                    [
                        'Сьогодні'
                    ],
                    [
                        'Завтра', 'Певна дата'
                    ],
                    self::main_menu_button
                    
                ]
            ]
        ]);
    }
}