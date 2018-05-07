<?
abstract class Unit 
{
	protected $mysqli; // Идентификатор соединения

	/*конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
	public function __construct($id) {
		$this->id = $id;
		$this->mysqli = new mysqli("localhost", "sonic", "", "cryptaur_socium");
		$this->mysqli->query("SET lc_time_names = 'ru_RU'");
		$this->mysqli->query("SET NAMES 'utf8'");
	}

    abstract public function setTable();

	public function showField($field)
	{
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
		$sql->bind_param("i", $this->id);
		$sql->execute();
		$unit = $sql->get_result()->fetch_assoc();
        $sql->close();
		return $unit[$field];
	}
	
	public function show()
	{  
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
		$sql->bind_param("i", $this->id);
		$sql->execute();		
		$unit = $sql->get_result()->fetch_assoc();
        $sql->close();
		return $unit;
	}

	public function getAllFields(){
        $par_arr = array();
        $columns_sql= $this->mysqli->prepare("SHOW COLUMNS FROM ".$this->setTable()."");
        $columns_sql->execute();
        $columns = $columns_sql->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($columns as $column){
            $par_arr[$column['Field']] = $column['Field'];
        }
        return $par_arr;
    }

    public function getAllUnits(){
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()."");
        $sql->execute();
        $units = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $units;
    }

    public function getAllUnitsReverse(){
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." ORDER BY publ_time DESC");
        $sql->execute();
        $units = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $units;
    }
}



?>