<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'functions.php';

$projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'taskName' => 'Собеседование в IT-компании',
        'deadline' => '01.06.2018',
        'category' => 'Работа',
        'isDone' => false
    ],
    [
        'taskName' => 'Выполнить тестовое задание',
        'deadline' => '25.05.2018',
        'category' => 'Работа',
        'isDone' => false
    ],
    [
        'taskName' => 'Сделать задание первого раздела',
        'deadline' => '21.04.2018',
        'category' => 'Учеба',
        'isDone' => true
    ],
    [
        'taskName' => 'Встреча с другом',
        'deadline' => '22.04.2018',
        'category' => 'Входящие',
        'isDone' => false
    ],
    [
        'taskName' => 'Купить корм для кота',
        'deadline' => '—',
        'category' => 'Домашние дела',
        'isDone' => false
    ],
    [
        'taskName' => 'Заказать пиццу',
        'deadline' => '—',
        'category' => 'Домашние дела',
        'isDone' => false
    ]
];

$mainContent = renderTemplate('templates/index.php', $tasks);

$templateData = [
    'indexTitle' => 'Дела в порядке',
    'username' => 'Константин',
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks
];

echo renderTemplate('templates/layout.php', $templateData);
