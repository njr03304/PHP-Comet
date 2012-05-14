<?php
	namespace njr03304\Comet;
	
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 */
	class Comet
	{
		private $sleep_time;
		private $poll_time;
		private $poll_callbacks = array();
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @param $poll_time 
 * @param $sleep_time 
 * 
 * @return 
 * 
 */
		public function __construct($poll_time = 28, $sleep_time = 500000)
		{
			$this->set_poll_time($poll_time);
			$this->set_sleep_time($sleep_time);
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @param $seconds 
 * 
 * @return 
 * 
 */
		public function set_poll_time($seconds)
		{
			if (!is_int($seconds)) {
				throw new \InvalidArgumentException('Poll time must be an INT.');
			}
			if ($seconds <= 0) {
				throw new \InvalidArgumentException('Poll time must be greater than 0 seconds.');
			}
			$this->poll_time = $seconds;
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @param $microseconds 
 * 
 * @return 
 * 
 */
		public function set_sleep_time($microseconds)
		{
			if (!is_int($microseconds)) {
				throw new \InvalidArgumentException('Sleep time must be an INT.');
			}
			if ($microseconds < 0) {
				throw new \InvalidArgumentException('Sleep time must be greater than or equal to 0 microseconds.');
			}
			$this->sleep_time = $microseconds;
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @return 
 * 
 */
		public function get_poll_time()
		{
			return $this->poll_time;
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @return 
 * 
 */
		public function get_sleep_time()
		{
			return $this->sleep_time;
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @param $observer 
 * 
 * @return 
 * 
 */
		public function attach_callback($observer)
		{
			$this->poll_callbacks []= $observer;
		}
		
/**
 * @brief 
 * 
 * 
 * @author nrussell <nrussell@clarkinc.biz>
 * 
 * @return 
 * 
 */
		public function poll()
		{
			$timer = time();
			while (time() - $timer < $this->get_poll_time()) {
				foreach ($this->poll_callbacks as $callback) {
					if ($resp = call_user_func($callback)) {
						return $resp;
					}
				}
				usleep($this->get_sleep_time());
			}
			return FALSE;
		}
	}
