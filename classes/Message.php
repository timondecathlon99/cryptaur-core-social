<?
class Message extends Unit{


    public function setTable(){
        return 'messages';
    }

    public function message_id(){
        return $this->showField('id');
    }

    public function title(){
        return $this->showField('title');
    }

    public function text(){
        return $this->showField('description');
    }

    public function getMemberInboxMessages(int $member_id){
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE to_id=? ORDER BY publ_time DESC");
        $sql->bind_param('i', $member_id);
        $sql->execute();
        $units = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $authors_arr  = array();
        $messages_arr  = array();
        foreach ($units as $message){
            if(!in_array($message['from_id'], $authors_arr)){
                array_push($authors_arr,$message['from_id']);
                array_push($messages_arr,$message['id']);
            }
        }

        $msg_line = implode(',',$messages_arr);

        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id IN($msg_line) ORDER BY publ_time DESC ");
        $sql->execute();
        $units = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $units;
    }

    public function getChatMessages(int $member_id, int $room_id){
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE (to_id=? AND from_id=?) OR (to_id=? AND from_id=?)");
        $sql->bind_param('iiii', $member_id, $room_id, $room_id, $member_id);
        $sql->execute();
        $units = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $units;
    }

    public function create($description, $to_id, $from_id){
        $publ_time = time();
        $line1 = 'to_id, from_id, description, publ_time';
        $line2 = $to_id.','.$from_id.",'$description',$publ_time";
        $sql = $this->mysqli->prepare("INSERT INTO ".$this->setTable()."(".$line1.")"."VALUES(".$line2.")");
        try{
            $sql->execute();
        }catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }
    }

    public function author(){
        return $this->showField('from_id');
    }

    public function destination(){
        return $this->showField('to_id');
    }


    public function publ_time(){
        return date_format_rus($this->showField('publ_time'));
    }





}