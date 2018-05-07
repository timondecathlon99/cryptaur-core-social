<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 03.05.2018
 * Time: 11:59
 */
class Group extends Unit
{
    public function setTable(){
            return 'user_groups';
    }

    public function title()
    {
        return $this->showField('title');
    }
}