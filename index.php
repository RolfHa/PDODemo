<?php
include 'config.php';
include 'class/Db.php';
include 'class/Person.php';

echo '<pre>';
print_r(Person::getById(1));
echo '</pre>';

echo '<pre>';
print_r(Person::getAll());
echo '</pre>';

Person::getById(2)->delete();

echo '<pre>';
print_r(Person::getAll());
echo '</pre>';
