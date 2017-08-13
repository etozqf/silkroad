#!/bin/bash
cur_dir=$(cd "$(dirname "$0")"; pwd)
cd $cur_dir

pid="cmstop_cron.pid"
if [ -f "$pid" ] && [ -e /proc/`cat $pid` ]; then
        echo "CmsTop Crontab Running..."
        exit 0
fi

trap "rm -fr $pid; exit 0" 0 1 2 3 15
echo $$ > $pid

/usr/local/server/php/bin/php crontab.php
