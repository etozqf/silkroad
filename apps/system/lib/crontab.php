<?php
/**
 * 类 crontab 语法解析器
 *
 * === 介绍 ===
 *
 * 该工具并未完全实现 GNU crontab 全部规范的语法，仅实现了如下语法的解析：
 * @code
 *
 *     @语法 文本描述的周期，可为
 *         @yearly 每年运行一次，结果为下一年的 01-01 00:00:00
 *         @annually 同 @yearly
 *         @monthly 每月运行一次，结果为下一个月的 01:01 00:00:00
 *         @weekly 每周运行一次，结果为下一周的 00:00:00
 *         @daily 每天运行一次，结果为明天的 00:00:00
 *         @midnight 同 @daily
 *         @hourly 每小时运行一次，结果为下个整点的零分零秒
 *
 *     常规 crontab 的数字格式（某些版本的 crontab 也支持长名称格式，也即月和周的英文前三位格式，如星期采用 Mon / Sun 等，暂未支持）
 *         * * * * *
 *         0 1 1,2 1,2-10 1,2,3-6/2

 * @endcode
 *
 * 常规 crontab 命令还有 user 和 command 项（第 6 项和第 7 项），在 CmsTop 应用中没有实际用途，暂未支持。
 *
 * === 示例 ===
 *
 * @code
 *
 *     $config = '40-59/2 0-12 5 1-8/2 0';
 *     $result = crontab::parse($config);
 *     if ($result)
 *     {
 *
 *         echo "尝试次数：" . crontab::attempts();
 *         echo "，使用时间：" . crontab::exectime();
 *         echo "，解析结果：" . date('Y-m-d H:i:s', $result);
 *     }
 *     else
 *     {
 *         echo "解析失败：" . crontab::error();
 *     }
 *
 * @endcode
 *
 * @todo 在 GUN 规范中，如果同时指定了月份中的天和星期中的星期几，则结果为两者的并集，当前实现为 2 选 1
 * @reference http://www.gnu.org/software/mcron/manual/html_node/Crontab-file.html
 * @reference http://en.wikipedia.org/wiki/Crontab
 * @version $Id$
 */
class crontab
{
    static protected $_error;
    static protected $_errno;
    static protected $_errors = array(
        1 => 'crontab 语法格式不正确',
        2 => '配置项的范围超出了合法值'
    );

    static protected $_last;
    static protected $_last_second;
    static protected $_last_minute;
    static protected $_last_hour;
    static protected $_last_dom;
    static protected $_last_month;
    static protected $_last_dow;
    static protected $_last_year;

    static protected $_nextrun;

    static protected $_range;
    static protected $_week_names;
    static protected $_attempts;
    static protected $_execstart;
    static protected $_exectime;

    static function parse($crontab, $lastrun = null)
    {
        self::_initEnv($lastrun);
        $crontab = trim($crontab);
        $crontab = preg_replace('/\s+/', ' ', $crontab);
        $crontab = explode(' ', $crontab);
        if (count($crontab) == 5)
        {
            self::_parseNumeric($crontab);
        }
        else if ($crontab[0][0] == '@')
        {
            self::_parseTextLike($crontab[0]);
        }
        else
        {
            self::$_errno = 1;
            return false;
        }
        self::$_exectime = sprintf("%09.5fs", microtime(true) - self::$_execstart);
        return self::$_nextrun;
    }

    static function error()
    {
        if (self::$_error) return self::$_error;
        if (self::$_errno) return self::$_errors[self::$_errno];
        return null;
    }

    static function attempts()
    {
        return self::$_attempts;
    }

    static function exectime()
    {
        return self::$_exectime;
    }

