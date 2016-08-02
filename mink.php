<?php

require __DIR__.'/vendor/autoload.php';

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;

//$driver = new GoutteDriver();
$driver = new Selenium2Driver();

$session = new Session($driver);

$session->start();

$session->visit('http://jurassicpark.wikia.com');

//echo "Status code: ". $session->getStatusCode() . "\n";
echo "Current URL: ". $session->getCurrentUrl() . "\n";

$page = $session->getPage();
echo "First 75 chars: ".substr($page->getText() , 0, 75) . "\n";

$header = $page->find('css', '.WikiHeader .WikiNav h2');
echo "The wiki nav text is: ".$header->getText()."\n";

$session->stop();