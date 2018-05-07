
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'>
          <tr>
            <th>Имя</th>
            <th>IP-адрес</th>
            <th>Время посещения</th>
          </tr>
<? 

   $table =  $_GET['type'];  
   $source = $pdo->prepare("SELECT * FROM $table $active");		
   $source->execute();
   while($src = $source->fetch()){
	 $date = date("d.m.y в G:i",$src['publ_time']); ?>
	    
	      <div class='desktop_form_unit'>
              <tr> 
                <? if($src['user_id'] > 0 ){?>
					<td><a href='post_form.php?category=users&id=<?=$src['user_id']?>&action=change&title=изменить'><b><?=$src['title']?></b></a></td>
				<?}else{ ?>
					<td><?=$src['title']?></td>
				<?}	  ?>		  
			    
				<td><?=$src['ip_address']?></td>
				<td><?=$date?></td>
		   </tr>
	      </div>
  <? } ?>
       </table>    
     </div>  
  </div>

  
	