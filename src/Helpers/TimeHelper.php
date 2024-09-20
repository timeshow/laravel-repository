<?php
namespace TimeShow\Repository\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * get today's start time
     * @return string
     */
    public static function getTodayStartTime() : string
    {
        return Carbon::today();
    }

    /**
     * get tomorrow's start time
     * @return string
     */
    public static function getTomorrowStartTime() : string
    {
        return Carbon::tomorrow();
    }

    /**
     * get yesterday's start time
     * @return string
     */
    public static function getYesterdayStartTime() : string
    {
        return Carbon::yesterday();
    }

    /**
     * get the start time of this year
     * @return string
     */
    public static function getYearStartTime() : string
    {
        return Carbon::now()->startOfYear()->toDateTimeString();
    }

    /**
     * obtain the start time of previous years
     * @param int $year
     * @return string
     */
    public static function getSubYearStartTime(int $year = 1) : string
    {
        return Carbon::now()->startOfYear()->subYear($year)->toDateTimeString();
    }

    /**
     * get the current date
     * @return string
     */
    public static function getCurrentDate() : string
    {
        return Carbon::now()->format('Y-m-d');
    }

    /**
     * obtain the date range for the current month
     * @return array
     */
    public static function getCurrentMonthRange() : array
    {
        $time = time();
        $start_time = date('Y-m-01', $time);
        $end_time = date('Y-m-t', $time);

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * format time
     * @param null $time
     * @return string
     */
    public static function getCurrentTime($time = null) : string
    {
        if ($time){
            return date('Y-m-d H:i:s', $time);
        }else{
            return Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    /**
     * get timestamp 13 digit
     * @return float|int
     */
    public static function getCurrentTimestamp() : float|int
    {
        return time() * 1000;
    }

    /**
     * timestamp conversion
     * @param int $time
     * @return string
     */
    public static function convertTime(int $time = 0) : string
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     * timestamp conversion
     * @param int $timestamp
     * @return string
     */
    public static function convertTimestamp(int $timestamp = 0) : string
    {
        return date("Y-m-d H:i:s", $timestamp / 1000);
    }

    /**
     * obtain the time interval for the current day
     * @return float[]|int[]
     */
    public static function getToday() : array
    {
        // set the time zone to China Standard Time (GMT+8)
        date_default_timezone_set('Asia/Shanghai');

        $start_timestamp = strtotime('today') * 1000;
        $end_timestamp = self::getTimestamp();

        return [
            'start_timestamp' => $start_timestamp,
            'end_timestamp' => $end_timestamp
        ];
    }

    /**
     * obtain the time interval of the day
     * @return array
     */
    public static function getTodayRange() : array
    {
        $today = Carbon::today();

        $start_time = $today->format('Y-m-d H:i:s');
        $end_time = $today->endOfDay()->format('Y-m-d H:i:s');
        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * obtain timestamps for yesterday's start and end times
     * @return float[]|int[]
     */
    public static function getYesterday() : array
    {
        // get yesterday's date
        $yesterday = strtotime('-1 day');

        // get yesterday's start timestamp（00:00:00）
        $yesterday_start_timestamp = strtotime('midnight', $yesterday);

        // get yesterday's end timestamp（23:59:59）
        $yesterday_end_timestamp = strtotime('tomorrow', $yesterday_start_timestamp) - 1;

        return [
            'start_timestamp' => $yesterday_start_timestamp * 1000,
            'end_timestamp' => $yesterday_end_timestamp * 1000
        ];
    }

    /**
     * obtain the time interval of the yesterday
     * @return array
     */
    public static function getYesterdayRange() : array
    {
        // 获取昨天的日期
        $yesterday = strtotime('-1 day');

        // 获取昨天的开始时间戳（00:00:00）
        $yesterday_start_timestamp = strtotime('midnight', $yesterday);

        // 获取昨天的结束时间戳（23:59:59）
        $yesterday_end_timestamp = strtotime('tomorrow', $yesterday_start_timestamp) - 1;

        $start_time = date('Y-m-d H:i:s', $yesterday_start_timestamp);
        $end_time = date('Y-m-d H:i:s', $yesterday_end_timestamp);
        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the current month
     * @return float[]|int[]
     */
    public static function getMonth() : array
    {
        // get current date
        $currentDate = Carbon::now();

        // get the start timestamp of this month
        $month_start_timestamp = $currentDate->startOfMonth()->startOfDay()->timestamp;

        // get the end timestamp of this month
        $month_end_timestamp = $currentDate->endOfMonth()->endOfDay()->timestamp;

        // convert timestamp to a 13 bit integer (in milliseconds)
        return [
            'start_timestamp' => $month_start_timestamp * 1000,
            'end_timestamp' => $month_end_timestamp * 1000
        ];
    }

    /**
     * get the time interval of the current month
     * @return array
     */
    public static function getMonthRange() : array
    {
        // get current date
        $currentDate = Carbon::now();

        // get the start timestamp of this month
        $month_start_timestamp = $currentDate->startOfMonth()->startOfDay()->timestamp;

        // get the end timestamp of this month
        $month_end_timestamp = $currentDate->endOfMonth()->endOfDay()->timestamp;

        $start_time = date('Y-m-d H:i:s', $month_start_timestamp);
        $end_time = date('Y-m-d H:i:s', $month_end_timestamp);
        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the current week's date
     * @return array
     */
    public static function getCurrentWeek() : array
    {
        $start_time = Carbon::now()->startOfWeek()->getTimestamp();
        $end_time = Carbon::now()->endOfWeek()->getTimestamp();

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the date range for the current week
     * @return array
     */
    public static function getCurrentWeekRange() : array
    {
        $start_time = Carbon::now()->startOfWeek()->toDateTimeString();
        $end_time = Carbon::now()->endOfWeek()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the previous few days
     * @param int $day
     * @return array
     */
    public static function getSubDay(int $day = 7) : array
    {
        $start_time = Carbon::today()->subDays($day)->getTimestamp();
        $end_time = Carbon::today()->getTimestamp();

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the date range of the previous few days
     * @param int $day
     * @return array
     */
    public static function getSubDayRange(int $day = 7) : array
    {
        $start_time = Carbon::today()->subDays($day)->toDateTimeString();
        $end_time = Carbon::today()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the current day's time
     * @param int $day
     * @return array
     */
    public static function getCurrentSubDay(int $day = 7) : array
    {
        $start_time = Carbon::now()->subDays($day)->getTimestamp();
        $end_time = Carbon::now()->getTimestamp();

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the date range of the current time in a few days
     * @param int $day
     * @return array
     */
    public static function getCurrentSubDayRange(int $day = 7) : array
    {
        $start_time = Carbon::now()->subDays($day)->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * retrieve timestamps of dates from previous years
     * @param int $year
     * @return array
     */
    public static function getSubYear(int $year = 1) : array
    {
        $start_time = Carbon::now()->startOfYear()->subYear()->getTimestamp();
        $end_time = Carbon::now()->endOfYear()->subYear()->getTimestamp();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * obtain the date range of previous years
     * @param int $year
     * @return array
     */
    public static function getSubYearRange(int $year = 1) : array
    {
        $start_time = Carbon::now()->startOfYear()->subYear($year)->toDateTimeString();
        $end_time = Carbon::now()->endOfYear()->subYear($year)->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

}