    static protected function _initEnv($last)
    {
        self::$_errno = null;
        self::$_error = null;

        $last = strtotime(date('Y-m-d H:i', is_int($last) ? $last : time()));
        self::$_last = $last;
        list(self::$_last_second, self::$_last_minute, self::$_last_hour, self::$_last_dom, self::$_last_month,
             self::$_last_dow, self::$_last_year) = explode(' ', date('s i G j n w Y', $last));

        self::$_nextrun = null;

        self::$_range = array(
            range(0, 59),
            range(0, 23),
            range(1, 31),
            range(1, 12),
            range(0, 7)
        );

        self::$_week_names = array(
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        );

        self::$_attempts = 0;

        self::$_execstart = microtime(true);
        self::$_exectime = 0;
    }

    static protected function _parseTextLike($config)
    {
        $config = substr($config, 1);
        switch ($config)
        {
            case 'yearly':
            case 'annually':
                self::$_nextrun = mktime(0, 0, 0, 1, 1, self::$_last_year + 1);
                break;
            case 'monthly':
                self::$_nextrun = mktime(0, 0, 0, self::$_last_month + 1, 1, self::$_last_year);
                break;
            case 'weekly':
                self::$_nextrun = strtotime('next sunday', self::$_last);
                break;
            case 'daily':
            case 'midnight':
                self::$_nextrun = mktime(0, 0, 0, self::$_last_month, self::$_last_dom + 1, self::$_last_year);
                break;
            case 'hourly':
                self::$_nextrun = mktime(self::$_last_hour + 1, 0, 0, self::$_last_month, self::$_last_dom, self::$_last_year);
                break;
            default:
                self::$_errno = 1;
                break;
        }
    }

    static protected function _parseNumeric($config)
    {
        // 目前，月份中的天和星期中的星期几只支持指定其中的一种
        if ($config[4] != '*') $config[2] = '*';
        if ($config[2] != '*') $config[4] = '*';

        // 修正星期 7 为周日，零值计算
        if ($config[4] == 7) $config[4] = 0;

        // 从月这一项开始，如果指定了非 * 值，那么该项之前的取值为 * 的项的值重置为该项的初始值
        $index = 4;
        while ($config[--$index] == '*') {}
        for (; --$index >= 0;)
        {
            if ($config[$index] == '*')
            {
                $config[$index] = self::$_range[$index][0];
            }
        }

        // 分项解析，获取每项的全部可能取值
        $result = array();
        foreach ($config as $index => $section)
        {
            $result[$index] = self::_parseSection($section, $index);
        }
        list($minutes, $hours, $day_of_months, $months, $day_of_weeks) = $result;

        // 调试，输出分析后的数据
        //print_r($result);

        // 快速修正或返回一些情况的特殊取值，如 * * * * * 或 * * * * 2
        if ($day_of_weeks)
        {
            // 如果指定了星期，特殊处理
            if (! $months)
            {
                if (! $hours)
                {
                    if (! $minutes)
                    {
                        return self::_walkDayOfWeek($day_of_weeks);
                    }
                    $hours = self::$_range[1];
                }
                // 如果没有指定月，那它一定是当前月（上次执行时间所在的月），
                // 因为基于上面的约定，星期中的星期几若指定了，必然不能指定月份中的天
                $months = array(self::$_last_month);
            }

            // 基于上面的约定，为方便下面的循环，月份中的天采用整月范围查找
            $day_of_months = self::$_range[2];
        }
        else
        {
            if (! $months)
            {
                if (! $day_of_months)
                {
                    if (! $hours)
                    {
                        if (! $minutes) return self::$_nextrun = self::$_last - self::$_last % 60 + 60;
                        $hours = array(self::$_last_hour);
                    }
                    $day_of_months = array(self::$_last_dom);
                }
                $months = array(self::$_last_month);
            }
        }

        $cases = array();
        foreach ($months as $month)
        {
            $start_year = self::$_last_year;
            $start_month = $month;
            if (($start_year == self::$_last_year))
            {
                $start_year = ($start_month < self::$_last_month) ? ($start_year + 1) : self::$_last_year;
            }
            if (self::_skipCurrentLoop($cases, $start_year, $start_month)) continue;
            foreach ($day_of_months as $day_of_month)
            {
                $start_dom = $day_of_month;
                if (($start_year == self::$_last_year))
                {
                    $start_month = ($start_month == self::$_last_month && $start_dom < self::$_last_dom) ? ($start_month + 1) : $month;
                }
                if (self::_skipCurrentLoop($cases, $start_year, $start_month, $start_dom)) continue;
                foreach ($hours as $hour)
                {
                    $start_hour = $hour;
                    if (($start_year == self::$_last_year) && ($start_month == self::$_last_month))
                    {
                        $start_dom = ($start_dom == self::$_last_dom && $start_hour < self::$_last_hour) ? ($start_dom + 1) : $day_of_month;
                    }
                    if (self::_skipCurrentLoop($cases, $start_year, $start_month, $start_dom, $start_hour)) continue;
                    foreach ($minutes as $minute)
                    {
                        if (($start_year == self::$_last_year) && ($start_month == self::$_last_month) && ($start_dom == self::$_last_dom))
                        {
                            $start_hour = ($start_hour == self::$_last_hour && $minute < self::$_last_minute) ? ($start_hour + 1) : $hour;
                        }
                        if (self::_skipCurrentLoop($cases, $start_year, $start_month, $start_dom, $start_hour, $minute)) continue;
                        $when = ($start_dom == end(self::$_range[2])) // 如果用户指定了月份中的天为 31，那他（她）可能是想说月份中的最后一天
                            ? mktime($start_hour, $minute, 0, $start_month + 1, 0, $start_year)
                            : mktime($start_hour, $minute, 0, $start_month, $start_dom, $start_year);
                        // 调试，输出解析过程中尝试的时间
                        //echo date("[ Y-m-d H:i:s ]\n", $when);
                        if ($day_of_weeks && is_array($day_of_weeks) && ! in_array(date('w', $when), $day_of_weeks)) continue;
                        if (! $cases || $when > self::$_last) $cases[] = $when;
                    }
                }
            }
        }

        return self::_findResult($cases);
    }

