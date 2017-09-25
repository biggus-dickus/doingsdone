<?php
require_once 'vendor/autoload.php';
require 'functions.php';
require 'init.php';

$link = connectToDb();

if (!$link) {
    print mysqli_connect_error();
    exit;
}


$users = fetchData($link,
    'SELECT u.id, u.email, u.name FROM users u '.
    'JOIN tasks t ON u.id = t.user_id '.
    'WHERE t.completed_on IS NULL AND t.deadline BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR) '.
    'GROUP BY u.id');

if ($users) {
    $transport = new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl');
    $transport->setUsername('doingsdone@mail.ru');
    $transport->setPassword('rds7BgcL');

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message();
    $message->setFrom(['doingsdone@mail.ru' => 'doingsdone']);
    $message->setSubject('Уведомление от сервиса «Дела в порядке»');

    foreach ($users as $user) {
        $tasks = fetchData($link,
            'SELECT name, TIME_FORMAT(deadline, "%H:%i") AS task_time FROM tasks ' .
            'WHERE completed_on IS NULL AND deadline BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR)' .
            'AND user_id = ?', [$user['id']]);

        $message_text = "Ув., " . $user['name'] . ". У вас запланирована задача:\n";

        foreach ($tasks as $task) {
            $message_text .= "\t" . $task['name'] . " на " . $task['task_time'] . "\n";
        }

        $message->setTo([$user['email'] => $user['name']]);
        $message->setBody($message_text, 'text/plain');
        $mailer->send($message);
    }
}
