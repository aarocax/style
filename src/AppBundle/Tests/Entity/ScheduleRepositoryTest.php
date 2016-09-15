<?php

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Model\TimeMachine;

class ScheduleRepositoryTest extends KernelTestCase
{

	/**
   * @var \Doctrine\ORM\EntityManager
   */
  private $em;

  /**
   * {@inheritDoc}
   */
  protected function setUp()
  {
      self::bootKernel();

      $this->em = static::$kernel->getContainer()
          ->get('doctrine')
          ->getManager();
  }

  public function testGetSchedulesPresentDay()
  {
  		$date = new \Datetime;
  		$date = TimeMachine::setTimeToZero($date);
        $schedulesPresentDay = $this->em->getRepository('AppBundle:Schedule')->getSchedulesPresentDay($date, 1);
      $this->assertCount(0, $schedulesPresentDay);
  }

  public function testGetSchedulesOfDay()
  {
  		$date = new \Datetime;
  		$date = TimeMachine::setTimeToZero($date);
        $schedulesOfDay = $this->em->getRepository('AppBundle:Schedule')->getSchedulesOfDay();
      $this->assertCount(0, $schedulesOfDay);
  }

  /**
   * {@inheritDoc}
   */
  protected function tearDown()
  {
      parent::tearDown();

      $this->em->close();
      $this->em = null; // avoid memory leaks
  }

}