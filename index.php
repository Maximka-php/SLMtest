<?php
require_once ('User.php');
require_once('MassiveUser.php');

//$a = new User('pasa','maxovich',1455896,0,'pogost');
//var_dump($a);
//
//$a->save();


$a = new MassiveUser(['name'=>['name'=>'marina',
                                'operand' => '='],
                      'city_birth'=>['city_birth'=>'moldova',
                                    'operand'=>'!=']]);

var_dump($a->massiv_id);






