<?php


class WellcomeAction extends Action implements ActionInterface{
    public function handler(){
        if(!$this->storage->existUser($this->id)){

            $this->bot->sendMessage($this->id, [
                'text' => "Привіт! Я <b>NPUD Rozklad Bot</b>, чи можна просто - Альберт 🤖. Якщо потрібний розклад пар, я обов'язково з цим допоможу. Все що протрібно - дати відповідь на декілька моїх запитань 📝",
                'reply_markup' => [
                    'remove_keyboard' => true
                ]
            ]);
        }
        else{
        
            if($this->role == 2){
                $group = $this->storage->getGroup($this->id);
                $this->bot->sendAnswer($this->id, [
                    'text' => "ОО 🤗 Привіт, я тебе знаю. Ти із групи {$group}",
                    'reply_markup' => self::main_menu
                ]);
            }
            else if($this->role == 1){
                $teacher_name = $this->storage->getNameTeacher($this->id);
                $this->bot->sendAnswer($this->id, [
                    'text' => "Мої вітання, {$teacher_name}! 👋",
                    'reply_markup' => self::main_menu
                ]);
            }
            exit;
                

        }
    }
}