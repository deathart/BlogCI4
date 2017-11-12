<?php namespace CodeIgniter\I18n;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2017 British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2014-2017 British Columbia Institute of Technology (https://bcit.ca/)
 * @license    https://opensource.org/licenses/MIT    MIT License
 * @link       https://codeigniter.com
 * @since      Version 3.0.0
 * @filesource
 */
use Locale;
use DateTime;
use DateInterval;
use DateTimeZone;
use IntlDateFormatter;

/**
 * Class Time
 *
 * A localized date/time package inspired
 * by Nesbot/Carbon and CakePHP/Chronos.
 *
 * Requires the intl PHP extension.
 *
 * @package CodeIgniter\I18n
 */
class Time extends DateTime
{

	/**
	 * @var string
	 */
	protected $timezone;

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * Format to use when displaying datetime through __toString
	 * @var int
	 */
	protected $toStringFormat = 'yyyy-MM-dd HH:mm:ss';

	/**
	 * Used to check time string to determine if it is relative time or not....
	 *
	 * @var string
	 */
	protected static $relativePattern = '/this|next|last|tomorrow|yesterday|midnight|today|[+-]|first|last|ago/i';

	/**
	 * @var \CodeIgniter\I18n\Time
	 */
	protected static $testNow;

	//--------------------------------------------------------------------
	// Constructors
	//--------------------------------------------------------------------

	public function __construct(string $time = null, $timezone = null, string $locale = null)
	{
		// If no locale was provided, grab it from Locale (set by IncomingRequest for web requests)
		$this->locale = ! empty($locale) ? $locale : Locale::getDefault();

		// If a test instance has been provided, use it instead.
		if (is_null($time) && static::$testNow instanceof Time)
		{
			if (empty($timezone))
			{
				$timezone = static::$testNow->getTimezone();
			}

			$time = static::$testNow->toDateTimeString();
		}

		$timezone = ! empty($timezone) ? $timezone : date_default_timezone_get();
		$this->timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);

		if ( ! empty($time))
		{
			// If the time string was a relative string (i.e. 'next Tuesday')
			// then we need to adjust the time going in so that we have a current
			// timezone to work with.
			if (is_string($time) && static::hasRelativeKeywords($time))
			{
				$instance = new DateTime('now', $this->timezone);
				$instance->modify($time);

				$time = $instance->format('Y-m-d H:i:s');
			}
		}

		return parent::__construct($time, $this->timezone);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new Time instance with the timezone set.
	 *
	 * @param string|DateTimeZone|null $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function now($timezone = null, string $locale = null)
	{
		return new Time(null, $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new Time instance while parsing a datetime string.
	 *
	 * Example:
	 *  $time = Time::parse('first day of December 2008');
	 *
	 * @param string      $datetime
	 * @param string|null $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function parse(string $datetime, $timezone = null, string $locale = null)
	{
		return new Time($datetime, $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Return a new time with the time set to midnight.
	 *
	 * @param null        $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function today($timezone = null, string $locale = null)
	{
		return new Time(date('Y-m-d 00:00:00'), $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an instance set to midnight yesterday morning.
	 *
	 * @param null        $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function yesterday($timezone = null, string $locale = null)
	{
		return new Time(date('Y-m-d 00:00:00', strtotime('-1 day')), $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an instance set to midnight tomorrow morning.
	 *
	 * @param null        $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function tomorrow($timezone = null, string $locale = null)
	{
		return new Time(date('Y-m-d 00:00:00', strtotime('+1 day')), $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new instance based on the year, month and day. If any of those three
	 * are left empty, will default to the current value.
	 *
	 * @param int|null $year
	 * @param int|null $month
	 * @param int|null $day
	 * @param          $timezone
	 * @param string   $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function createFromDate(int $year = null, int $month = null, int $day = null, $timezone = null, string $locale = null)
	{
		return static::create($year, $month, $day, null, null, null, $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new instance with the date set to today, and the time set to the values passed in.
	 *
	 * @param int|null    $hour
	 * @param int|null    $minutes
	 * @param int|null    $seconds
	 * @param null        $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function createFromTime(int $hour = null, int $minutes = null, int $seconds = null, $timezone = null, string $locale = null)
	{
		return static::create(null, null, null, $hour, $minutes, $seconds, $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new instance with the date time values individually set.
	 *
	 * @param int|null    $year
	 * @param int|null    $month
	 * @param int|null    $day
	 * @param int|null    $hour
	 * @param int|null    $minutes
	 * @param int|null    $seconds
	 * @param null        $timezone
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function create(int $year = null, int $month = null, int $day = null, int $hour = null, int $minutes = null, int $seconds = null, $timezone = null, string $locale = null)
	{
		$year = is_null($year) ? date('Y') : $year;
		$month = is_null($month) ? date('m') : $month;
		$day = is_null($day) ? date('d') : $day;
		$hour = empty($hour) ? 0 : $hour;
		$minutes = empty($minutes) ? 0 : $minutes;
		$seconds = empty($seconds) ? 0 : $seconds;

		return new Time(date("Y-m-d H:i:s", strtotime("{$year}-{$month}-{$day} {$hour}:{$minutes}:{$seconds}")), $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Provides a replacement for DateTime's own createFromFormat function, that provides
	 * more flexible timeZone handling
	 *
	 * @param string       $format
	 * @param string       $datetime
	 * @param DateTimeZone $timeZone
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function createFromFormat($format, $datetime, $timeZone = null)
	{
		$date = parent::createFromFormat($format, $datetime);

		return new Time($date->format('Y-m-d H:i:s'), $timeZone);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a new instance with the datetime set based on the provided UNIX timestamp.
	 *
	 * @param int          $timestamp
	 * @param DateTimeZone $timeZone
	 * @param string|null  $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function createFromTimestamp(int $timestamp, $timeZone = null, string $locale = null)
	{
		return new Time(date('Y-m-d H:i:s', $timestamp), $timeZone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Takes an instance of DateTime and returns an instance of Time with it's same values.
	 *
	 * @param \DateTime   $dateTime
	 * @param string|null $locale
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public static function instance(\DateTime $dateTime, string $locale = null)
	{
		$date = $dateTime->format('Y-m-d H:i:s');
		$timezone = $dateTime->getTimezone();

		return new Time($date, $timezone, $locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Converts the current instance to a mutable DateTime object.
	 *
	 * @return \DateTime
	 */
	public function toDateTime()
	{
		$dateTime = new \DateTime(null, $this->getTimezone());
		$dateTime->setTimestamp(parent::getTimestamp());

		return $dateTime;
	}

	//--------------------------------------------------------------------
	// For Testing
	//--------------------------------------------------------------------

	/**
	 * Creates an instance of Time that will be returned during testing
	 * when calling 'Time::now' instead of the current time.
	 *
	 * @param \CodeIgniter\I18n\Time|string $datetime
	 * @param null                          $timezone
	 * @param string|null                   $locale
	 */
	public static function setTestNow($datetime = null, $timezone = null, string $locale = null)
	{
		// Reset the test instance
		if (is_null($datetime))
		{
			static::$testNow = null;
			return;
		}

		// Convert to a Time instance
		if (is_string($datetime))
		{
			$datetime = new Time($datetime, $timezone, $locale);
		}
		else if ($datetime instanceof \DateTime && ! $datetime instanceof Time)
		{
			$datetime = new Time($datetime->format('Y-m-d H:i:s'), $timezone);
		}

		static::$testNow = $datetime;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns whether we have a testNow instance saved.
	 *
	 * @return bool
	 */
	public static function hasTestNow(): bool
	{
		return ! is_null(static::$testNow);
	}

	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	// Getters
	//--------------------------------------------------------------------

	/**
	 * Returns the localized Year
	 *
	 * @return string
	 */
	public function getYear()
	{
		return $this->toLocalizedString('Y');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the localized Month
	 *
	 * @return string
	 */
	public function getMonth()
	{
		return $this->toLocalizedString('M');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the localized day of the month.
	 *
	 * @return string
	 */
	public function getDay()
	{
		return $this->toLocalizedString('d');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the localized hour (in 24-hour format).
	 *
	 * @return string
	 */
	public function getHour()
	{
		return $this->toLocalizedString('H');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the localized minutes in the hour.
	 *
	 * @return string
	 */
	public function getMinute()
	{
		return $this->toLocalizedString('m');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the localized seconds
	 *
	 * @return string
	 */
	public function getSecond()
	{
		return $this->toLocalizedString('s');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the index of the day of the week
	 *
	 * @return string
	 */
	public function getDayOfWeek()
	{
		return $this->toLocalizedString('c');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the index of the day of the year
	 *
	 * @return string
	 */
	public function getDayOfYear()
	{
		return $this->toLocalizedString('D');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the index of the week in the month
	 *
	 * @return string
	 */
	public function getWeekOfMonth()
	{
		return $this->toLocalizedString('W');
	}

	//--------------------------------------------------------------------

	/**
	 * Return the index of the week in the year
	 *
	 * @return string
	 */
	public function getWeekOfYear()
	{
		return $this->toLocalizedString('w');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the age in years from the "current" date and 'now'
	 */
	public function getAge()
	{
		$now = Time::now()->getTimestamp();
		$time = $this->getTimestamp();

		if ( ! $now >= $time)
			return 0;

		return date('Y', $now) - date('Y', $time);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the number of the current quarter for the year.
	 *
	 * @return string
	 */
	public function getQuarter()
	{
		return $this->toLocalizedString('Q');
	}

	//--------------------------------------------------------------------

	/**
	 * Are we in daylight savings time currently?
	 */
	public function getDst()
	{
		$start = strtotime('-1 year', $this->getTimestamp());
		$end = strtotime('+2 year', $start);

		$transitions = $this->timezone->getTransitions($start, $end);

		foreach ($transitions as $transition)
		{
			if ($transition['time'] > $this->format('U'))
			{
				return (bool) $transition['isdst'];
			}
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns boolean whether the passed timezone is the same as
	 * the local timezone.
	 */
	public function getLocal()
	{
		$local = date_default_timezone_get();

		return $local === $this->timezone->getName();
	}

	//--------------------------------------------------------------------

	/**
	 * Returns boolean whether object is in UTC.
	 */
	public function getUtc()
	{
		return $this->getOffset() === 0;
	}

	/**
	 * Reeturns the name of the current timezone.
	 *
	 * @return string
	 */
	public function getTimezoneName()
	{
		return $this->timezone->getName();
	}

	//--------------------------------------------------------------------
	// Setters
	//--------------------------------------------------------------------

	/**
	 * Sets the current year for this instance.
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setYear($value)
	{
		return $this->setValue('year', $value);
	}

	/**
	 * Sets the month of the year.
	 *
	 * @todo check max months in current calendar (localized)
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setMonth($value)
	{
		if ($value < 0 || $value > 12)
		{
			throw new \InvalidArgumentException(lang('time.invalidMonth'));
		}

		if (is_string($value) && ! is_numeric($value))
		{
			$value = date('m', strtotime("{$value} 1 2017"));
		}

		return $this->setValue('month', $value);
	}

	/**
	 * Sets the day of the month.
	 *
	 * @todo check max days in month (localized)
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setDay($value)
	{
		if ($value < 0 || $value > 31)
		{
			throw new \InvalidArgumentException(lang('time.invalidDay'));
		}

		return $this->setValue('day', $value);
	}

	/**
	 * Sets the hour of the day (24 hour cycle)
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setHour($value)
	{
		if ($value < 0 || $value > 23)
		{
			throw new \InvalidArgumentException(lang('time.invalidHours'));
		}

		return $this->setValue('hour', $value);
	}

	/**
	 * Sets the minute of the hour
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setMinute($value)
	{
		if ($value < 0 || $value > 59)
		{
			throw new \InvalidArgumentException(lang('time.invalidMinutes'));
		}

		return $this->setValue('minute', $value);
	}

	/**
	 * Sets the second of the minute.
	 *
	 * @param $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setSecond($value)
	{
		if ($value < 0 || $value > 59)
		{
			throw new \InvalidArgumentException(lang('time.invalidSeconds'));
		}

		return $this->setValue('second', $value);
	}

	/**
	 * Helper method to do the heavy lifting of the 'setX' methods.
	 *
	 * @param string $name
	 * @param        $value
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	protected function setValue(string $name, $value)
	{
		list($year, $month, $day, $hour, $minute, $second) = explode('-', $this->format('Y-n-j-G-i-s'));
		$$name = $value;

		return Time::create($year, $month, $day, $hour, $minute, $second, $this->getTimezoneName(), $this->locale);
	}

	/**
	 * Returns a new instance with the revised timezone.
	 *
	 * @param \DateTimeZone $timezone
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setTimezone($timezone)
	{
		return Time::parse($this->toDateTimeString(), $timezone, $this->locale);
	}

	/**
	 * Returns a new instance with the date set to the new timestamp.
	 *
	 * @param int $timestamp
	 *
	 * @return \CodeIgniter\I18n\Time
	 */
	public function setTimestamp($timestamp)
	{
		$time = date('Y-m-d H:i:s', $timestamp);

		return Time::parse($time, $this->timezone, $this->locale);
	}

	//--------------------------------------------------------------------
	// Add/Subtract
	//--------------------------------------------------------------------

	/**
	 * Returns a new Time instance with $seconds added to the time.
	 *
	 * @param int $seconds
	 *
	 * @return static
	 */
	public function addSeconds(int $seconds)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$seconds} seconds"));
	}

	/**
	 * Returns a new Time instance with $minutes added to the time.
	 *
	 * @param int $minutes
	 *
	 * @return static
	 */
	public function addMinutes(int $minutes)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$minutes} minutes"));
	}

	/**
	 * Returns a new Time instance with $hours added to the time.
	 *
	 * @param int $hours
	 *
	 * @return static
	 */
	public function addHours(int $hours)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$hours} hours"));
	}

	/**
	 * Returns a new Time instance with $days added to the time.
	 *
	 * @param int $days
	 *
	 * @return static
	 */
	public function addDays(int $days)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$days} days"));
	}

	/**
	 * Returns a new Time instance with $months added to the time.
	 *
	 * @param int $months
	 *
	 * @return static
	 */
	public function addMonths(int $months)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$months} months"));
	}

	/**
	 * Returns a new Time instance with $years added to the time.
	 *
	 * @param int $years
	 *
	 * @return static
	 */
	public function addYears(int $years)
	{
		$time = clone($this);

		return $time->add(DateInterval::createFromDateString("{$years} years"));
	}

	/**
	 * Returns a new Time instance with $seconds subtracted from the time.
	 *
	 * @param int $seconds
	 *
	 * @return static
	 */
	public function subSeconds(int $seconds)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$seconds} seconds"));
	}

	/**
	 * Returns a new Time instance with $minutes subtracted from the time.
	 *
	 * @param int $minutes
	 *
	 * @return static
	 */
	public function subMinutes(int $minutes)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$minutes} minutes"));
	}

	/**
	 * Returns a new Time instance with $hours subtracted from the time.
	 *
	 * @param int $hours
	 *
	 * @return static
	 */
	public function subHours(int $hours)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$hours} hours"));
	}

	/**
	 * Returns a new Time instance with $days subtracted from the time.
	 *
	 * @param int $days
	 *
	 * @return static
	 */
	public function subDays(int $days)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$days} days"));
	}

	/**
	 * Returns a new Time instance with $months subtracted from the time.
	 *
	 * @param int $months
	 *
	 * @return static
	 */
	public function subMonths(int $months)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$months} months"));
	}

	/**
	 * Returns a new Time instance with $hours subtracted from the time.
	 *
	 * @param int $years
	 *
	 * @return static
	 */
	public function subYears(int $years)
	{
		$time = clone($this);

		return $time->sub(DateInterval::createFromDateString("{$years} years"));
	}

	//--------------------------------------------------------------------
	// Formatters
	//--------------------------------------------------------------------

	/**
	 * Returns the localized value of the date in the format 'Y-m-d H:i:s'
	 */
	public function toDateTimeString()
	{
		return $this->toLocalizedString('yyyy-MM-dd HH:mm:ss');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a localized version of the date in Y-m-d format.
	 *
	 * @return string
	 */
	public function toDateString()
	{
		return $this->toLocalizedString('yyyy-MM-dd');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a localized version of the date in nicer date format:
	 *
	 *  i.e. Apr 1, 2017
	 *
	 * @return string
	 */
	public function toFormattedDateString()
	{
		return $this->toLocalizedString('MMM d, yyyy');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a localized version of the time in nicer date format:
	 *
	 *  i.e. 13:20:33
	 *
	 * @return string
	 */
	public function toTimeString()
	{
		return $this->toLocalizedString('HH:mm:ss');
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the localized value of this instance in $format.
	 *
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function toLocalizedString(string $format = null)
	{
		$format = is_null($format) ? $this->toStringFormat : $format;

		return IntlDateFormatter::formatObject($this->toDateTime(), $format, $this->locale);
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Comparison
	//--------------------------------------------------------------------

	/**
	 * Determines if the datetime passed in is equal to the current instance.
	 * Equal in this case means that they represent the same moment in time,
	 * and are not required to be in the same timezone, as both times are
	 * converted to UTC and compared that way.
	 *
	 * @param Time|DateTime|string $testTime
	 * @param string|null          $timezone
	 *
	 * @return bool
	 */
	public function equals($testTime, string $timezone = null): bool
	{
		$testTime = $this->getUTCObject($testTime, $timezone);

		$ourTime = $this->toDateTime()
		                ->setTimezone(new DateTimeZone('UTC'))
		                ->format('Y-m-d H:i:s');

		return $testTime->format('Y-m-d H:i:s') === $ourTime;
	}

	//--------------------------------------------------------------------

	/**
	 * Ensures that the times are identical, taking timezone into account.
	 *
	 * @param Time|DateTime|string  $testTime
	 * @param string|null           $timezone
	 *
	 * @return bool
	 */
	public function sameAs($testTime, string $timezone = null): bool
	{
		if ($testTime instanceof DateTime)
		{
			$testTime = $testTime->format('Y-m-d H:i:s');
		}
		else if (is_string($testTime))
		{
			$timezone = $timezone ?: $this->timezone;
			$timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
			$testTime = new DateTime($testTime, $timezone);
			$testTime = $testTime->format('Y-m-d H:i:s');
		}

		$ourTime = $this->toDateTimeString();

		return $testTime === $ourTime;
	}

	//--------------------------------------------------------------------

	/**
	 * Determines if the current instance's time is before $testTime,
	 * after converting to UTC.
	 *
	 * @param             $testTime
	 * @param string|null $timezone
	 *
	 * @return bool
	 */
	public function isBefore($testTime, string $timezone = null): bool
	{
		$testTime = $this->getUTCObject($testTime, $timezone)->getTimestamp();
		$ourTime = $this->getTimestamp();

		return $ourTime < $testTime;
	}

	//--------------------------------------------------------------------

	/**
	 * Determines if the current instance's time is after $testTime,
	 * after converting in UTC.
	 *
	 * @param             $testTime
	 * @param string|null $timezone
	 *
	 * @return bool
	 */
	public function isAfter($testTime, string $timezone = null): bool
	{
		$testTime = $this->getUTCObject($testTime, $timezone)->getTimestamp();
		$ourTime = $this->getTimestamp();

		return $ourTime > $testTime;
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Differences
	//--------------------------------------------------------------------

	/**
	 * Returns a text string that is easily readable that describes
	 * how long ago, or how long from now, a date is, like:
	 *
	 *  - 3 weeks ago
	 *  - in 4 days
	 *  - 6 hours ago
	 */
	public function humanize()
	{
		$now  = \IntlCalendar::fromDateTime(Time::now($this->timezone)->toDateTimeString());
		$time = $this->getCalendar()->getTime();

		$years = $now->fieldDifference($time, \IntlCalendar::FIELD_YEAR);
		$months = $now->fieldDifference($time, \IntlCalendar::FIELD_MONTH);
		$days = $now->fieldDifference($time, \IntlCalendar::FIELD_DAY_OF_YEAR);
		$hours = $now->fieldDifference($time, \IntlCalendar::FIELD_HOUR_OF_DAY);
		$minutes = $now->fieldDifference($time, \IntlCalendar::FIELD_MINUTE);

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
			$before = $days < 0;

			// Yesterday/Tommorrow special cases
			if (abs($days) === 1)
			{
				return $before
					? lang('Time.yesterday')
					: lang('Time.tomorrow');
			}

			$phrase = lang('Time.days', [abs($days)]);
		}
		else if ($hours !== 0)
		{
			// Display the actual time instead of a regular phrase.
			return $this->format('g:i a');
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
	 * @param             $testTime
	 * @param string|null $timezone
	 *
	 * @return \CodeIgniter\I18n\TimeDifference
	 */
	public function difference($testTime, string $timezone = null)
	{
		$testTime = $this->getUTCObject($testTime, $timezone);
		$ourTime = $this->getUTCObject($this);

		return new TimeDifference($ourTime, $testTime);
	}


	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Returns a Time instance with the timezone converted to UTC.
	 *
	 * @param             $time
	 * @param string|null $timezone
	 *
	 * @return \DateTime|static
	 */
	public function getUTCObject($time, string $timezone=null)
	{
		if ($time instanceof Time)
		{
			$time = $time->toDateTime()
			             ->setTimezone(new DateTimeZone('UTC'));
		}
		else if ($time instanceof \DateTime)
		{
			$time = $time->setTimezone(new DateTimeZone('UTC'));
		}
		else if (is_string($time))
		{
			$timezone = $timezone ?: $this->timezone;
			$timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
			$time     = new DateTime($time, $timezone);
			$time     = $time->setTimezone(new DateTimeZone('UTC'));
		}

		return $time;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the IntlCalendar object used for this object,
	 * taking into account the locale, date, etc.
	 *
	 * Primarily used internally to provide the difference and comparison functions,
	 * but available for public consumption if they need it.
	 *
	 * @return \IntlCalendar
	 */
	public function getCalendar()
	{
		return \IntlCalendar::fromDateTime($this->toDateTimeString());
	}

	//--------------------------------------------------------------------

	/**
	 * Check a time string to see if it includes a relative date (like 'next Tuesday').
	 *
	 * @param string $time
	 *
	 * @return bool
	 */
	protected static function hasRelativeKeywords(string $time): bool
	{
		// skip common format with a '-' in it
		if (preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1)
		{
			return preg_match(static::$relativePattern, $time) > 0;
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Outputs a short format version of the datetime.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return IntlDateFormatter::formatObject($this->toDateTime(), $this->toStringFormat, $this->locale);
	}

	//--------------------------------------------------------------------

	/**
	 * Allow for property-type access to any getX method...
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		$method = 'get' . ucfirst($name);

		if (method_exists($this, $method))
		{
			return $this->$method();
		}
	}

	//--------------------------------------------------------------------

	public function __set($name, $value)
	{
		$method = 'set' . ucfirst($name);

		if (method_exists($this, $method))
		{
			return $this->$method($value);
		}
	}

}
