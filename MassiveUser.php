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
     * Конструктор класса, при создании объекта получаем массив id по заданным критериям.
     * Возможные ключи значений: name,surname,date_birth,gender,city_birth,operand.
     */

    public function __construct(array $params)
    {
        foreach ($params as $key => $param) {
            if ($key == 'name') {
                $options[] = "`name` {$param['operand']} '{$param['name']}'";
            }
            if ($key == 'surname') {
                $options[] = "`surname` {$param['operand']} '{$param['surname']}'";
            }
            if ($key == 'date_birth') {
                $options[] = "`date_birth` {$param['operand']} '{$param['date_birth']}'";
            }
            if ($key == 'gender') {
                $options[] = "`gender` {$param['operand']} '{$param['gender']}'";
            }
            if ($key == 'city_birth') {
                $options[] = "`city_birth` {$param['operand']} '{$param['city_birth']}'";
            }
        }

        $sql = "SELECT id FROM Human WHERE {$options[0]}";
        if (($a = count($options)) > 1) {
            for ($i = 1; $i < $a; $i++) {
                $sql .= " and {$options[$i]}";
            }
        }
        $sql .= ';';
        $conn = User::connect();
        $result = (mysqli_query($conn, $sql));
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $this->massiv_id[] .= $row['id'];
            }
        }
    }
}


