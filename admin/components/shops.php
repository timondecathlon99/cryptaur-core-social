
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'>
          <tr>
            <th>Название</th>
            <th style='width : 150px;'>Город</th>
            <th>Адрес</th>
			<th>Время работы</th>
			<th style='width : 150px;'>Телефон</th>
          </tr>
<?php 
   //$sort_par = $_GET['sort_par'];  
   $sort_par = 'publ_time';
   (isset($_GET['city'])) ? $city = "%".$_GET['city']."%" : $city = "%%";	   
   $source = $pdo->prepare("SELECT * FROM $table_shops $active AND city LIKE :city ORDER BY $sort_par DESC");		
   $source->bindParam(':city', $city);
   $source->execute();
   while($src = $source->fetch()){ ?>
	    
	      <div class='desktop_form_unit'>
              <tr> 
			    <td><a href='post_form.php?category=<?=$table_shops?>&id=<?=$src['id']?>&action=change&title=изменить'><b><?=$src['title']?></b></a></td>
			    <td><?=$src['city']?></td>
				<td><?=$src['address']?></td>
				<td><?=$src['working_time']?></td>
				<td><?=$src['phone']?></td>
				<td><?=$src['respect_points']?></td>
		     <td>
		       <a  class='delete_post' href='javascript: void(0);' name='<?=$src['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		     </td> 
		   </tr>
	      </div>
  <? } ?>
       </table>
</div>  
  </div>

  
	