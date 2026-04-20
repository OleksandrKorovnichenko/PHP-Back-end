<?php

namespace guestbook\Controllers;

class RegisterController extends BaseController
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
            } elseif (mb_strlen($password) < 6) {
                $errors['password'] = 'Пароль має містити мінімум 6 символів.';
            }

            if (empty($errors)) {
                $pdo = $this->getDbConnection($config);
                $checkStatement = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                $checkStatement->execute([':email' => $email]);
                $existingUser = $checkStatement->fetch();

                if ($existingUser) {
                    $infoMessage = 'Такий користувач уже існує! Перейдіть на сторінку входу. <a href="/login">Сторінка входу</a>';
                } else {
                    $insertStatement = $pdo->prepare(
                        'INSERT INTO users (email, password, date) VALUES (:email, :password, NOW())'
                    );
                    $insertStatement->execute([
                        ':email' => $email,
                        ':password' => $password,
                    ]);

                    header('Location: /login');
                    exit;
                }
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
        $this->render('register', $arguments);
    }
}
