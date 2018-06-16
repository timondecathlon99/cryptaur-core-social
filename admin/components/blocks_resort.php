<?include('../../global_pass.php');?>
<?require_once('../../classes/autoload.php');

$page = $_GET['page'];

//если нету пути то это главная
($_GET['page'] == $domain) ? $page = 1 : '';

$logedUser = new Member($_COOKIE['member_id']);
$page = new Page($page);
if($logedUser->is_valid()){
    $page->setBlocks('center',$_GET['blocksCenter']);

    //Если не пустой список блоков левого столбца
    //if($_GET['blocksLeft'] != NULL){
    $page->setBlocks('left',$_GET['blocksLeft']);
    //}
    //Если не пустой список блоков правого столбца
    //if($_GET['blocksRight'] != NULL){
    $page->setBlocks('right',$_GET['blocksRight']);
    //}
}else{
    echo "F*ck you, hacker=)";
}