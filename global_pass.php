<?php
$hostname = 'http://socium.linkholder.ru';
$domain = 'http://socium.linkholder.ru/';
define("DOMAIN", "/");
define("PHOTO_FOLDER", 'uploads');

$host = 'localhost';
$user ='sonic';
$password ='20091993decSonic-tgogogo';
$db = 'cryptaur_socium';  


$WHITE_LIST_SORT = array(
  'price' => 'price',
  'popularity' => 'popularity',
  'itemtypes' => 'itemtypes',   
  'categories' => 'categories', 
);

$GLOBAL_LIST = array (
  'login' => 'admin_login',
  'pass' => 'admin_pass', 
  'user_key' => 'admin_hash',
  'email' => 'admin_email',
  'theme' => 'theme',
  'phone' => 'phone',
  'fax' => 'fax', 
  'email' => 'email',
  'address' => 'address',
  'insta' => 'instagram',
  'fb' => 'facebook',
  'vk' => 'vkontakte',
  'twitter' => 'twitter',
  'copyright' => 'copyright',
  'lines_cooldown' => 'ad_cooldown'
);

$TABLE = (object)array (
  'banners' => 'banners',
  'brands' => 'brands',
  'itemtypes' => 'itemtypes',
  'categories' => 'categories',
  'leftovers' => 'leftovers',
  'filters' => 'filters',
  'sales' => 'sales',
  'collections' => 'collections',
  'items' => 'items',
  'shops' => 'shops',
  'regions' => 'regions',
  'delivery' => 'delivery',
  'news' => 'news',
  'ads' => 'ads',
  'users' => 'users',
  'orders' => 'orders',
  'global_media' => 'global_media',
  'themes' => 'themes'
);

$table_ban = $TABLE->banners;
$table_brands = $TABLE->brands;
$table_types = $TABLE->itemtypes;
$table_categories = $TABLE->categories;
$table_filters = $TABLE->filters;
$table_sales = $TABLE->sales;
$table_collections = $TABLE->collections;
$table_leftovers = $TABLE->leftovers;
$table_items = $TABLE->items;
$table_shops = $TABLE->shops;
$table_regions = $TABLE->regions;
$table_delivery = $TABLE->delivery;
$table_news = $TABLE->news;
$table_ads = $TABLE->ads;
$table_users = $TABLE->users;
$table_orders = $TABLE->orders;
$table_medias = $TABLE->global_media;
$table_themes = $TABLE->themes;


    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    //$dsn = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
	
	$pdo = new PDO($dsn, $user, $password, $opt);
	$pdo->exec("set names utf8");

           $media = $pdo->prepare("SELECT * FROM $table_medias LIMIT 1");
           $media->execute();
		   $media_src = $media->fetch();
    $radius = $media_src['radius'] * 1000;
	
	
$WHITE_LIST = array();
     $tables_sql= $pdo->prepare("SHOW TABLES FROM $db");
     $tables_sql->execute();
	 $i = 0;
     while($table = $tables_sql->fetch()){
	   $WHITE_LIST[$i] = $table["Tables_in_$db"];
	   $i++;
} 	
	

	$active = "WHERE activity='1'";



require_once('functions/index.php');	
?>