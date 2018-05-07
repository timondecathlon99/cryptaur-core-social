<?// Инициализируем сеанс CURL
            $ch = curl_init($media_src['instagram']);
            // Указываем, что результат должен записаться в переменную
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           // Выполняем CURL запрос
           $data = curl_exec($ch);
		   $var = explode('<script type="text/javascript">window._sharedData = ', $data);
		   $bar = explode("</script>", $var[1]);
		   //echo $bar[0];
  		      $ParsJson=$bar[0];
  		      $ParsJson=substr($ParsJson,0,strlen($ParsJson)-1);
  		      $ParsJson2=json_decode($ParsJson,true);
              $countPhoto = count($ParsJson2['entry_data']['ProfilePage']['0']['user']['media']['nodes']);
              $ParsJson3=json_decode($ParsJson);
  	          for ($x=0; $x < 12; $x=$x+2) {
			   echo "<div>";
			   for ($y=$x; $y < $x + 2; $y++) {
 		        $img= $ParsJson3->entry_data->ProfilePage[0]->user->media->nodes[$y]->thumbnail_src;
	            echo "<a href='".$media_src['instagram']."' target='_blank''><img src='".$img."'/></a>";
               }
               echo "</div>";			   
		      } ?>