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
 * @param string $cat
 * @return int
 */
function getTasksAmount($tasksArr, $cat) {
    $total = 0;

    foreach ($tasksArr as $task) {
        if ($task['project'] === $cat || $cat === 'Все') {
            $total++;
        }
    }

    return $total;
}


/**
 * Checks the form for empty fields.
 * @param array $requiredFields
 * @return array
 */
function checkForEmptyFields($requiredFields) {
    $errMessages = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, $requiredFields) && $value === '') {
            $errMessages[$key] = 'Заполните это поле.';
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
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) == $date;
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

    return $result;
}


/**
 * Performs the rest of db operations (delete and update).
 * @param mysqli $link
 * @param string $sql
 * @param array $data
 * @return bool
 */
function execQuery($link, $sql, $data = []) {
    $result = false;

    $stmt = db_get_prepare_stmt($link, $sql, $data);

    if($stmt && mysqli_stmt_execute($stmt)) {
        $result = true;
    }

    return $result;
}
