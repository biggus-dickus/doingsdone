<?php
require_once 'mysql_helper.php';

/**
 * Processes and renders the file contents via output buffer.
 * @param string $path
 * @param array $data
 * @return string
 */
function renderTemplate($path, $data) {
    $templateString = '';

    if (file_exists($path) && is_array($data)) {
        ob_start();
        require $path;
        $templateString = ob_get_clean();
    }

    return $templateString;
}


/**
 * Calculates the total number of tasks in the project.
 * @param array $tasksArr
 * @param int $cat
 * @return int
 */
function getTasksAmount($tasksArr, $cat) {
    $total = 0;

    foreach ($tasksArr as $task) {
        if ($task['project_id'] === $cat || $cat === 0) {
            $total++;
        }
    }

    return $total;
}


/**
 * Checks the form for empty fields and performs all required validation.
 * @param array $requiredFields
 * @param array $usersArr
 * @return array
 */
function validateForm($requiredFields, $usersArr) {
    $errMessages = [];
    $mustNotBeEmpty = array_filter($requiredFields, function($item) {
        return $item !== 'deadline';
    });

    foreach ($_POST as $key => $value) {
        if (in_array($key, $mustNotBeEmpty) && $value === '') {
            $errMessages[$key] = 'Заполните это поле.';
        }

        if ($key === 'deadline') {
            $deadline = $value;

            if(!empty($deadline)) {
                if (!validateDate($deadline)) {
                    $errMessages[$key] = 'Введите дату в формате ДД.ММ.ГГГГ.';
                    break;
                }

                if (validateDate($deadline) && isOverdue($deadline)) {
                    $errMessages[$key] = 'Дата выполнения задачи должна быть больше текущей даты.';
                    break;
                }
            }
        }

        if ($key === 'password' && $_POST['form_name'] === 'sign_in') {
            $password = $value;
            $errMessages[$key] = 'Неверный электронный адрес или пароль. Попробуйте еще раз.';

            if (!empty($password) && $user = searchUserByEmail($_POST['email'], $usersArr)) {
                if (password_verify($password, $user['password'])) {
                    $errMessages = [];
                }
            }

            break;
        }

        if ($key === 'password' && $_POST['form_name'] === 'sign_up') {
            $password = $value;

            if (!empty($password) && strlen($password) < 8 ||
                !empty($password) && !preg_match('#[0-9]+#', $password) ||
                !empty($password) && !preg_match('#[a-zA-Z]+#', $password)) {
                $errMessages[$key] = 'Надежный пароль должен состоять из не менее 8 символов, содержать как минимум одну букву и одну цифру.';
            }
        }

        if ($key === 'email' && $_POST['form_name'] === 'sign_up') {
            $email = $value;

            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errMessages[$key] = 'Введите корректный email: например, test@test.com.';
            }

            if (!empty($email) && searchUserByEmail($email, $usersArr)) {
                $errMessages[$key] = 'Пользователь с таким электронным адресом уже зарегистрирован.';
            }
        }
    }

    return $errMessages;
}


/**
 * Checks if the date string matches the required format.
 * @param string $date
 * @param string $format
 * @return bool
 */
function validateDate($date, $format = 'd.m.Y') {
    $correctDate = DateTime::createFromFormat($format, $date);

    return $correctDate && $correctDate->format($format) === $date;
}


/**
 * Checks if the supplied date is overdue.
 * @param string $date
 * @return bool
 */
function isOverdue($date) {
    if(strtotime($date) - time() > 0) {
        return false;
    }

    return true;
}


/**
 * Combs user's input and strips it from potentially malicious fragments.
 * @param string $data
 * @return string
 */
function parseUserInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);

    return $data;
}


/**
 * Searches if a certain email exists in the array and returns it.
 * @param string $email
 * @param array $users
 * @return string
 */
function searchUserByEmail($email, $users) {
    $result = null;

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $result = $user;
            break;
        }
    }

    return $result;
}


/**
 * Reads data from MySQL and returns it as a two-dimensional array.
 * @param mysqli $link
 * @param string $sql
 * @param array $data
 * @return array
 *
 * @example fetchData($link, 'SELECT * FROM users WHERE id = ?', [123]);
 */
function fetchData($link, $sql, $data = []) {
    $result = [];

    $stmt = db_get_prepare_stmt($link, $sql, $data);

    if($stmt && mysqli_stmt_execute($stmt)) {
        $result = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    }

    return $result;
}


/**
 * Inserts data (prepared statement) into MySQL table. Returns the primary key of the last successful entry or false if operation failed.
 * @param mysqli $link
 * @param string $table
 * @param array $data
 * @return bool || int
 *
 * @example insertData($link, 'users', ['email' => 'abc@bca.ru', 'name' => 'neo777']);
 */
function insertData($link, $table, $data) {
    $result = false;

    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = array_fill(0, count($values), '?');

    $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';

    $stmt = db_get_prepare_stmt($link, $sql, $values);

    if ($stmt && mysqli_stmt_execute($stmt)) {
        $result = mysqli_insert_id($link);
    }

    if(!$result) {
        print renderTemplate('templates/error.php', ['error' => mysqli_error($link)]);
        exit;
    }

    return $result;
}


/**
 * Performs the rest of db operations (delete and update).
 * @param mysqli $link
 * @param string $sql
 * @param array $data
 * @return bool
 *
 * @example execQuery($link, 'DELETE FROM users WHERE id = ?', [123]);
 */
function execQuery($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);

    $result = ($stmt && mysqli_stmt_execute($stmt));

    return $result;
}
