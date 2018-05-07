<?
class Telegram{ 

   public $msg;
   
   public function __construct($token, $chat, $site_name){
     $this->site = $site_name;
     $this->chat = $chat;
     $this->url = "https://api.telegram.org/bot$token/";
   }
   
    public function send($msg){
	  $url = $this->url;
	  $chat = $this->chat;
	  $site = $this->site;
	  if(file_get_contents($url."sendmessage?parse_mode=HTML&text=$msg&chat_id=$chat")){
	    return true;
	  }else{
	    return false;
	  }
    }
	
	public function sendPhoto($msg){
	  $url = $this->url;
	  $chat = $this->chat;
	  $site = $this->site;
	  if(file_get_contents($url."sendPhoto?parse_mode=HTML&photo=$msg&chat_id=$chat")){
	    return true;
	  }else{
	    return false;
	  }
    }
 } 
   ?>