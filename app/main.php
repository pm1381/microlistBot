<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); //for showing all errors that happens
include "classes/Telegram.php";
include "core/Connection.php";
include "core/Config.php";

use Classes\Telegram;
use Core\Connection;

$setup = new Connection();
$connection= $setup->openConnection(HOST,USERNAME,PASSWORD,DBNAME);
$telegram = new Telegram($connection);
$result = $telegram->recieveData();

if($result->message->from->id == '1152769803'){
    if($result->message->text == '/start' || $result->message->text == 'منوی اصلی'){
        $telegram->showMenu([['hyundai'],['kia']],$result,'کمپانی مورد نظر :');
    }elseif($result->message->text == 'hyundai'){
        $telegram->addToUsers($result);

    }elseif($result->message->text == 'kia'){
        $telegram->addToUsers($result);

    }else{
        $telegram->telegramApi("sendMessage",["chat_id" => $result->message->chat->id, "text"=>'متوجه پیام شما نشدم']);
        $telegram->showMenu([['hyundai'],['kia']],$result,"استیکر گلی چیزی ...");
    }
    $telegram->telegramApi("sendMessage",["chat_id" => $result->message->chat->id, "text" => json_encode($result)]);
}
?>