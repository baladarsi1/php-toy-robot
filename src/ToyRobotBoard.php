<?php
/**
 * Created by PhpStorm.
 * User: Kamepalli
 * Date: 2020-02-20
 * Time: 11:30 PM
 */

namespace src\ToyRobotBoard;

use Exception;

/**
 * Class ToyRobotBoard
 * @package src\ToyRobotBoard
 */
class ToyRobotBoard
{

    /**
     * @var
     */
    protected $xMax;
    /**
     * @var
     */
    protected $yMax;
    /**
     * @var
     */
    protected $xOutput;
    /**
     * @var
     */
    protected $yOutput;
    /**
     * @var
     */
    protected $directionOutput;
    /**
     * @var bool
     */
    protected $initialiseBoard = false;
    /**
     * @var bool
     */
    protected $turningClockDirection = false;

    /**
     * @param $xMax
     * @param $yMax
     */
    public function setXYPaths($xMax, $yMax)
    {
        if (empty($xMax) || empty($yMax)) {
            return;
        }

        $this->xMax = $xMax;
        $this->yMax = $yMax;
    }

    /**
     * @param $source
     * @throws Exception
     */
    public function executeInput($source)
    {
        $handle = fopen($source, 'r');

        while ($line = fgets($handle)) {
            $inputLineToArray = explode(',', $line);
            if (!empty($inputLineToArray) && count($inputLineToArray) > 1) {
                $this->placeRobot($inputLineToArray);
            } elseif (count($inputLineToArray) == 1 && in_array(trim($line), $this->allowedCommands())) {
                $this->executeCommand(trim($line));
            } else {
                $this->isBoardInitialised();
            }
        }

        fclose($handle);
    }

    /**
     * @param $inputLineToArray
     * @throws Exception
     */
    public function placeRobot($inputLineToArray)
    {
        if (empty($inputLineToArray [2]) || !in_array(trim($inputLineToArray [2]), $this->turningFacesList())) {
            $this->isBoardInitialised();
        }

        $getXPosition = explode(' ', $inputLineToArray[0]);

        if (!empty($getXPosition) && count($getXPosition) == 2) {
            if ($getXPosition[0] == trim($this->allowedCommands() [0])
                && is_integer((int)$getXPosition[1])
                && is_integer((int)$inputLineToArray[1])) {
                $this->xOutput = $getXPosition[1];
                $this->yOutput = $inputLineToArray[1];
                $this->directionOutput = trim($inputLineToArray[2]);

                if ($this->checkForValidBoundariesAndDirection()) {
                    $this->initialiseBoard = true;
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function turningFacesList() : array
    {
        return [
            'NORTH', 'EAST', 'SOUTH', 'WEST'
        ];
    }

    /**
     * @throws Exception
     */
    public function isBoardInitialised()
    {
        if (!$this->initialiseBoard) {
            throw new Exception($this->errorsList() ['init']);
        }
    }

    /**
     * @return array
     */
    protected function errorsList() : array
    {
        return [
            'init' => "Board is not initialised",
            'command' => "Not a valid command"
        ];
    }

    /**
     * @return array
     */
    protected function allowedCommands(): array
    {
        return [
            'PLACE', 'MOVE', 'REPORT', 'LEFT', 'RIGHT'
        ];
    }

    /**
     * @return mixed
     */
    public function checkForValidBoundariesAndDirection()
    {
        return ($this->xOutput < 0
            || $this->xOutput > $this->xMax
            || $this->yOutput < 0
            || $this->yOutput > $this->yMax
            || !in_array(trim($this->directionOutput), $this->turningFacesList()))
            ? false : true;
    }

    /**
     * @param $line
     * @throws Exception
     */
    public function executeCommand($line)
    {
        $this->isValidCommand($line);

        switch ($line) {
            case "MOVE" :
                $this->moveRobot();
                break;
            case "LEFT" :
                $this->turningClockDirection = false;
                $this->turnRobot();
                break;
            case "RIGHT" :
                $this->turningClockDirection = true;
                $this->turnRobot();
                break;
            case "REPORT" :
                $this->reportRobot();
                break;
            case "DEFAULT" :
                $this->isValidCommand();
        }
    }

    /**
     * @param null $command
     * @throws Exception
     */
    protected function isValidCommand($command = null)
    {
        if (empty($command) || !in_array($command, $this->allowedCommands())) {
            throw new Exception($this->errorsList() ['command']);
        }
    }

    /**
     * @throws Exception
     */
    public function moveRobot()
    {
        $this->isBoardInitialised();
        switch (trim($this->directionOutput)) {
            case "NORTH" :
                ++$this->yOutput;
                break;
            case "EAST" :
                ++$this->xOutput;
                break;
            case "WEST" :
                --$this->xOutput;
                break;
            case "SOUTH" :
                --$this->yOutput;
                break;
            default:
                throw new Exception($this->errorsList() ['init']);
        }
    }

    /**
     * @throws Exception
     */
    public function turnRobot()
    {
        $this->isBoardInitialised();
        $turningFacesList = ($this->turningClockDirection) ? $this->turningFacesList() : array_reverse($this->turningFacesList());
        $getCurrentFaceIndex = array_search($this->directionOutput, $turningFacesList);
        $this->directionOutput = ($getCurrentFaceIndex == count($turningFacesList) - 1) ? $turningFacesList [0] : $turningFacesList [$getCurrentFaceIndex + 1];
    }

    /**
     * @return string
     */
    public function reportRobot()
    {
        return $this->xOutput . ' ' . $this->yOutput . ' ' . $this->directionOutput;
    }
}
