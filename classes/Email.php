<?
class Email{ 

   public $msg;
   public $subject;
   public $to;
   
   public function __construct($to){
     $this->headers = "MIME-Version: 1.0\r\n Content-type: text/html; charset=utf-8\r\n";
   }
   
    public function send($to,$subject,$msg){
	  if(mail($to,$subject,$msg,$this->headers)){
       return true;
      }else{
	   return false; 
	  }
    }
 } 
   ?>