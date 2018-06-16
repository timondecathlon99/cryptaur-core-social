<div class='desktop'>
     <div class='desktop_form'>
		    
	        <div class='desktop_form_unit'>
			  <div class='unit_name'><b>Название сайта</b></div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['site_name'];?>'></input>
				 <input type='hidden' name='name' value='site_name'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>

             <div class='desktop_form_unit'>
                 <div class='unit_name'>Курс обмена баллы/СРТ</div>
                 <div>
                     <form action='components/change_media.php' method='POST'>
                         <input name='new_val' disabled type='text'  value='<?=$media_src['rate_course'];?>'></input>
                         <input type='hidden' name='name' value='rate_course'></input>
                         <div class='change_media'><img src='img/change_media.png'/></div>
                         <button>Применить</button>
                     </form>
                 </div>
             </div>

         <div class='desktop_form_unit'>
             <div class='unit_name'>Срок годности баллов(дней)</div>
             <div>
                 <form action='components/change_media.php' method='POST'>
                     <input name='new_val' disabled type='text'  value='<?=$media_src['points_lifetime'];?>'></input>
                     <input type='hidden' name='name' value='points_lifetime'></input>
                     <div class='change_media'><img src='img/change_media.png'/></div>
                     <button>Применить</button>
                 </form>
             </div>
         </div>

         <div class='desktop_form_unit'>
             <div class='unit_name'>Кулдаун отзыва к товару(дней)</div>
             <div>
                 <form action='components/change_media.php' method='POST'>
                     <input name='new_val' disabled type='text'  value='<?=$media_src['good_comment_cooldown']?>'></input>
                     <input type='hidden' name='name' value='good_comment_cooldown'></input>
                     <div class='change_media'><img src='img/change_media.png'/></div>
                     <button>Применить</button>
                 </form>
             </div>
         </div>

         <div class='desktop_form_unit'>
             <div class='unit_name'>Цена участия в выборах(баллов)</div>
             <div>
                 <form action='components/change_media.php' method='POST'>
                     <input name='new_val' disabled type='text'  value='<?echo $media_src['rate_course'];?>'></input>
                     <input type='hidden' name='name' value='rate_course'></input>
                     <div class='change_media'><img src='img/change_media.png'/></div>
                     <button>Применить</button>
                 </form>
             </div>
         </div>

			
			<div class='desktop_form_unit'>
			  <div class='unit_name'><b>E-mail администратора</b></div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['admin_email'];?>'></input>
				 <input type='hidden' name='name' value='admin_email'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Тема (<b>
			    <?  $theme_id = $media_src['theme'];
				    $theme_sql = $pdo->prepare("SELECT * FROM $table_themes WHERE id='$theme_id' ");
                    $theme_sql->execute();
					$theme_info = $theme_sql->fetch();
				    echo $theme_info['title']; 
				?>
				</b>)
			  </div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <select class='theme_select' disabled name='new_val'>
				  <? $theme_sql = $pdo->prepare("SELECT * FROM $table_themes WHERE activity='1' ORDER BY order_row DESC");
                      $theme_sql->execute();
					 while($theme_info = $theme_sql->fetch()){
					  echo "<option value='".$theme_info['id']."'>".$theme_info['title']."</option> ";
				     }
				   ?>
				 </select>
				 <input type='hidden' name='name' value='theme'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Номер телеграм чата</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?=$media_src['telegram_id'];?>'></input>
				 <input type='hidden' name='name' value='telegram_id'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
		  
		    <div class='desktop_form_unit'>
			  <div class='unit_name'>Вконтакте</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['vkontakte'];?>'></input>
				 <input type='hidden' name='name' value='vkontakte'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Facebook</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['facebook'];?>'></input>
				 <input type='hidden' name='name' value='facebook'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Instagram</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['instagram'];?>'></input>
				 <input type='hidden' name='name' value='instagram'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Google maps API key</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['g_maps_key'];?>'></input>
				 <input type='hidden' name='name' value='g_maps_key'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Яндекс.Карты API key</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['y_maps_key'];?>'></input>
				 <input type='hidden' name='name' value='y_maps_key'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Copyright</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['copyright'];?>'></input>
				 <input type='hidden' name='name' value='copyright'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Телефон</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['phone'];?>'></input>
				 <input type='hidden' name='name' value='phone'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Время работы</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['working_time'];?>'></input>
				 <input type='hidden' name='name' value='working_time'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Адрес</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['address'];?>'></input>
				 <input type='hidden' name='name' value='address'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>Факс</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['fax'];?>'></input>
				 <input type='hidden' name='name' value='fax'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>
			
			<div class='desktop_form_unit'>
			  <div class='unit_name'>email</div>
			  <div> 
			    <form action='components/change_media.php' method='POST'>
				 <input name='new_val' disabled type='text' value='<?echo $media_src['email'];?>'></input>
				 <input type='hidden' name='name' value='email'></input>
				 <div class='change_media'><img src='img/change_media.png'/></div>
				 <button>Применить</button>
				</form>
			  </div>
			</div>


	  </div>  
  </div>
