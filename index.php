<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'functions.php';

$projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'taskName' => 'Собеседование в IT-компании',
        'deadline' => '01.06.2018',
        'project' => $projects[3],
        'isDone' => false
    ],
    [
        'taskName' => 'Выполнить тестовое задание',
        'deadline' => '25.05.2018',
        'project' => $projects[3],
        'isDone' => false
    ],
    [
        'taskName' => 'Сделать задание первого раздела',
        'deadline' => '21.04.2018',
        'project' => $projects[2],
        'isDone' => true
    ],
    [
        'taskName' => 'Встреча с другом',
        'deadline' => '22.04.2018',
        'project' => $projects[1],
        'isDone' => false
    ],
    [
        'taskName' => 'Купить корм для кота',
        'deadline' => '—',
        'project' => $projects[4],
        'isDone' => false
    ],
    [
        'taskName' => 'Заказать пиццу',
        'deadline' => '—',
        'project' => $projects[4],
        'isDone' => false
    ]
];

$mainContent = renderTemplate('templates/index.php', ['projects' => $projects, 'tasks' => $tasks]);


// Validation
$errors = [];
$required = ['taskName', 'project', 'deadline'];
$newTask = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deadline = $_POST['deadline'] ?? '';

    // Check for empty fields
    foreach ($_POST as $key => $value) {
        if (in_array($key, $required) && $value === '') {
            $errors[$key] = 'Заполните это поле.';
        }
    }

    // Check correct date format
    if (!empty($deadline) && !validateDate($deadline)) {
        $errors['deadline'] = 'Введите дату в формате ДД.ММ.ГГГГ.';
    }

    // Check if deadline is greater than the current date
    if (validateDate($deadline) && isOverdue($deadline)) {
        $errors['deadline'] = 'Дата выполнения задачи должна быть больше текущей даты.';
    }

    // Place the file to project root
    if (isset($_FILES['task-attachment'])) {
        $file_name = $_FILES['task-attachment']['name'];
        $file_path = __DIR__ . '/';

        move_uploaded_file($_FILES['task-attachment']['tmp_name'], $file_path . $file_name);
    }

    // Finally do smth useful
    if (count($errors)) {
        $_GET['add_task'] = 1;
    } else {
        foreach ($_POST as $key => $value) {
            $newTask[$key] = parseUserInput($value);
        }

        $newTask['isDone'] = false;
        array_unshift($tasks, $newTask);
    }
}

$templateData = [
    'indexTitle' => 'Дела в порядке',
//    'user' => ['username' => 'Вася'],
    'user' => [],
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks,
    'errors' => $errors
];

print renderTemplate('templates/layout.php', $templateData);
