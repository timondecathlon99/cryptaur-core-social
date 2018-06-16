<?
abstract class Unit
{
    protected $mysqli; // Идентификатор соединения

    /*конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
    public function __construct($id) {
        $this->id = $id;
        $this->mysqli = new mysqli('localhost', 'sonic', '20091993decSonic-tgogogo', 'cryptaur_socium');
        $this->mysqli->query("SET lc_time_names = 'ru_RU'");
        $this->mysqli->query("SET NAMES 'utf8'");
        $this->pdo = new PDO('mysql:host=localhost;dbname=cryptaur_socium', 'sonic', '20091993decSonic-tgogogo');
        $this->pdo->exec("set names utf8");
    }

    abstract public function setTable();

    public function showField($field)
    {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->setTable()." WHERE id=:id");
        $sql->bindParam(':id', $this->id);
        $sql->execute();
        $unit = $sql->fetch(PDO::FETCH_LAZY);
        return $unit[$field];
    }

    public function updateField($field, $param){
        $sql = $this->pdo->prepare("UPDATE ".$this->setTable()." SET $field=:param WHERE id=:id");
        $sql->bindParam(':param', $param);
        $sql->bindParam(':id', $this->id);
        if(in_array($field,$this->getTableColumnsNames())) {
            $sql->execute();
        }
    }

    public function deleteLine(){
        try {
            $post_del_sql = $this->pdo->prepare("DELETE FROM ".$this->setTable()." WHERE id=:id");
            $post_del_sql->bindParam(':id', $this->id);
            $post_del_sql->execute();
        }catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

    public function activate(){
        $this->updateField('activity', 1);
    }

    public function deactivate(){
        $this->updateField('activity', 0);
    }

    public function show()
    {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->setTable()." WHERE id=:id");
        $sql->bindParam(':id', $this->id);
        $sql->execute();
        $unit = $sql->fetch(PDO::FETCH_LAZY);
        return $unit;
    }

    public function getTableColumns(){
        $par_arr = array();
        $columns_sql= $this->pdo->prepare("SHOW COLUMNS FROM ".$this->setTable()."");
        $columns_sql->execute();
        return $columns_sql->fetchAll();
    }

    public function getTableColumnsNames(){
        $par_arr = array();
        $columns_sql= $this->pdo->prepare("SHOW COLUMNS FROM ".$this->setTable()."");
        $columns_sql->execute();
        foreach ($this->getTableColumns() as $column){
            $par_arr[$column['Field']] = $column['Field'];
        }
        return $par_arr;
    }

    public function getAllUnits(){
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->setTable()."");
        $sql->execute();
        $units = $sql->fetchAll();
        return $units;
    }

    public function getAllUnitsReverse(){
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->setTable()." ORDER BY publ_time DESC");
        $sql->execute();
        $units = $sql->fetchAll();
        return $units;
    }

    public function getMaxId(){
        $sql = $this->pdo->prepare("SELECT MAX(id) FROM ".$this->setTable()." ");
        $sql->execute();
        $id_info = $sql->fetch(PDO::FETCH_LAZY);
        return $id_info['MAX(id)'];
    }
}



?>