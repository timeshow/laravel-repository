<?php
namespace TimeShow\Repository\Helpers;

use Carbon\Carbon;

class TimeHelper
{

    /**
     * Determine whether it is a leap year
     * @param int|null $year
     * @return bool
     */
    public static function isLeapYear(int $year = null) : bool
    {
        if ($year){
            $date = Carbon::create($year, 1, 1, 0, 0, 0);
        }else{
            $date = Carbon::now();
        }
        return$date->isLeapYear();
    }

    /**
     * Determine if today is a working day
     * @return bool
     */
    public static function isWeekday() : bool
    {
        return Carbon::now()->isWeekday();
    }

    /**
     * Determine if today is a day off
     * @return bool
     */
    public static function isWeekend() : bool
    {
        return Carbon::now()->isWeekend();
    }

    /**
     * What day of the week is the current time obtained
     * @return int
     *
     * @example 1~7
     */
    public static function getDayOfWeek() : int
    {
        return Carbon::now()->dayOfWeek;
    }

    /**
     * What week of the month is the current time obtained
     * @return int
     *
     * @example 1~5
     */
    public static function getWeekOfMonth() : int
    {
        return Carbon::now()->weekOfMonth;
    }

    /**
     * What week of the year is the current time obtained
     * @return int
     *
     * @example 1~54
     */
    public static function getWeekOfYear() : int
    {
        return Carbon::now()->weekOfYear;
    }

    /**
     * What day of the week is the current time obtained
     * @return int
     *
     * @example 30
     */
    public static function getDaysInMonth() : int
    {
        return Carbon::now()->daysInMonth;
    }

    /**
     * What day of the year is the current time obtained
     * @return int
     *
     * @example 235
     */
    public static function getDayOfYear() : int
    {
        return Carbon::now()->dayOfYear;
    }

    /**
     * get today's start time
     * @return string
     *
     * @example 2024-10-03 00:00:00
     */
    public static function getTodayStartTime() : string
    {
        return Carbon::today();
    }

    /**
     * get tomorrow's start time
     * @return string
     *
     * @example 2024-10-02 00:00:00
     */
    public static function getTomorrowStartTime() : string
    {
        return Carbon::tomorrow();
    }

    /**
     * get yesterday's start time
     * @return string
     *
     * @example 2024-10-01 00:00:00
     */
    public static function getYesterdayStartTime() : string
    {
        return Carbon::yesterday();
    }

    /**
     * get the start time of this year
     * @return string
     *
     * @example 2024-01-01 00:00:00
     */
    public static function getYearStartTime() : string
    {
        return Carbon::now()->startOfYear()->toDateTimeString();
    }

    /**
     * obtain the start time of previous years
     * @param int $year
     * @return string
     *
     * @example 2023-01-01 00:00:00
     */
    public static function getSubYearStartTime(int $year = 1) : string
    {
        return Carbon::now()->startOfYear()->subYear($year)->toDateTimeString();
    }

    /**
     * obtain the start time of future years
     * @param int $year
     * @return string
     *
     * @example 2025-01-01 00:00:00
     */
    public static function getAddYearStartTime(int $year = 1) : string
    {
        return Carbon::now()->startOfYear()->addYear($year)->toDateTimeString();
    }

    /**
     * get the current date
     * @return string
     *
     * @example 2024-10-03
     */
    public static function getCurrentDate() : string
    {
        return Carbon::now()->format('Y-m-d');
    }

    /**
     * obtain the date range for the current month
     * @return array
     *
     * @example [start_time: "2024-09-01", end_time: "2024-09-30"]
     */
    public static function getCurrentMonthDateRange() : array
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
     * @param int $time
     * @return string
     *
     * @example 20240101000000
     */
    public static function getStringTime(int $time = 0) : string
    {
        if ($time){
            return date('YmdHis', $time);
        }else{
            return Carbon::now()->format('YmdHis');
        }
    }

    /**
     * format time
     * @return string
     *
     * @example 2024-01-01 00:00:00
     */
    public static function getCurrentTime() : string
    {
        return Carbon::now()->toDateTimeString();
    }

    /**
     * get timestamp 10 digit
     * @return float|int
     *
     * @example 1727020799
     */
    public static function getCurrentTimestamp() : float|int
    {
        return Carbon::now()->timestamp;
    }

    /**
     * get timestamp 13 digit milliseconds
     * @return float|int
     *
     * @example 1727020799
     */
    public static function getCurrentMillisecond() : float|int
    {
        return (int) (Carbon::now()->getPreciseTimestamp() / 1000);
    }

    /**
     * get timestamp 16 digit microseconds
     * @return float|int
     *
     * @example 1727081142240395
     */
    public static function getCurrentMicrosecond() : float|int
    {
        return Carbon::now()->getPreciseTimestamp();
    }

