<?php

class MenuPeriodScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $this->bot->sendAnswer($this->id, [
            'text' => "За певний період",
            'disable_notification' => true,
            'reply_markup' => [
                'keyboard' =>[
                    [
                        'Поточний до дати ⌛️'
                    ],
                    [
                        'Від сьогодні до дати'
                    ],
                    [
                        'Від дати до дати'
                    ],
                    self::main_menu_button
                    
                ]
            ]
        ]);
    }

    
}