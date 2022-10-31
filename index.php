<?php

require_once 'classes/fake.php';

$jonas = new fake ;

echo('<br>');
echo('Cpr');
echo('<br>');
$jonas->setCpr();
echo($jonas->getCpr());
echo('<br>');

echo('<br>');
echo('setFullNameGender');
echo('<br>');
$jonas->setFullNameGender();
echo($jonas->getFullNameGender());

echo('<br>');
echo('setFullNameGenderDate');
echo('<br>');
$jonas->setFullNameGenderDate();
echo($jonas->getFullNameGenderDate());

echo('<br>');
echo('setCprFullNameGender');
echo('<br>');
$jonas->setCprFullNameGender();
echo($jonas->getCprFullNameGender());

echo('<br>');
echo('setCprFullNameGenderDate');
echo('<br>');
$jonas->setCprFullNameGenderDate();
echo($jonas->getCprFullNameGenderDate());

echo('<br>');
echo('setOneFullInformations');
echo('<br>');
$jonas->setOneFullInformations();
echo($jonas->getOneFullInformations());



echo('<br>');
echo('bulkFullInformations');
echo('<br>');
echo($jonas->bulkFullInformations(['infoNumber'=>2]));

