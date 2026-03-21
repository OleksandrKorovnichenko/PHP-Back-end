<?php

$to      = "o.v.korovnichenko@student.khai.edu";
$topic   = "Практична робота №1";
$from    = "sashass20062727@gmail.com";

$student = "Korovnichenko Olexandr Volodymyrovych";
$group   = "539";

$body = implode("\n", [
    "Студент: $student",
    "Група: $group",
    "Тестування email розсилки.",
]);

mail($to, $topic, $body, "From: $from");

echo "Done";
