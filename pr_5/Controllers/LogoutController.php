<?php

namespace guestbook\Controllers;

class LogoutController extends BaseController
{
    public function execute(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    public function renderView(array $arguments = []): void
    {
    }
}
