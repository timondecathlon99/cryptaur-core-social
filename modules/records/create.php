<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:16
 */
require_once('./../../global_pass.php');
require_once('../../classes/autoload.php');

$currRecord = new Post(0);
$currRecord->getTable('database_records');
$currRecord->setAuthor();
$currRecord->createUpdate();



header("Location: ".$_SERVER['HTTP_REFERER']);