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
		 * Constructor.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @param $poll_time Int. Optional. A value to set the poll time to.
		 * @param $sleep_time Int. Optional. A value to set the sleep time to.
		 * 
		 * @return Void.
		 * 
		 */
		public function __construct($poll_time = 28, $sleep_time = 500000)
		{
			$this->set_poll_time($poll_time);
			$this->set_sleep_time($sleep_time);
		}
		
		/**
		 * @brief 
		 * Set the poll time for the service.
		 * 
		 * Poll time is the maximum amount of time that the connection is held open.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @param $seconds Int. The number of seconds to hold the connection open.
		 * 
		 * @return Void.
		 * 
		 * @throws InvalidArgumentException if $seconds is not an Int.
		 * @throws InvalidArgumentException if number of seconds is not greater than 0.
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
		 * Set the sleep time for the service.
		 * 
		 * Sleep time is the amount of time to wait in between each loop through the callbacks.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @param $microseconds Int the number of microseconds to sleep.
		 * 
		 * @return Void.
		 * 
		 * @throws InvalidArgumentException if microseconds is not an int.
		 * @throws InvalidArgumentException if microseconds is less than 0.
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
		 * Get the poll time.
		 * 
		 * Poll time is the number of seconds to hold the connection open.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @return Int. The poll time.
		 * 
		 */
		public function get_poll_time()
		{
			return $this->poll_time;
		}
		
		/**
		 * @brief 
		 * Get the sleep time.
		 * 
		 * Sleep time is the number of microseconds to sleep in between each loop through the callbacks.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @return Int. The sleep time.
		 * 
		 */
		public function get_sleep_time()
		{
			return $this->sleep_time;
		}
		
		/**
		 * @brief 
		 * Attach a callback that can be run with call_user_func().
		 * 
		 * Callbacks should check for some kind of change to happen outside the current request.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @param $observer Mixed. A method that can be called by call_user_func().
		 * 
		 * @return Void.
		 * 
		 */
		public function attach_callback($observer)
		{
			$this->poll_callbacks []= $observer;
		}
		
		/**
		 * @brief 
		 * Run the long poll.
		 * 
		 * Loops through the callbacks as long as there is time left in the poll timer.
		 * 
		 * @author nrussell <nrussell@clarkinc.biz>
		 * 
		 * @return Mixed. The value returned by a callback (as long as it evaluates to TRUE). Else, when loop is done, Bool FALSE.
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
