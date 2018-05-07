<?include ('distance_count.php'); ?>
<div id='map_block' style=' margin-left: -20px; margin-right: -20px; '>
	   <div class='map' id='map'>
	    
	   </div>
	   <div class='map_list'>
	    <? if(!isset($_GET['city'])){ ?>
	     <div class='big_title'>Магазины</div>
		<? }?>
		<ul>
		 <?php  				
             if($city_req){
			  if($_GET['city'] == 'магазины рядом'){
				session_start();
	            $user_lat = $_SESSION['user_lat'];
	            $user_lon = $_SESSION['user_lon'];

                $near_shops_sql = $pdo->prepare("SELECT * FROM $table_shops" );
                $near_shops_sql->execute();
				$shops_id = array();
                $j = 0;
				//выбираем массив айдишников всех магазинов в радиусе
                while($near_shops_info = $near_shops_sql->fetch())
                {
                 $near_lat = $near_shops_info['latitude'];
                 $near_lon = $near_shops_info['longitude'];

                 if((calculateTheDistance($user_lat, $user_lon, $near_lat, $near_lon) < $radius) ){
                  $shops_id[$j] = $near_shops_info['id'];
	              $j++;	
				  
                 }
                }
				//преобразуем массив в строку чтобы подставить в запрос
                foreach($shops_id as $shops_id_elem){$near_shops_str = $near_shops_str."'$shops_id_elem',";}
                  $near_shops_str = substr($near_shops_str,0,-1);
                  $near_shops_arr = "AND id IN($near_shops_str)";
				  
				  
                } 
			    
			  //проверяем конкретный ли город или поблизости
			  if($_GET['city'] == 'магазины рядом'){
			    $shop = $pdo->prepare("SELECT * FROM $table_shops $active $near_shops_arr ORDER BY order_row DESC");
			  }else{
			    $shop = $pdo->prepare("SELECT * FROM $table_shops $active AND city= :city $near_shops_arr ORDER BY order_row DESC");
			    $shop->bindParam(':city', $_GET['city']);
			  }
			  $shop->execute();
			  while($src = $shop->fetch()){
			   if($src['status']){$status = "<div class='shop_status'>".$src['status']."</div>";}
               echo "<li><a href='shop_card.php?id=".$src['id']."&cathegory=shops&city=".$src['city']."&title=".$src['title']."'>".$status."".$src['title']."</a><div class='shop_det'>".$src['address'].". Тел: ".$src['phone']."</div></li>";
			  }
			 }else{
				$arr_2=array();
				$shop = $pdo->prepare("SELECT * FROM $table_shops");
				$shop->execute();
				while($src = $shop->fetch())
				{
			     $curr_city = $src['city'];
			     if(!in_array($curr_city, $arr_2)){
				  array_push($arr_2, $curr_city);	 
				  $shop_num = $pdo->prepare("SELECT * FROM $table_shops WHERE city='$curr_city'"); 	
				  $shop_num->execute();
				  $num = $shop_num->rowCount();	   
			      echo "<li><a href='shops.php?city=".$src['city']."&cathegory=shops'>".$src['city']." <span>".shop_num($num)."</span></a></li>"; 
			     }
			    }			  
		      }
		 ?>
		</ul>
	   </div>
  </div>
