<?php


class DonateAction extends Action implements ActionInterface{
    public function handler(){
        
        $message  = "Оу.. Як неочікувано, що я настільки корисний 🥰\n";
        $message .= "Моєму творцеві дуже приємно, що ти хочеш йому віддячити.\n";
        $message .= "Він дуже старався, щоб створити мене.\n\n";
        $message .= "Думаю він зрадіє, якщо йому хтось підкине грошенят 🤑 тому.. залишу це тут:\n\n";
        $message .= "<b><code>5168 7520 1128 2228</code></b>\n\n\n";
        $message .= "Звісно я не змушую 😊 ";
        $message .= "Але за що тоді мене будуть утримувати? 🥺";

        $this->bot->sendAnswer($this->id, [
            'text' => $message
        ]);
    }
}