    static protected function _walkDayOfWeek($day_of_weeks)
    {
        $cases = array();
        foreach ($day_of_weeks as $day_of_week)
        {
            if ($day_of_week == 7) $day_of_week = 0;
            $cases[] = $day_of_week == self::$_last_dow
                ? mktime(0, 0, 0, self::$_last_month, self::$_last_dom, self::$_last_year)
                : strtotime(date('Y-m-d', strtotime('next ' . self::$_week_names[$day_of_week])));
        }
        return self::_findResult($cases);
    }

    static protected function _findResult(&$cases)
    {
        self::$_attempts = count($cases);
        if (self::$_attempts)
        {
            self::$_nextrun = min($cases);
            unset($cases);
            return true;
        }
        return false;
    }

    static protected function _skipCurrentLoop(&$cases, $year, $month = null, $dom = null, $hour = null, $minute = null)
    {
        if (! $cases) return false;
        $min = min($cases);
        list($min_year, $min_month, $min_dom, $min_hour, $min_minute) = explode(' ', date('Y n j G i', $min));
        if ($year > $min_year || 
           ($month && $year == $min_year && $month > $min_month) || 
           ($month && $dom && $year == $min_year && $month == $min_month && $dom > $min_dom) || 
           ($month && $dom && $hour && $year == $min_year && $month == $min_month && $dom == $min_dom && $hour > $min_hour) || 
           ($month && $dom && $hour && $minute && $year == $min_year && $month == $min_month && $dom == $min_dom && $hour == $min_hour && $minute > $min_minute)) return true;
        return false;
    }

