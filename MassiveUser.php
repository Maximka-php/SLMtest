<?php
if (class_exists('user')) {
    require_once('User.php');
} else {
    die();
}

/**
 * Класс MassiveUser;
 * содержит методы чтения/записи/удаления информации из БД при работе с группой записей
 */
class MassiveUser
{

    public $massiv_id;

    /**
     * Конструктор класса, при создании объекта получаем массив id по заданным критериям либо все
     */
    public function __construct($param)
    {

    }


}


