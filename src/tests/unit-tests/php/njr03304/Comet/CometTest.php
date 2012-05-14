<?php
	namespace njr03304\Comet;
	
	class CometTest extends \PHPUnit_Framework_TestCase
	{
		public function setUp()
		{
			$this->poll = new Comet();
		}
		
		public function tearDown()
		{
			unset($this->poll);
		}
		
		public function testPoll()
		{
			$this->poll->attach_callback(
					function()
					{
						return TRUE;
					}
				);
				
			$response = $this->poll->poll();
			$this->assertTrue($response);
		}
		
		public function testLoopingPoll()
		{
			$this->poll->attach_callback(
					function()
					{
						static $k = 0;
						if($k >= 3)
						{
							return TRUE;
						}
						echo 'A';
						$k++;
						return FALSE;
					}
				);
				
			ob_start();
			$response = $this->poll->poll();
			$str = ob_get_clean();
			$this->assertTrue($str == 'AAA');
		}
		
		public function testValidSleepTime()
		{
			$this->poll->set_sleep_time(20000);
		}
		
		public function testValidPollTime()
		{
			$this->poll->set_poll_time(12);
		}
		
		public function testInvalidSleepTime()
		{
			$this->setExpectedException('InvalidArgumentException', 'Sleep time must be an INT.');
			$this->poll->set_sleep_time(20000.32);
		}
		
		public function testInvalidSleepTime2()
		{
			$this->setExpectedException('InvalidArgumentException', 'Sleep time must be greater than or equal to 0 microseconds.');
			$this->poll->set_sleep_time(-1);
		}
		
		public function testInvalidPollTime()
		{
			$this->setExpectedException('InvalidArgumentException', 'Poll time must be an INT.');
			$this->poll->set_poll_time(12.4);
		}
		
		public function testInvalidPollTime2()
		{
			$this->setExpectedException('InvalidArgumentException', 'Poll time must be greater than 0 seconds.');
			$this->poll->set_poll_time(-1);
		}
		
		public function testNoChangePoll()
		{
			$this->poll->set_poll_time(1);
			$this->poll->set_sleep_time(0);
			$this->poll->attach_callback(
					function()
					{
						return FALSE;
					}
				);
			$result = $this->poll->poll();
			$this->assertFalse($result);
		}
	}
