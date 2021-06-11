<?php

class WeekScheduleMenuAction extends Action implements ActionInterface{
    public function handler(){
        $this->bot->sendAnswer($this->id, [
            'text' => "На тиждень",
            'disable_notification' => true,
            'reply_markup' => [
                'keyboard' =>[
                    [
                        'Поточний ⌛️',
                        'Від сьогодні'
                    ],
                    [
                        'Від певної дати'
                    ],
                    self::main_menu_button
                    
                ]
            ]
        ]);
    }
}