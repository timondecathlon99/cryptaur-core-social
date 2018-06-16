<?
class Telegram{

    public $msg;

    public function __construct($token){
        $this->url = "https://api.telegram.org/bot$token/";
    }

    public function send($msg, $chat){
        if(file_get_contents($this->url."sendmessage?parse_mode=HTML&text=$msg&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function sendPhoto($msg, $chat){
        if(file_get_contents($this->url."sendPhoto?parse_mode=HTML&photo=$msg&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function sendDocument($link, $chat){
        if(file_get_contents($this->url."sendDocument?document=$link&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function sendLocation($latitude, $longitude, $chat){
        if(file_get_contents($this->url."sendLocation?latitude=$latitude&longitude=$longitude&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function sendVenue($latitude, $longitude, $title, $address, $chat){
        if(file_get_contents($this->url."sendVenue?latitude=$latitude&longitude=$longitude$title=$title$address=$address&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function sendContact($phone_number, $first_name, $last_name, $chat){
        if(file_get_contents($this->url."sendContact?phone_number=$phone_number&first_name=$first_name&last_name=$last_name&chat_id=$chat")){
            return true;
        }else{
            return false;
        }
    }

    public function getUserProfilePhotos($user_id){
        return file_get_contents($this->url."getUserProfilePhotos?user_id=$user_id");
    }
}