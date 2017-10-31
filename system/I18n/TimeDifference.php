<?php namespace CodeIgniter\I18n;

use DateTime;

class TimeDifference
{
	/**
	 * The timestamp of the "current" time.
	 *
	 * @var int
	 */
	protected $currentTime;

	/**
	 * The timestamp to compare the current time to.
	 *
	 * @var int
	 */
	protected $testTime;

	/**
	 * @var float
	 */
	protected $eras = 0;

	/**
	 * @var float
	 */
	protected $years = 0;
	/**
	 * @var float
	 */
	protected $months = 0;
	/**
	 * @var int
	 */
	protected $weeks = 0;
	/**
	 * @var int
	 */
	protected $days = 0;
	/**
	 * @var int
	 */
	protected $hours = 0;
	/**
	 * @var int
	 */
	protected $minutes = 0;
	/**
	 * @var int
	 */
	protected $seconds = 0;

	/**
	 * Difference in seconds.
	 * @var int
	 */
	protected $difference;

	/**
	 * Note: both parameters are required to be in the same timezone. No timezone
	 * shifting is done internally.
	 *
	 * @param DateTime $currentTime
	 * @param DateTime $testTime
	 */
	public function __construct(DateTime $currentTime, DateTime $testTime)
	{
		$this->difference = $currentTime->getTimestamp() - $testTime->getTimestamp();

		$current = \IntlCalendar::fromDateTime($currentTime->format('Y-m-d H:i:s'));
		$time    = \IntlCalendar::fromDateTime($testTime->format('Y-m-d H:i:s'))
		                ->getTime();

		$this->currentTime = $current;
		$this->testTime    = $time;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the number of years of difference between the two.
	 *
	 * @return mixed
	 */
	public function getYears(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / YEAR;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_YEAR);
	}

	/**
	 * Returns the number of months difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getMonths(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / MONTH;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_MONTH);
	}

	/**
	 * Returns the number of weeks difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getWeeks(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / WEEK;
		}

		$time = clone($this->currentTime);
		return (int)($time->fieldDifference($this->testTime, \IntlCalendar::FIELD_DAY_OF_YEAR) / 7);
	}

	/**
	 * Returns the number of days difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getDays(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / DAY;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_DAY_OF_YEAR);
	}

	/**
	 * Returns the number of hours difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getHours(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / HOUR;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_HOUR_OF_DAY);
	}

	/**
	 * Returns the number of minutes difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getMinutes(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference / MINUTE;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_MINUTE);
	}

	/**
	 * Returns the number of seconds difference between the two dates.
	 *
	 * @return mixed
	 */
	public function getSeconds(bool $raw=false)
	{
		if ($raw)
		{
			return $this->difference;
		}

		$time = clone($this->currentTime);
		return $time->fieldDifference($this->testTime, \IntlCalendar::FIELD_SECOND);
	}

	public function humanize(): string
	{
		$current = clone($this->currentTime);

		$years = $current->fieldDifference($this->testTime, \IntlCalendar::FIELD_YEAR);
		$months = $current->fieldDifference($this->testTime, \IntlCalendar::FIELD_MONTH);
		$days = $current->fieldDifference($this->testTime, \IntlCalendar::FIELD_DAY_OF_YEAR);
		$hours = $current->fieldDifference($this->testTime, \IntlCalendar::FIELD_HOUR_OF_DAY);
		$minutes = $current->fieldDifference($this->testTime, \IntlCalendar::FIELD_MINUTE);

		$phrase = null;

		if ($years !== 0)
		{
			$phrase = lang('Time.years', [abs($years)]);
			$before = $years < 0;
		}
		else if ($months !== 0)
		{
			$phrase = lang('Time.months', [abs($months)]);
			$before = $months < 0;
		}
		else if ($days !== 0 && (abs($days) >= 7))
		{
			$weeks = ceil($days / 7);
			$phrase = lang('Time.weeks', [abs($weeks)]);
			$before = $days < 0;
		}
		else if ($days !== 0)
		{
			$phrase = lang('Time.days', [abs($days)]);
			$before = $days < 0;
		}
		else if ($hours !== 0)
		{
			$phrase = lang('Time.hours', [abs($hours)]);
			$before = $hours < 0;
		}
		else if ($minutes !== 0)
		{
			$phrase = lang('Time.minutes', [abs($minutes)]);
			$before = $minutes < 0;
		}
		else
		{
			return lang('Time.now');
		}

		return $before
			? lang('Time.ago', [$phrase])
			: lang('Time.inFuture', [$phrase]);
	}

	/**
	 * Allow property-like access to our calucalated values.
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		$name = ucfirst(strtolower($name));
		$method = "get{$name}";

		if (method_exists($this, $method))
		{
			return $this->{$method}($name);
		}
	}
}
