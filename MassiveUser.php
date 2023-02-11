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
        $options = [];
        foreach ($params as $key => $param) {
            $options[] = "`{$key}` {$param['operand']} '{$param[$key]}'";
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

    /**
     * @return array
     * Возвращает массив объектов класса User в соответствии с massiv_id
     */
    public function find_users() : array
    {
        $massiv_users = [];
        foreach ($this->massiv_id as $id) {
            $massiv_users[] = new User($id);
        }

        return $massiv_users;
    }

    /**
     * @return void
     * Удаляет записи из БД в соответствии с massiv_id
     */
    public function delete_users() : void
    {
        foreach ($this->massiv_id as $id){
            User::delete($id);
        }

    }
}


