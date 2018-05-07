
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'>
          <tr>
            <th>Регион</th>
            <th>Служба доставки</th>
            <th>Стоимость доставки</th>
			<th>Время доставки</th>
          </tr>
<?php 
   //$sort_par = $_GET['sort_par'];  
   $sort_par = 'publ_time';
   (isset($_GET['city'])) ? $city = "%".$_GET['city']."%" : $city = "%%";	   
   $source = $pdo->prepare("SELECT * FROM $table_regions $active AND title LIKE :city ORDER BY $sort_par DESC");		
   $source->bindParam(':city', $city);
   $source->execute();
   while($src = $source->fetch()){ ?>
	    
	      <div class='desktop_form_unit'>
              <tr> 
			    <td><a href='post_form.php?category=<?=$table_regions?>&id=<?=$src['id']?>&action=change&title=изменить'><b><?=$src['title']?></b></a></td>
			    <td><?=$src['delivery_service']?></td>
				<td><?=$src['delivery_price']?></td>
				<td><?=$src['delivery_time']?></td>
		        <td>
		         <a  class='delete_post' href='javascript: void(0);' name='<?=$src['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		        </td> 
		      </tr>
	      </div>
  <? } ?>
       </table>
	 </div>  
  </div>

  
	