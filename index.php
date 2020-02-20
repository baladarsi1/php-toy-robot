<?php
/**
 * Created by PhpStorm.
 * User: bala
 * Date: 20/02/2020
 * Time: 11:29 PM
 */

require 'vendor/autoload.php';
require_once 'src/ToyRobotBoard.php';

$baseClass = new \src\ToyRobotBoard\ToyRobotBoard();
$baseClass->setXYPaths(5,5);

    try {
        $baseClass->executeInput('examples/example-c.txt');
        echo $baseClass->reportRobot() . "\n";
    }
    catch (\Exception $e)
    {
        print_r($e->getMessage());
    }

