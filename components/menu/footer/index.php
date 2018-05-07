<footer class='footer container'>
		    <div>
		     <ul>
              <?
	          $menu_sql = $pdo->prepare("SELECT * FROM pages $active ORDER BY order_row DESC");
              $menu_sql->execute();    
		       while($menu_item = $menu_sql->fetch()){
               $permissions_arr = unserialize($menu_item['permissions']);
               if($logedUser->can_see($permissions_arr) ) {		  
		       ($menu_item['link'] != ' ') ? $page_link = $menu_item['link'] : $page_link = $menu_item['furl'].'/';  ;?>  
 		        <li class='menu_items'>   
		           <a href="<?=$domain?><?=$page_link?>"><?=$menu_item['title']?></a>
				</li>
		      <?}
		      }?>			  
			 </ul>
		    </div>
			<div>
			 <ul>
			  <li><a href='tel: <?=explode(',',$media_src['phone'])[0]?>' ><i class="fa fa-phone" aria-hidden="true"></i>  <?=explode(',',$media_src['phone'])[0]?></a></li>
			  <li><a href='mailto: <?=$media_src['email']?>' ><span><i class="fa fa-envelope-o" aria-hidden="true"></i>  <?=$media_src['email']?></span></a></li>
			  <li class='empty_item'>  </li> 
			  <li class='empty_item'>  </li>
			  <li><span><?=$media_src['copyright']?></span></li>
			 </ul>
		    </div>
		    <div>
		     <ul>
			  <li><span class='tall'>Подписаться на новости</span></li>
			  <li>
			    <form  class='sub_form'  action='admin/send_msg.php' method='POST'>
                                  <input type='hidden' name='topic' value='Подписка на новости'></input>
				  <input type='text' name='name' placeholder='Ваше имя'></input><br>
				  <input type='email' name='email' placeholder='Ваш e-mail'></input><br><br> 
				  <button  class='liblenn_button'>ок</button>
				</form>
			  <li>
			 </ul>
		    </div>
		    
			<div>
		     <ul>
			  <li>
			      <? $ad_sql = $pdo->prepare("SELECT * FROM ads $active ORDER BY order_row DESC"); 
		                $ad_sql->execute();
		                $adv = $ad_sql->fetch();
						$photo = unserialize($adv['photo']);
			         ?>
			      <div class='footer_ad' style='background: url(<?=$domain.$photo[0]?>);'>		     
				     <a href='<?=$adv['link']?>'><?=$adv['title']?></a>
                  </div>				  	
			  </li>
			 </ul>
		    </div>
    
</footer>   