<?php

/**
 * Класс User;
 * реализует связь с БД,содержит методы чтения/записи/удаления информации,
 * содержит некоторые методы обработки информации из БД,
 * таблица Human.
 */
class User
{
    public $id;
    public $name;
    public $surname;
    public $date_birth;
    public $gender;
    public $city_birth;

    /**
     * Конструктор класса, при создании объекта создает/выбирает информацию в/из БД
     * в зависимости от входящих параметров
     */
    function __construct()
    {
        if (func_num_args() == 5) {
            $a = func_get_args();
            self::validate($a[0], $a[1], $a[2], $a[3], $a[4]);
            $this->name = $a[0];
            $this->surname = $a[1];
            $this->date_birth = $a[2];
            $this->gender = $a[3];
            $this->city_birth = $a[4];
        } else {
            $a = func_get_args();
            $this->find($a[0]);
        }
    }

    public function find($id)
    {
        $conn = $this->connect();
        $sql = "SELECT * FROM Human WHERE id = '$id'";
        if ($human = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
            $this->id = $id;
            $this->name = $human['name'];
            $this->surname = $human['surname'];
            $this->date_birth = $human['date_birth'];
            $this->gender = $human['gender'];
            $this->city_birth = $human['city_birth'];
        } else {
            echo 'Ошибка поиска данных';
        }
    }

    /**
     * @return bool
     * Сохранение полей экземпляра класса в БД
     */
    public function save(): bool
    {
        $conn = $this->connect();
        $sql = "INSERT INTO `User`.`Human` (`name`,`surname`,`date_birth`,`gender`,`city_birth`)
                VALUES ('$this->name', '$this->surname', '$this->date_birth', '$this->gender', '$this->city_birth');";
        if (mysqli_query($conn, $sql)) {
            echo 'Данные успешно добавлены';
            return true;
        } else {
            echo 'Ошибка записи данных';
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     * Удаление человека из БД в соответствии с id объекта
     */
    static function delete($id): bool
    {
        $conn = self::connect();
        $sql = "DELETE FROM Human WHERE id = $id;";
        if (mysqli_query($conn, $sql)) {
            echo 'Запись успешно удалена';
            return true;
        } else {
            echo 'Ошибка удаления записи';
            return false;
        }
    }

    /**
     * @param $date_birth
     * @return int
     * преобразование даты рождения в возраст (полных лет)
     */
    static function fetch_age($date_birth): int
    {
        $age = intval((time() - $date_birth) / (365 * 24 * 60 * 60));
        return $age;
    }

    /**
     * @param $gender
     * @return string
     * преобразование пола из двоичной системы в текстовую
     */
    static function fetch_gender($gender): string
    {
        if ($gender == '0') {
            $string_gender = 'муж';
        }
        if ($gender == '1') {
            $string_gender = 'жен';
        }
        return $string_gender;
    }

    /**
     * @return object
     */
    static function connect(): object
    {
        $connect = mysqli_connect('localhost', 'root', '', 'User');
        if (!$connect) {
            die('Ошибка подключения');
        }
        return $connect;
    }

    /**
     * Метод получения объекта "нового" класса с "улучшенными" полями age и gender,
     * остальные свойства повторяют текущий класс.
     * Может принимать один или два параметра.
     * Если параметра два - первым принимается дата рождения.
     */
    public function find_format_human(): object
    {
        $a = new stdClass();
        $a->id = $this->id;
        $a->name = $this->name;
        $a->surname = $this->surname;
        if (func_num_args() == 1) {
            if ($b = strlen(func_get_arg('0')) == 1) {
                $a->gender_string = self::fetch_gender($b);
                $a->date_birth = $this->date_birth;
            }
            if ($b = strlen(func_get_arg('0')) > 1) {
                $a->age = self::fetch_age($b);
                $a->gender = $this->gender;
            }
        }
        if (func_num_args() == 2) {
            $a->age = self::fetch_age(func_get_arg(0));
            $a->gender_string = self::fetch_gender(func_get_arg(1));
        }
        $a->city_birth = $this->city_birth;

        return (object)$a;
    }

    /**
     * @param $name
     * @param $surname
     * @param $date_birth
     * @param $gender
     * @param $city_birth
     * @return void
     *
     */
    static function validate($name, $surname, $date_birth, $gender, $city_birth): void
    {
        if ($name !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $name)) {
                die('Неверные символы имени, попробуйте еще раз');
            }
        }
        if ($surname !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $surname)) {
                die('Неверные символы фамилии, попробуйте еще раз');
            }
        }
        if ($date_birth !== null) {
            if (!preg_match("/^[1-9]+$/i", $date_birth)) {
                die('Неверные символы даты рождения, попробуйте еще раз');
            }
        }
        if ($gender !== null) {
            if (!preg_match("/^[0-1]+$/i", $gender)) {
                die('Неверные указан пол, попробуйте еще раз');
            }
        }
        if ($city_birth !== null) {
            if (!preg_match("/^[a-zA-Zа-яА-Я]+$/i", $city_birth)) {
                die('Неверные символы города рождения, попробуйте еще раз');
            }
        }
    }
}