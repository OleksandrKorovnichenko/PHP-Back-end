<?php

namespace guestbook\Controllers;

class HomeController extends BaseController
{
    public function execute(): void
    {
        $this->renderView();
    }

    public function renderView(array $arguments = []): void
    {
        $this->render('home', $arguments);
    }
}
