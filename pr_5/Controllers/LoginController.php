<?php

namespace guestbook\Controllers;

class LoginController extends BaseController
{
    public function execute(): void
    {
        if (!empty($_SESSION['auth'])) {
            header('Location: /');
            exit;
        }

        $config = require __DIR__ . '/../config.php';
        $infoMessage = '';
        $errors = [];
        $email = '';

        if (!empty($_POST)) {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email)) {
                $errors['email'] = 'Email є обов\'язковим полем.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Невірний формат email.';
            }

            if (empty($password)) {
                $errors['password'] = 'Пароль є обов\'язковим полем.';
            }

            if (empty($errors)) {
                $pdo = $this->getDbConnection($config);
                $statement = $pdo->prepare('SELECT id FROM users WHERE email = :email AND password = :password LIMIT 1');
                $statement->execute([
                    ':email' => $email,
                    ':password' => $password,
                ]);
                $user = $statement->fetch();

                if ($user) {
                    $_SESSION['auth'] = true;
                    $_SESSION['email'] = $email;
                    header('Location: /');
                    exit;
                }

                $infoMessage = 'Такого користувача не існує. Перейдіть на сторінку реєстрації. <a href="/register">Сторінка реєстрації</a>';
            }
        }

        $this->renderView([
            'errors' => $errors,
            'email' => $email,
            'infoMessage' => $infoMessage,
        ]);
    }

    public function renderView(array $arguments = []): void
    {
        $this->render('login', $arguments);
    }
}
