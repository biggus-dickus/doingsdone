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

$templateData = [
    'indexTitle' => 'Дела в порядке',
    'username' => 'Константин',
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks
];

print renderTemplate('templates/layout.php', $templateData);
