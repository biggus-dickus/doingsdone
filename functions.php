<?php

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
