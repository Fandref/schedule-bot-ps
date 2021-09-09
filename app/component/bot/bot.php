<?php



class Bot{

    private $url;
    private $load;
    public $history_command;
    public $registry;
    public function __construct(){
        $this->url = "https://api.telegram.org/bot".API_TOKEN."/";
        $this->load = new Loader();
        $this->history_command = $this->load->component(
            'bot/command_history/command_history',
            [$this->getUserId()]
        );
        $this->registry = new Registry();
       
            
    }
    

    public function getUpdate(){

        $update = $this->getRequest() ?? $this->getUpdates();
        return $update;

    }
    public function setWebhook(string $url){
        $result = $this->sendRequest("setWebhook", [
            'url' => $url
        ]);
        return $result;

    }
    private function getRequest(){
        return json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY) ?? NULL;
    }
 
    public function getUpdates(int $offset = 0){
        $data = $this->sendRequest("getUpdates", [
            'offset' => $offset
        ]);
   
        return array_pop($data['result']) ?? null;
    }   
    public function onCommand(string $command, $callback){

        $message = $this->getUpdate()['message'];
        $last_command = $this->history_command->get();

        $type = $message['entities'][0]['type'];
        $text = str_replace('/', '', $message['text']);
    
        if($type == 'bot_command' && $text == $command || $last_command == $command){
            $this->history_command->set($command);
            $this->callFunction($callback);
            
        
            
        }
         

    }
    public function getMe(){
        $data = $this->sendRequest("getMe");
        return $data;
    }
    public function getUserId(){
        $response = $this->getUpdate();
        return $response['message']['from']['id'] ?? $response['callback_query']['from']['id'];
    }
    public function sendMessage($chat_id, array $params){
        $added_params = ['chat_id' => $chat_id];
        
        
        if(!array_key_exists('parse_mode', $params))
            $added_params['parse_mode'] = 'HTML';
        if(array_key_exists('reply_markup', $params))
            $params['reply_markup'] = json_encode( $params['reply_markup']);
        

        $params =  $added_params + $params;

        $this->sendRequest("sendMessage", $params);
    }
    public function sendAnswer($chat_id, array $params){
        $this->sendMessage($chat_id, $params);
        $this->history_command->clear();
    }
    public function editMessage($chat_id, $message_id, array $params){
        $added_params = [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ];

        if(!array_key_exists('parse_mode', $params))
            $added_params['parse_mode'] = 'HTML';
        $params =  $added_params + $params;

        $this->sendRequest("editMessageText", $params);
    }

    public function deleteMessage($chat_id, $message_id){
        $params = [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ];

        $this->sendRequest("deleteMessage", $params);
    }

    public function editMessageReplyMarkup($chat_id, $message_id, $replyMarkup = null){
        $params = [
            'chat_id' => $chat_id, 
            'message_id' => $message_id,
            'reply_markup' => null
        ];
        if($reply_markup != null){
            $params['reply_markup'] = json_encode($replyMarkup);
        }
        $this->sendRequest("editMessageReplyMarkup", $params);
    }
    

    public function waitAnswer($trigger, $callback, $freez = false){
        $current_trigger = $this->history_command->get();
        if($current_trigger == $trigger){
            $this->callFunction($callback);
            
            if(!$freez)
                $this->history_command->clear();
            exit;
        }
    }

    public function getMessage(){
        $answer = $this->getUpdate();
        $message = $answer['message'] ?? null; 
        return $message;
    }
    public function getCallback(){
        return $this->getUpdate()["callback_query"] ?? false;
    }

    public function onCallback($callback_query, $callback){
        $callback_data = $this->getUpdate()['callback_query'];
        $callback_command = $callback_data['data']; 
        if($callback_command == $callback_query){
            
            $this->callFunction($callback);
            $this->history_command->set($callback_query);

            exit;
        }
    }

    public function onMessage(string $trigger, $callback){
   
        $message = $this->getMessage()['text'];
        $last_command = $this->history_command->get();
        $preparation_trigger =  '#^'.$trigger.'$#';

        if(preg_match($preparation_trigger, $message) || preg_match($preparation_trigger, $last_command)){
            $this->history_command->set($trigger);
            $this->callFunction($callback);
            
            exit;
        }
    }



    private function sendRequest(string $request, array $params = []){
        
        if(empty($params))
            $request_answer = file_get_contents($this->url.$request);
        else{
            
            $preparation_params = http_build_query($params);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => $preparation_params
        
                )
            );
            $context  = stream_context_create($options);
            $request_answer = file_get_contents($this->url.$request, false, $context);
        }
            
            
        
            

        return json_decode($request_answer, JSON_OBJECT_AS_ARRAY);
    }


    private function callFunction($callback){
        if(is_callable($callback))
                $callback();
        else{
            if(is_array($callback[1]))
                call_user_func_array(...$callback);
            else {
                call_user_func($callback);
            }
            
        }
    }

    public function __set($key, $value){
        return $this->registry->set($key, $value);
    }

    public function __get($key){
        return $this->registry->get($key);
    }

    public function __isset($key){
        return $this->registry->has($key);
    }


}