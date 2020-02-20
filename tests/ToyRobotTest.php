<?php
/**
 * Created by PhpStorm.
 * User: Kamepalli
 * Date: 2020-02-20
 * Time: 11:50
 */

use PHPUnit\Framework\TestCase;
use src\ToyRobotBoard\ToyRobotBoard;


class ToyRobotTest extends TestCase
{

    public function testBasic()
    {
        $this->assertTrue(true);
    }

    public function testIsBoardInitialised()
    {
        $toyRobotBoard = new ToyRobotBoard();
        $toyRobotBoard->setXYPaths(5,5);
        $testArray = [
            'PLACE 1', 2
        ];

        try
        {
            $toyRobotBoard->placeRobot($testArray);
        }
        catch (\Exception $e)
        {
            $this->assertEquals($e->getMessage(), 'Board is not initialised');
        }
    }

    public function testRobotIsPlaced()
    {
        $toyRobotBoard = new ToyRobotBoard();
        $toyRobotBoard->setXYPaths(5,5);
        $testArray = [
            'PLACE 1', '2', 'EAST'
        ];

        $toyRobotBoard->placeRobot($testArray);
        $this->assertEquals($toyRobotBoard->reportRobot(), '1 2 EAST');
    }

    public function testRobotCanTurn()
    {
        $toyRobotBoard = new ToyRobotBoard();
        $toyRobotBoard->setXYPaths(5,5);
        $testArray = [
            'PLACE 1', '2', 'EAST'
        ];

        $toyRobotBoard->placeRobot($testArray);
        $toyRobotBoard->turnRobot('LEFT');
        $this->assertEquals($toyRobotBoard->reportRobot(), '1 2 NORTH');
    }

    public function testRobotTakeMove()
    {
        $toyRobotBoard = new ToyRobotBoard();
        $toyRobotBoard->setXYPaths(5,5);
        $testArray = [
            'PLACE 1', '2', 'EAST'
        ];

        $toyRobotBoard->placeRobot($testArray);
        $toyRobotBoard->moveRobot();
        $this->assertEquals($toyRobotBoard->reportRobot(), '2 2 EAST');
    }

    public function testRobotResult()
    {
        $toyRobotBoard = new ToyRobotBoard();
        $toyRobotBoard->setXYPaths(5,5);
        $testArray = [
            'PLACE 1', '2', 'EAST'
        ];

        $toyRobotBoard->placeRobot($testArray);
        $toyRobotBoard->moveRobot();
        $this->assertEquals($toyRobotBoard->reportRobot(), '2 2 EAST');
    }
}
