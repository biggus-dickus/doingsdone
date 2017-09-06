<?php

/**
 * Process and render the file contents via output buffer.
 * @param {string} $path
 * @param {Array} $data
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
 * Calculate the total number of tasks in the project.
 * @param {Array} $tasksArr
 * @param {string} $cat
 * @return int
 */
function getTasksAmount($tasksArr, $cat) {
    $total = 0;

    foreach ($tasksArr as $task) {
        if ($task['category'] === $cat || $cat === 'Все') {
            $total++;
        }
    }

    return $total;
}