    static protected function _parseSection($config, $index)
    {
        $range = self::$_range[$index];
        $min = $range[0];
        $max = end($range);
        $values = array();
        foreach (explode(',', $config) as $part)
        {
            $step = 1;
            $temp = $range;
            if ($part == '*')
            {
                continue;
            }
            else if (is_numeric($part) && self::_inRange($part, $range))
            {
                $values[] = $part;
            }
            elseif (preg_match('#^[\*\d\-]+(?:/([\d]+))?$#', $part, $part))
            {
                if (isset($part[1]))
                {
                    $step = $part[1];
                }
                $part = $part[0];
                if (strpos($part, '-'))
                {
                    $part = explode('-', $part);
                    if (! $part[0]) $part[0] = $min;
                    if (! $part[1]) $part[1] = $max;
                    if (! self::_inRange($part[0], $range) || ! self::_inRange($part[1], $range))
                    {
                        self::$_errno = 2;
                        return false;
                    }
                    $temp = range($part[0], $part[1]);
                }
                for ($index = 0, $total = count($temp); $index < $total; $index += $step)
                {
                    $values[] = $temp[$index];
                }
            }
            else
            {
                self::$_errno = 1;
                return false;
            }
        }
        $result = array_unique($values);
        asort($result);
        return $result;
    }

    static protected function _inRange($item, array $range)
    {
        return $item >= $range[0] && $item <= end($range);
    }
}

//date_default_timezone_set('Asia/Chongqing');

//$result = crontab::parse('0-30/3 0-12 * 1-8/2 0-5');
//$result = crontab::parse('* 0 * 1 7');
//$result = crontab::parse('* * * * 3-7');
//$result = crontab::parse('* * 25-31/3 * 0-4/2');
//$result = crontab::parse('* * * 1-10 *');
//$result = crontab::parse('* * * 1-10 2');
//$result = crontab::parse('0-5 0-14/3 31 1-8/2 2-7/3');
//$result = crontab::parse('20,30-40/2 11 15-25/3 1 *');
//$result = crontab::parse('20,30-40/2 11 15-25/3 7 *');
//$result = crontab::parse('56-59/2,30 11-14/2 * * *');
//$result = crontab::parse('0 0 1 * *');
//$result = crontab::parse('*/5 * * * *');
//$result = crontab::parse('* * * * *');
//$result = crontab::parse('* * * 1 *');
//$result = crontab::parse('0 2 * 2 2-3');
//$result = crontab::parse('0 3 * 5 2-3');
//$error = crontab::error();
//echo $result ? date('Y-m-d H:i:s', $result) : ($error ? $error : 'nextrun error');
//exit;

/*
function log_to_file($log)
{
    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'crontab.log', $log, FILE_APPEND);
}

$minutes = array('*', 0, 59, 2, 45, '0-5', '50-59', '0-30/3', '40-59/2');
$hours = array('*', 0, 23, 3, 20, '0-12', '16-23', '0-14/3', '15-22/5');
$doms = array('*', 1, 31, 5, 20, '1-15', '20-31', '1-6/2', '25-31/3');
$months = array('*', 1, 12, 3, 10, '1-10', '6-12', '1-8/2', '2-12/3');
$weeks = array('*', 0, 7, 2, 5, '0-5', '3-7', '0-4/2', '2-7/3');
set_time_limit(0);

foreach ($minutes as $minute)
{
    foreach ($hours as $hour)
    {
        foreach ($doms as $dom)
        {
            foreach ($months as $month)
            {
                foreach ($weeks as $week)
                {
                    $crontab = "$minute $hour $dom $month $week";
                    //$crontab = '0-30/3 0-12 * 1-8/2 0-5';
                    $result = crontab::parse($crontab);
                    $error = crontab::error();
                    $error = $error ? "empty with $error" : '';
                    $attempts = crontab::attempts();
                    $exectime = crontab::exectime();
                    $message = "[" . sprintf('%10s', $attempts) . "] [" . sprintf('%12s', $exectime) . "]" . sprintf('%50s', $crontab) . "   ->   ";
                    if ($result)
                    {
                        $nextrun = $result ? date('Y-m-d H:i:s', $result) : $error;
                        $message .= "$nextrun";
                    }
                    else
                    {
                        $message .= "error with $error";
                    }
                    //echo $message;
                    log_to_file($message . "\n");
                }
            }
        }
    }
}

echo "done\n";
*/