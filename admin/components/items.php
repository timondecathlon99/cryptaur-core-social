
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'>
          
		    <th></th>
			<th>Фото</th>
            <th>
			  <?($_GET['dir']== 'DESC') ? $direction = 'ASC' :  $direction = 'DESC';?>
			  <a href='<?=$_SERVER['REQUEST_URI']?>&dir=<?=$direction?>'>Артикул</a>
			</th>
            <th>Наименование</th>
			<th>Цвет</th>
			<th>Остаток</th>
            <th>Цена</th>
            <th>Категория</th>
          
	<span id='curr_items'>	  
       <?include('items_row.php');?>  
    </span>
       </table>
	 </div>  
  </div>
  
  