<?php
require 'functions.php';
require 'init.php';

$link = connectToDb();

if (!$link) {
    print renderTemplate('templates/error.php', ['error' => mysqli_connect_error()]);
    exit;
}

require 'notify.php';

mysqli_set_charset($link, 'utf8');

$users = fetchData($link, 'SELECT id, email, name, password, userpic FROM users');
$projects = ['Все'];
$tasks = [];

session_start();
$userId = $_SESSION['user']['id'] ?? 0;

require_once 'db-queries.php';


// Validation
$errors = [];
$required = ['taskName', 'projectName', 'email', 'password', 'name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $taskName = $_POST['taskName'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $formName = $_POST['form_name'];

    $errors = checkForEmptyFields($required);

    switch ($formName) {
        /////////////////////
        /// 'ADD TASK' FORM
        /// /////////////////
        case 'add_task':
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
                $taskName = parseUserInput($taskName);

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
            break;

        /////////////////////
        /// 'ADD PROJECT' FORM
        /// /////////////////
        case 'add_project':
            if (count($errors)) {
                $_GET['add_project'] = 1;
            } else {
                $projectName = parseUserInput($_POST['projectName']);

                if (insertData($link, 'projects', [
                    'name' => $projectName,
                    'created_by' => $userId
                ])) {
                    header('Location: /');
                } else {
                    print renderTemplate('templates/error.php', ['error' => mysqli_error($link)]);
                }
            }
            break;

        /////////////////////
        /// 'SIGN IN' FORM
        /// /////////////////
        case 'sign_in':
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
            break;

        /////////////////////
        /// 'SIGN UP' FORM
        /// /////////////////
        case 'sign_up':
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Введите корректный email: например, test@test.com.';
            }

            if (!empty($email) && searchUserByEmail($email, $users)) {
                $errors['email'] = 'Пользователь с таким электронным адресом уже зарегистрирован.';
            }

            if (!empty($password) && strlen($password) < 8 ||
                !empty($password) && !preg_match('#[0-9]+#', $password) ||
                !empty($password) && !preg_match('#[a-zA-Z]+#', $password)) {
                $errors['password'] = 'Надежный пароль должен состоять из не менее 8 символов, содержать как минимум одну букву и одну цифру.';
            }

            if (count($errors)) {
                $_GET['sign_up'] = 1;
            } else {
                $username = parseUserInput($username);

                if (insertData($link, 'users', [
                    'created_on' => date('Y-m-d H:i:s'),
                    'email' => $email,
                    'name' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ])) {
                    header('Location: /?sign_in&registration_success');
                } else {
                    print renderTemplate('templates/error.php', ['error' => mysqli_error($link)]);
                }
            }
            break;

        default:
            exit;
    }
}


// Sign out
if (isset($_GET['sign_out'])) {
    unset($_SESSION['user']);
    header('Location: /');
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