<script> 
//$('body').on('mouseover', '.near_shops', function(){
$(document).ready(function() {
 var check = '<?echo $_GET['city'];?>';
  //alert(check);
 if( check == 'магазины рядом'){
  if (!navigator.geolocation){
    alert("<p>Геолокация не поддерживается вашим браузером</p>");
    return;
  }
  
  function success(position) {
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;
    
    var contentString = "<div id='content'>ВЫ ЗДЕСЬ</div>";
    var infowindow = new google.maps.InfoWindow({
	    content: contentString
    });
   
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: latitude, lng: longitude},
          scrollwheel: true,
          zoom: 10,
          disableDefaultUI: true,
		  styles :[ { "elementType": "geometry", "stylers": [ { "color": "#f5f5f5" } ] }, { "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "elementType": "labels.text.fill", "stylers": [ { "color": "#616161" } ] }, { "elementType": "labels.text.stroke", "stylers": [ { "color": "#f5f5f5" } ] }, { "featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [ { "color": "#bdbdbd" } ] }, { "featureType": "poi", "elementType": "geometry", "stylers": [ { "color": "#eeeeee" } ] }, { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [ { "color": "#757575" } ] }, { "featureType": "poi.park", "elementType": "geometry", "stylers": [ { "color": "#e5e5e5" } ] }, { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] }, { "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#ffffff" } ] }, { "featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [ { "color": "#757575" } ] }, { "featureType": "road.highway", "elementType": "geometry", "stylers": [ { "color": "#dadada" } ] }, { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [ { "color": "#616161" } ] }, { "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] }, { "featureType": "transit.line", "elementType": "geometry", "stylers": [ { "color": "#e5e5e5" } ] }, { "featureType": "transit.station", "elementType": "geometry", "stylers": [ { "color": "#eeeeee" } ] }, { "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#c9c9c9" } ] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] } ]
        });
    
        var marker = new google.maps.Marker({
          position: {lat: latitude , lng: longitude},
          map: map,
          //animation: google.maps.Animation.DROP,
          label: 'Я',
          title: 'ВЫ ЗДЕСЬ'
        });
    
    $.ajax({
            url: "http://borelli.beget.tech/magic/components/locator_map.php",
            type: "GET",
            data: {"user_latitude": latitude, "user_longitude": longitude },
            cache: false, 
                success: function(response){
                  if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                     //$('#result').html('местоположение не определено<br> Попробуйте указать его вручную');
                  }else{
                   //alert(response);
                     var near = response;
                    //alert(near);
                    var points = JSON.parse(near);
					
					var center_point = points[0].split('&');				
										
                    points.forEach(function(item, i, arr){
                      var params_points = item.split('&');
                      var id = params_points[0];
                      var title = params_points[1];
                      var lat = Number(params_points[2]);
                      var lon = Number(params_points[3]);
                      var city = params_points[4];
                      var address = params_points[5];
                      var phone = params_points[6];
					  var working_time = params_points[7];
					  var description = params_points[8];
					  var photo = params_points[9];
                       
                      var contentString = "<div style='text-align: center; ' id='content'><a style='text-align: center;' href='http://borelli.beget.tech/magic/shop_card.php?id="+id+"&cathegory=shops&city="+city+"&title="+ title +"' ><b>"+ title +"</b></a><br><p class='map_address'><img style='width: 100px;' src='" + photo+ "' /><p>Адрес: " + address+ "</p><p class=''>Тел: " + phone + "</p><p class='map_status'>Время работы: " + working_time + "</p></div>";
                        var infowindow_users = new google.maps.InfoWindow({
	                    content: contentString
                      });     
                       
                      var marker = new google.maps.Marker({
                        position: {lat: lat , lng: lon},
                        map: map
                        //animation: google.maps.Animation.DROP
                      });
                     
                      google.maps.event.addListener(marker, 'click', function() {
	                    infowindow_users.open(map,marker);
                      });    
                      
                    }); 
                  }  
               }
            });
    google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
    });
  }
 
  function error() {
   alert("Невозможно определить ваше местоположение. Разрешите отслеживание местоположнияв браузере");  
  }

    $('#map').html("<div style='text-align: center; padding-top: 10%;'><p>Определение мастоположения…</p><img style='width: 100px;' src='https://ironlinks.ru/find/img/loading.gif' /></div>");
     navigator.geolocation.getCurrentPosition(success, error);
  } 
}); 	
</script> 
<script>
   $(document).ready(function() {
	    var city = "<? echo $_GET['city']; ?>";
		var shop_id = "<? echo $_GET['id']; ?>";
		
       $.ajax({
            url: "http://borelli.beget.tech/magic/components/locator_map.php",
            type: "GET",
            data: {"city_map": city, "shop_id": shop_id },
            cache: false, 
                success: function(response){
                  if(response == 0){ 
                      //alert("не получили ответ");
                  }else{
					  
                    var near = response;
                    //alert(near);
                    var points = JSON.parse(near);
					
					var center_point = points[0].split('&');
					
					var center_lat = Number(center_point[2]);
					var center_lon = Number(center_point[3]);
					
                     map = new google.maps.Map(document.getElementById('map'), {
                       center: {lat: center_lat, lng: center_lon},
                       zoom: 10,
					   styles :[ { "elementType": "geometry", "stylers": [ { "color": "#f5f5f5" } ] }, { "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "elementType": "labels.text.fill", "stylers": [ { "color": "#616161" } ] }, { "elementType": "labels.text.stroke", "stylers": [ { "color": "#f5f5f5" } ] }, { "featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [ { "color": "#bdbdbd" } ] }, { "featureType": "poi", "elementType": "geometry", "stylers": [ { "color": "#eeeeee" } ] }, { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [ { "color": "#757575" } ] }, { "featureType": "poi.park", "elementType": "geometry", "stylers": [ { "color": "#e5e5e5" } ] }, { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] }, { "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#ffffff" } ] }, { "featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [ { "color": "#757575" } ] }, { "featureType": "road.highway", "elementType": "geometry", "stylers": [ { "color": "#dadada" } ] }, { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [ { "color": "#616161" } ] }, { "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] }, { "featureType": "transit.line", "elementType": "geometry", "stylers": [ { "color": "#e5e5e5" } ] }, { "featureType": "transit.station", "elementType": "geometry", "stylers": [ { "color": "#eeeeee" } ] }, { "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#c9c9c9" } ] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [ { "color": "#9e9e9e" } ] } ],
                       disableDefaultUI: true 
                    });  
					
					
                    points.forEach(function(item, i, arr){
                      var params_points = item.split('&');
                      var id = params_points[0];
                      var title = params_points[1];
                      var lat = Number(params_points[2]);
                      var lon = Number(params_points[3]);
                      var city = params_points[4];
                      var address = params_points[5];
                      var phone = params_points[6];
					  var working_time = params_points[7];
					  var description = params_points[8];
					  var photo = params_points[9];
                       
                      var contentString = "<div style='text-align: center; ' id='content'><a style='text-align: center;' href='http://borelli.beget.tech/magic/shop_card.php?id="+id+"&cathegory=shops&city="+city+"&title="+ title +"' ><b>"+ title +"</b></a><br><p class='map_address'><img style='width: 100px;' src='" + photo+ "' /><p>Адрес: " + address+ "</p><p class=''>Тел: " + phone + "</p><p class='map_status'>Время работы: " + working_time + "</p></div>";
                        var infowindow_users = new google.maps.InfoWindow({
	                    content: contentString
                      });     
                       
                      var marker = new google.maps.Marker({
                        position: {lat: lat , lng: lon},
                        map: map
                        //animation: google.maps.Animation.DROP
                      });
                     
                      google.maps.event.addListener(marker, 'click', function() {
	                    infowindow_users.open(map,marker);
                      });    
                    }); 
              }  
           }
        });
	 }); 
</script>
