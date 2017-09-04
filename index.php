<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'functions.php';

$projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'taskName' => 'Собеседование в IT-компании',
        'deadline' => '01.06.2018',
        'category' => $projects[3],
        'isDone' => false
    ],
    [
        'taskName' => 'Выполнить тестовое задание',
        'deadline' => '25.05.2018',
        'category' => $projects[3],
        'isDone' => false
    ],
    [
        'taskName' => 'Сделать задание первого раздела',
        'deadline' => '21.04.2018',
        'category' => $projects[2],
        'isDone' => true
    ],
    [
        'taskName' => 'Встреча с другом',
        'deadline' => '22.04.2018',
        'category' => $projects[1],
        'isDone' => false
    ],
    [
        'taskName' => 'Купить корм для кота',
        'deadline' => '—',
        'category' => $projects[4],
        'isDone' => false
    ],
    [
        'taskName' => 'Заказать пиццу',
        'deadline' => '—',
        'category' => $projects[4],
        'isDone' => false
    ]
];

$mainContent = renderTemplate('templates/index.php', [$projects, $tasks]);

// Validation
$errors = [];
$required = ['task-name', 'task-project', 'task-deadline'];
$rules = ['email' => 'validateEmail'];

function validateEmail($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function parseData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (in_array($key, $required) && $value === '') {
            $errors[] = $key;
//            $userData[$key] = $value;
//            break;
        }

//        if (in_array($key, $rules)) {
//            $result = call_user_func('validateEmail', $value);
//
//            if (!$result) {
//                $errors[] = $key;
//            }
//        }
    }

    if (count($errors)) {
        $_GET['add_task'] = 1;
    }
    else {
        $_POST = array();
        header('Location: index.php?success=true');
    }
}

$templateData = [
    'indexTitle' => 'Дела в порядке',
    'username' => 'Константин',
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks,
    'errors' => $errors
];

echo(var_dump($_POST));

print renderTemplate('templates/layout.php', $templateData);
