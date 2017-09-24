<?php
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