    /**
     * get timestamp 19 digit nanoseconds
     * @return float|int
     *
     * @example 1727081075039719000
     */
    public static function getCurrentNanosecond() : float|int
    {
        return Carbon::now()->getPreciseTimestamp() * 1000;
    }

    /**
     * timestamp conversion
     * @param int $time
     * @return string
     *
     * @example 2024-10-03 00:00:00
     */
    public static function convertTime(int $time = 0) : string
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     * timestamp conversion
     * @param string $timestamp
     * @return int
     *
     * @example 946656000
     */
    public static function convertTimestamp(string $timestamp = '2000-01-01 00:00:00') : int
    {
        return strtotime($timestamp);
    }

    /**
     * obtain the time interval for the current day
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1726934400, end_timestamp: 1727020799]
     */
    public static function getToday() : array
    {
        $start_time = Carbon::today()->timestamp;
        $end_time = Carbon::today()->endOfDay()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * obtain the time interval of the day
     * @return array
     *
     * @example [start_time: "2024-10-03 00:00:00", end_time: "2024-10-03 23:59:59"]
     */
    public static function getTodayRange() : array
    {
        $start_time = Carbon::today()->toDateTimeString();
        $end_time = Carbon::today()->endOfDay()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * obtain timestamps for yesterday's start and end times
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1726934400, end_timestamp: 1727020799]
     */
    public static function getYesterday() : array
    {
        $start_time = Carbon::yesterday()->startOfDay()->timestamp;
        $end_time = Carbon::yesterday()->endOfDay()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * obtain the time interval of the yesterday
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-01 23:59:59"]
     */
    public static function getYesterdayRange() : array
    {
        $start_time = Carbon::yesterday()->startOfDay()->toDateTimeString();
        $end_time = Carbon::yesterday()->endOfDay()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the current month
     * @return array
     *
     * @example [start_timestamp: 1725120000, end_timestamp: 1727711999]
     */
    public static function getCurrentMonth() : array
    {
        $start_time = Carbon::now()->startOfMonth()->startOfDay()->timestamp;
        $end_time = Carbon::now()->endOfMonth()->endOfDay()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time interval of the current month
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-31 23:59:59"]
     */
    public static function getCurrentMonthRange() : array
    {
        $start_time = Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString();
        $end_time = Carbon::now()->endOfMonth()->endOfDay()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the last month
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1722441600, end_timestamp: 1725033600]
     */
    public static function getLastMonth() : array
    {
        $start_time = Carbon::now()->subMonth()->firstOfMonth()->timestamp;
        $end_time = Carbon::now()->subMonth()->lastOfMonth()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time interval of the last month
     * @return array
     *
     * @example [start_time: "2024-08-01 00:00:00", end_time: "2024-08-31 00:00:00"]
     */
    public static function getLastMonthRange() : array
    {
        $start_time = Carbon::now()->subMonth()->firstOfMonth()->toDateTimeString();
        $end_time = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the next month
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1722441600, end_timestamp: 1725033600]
     */
    public static function getNextMonth() : array
    {
        $start_time = Carbon::now()->addMonth()->firstOfMonth()->timestamp;
        $end_time = Carbon::now()->addMonth()->lastOfMonth()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time interval of the next month
     * @return array
     *
     * @example [start_time: "2024-08-01 00:00:00", end_time: "2024-08-31 00:00:00"]
     */
    public static function getNextMonthRange() : array
    {
        $start_time = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();
        $end_time = Carbon::now()->addMonth()->lastOfMonth()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the few month
     * @param int $month
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1722441600, end_timestamp: 1725033600]
     */
    public static function getSubMonth(int $month = 1) : array
    {
        $start_time = Carbon::now()->subMonth($month)->firstOfMonth()->timestamp;
        $end_time = Carbon::now()->subMonth($month)->lastOfMonth()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time interval of the few month
     * @param int $month
     * @return array
     *
     * @example [start_time: "2024-08-01 00:00:00", end_time: "2024-08-31 00:00:00"]
     */
    public static function getSubMonthRange(int $month = 1) : array
    {
        $start_time = Carbon::now()->subMonth($month)->firstOfMonth()->toDateTimeString();
        $end_time = Carbon::now()->subMonth($month)->lastOfMonth()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the start and end time of the future month
     * @param int $month
     * @return float[]|int[]
     *
     * @example [start_timestamp: 1722441600, end_timestamp: 1725033600]
     */
    public static function getAddMonth(int $month = 1) : array
    {
        $start_time = Carbon::now()->addMonth($month)->firstOfMonth()->timestamp;
        $end_time = Carbon::now()->addMonth($month)->lastOfMonth()->timestamp;

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time interval of the future month
     * @param int $month
     * @return array
     *
     * @example [start_time: "2024-08-01 00:00:00", end_time: "2024-08-31 00:00:00"]
     */
    public static function getAddMonthRange(int $month = 1) : array
    {
        $start_time = Carbon::now()->addMonth($month)->firstOfMonth()->toDateTimeString();
        $end_time = Carbon::now()->addMonth($month)->lastOfMonth()->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the current week's date
     * @return array
     *
     * @example [start_timestamp: 1726156800, end_timestamp: 1727020800]
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
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-8 00:00:00"]
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
     * get the timestamp of last week
     * @return array
     *
     * @example [start_timestamp: 1726416000, end_timestamp: 1727020800]
     */
    public static function getLastWeek() : array
    {
        $lastWeek = self::getSubDay(7);

        return [
            'start_timestamp' => $lastWeek['start_timestamp'],
            'end_timestamp' => $lastWeek['end_timestamp'],
        ];
    }

    /**
     * get the time range of last week
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-08 00:00:00"]
     */
    public static function getLastWeekRange() : array
    {
        $lastWeek = self::getSubDayRange(7);

        return [
            'start_time' => $lastWeek['start_time'],
            'end_time' => $lastWeek['end_time'],
        ];
    }

    /**
     * get the timestamp of next week
     * @return array
     *
     * @example [start_timestamp: 1726416000, end_timestamp: 1727020800]
     */
    public static function getNextWeek() : array
    {
        $lastWeek = self::getAddDay(7);

        return [
            'start_timestamp' => $lastWeek['start_timestamp'],
            'end_timestamp' => $lastWeek['end_timestamp'],
        ];
    }

    /**
     * get the time range of next week
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-08 00:00:00"]
     */
    public static function getNextWeekRange() : array
    {
        $lastWeek = self::getAddDayRange(7);

        return [
            'start_time' => $lastWeek['start_time'],
            'end_time' => $lastWeek['end_time'],
        ];
    }

    /**
     * get the timestamp of the previous few days
     * @param int $day
     * @return array
     *
     * @example [start_timestamp: 1726156800, end_timestamp: 1727020800]
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
     * get the time range of the previous few days
     * @param int $day
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-11 00:00:00"]
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
     * get the timestamp of the future few days
     * @param int $day
     * @return array
     *
     * @example [start_timestamp: 1726156800, end_timestamp: 1727020800]
     */
    public static function getAddDay(int $day = 7) : array
    {
        $start_time = Carbon::tomorrow()->getTimestamp();
        $end_time = Carbon::tomorrow()->addDays($day)->getTimestamp();

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the time range of the future few days
     * @param int $day
     * @return array
     *
     * @example [start_time: "2024-10-01 00:00:00", end_time: "2024-10-11 00:00:00"]
     */
    public static function getAddDayRange(int $day = 7) : array
    {
        $start_time = Carbon::tomorrow()->toDateTimeString();
        $end_time = Carbon::tomorrow()->addDays($day)->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * get the timestamp of the current day's time
     * @param int $day
     * @return array
     *
     * @example [start_timestamp: 1726199585, end_timestamp: 1727063585]
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
     *
     * @example [start_time: "2024-10-01 11:51:42", end_time: "2024-10-11 11:51:42"]
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
     * get the timestamp of the current day's time
     * @param int $day
     * @return array
     *
     * @example [start_timestamp: 1726199585, end_timestamp: 1727063585]
     */
    public static function getCurrentAddDay(int $day = 7) : array
    {
        $start_time = Carbon::now()->getTimestamp();
        $end_time = Carbon::now()->addDays($day)->getTimestamp();

        return [
            'start_timestamp' => $start_time,
            'end_timestamp' => $end_time
        ];
    }

    /**
     * get the date range of the current time in a few days
     * @param int $day
     * @return array
     *
     * @example [start_time: "2024-10-01 11:51:42", end_time: "2024-10-11 11:51:42"]
     */
    public static function getCurrentAddDayRange(int $day = 7) : array
    {
        $start_time = Carbon::now()->toDateTimeString();
        $end_time = Carbon::now()->addDays($day)->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * retrieve timestamps of dates from previous years
     * @param int $year
     * @return array
     *
     * @example [start_time: 1672502400, end_time: 1704038399]
     */
    public static function getSubYear(int $year = 1) : array
    {
        $start_time = Carbon::now()->startOfYear()->subYear($year)->getTimestamp();
        $end_time = Carbon::now()->endOfYear()->subYear($year)->getTimestamp();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * obtain the date range of previous years
     * @param int $year
     * @return array
     *
     * @example [start_time: "2023-01-01 00:00:00", end_time: "2023-12-31 23:59:59"]
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

    /**
     * retrieve timestamps of dates from future years
     * @param int $year
     * @return array
     *
     * @example [start_time: 1672502400, end_time: 1704038399]
     */
    public static function getAddYear(int $year = 1) : array
    {
        $start_time = Carbon::now()->startOfYear()->addYear($year)->getTimestamp();
        $end_time = Carbon::now()->endOfYear()->addYear($year)->getTimestamp();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * obtain the date range of future years
     * @param int $year
     * @return array
     *
     * @example [start_time: "2023-01-01 00:00:00", end_time: "2023-12-31 23:59:59"]
     */
    public static function getAddYearRange(int $year = 1) : array
    {
        $start_time = Carbon::now()->startOfYear()->addYear($year)->toDateTimeString();
        $end_time = Carbon::now()->endOfYear()->addYear($year)->toDateTimeString();

        return [
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
    }

    /**
     * retrieve timestamps of dates from previous hour
     * @param int $hour
     * @return int
     *
     * @example 1704038399
     */
    public static function getSubHourTimestamp(int $hour = 1) : int
    {
        return Carbon::now()->subHours($hour)->getTimestamp();
    }

    /**
     * obtain the date time of previous hour
     * @param int $hour
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getSubHourTime(int $hour = 1) : string
    {
        return Carbon::now()->subHours($hour)->toDateTimeString();
    }

    /**
     * retrieve timestamps of dates from future hour
     * @param int $hour
     * @return int
     *
     * @example 1704038399
     */
    public static function getAddHourTimestamp(int $hour = 1) : int
    {
        return Carbon::now()->addHours($hour)->getTimestamp();
    }

    /**
     * obtain the date time of future hour
     * @param int $hour
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getAddHourTime(int $hour = 1) : string
    {
        return Carbon::now()->addHours($hour)->toDateTimeString();
    }

    /**
     * retrieve timestamps of dates from previous hours and minutes
     * @param int $hour
     * @param int $minute
     * @return int
     *
     * @example 1704038399
     */
    public static function getSubHourMinuteTimestamp(int $hour = 1, int $minute = 0) : int
    {
        return Carbon::now()->subHours($hour)->subMinutes($minute)->getTimestamp();
    }

    /**
     * obtain the date time of previous hours and minutes
     * @param int $hour
     * @param int $minute
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getSubHourMinuteTime(int $hour = 1, int $minute = 0) : string
    {
        return Carbon::now()->subHours($hour)->subMinutes($minute)->toDateTimeString();
    }

    /**
     * retrieve timestamps of dates from future hours and minutes
     * @param int $hour
     * @param int $minute
     * @return int
     *
     * @example 1704038399
     */
    public static function getAddHourMinuteTimestamp(int $hour = 1, int $minute = 0) : int
    {
        return Carbon::now()->addHours($hour)->addMinutes($minute)->getTimestamp();
    }

    /**
     * obtain the date range of future hours and minutes
     * @param int $hour
     * @param int $minute
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getAddHourMinuteTime(int $hour = 1, int $minute = 0) : string
    {
        return Carbon::now()->addHours($hour)->addMinutes($minute)->toDateTimeString();
    }

    /**
     * retrieve timestamps of dates from previous day
     * @param int $modify
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getSubModifyDayTimestamp(int $modify = 1) : string
    {
        return Carbon::now()->modify('-'. $modify .' days')->getTimestamp();
    }

    /**
     * obtain the date time of previous day
     * @param int $modify
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getSubModifyDayTime(int $modify = 1) : string
    {
        return Carbon::now()->modify('-'. $modify .' days')->toDateTimeString();
    }

    /**
     * retrieve timestamps of dates from future day
     * @param int $modify
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getAddModifyDayTimestamp(int $modify = 1) : string
    {
        return Carbon::now()->modify('+'. $modify .' days')->getTimestamp();
    }

    /**
     * obtain the date time of future day
     * @param int $modify
     * @return string
     *
     * @example "2023-12-31 23:59:59"
     */
    public static function getAddModifyDayTime(int $modify = 1) : string
    {
        return Carbon::now()->modify('+'. $modify .' days')->toDateTimeString();
    }

    /**
     * Return the number of seconds in a minute
     * @param int $minutes
     * @return int
     *
     * @example 60
     */
    public static function secondOfMinute(int $minutes = 1): int
    {
        return 60 * $minutes;
    }

    /**
     * Return the number of seconds in hour
     * @param int $hours
     * @return int
     *
     * @example 3600
     */
    public static function secondOfHour(int $hours = 1): int
    {
        return 3600 * $hours;
    }

    /**
     * Return the number of seconds in a day
     * @param int $days
     * @return int
     *
     * @example 86400
     */
    public static function secondOfDay(int $days = 1): int
    {
        return 86400 * $days;
    }

    /**
     * Return the number of seconds in a week
     * @param int $weeks
     * @return int
     *
     * @example 604800
     */
    public static function secondOfWeek(int $weeks = 1): int
    {
        return 604800 * $weeks;
    }

}
