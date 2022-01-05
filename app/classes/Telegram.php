<?php
namespace Classes;
class Telegram{
    private $con;

    public function __construct($connection){
        $this->con = $connection;
    }

    public function recieveData(){
        $text = json_decode(file_get_contents('php://input'));
        return $text;
    }

    public function curlExecute($data,$url){
        if (!$curld = curl_init()) {
            exit;
        }
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        return $output;
    }

    public function telegramApi($method,$dataArray){
        $url = 'https://api.telegram.org/bot'.TOKEN.'/'.$method;
        return $this->curlExecute($dataArray,$url);
    }

    public function addToUsers($result){
        // $messageid  = $result->message->message_id;
        // $userid     = $result->message->from->id;
        // $text       = $result->message->text;
        // $chatid     = $result->message->chat->id;
        if($this->newUser($result)){
            $firstname = $result->message->from->first_name;
            $username   = $result->message->from->username;
            $zero = 0;
            $statement = $this->con->prepare("INSERT INTO user (userUsername,userFirstname,userRequestsCount)
                VALUES (:username, :firstname, :reqCount)");
            $statement->bindParam(":username",$username);
            $statement->bindParam(":firstname",$firstname);
            $statement->bindParam(":reqCount",$zero); 
            $statement->execute();
        } 
    }

    private function newUser($result){
        $username   = $result->message->from->username;
        $rows = $this->con->query("SELECT userUsername FROM user WHERE userUsername = '.$username.'");
        if($rows->fetchAll() > 0 ){
            return false;
        }
        return true;
    }

    public function showMenu($options,$result,$text){
        $keyboard = $this->menuMarkup($options);
        $data = ["chat_id" => $result->message->from->id , "text" => $text , "reply_markup" => $keyboard];
        $this->telegramApi("sendMessage",$data);
    }

    private function menuMarkup($options){
        $keyboard = [
            'keyboard' => $options ,
            'resize_keyboard' => true ,
            'one_time_keyboard' => false ,
            'selective' => true
        ];    
        return json_encode($keyboard);
    }
}

?>