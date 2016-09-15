<?php

namespace AppBundle\Tests\Model;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Model\TimeMachine;

class TimeMachineTest extends \PHPUnit_Framework_TestCase
{

	public function testFirstDayOfWeek()
	{
		$date = new \Datetime('2016-08-30 00:00:00');
		$firstDayOfWeek = TimeMachine::firstDayOfWeek($date);
		$this->assertEquals('2016-08-29', $firstDayOfWeek->format('Y-m-d'));
	}

	public function testLastDayOfWeek()
	{
		$date = new \Datetime('2016-08-30 00:00:00');
		$lastDayOfWeek = TimeMachine::lastDayOfWeek($date);
		//var_dump($lastDayOfWeek);
		$this->assertEquals('2016-09-04', $lastDayOfWeek->format('Y-m-d'));
	}

	public function testSetTimeToZero()
	{
		$date = new \Datetime('now');
		$setTimeToZero = TimeMachine::setTimeToZero($date);
		$this->assertEquals('00:00:00', $setTimeToZero->format('H:i:s'));
	}

	public function testGetTimeOfDatetime()
	{
		$date = new \Datetime('2016-08-30 12:10:00');
		$getTimeOfDatetime = TimeMachine::getTimeOfDatetime($date);
		$this->assertEquals('12:10', $getTimeOfDatetime);
	}

	public function testGetDayOfDatetime()
	{
		$date = new \Datetime('2016-08-30 12:10:00');
		$getDayOfDatetime = TimeMachine::getDayOfDatetime($date);
		$this->assertEquals('30', $getDayOfDatetime);
	}

	/**
   * @param array with hour and minutes
   * @param string with hour and minutes round to 15
   *
   * @dataProvider providerTestRoundMinutes
   */
	public function testRoundMinutes(Array $time, $expectedResult)
	{
		$hr = TimeMachine::roundMinutes($time); // obtengo la hora inicio redondeada
		$this->assertEquals($hr, $expectedResult);
	}

	public function providerTestRoundMinutes()
  {
      return array(
          array(array('minutes'=>'00', 'hours'=>'00'), '00:00'),
          array(array('minutes'=>'02', 'hours'=>'00'), '00:15'),
          array(array('minutes'=>'16', 'hours'=>'10'), '10:30'),
          array(array('minutes'=>'31', 'hours'=>'10'), '10:45'),
          array(array('minutes'=>'59', 'hours'=>'10'), '11:00'),
          array(array('minutes'=>'46', 'hours'=>'10'), '11:00'),
          array(array('minutes'=>'59', 'hours'=>'23'), '00:00'),
      );
  }

  /**
   * @param array with startingHour and finishHOur
   * @param array with expected results
   *
   * @dataProvider providertestarrayIntervaloHoras
   */
	public function testarrayIntervaloHoras(Array $time, Array $expectedResult)
	{
		$intervaloHoras = TimeMachine::arrayIntervaloHoras($time['startingHour'], $time['finishHour'], $time['interval']);
		$numEltos = count($intervaloHoras);
		$numEltos1 = count($expectedResult);

		$this->assertEquals($numEltos, $numEltos1);

		for ($i=0; $i < $numEltos; $i++) {
			$this->assertEquals($expectedResult[$i], $intervaloHoras[$i]);
		}
	}

	public function providertestarrayIntervaloHoras()
  {
      return
        array(
      		array(
          	array(
          		'startingHour'=>'10:00',
          		'finishHour'=>'11:00',
          		'interval' => '15'
          	),
          	array('10:00', '10:15', '10:30', '10:45'),
          ),
	      	array(
	         	array(
	         		'startingHour'=>'08:15',
	         		'finishHour'=>'09:30',
	         		'interval' => '15'
	         	),
	         	array('08:15', '08:30', '08:45', '09:00', '09:15'),
	        ),
	        array(
	         	array(
	         		'startingHour'=>'10:15',
	         		'finishHour'=>'12:16',
	         		'interval' => '15'
	         	),
	         	array('10:15', '10:30', '10:45', '11:00', '11:15','11:30','11:45','12:00','12:15'),
	        ),
        );
  }

}