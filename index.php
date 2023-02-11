<?php
require_once ('User.php');
require_once('MassiveUser.php');

$a = new User('pasa','maxovich',1455896,0,'pogost');
var_dump($a);

$a->save();






