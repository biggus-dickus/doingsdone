<?php
// Get dynamic data
$showAll = (isset($_COOKIE['show_completed']) && $_COOKIE['show_completed']) ? '' : '&& completed_on IS NULL';

if (isset($_SESSION['user'])) {
    $tasks = fetchData($link, "SELECT id, name, deadline, project_id, completed_on from tasks WHERE created_by = ? && is_deleted = ? $showAll ORDER BY created_on DESC", [$userId, 0]);

    $dbProjects = fetchData($link, 'SELECT name from projects WHERE created_by = ?', [$userId]);

    foreach ($dbProjects as $key => $val) {
        array_push($projects, $val['name']);
    }


    // Delete task
    if (isset($_GET['delete_task'])) {
        execQuery($link, 'UPDATE tasks SET is_deleted = 1 WHERE id = ?', [$_GET['delete_task']]);
        header('Location: /');
    }


    // Complete task
    if (isset($_GET['complete_task'])) {
        execQuery($link, 'UPDATE tasks SET completed_on = NOW() WHERE id = ?', [$_GET['complete_task']]);
        header('Location: /');
    }


    // Filter tasks acc. to their deadlines
    if (isset($_GET['show_tasks'])) {
        $deadline = null;
        $queryDate = $_GET['show_tasks'];

        switch ($queryDate) {
            case 'tomorrow':
                $deadline = 'deadline IN (CURDATE() + INTERVAL 1 DAY)';
                break;

            case 'today':
                $deadline = 'DATE(deadline) = CURDATE()';
                break;

            case 'overdue':
                $deadline = 'deadline < NOW()';
                break;
        }

        $tasks = fetchData($link, "SELECT id, name, deadline, project_id, completed_on from tasks WHERE created_by = ? && is_deleted = ? && $deadline $showAll", [$userId, 0]);
    }
}
