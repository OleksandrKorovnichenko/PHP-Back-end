<?php

namespace guestbook\Controllers;

class GuestbookController extends BaseController
{
    public function execute(): void
    {
        $config = require __DIR__ . '/../config.php';
        $pdo = $this->getDbConnection($config);
        $errors = [];
        $infoMessage = '';
        $formData = [
            'email' => '',
            'name' => '',
            'text' => '',
        ];

        if (!empty($_POST)) {
            $formData['email'] = trim($_POST['email'] ?? '');
            $formData['name'] = trim($_POST['name'] ?? '');
            $formData['text'] = trim($_POST['text'] ?? '');

            if (empty($formData['email'])) {
                $errors['email'] = 'Email є обов\'язковим полем.';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Невірний формат email.';
            }

            if (empty($formData['name'])) {
                $errors['name'] = 'Ім\'я є обов\'язковим полем.';
            } elseif (mb_strlen($formData['name']) < 2) {
                $errors['name'] = 'Ім\'я має містити мінімум 2 символи.';
            }

            if (empty($formData['text'])) {
                $errors['text'] = 'Текст коментаря є обов\'язковим полем.';
            } elseif (mb_strlen($formData['text']) < 5) {
                $errors['text'] = 'Коментар має містити мінімум 5 символів.';
            }

            if (empty($errors)) {
                $statement = $pdo->prepare(
                    'INSERT INTO comments (email, name, text, date) VALUES (:email, :name, :text, NOW())'
                );
                $statement->execute([
                    ':email' => $formData['email'],
                    ':name' => $formData['name'],
                    ':text' => $formData['text'],
                ]);

                header('Location: /guestbook?success=1');
                exit;
            }
        }

        if (isset($_GET['success'])) {
            $infoMessage = 'Коментар успішно додано!';
        }

        $statement = $pdo->query(
            'SELECT email, name, text, DATE_FORMAT(date, "%d.%m.%Y %H:%i") AS date FROM comments ORDER BY id DESC'
        );
        $comments = $statement->fetchAll();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perPage = 5;

        $this->renderView([
            'errors' => $errors,
            'infoMessage' => $infoMessage,
            'comments' => $comments,
            'page' => $page,
            'perPage' => $perPage,
            'formData' => $formData,
        ]);
    }

    public function renderView(array $arguments = []): void
    {
        $this->render('guestbook', $arguments);
    }
}
