<?
include('../../global_pass.php');
$column = $_GET['column_info'];

$column = str_replace('block_','',explode('~', $column));

$field_id =  $column[0]; //получем айди поля
$table =  $column[1];    //получаем название таблицы 


//получаем имя поля и его тип
$field_sql = $pdo->prepare("SELECT * FROM fields WHERE id = :id");
$field_sql->bindParam(':id', $field_id);
$field_sql->execute();
$field = $field_sql->fetch(); 
$column_name =  $field['title'];
$column_type =  $field['type'];

//проверяем нет ли уже поля в таблице
$columns_sql = $pdo->prepare("SHOW COLUMNS FROM ".$table  );
$columns_sql->execute();
while($column = $columns_sql->fetch()){
   if($column['Field'] == $column_name){
	 $flag =1;
     break;	 
   }
} 

if($flag != 1){
	 $add_sql = $pdo->prepare("ALTER TABLE $table ADD $column_name $column_type");
     if($add_sql->execute()){	
       echo 'Столбец добавлен';	
     }
}else{
   echo 'Столбец с таким именем уже есть в таблице';	
}








?>