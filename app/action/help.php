<?php


class HelpAction extends Action{
    public function handler(){
        $help  =  "<b>Допомога ℹ️</b>\n\n\n";
        $help .=  "<b>Швидкий розклад 🚀</b> — виводить найближчий день з розкладом в межах п'яти днів\n\n";

        $help .=  "<b>Поточний ⌛️</b>\n";
        $help .=  "Виводить потрібний розклад, виключаючи сьогоднішній день, якщо вже пари закінчились\n\n";
        $role_name;
        $role_name_v;
        $example;
        if($this->role == 1){
            $role_name = "групи";
            $role_name_v = "груп";
            $example = "Тобто, при введенні <code>ІПЗ</code> буде виведено всі групи спеціальності ІПЗ";
        }
        else if($this->role == 2){
            $role_name = "імені викладача";
            $role_name_v = "викладачів";
            $example = "Тобто, при введенні прізвища <code>Андрійчук</code> буде виведено всіх викладачів з прізвищем Андрійчук";
        }
        $help .= "<b>Введення {$role_name}</b>\n";
        $help .= "При введені {$role_name} можна не турбуватись, що дані введені не точно. Якщо буде декілька {$role_name_v} - я їх запропоную.\n";

        $help .= $example;

        $this->bot->sendAnswer($this->id, [
            'text' => $help,
            'disable_notification' => true
        ]);
    
            
            
    }
}