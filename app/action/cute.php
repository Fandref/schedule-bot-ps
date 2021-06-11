<?php 


class CuteAction extends Action{
    public function handler(){
        $answers = [
            "Ой, як мило ☺️",
            "Дякую, я збентежений 🥰",
            "Дякусі-пірагусі 💕",
            "Ой, а хто це в нас тут такий миленький? ☺️💕\nТоочно, це ж ти)",
            "Обійням 🤗"
        ];
      
        $index_answer = rand(0, count($answers)-1);
        $current_answer = $answers[$index_answer];

        $this->bot->sendAnswer($this->id, [
            'text' => $current_answer
        ]);
    }
}