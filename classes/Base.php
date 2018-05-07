<?
abstract class Base
{
	private $mysqli; // Идентификатор соединения

	/*конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
	public function __construct($id) {
		$this->id = $id;
		$this->mysqli = new mysqli("localhost", "sonic", "20091993decSonic-tgogogo", "cms");
		$this->mysqli->query("SET lc_time_names = 'ru_RU'");
		$this->mysqli->query("SET NAMES 'utf8'");
	}
   
	public function setTable(){
	  return 'items';
	}
  
	public function showField($field)
	{
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
		$sql->bind_param("s", $this->id);
		$sql->execute();
		$result = $sql->get_result();
		$block = $result->fetch_assoc();
		if($block[$field]){
			return $block[$field];
		}
		$sql->close();
	}
	
	public function showField1($field)
	{
		$sql = $this->mysqli->query("SELECT * FROM ".$this->setTable()." WHERE id='".$this->id."'");
		$block= mysqli_fetch_assoc($sql);
		if($block[$field]){
			return $block[$field];
		}
		$sql->close();
	}
	
}
?>