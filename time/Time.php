<?php

namespace marx\time;

/**
 * 时间 工具.
 */
class Time
{
    const FMT_DATE = 'Y-m-d';
    const FMT_DATE_HOUR = 'Y-m-d H';
    const FMT_DATE_MINUTE = 'Y-m-d H:i';
    const FMT_DATE_TIME = 'Y-m-d H:i:s';
    const FMT_TIME = 'H:i:s';

    /**
     * 当前时间戳.
     *
     * @return int
     */
    public static function nowTime()
    {
        return time();
    }

    /**
     * 当前时间字符串.
     *
     * @param string $format 默认Y-m-d H:i:s
     *
     * @return string
     */
    public static function nowDate($format = '')
    {
        return date($format ?: static::FMT_DATE_TIME);
    }

    /**
     * 当前毫秒时间.
     *
     * @param string $format 默认Y-m-d H:i:s
     *
     * @return string
     */
    public static function nowMicro($format = '')
    {
        list($m_sec, $sec) = explode(' ', microtime());

        return date($format ?: static::FMT_DATE_TIME, $sec).'.'.sprintf('%.0f', floatval($m_sec) * 1000);
    }

    /**
     * 获取当前时间前/后几天的时间.
     *
     * @param int    $days
     * @param string $format 默认Y-m-d H:i:s
     *
     * @return string
     */
    public static function addDaysDateFromNow($days, $format = '')
    {
        return date($format ?: static::FMT_DATE_TIME, strtotime("{$days} days"));
    }

    /**
     * 获取当前时间前/后几分钟的时间.
     *
     * @param int    $minutes
     * @param string $format  默认Y-m-d H:i:s
     *
     * @return string
     */
    public static function addMinutesDateFromNow($minutes, $format = '')
    {
        return date($format ?: static::FMT_DATE_TIME, strtotime("{$minutes} minutes"));
    }

    /**
     * 获取某个时间前/后几天的时间.
     *
     * @param int    $days
     * @param string $date
     * @param string $format 默认Y-m-d H:i:s
     *
     * @return string
     */
    public static function addDaysDate($days, $date, $format = '')
    {
        return date($format ?: static::FMT_DATE_TIME, strtotime("{$days} days", strtotime($date)));
    }

    /**
     * 格式化时间.
     *
     * @param int    $time
     * @param string $format     默认Y-m-d H:i:s
     * @param string $fail_value
     *
     * @return false|string
     */
    public static function format($time, $format = '', $fail_value = '')
    {
        try {
            $rest = date($format ?: static::FMT_DATE_TIME, $time);
        } catch (\Throwable $th) {
            $rest = false;
        }

        return false !== $rest ? $rest : $fail_value;
    }

    /**
     * 时间比较 晚于当前时间
     * 比当前时间晚返回true 否则返回false.
     *
     * @param string $date
     *
     * @return bool
     */
    public static function laterThanNow($date)
    {
        return strtotime($date) > time();
    }

    /**
     * 时间比较 早于当前时间
     * 比当前时间早返回true 否则返回false.
     *
     * @param string $date
     *
     * @return bool
     */
    public static function earlierThanNow($date)
    {
        return strtotime($date) < time();
    }

    /**
     * 时间比较
     * 第一个时间比第二个晚返回true 否则返回false.
     *
     * @param string      $date
     * @param null|string $compare null表示当前时间
     *
     * @return bool
     */
    public static function laterThanTime($date, $compare = null)
    {
        $date = strtotime($date);
        $compare = is_null($compare) ? time() : strtotime($compare);

        return $date > $compare;
    }

    /**
     * 时间比较
     * 第一个时间比第二个早返回true 否则返回false.
     *
     * @param string      $date
     * @param null|string $compare null表示当前时间
     *
     * @return bool
     */
    public static function earlierThanTime($date, $compare = null)
    {
        $date = strtotime($date);
        $compare = is_null($compare) ? time() : strtotime($compare);

        return $date < $compare;
    }
}
