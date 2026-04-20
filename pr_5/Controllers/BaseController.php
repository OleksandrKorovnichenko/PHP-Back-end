<?php

namespace guestbook\Controllers;

use PDO;
use PDOException;

abstract class BaseController
{
    abstract public function execute(): void;

    abstract public function renderView(array $arguments = []): void;

    protected function getDbConnection(array $config): PDO
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $config['host'],
                $config['name']
            );

            return new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            die('Помилка підключення до БД: ' . $exception->getMessage());
        }
    }

    protected function render(string $viewName, array $arguments = []): void
    {
        extract($arguments, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $viewName . '.php';
    }
}
