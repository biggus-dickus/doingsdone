<?php
require 'functions.php';
require 'init.php';

$link = connectToDb();

if (!$link) {
    print renderTemplate('templates/error.php', ['error' => mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($link, 'utf8');

$users = fetchData($link, 'SELECT id, email, name, password FROM users');
$projects = ['Все'];
$tasks = [];

session_start();
$userId = $_SESSION['user']['id'] ?? 0;


// Get dynamic data
if (isset($_SESSION['user'])) {
    $tasks = fetchData($link, 'SELECT name, deadline, project_id, completed_on from tasks WHERE created_by = ? ORDER BY created_on DESC', [$userId]);

    $dbProjects = fetchData($link, 'SELECT name from projects WHERE created_by = ?', [$userId]);

    foreach ($dbProjects as $key => $val) {
        array_push($projects, $val['name']);
    }
}


// Validation
$errors = [];
$required = ['taskName', 'project', 'deadline', 'email', 'password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deadline = $_POST['deadline'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = checkForEmptyFields($required);

//////////////////////////////////
/// 'ADD TASK' FORM PROCESSING
/// /////////////////////////////
    if($_POST['form_name'] === 'add_task') {
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
            $taskName = parseUserInput($_POST['taskName']);

            if (insertData($link, 'tasks', [
                'project_id' => array_search($_POST['project'], $projects),
                'name' => $taskName,
                'created_by' => $userId,
                'created_on' => date('Y-m-d H:i:s'),
                'deadline' => date('Y-m-d H:i:s', strtotime($_POST['deadline'])),
                'attachment_url' => (isset($_FILES['task-attachment'])) ? __DIR__.'/'.$_FILES['task-attachment']['name'] : NULL
            ])) {
                header('Location: /');
            } else {
                print renderTemplate('templates/error.php', ['error' => mysqli_error($link)]);
            }
        }

//////////////////////////////////
/// 'SIGN IN' FORM PROCESSING
/// /////////////////////////////
    } else if($_POST['form_name'] === 'sign_in') {
        $errors['email'] = ' ';
        $errors['password'] = 'Неверный электронный адрес и/или пароль. Попробуйте еще раз.';

        if (!empty($password) && $user = searchUserByEmail($email, $users)) {
            if (password_verify($password, $user['password'])) {
                $errors = [];

                $_SESSION['user'] = $user;
                header('Location: /index.php');
            }
        }

        if (count($errors)) {
            $_GET['sign_in'] = 1;
        }
    }
}


// Sign out
if (isset($_GET['sign_out'])) {
    unset($_SESSION['user']);
    header('Location: /index.php');
}


// My little kooky dance
if (isset($_GET['show_completed'])) {
    setcookie('show_completed', $_GET['show_completed']);
    header('Location: /');
}


// Template data
$mainContent = renderTemplate('templates/index.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'showCompleted' => (isset($_COOKIE['show_completed']) && $_COOKIE['show_completed'])
]);

$templateData = [
    'indexTitle' => 'Дыра в порядке',
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks,
    'errors' => $errors
];

print renderTemplate('templates/layout.php', $templateData);
