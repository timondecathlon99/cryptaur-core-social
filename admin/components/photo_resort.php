<?include('./../../global_pass.php');?>
<? require_once('../../classes/autoload.php');

$post = new Post($_GET['id']);
$post->getTable($_GET['category']);
$post->photoChange($_GET['photo']);