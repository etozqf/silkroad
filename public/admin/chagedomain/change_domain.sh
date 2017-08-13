#!/bin/bash
#
####################################################################
# @filename: change_domain.sh
# @author:   me@mruse.cn
# @create:   2013.05.15
# @update:   2015.05.14
###################################################################

PATH=/bin:/usr/bin:/sbin:/usr/sbin:/usr/local/sbin:/usr/local/server/mysql/bin
export PATH=$PATH

# ----------------------------------------------------------------- defination
IS_BACKUP=0

HOSTIP="192.168.20.110"
ADMIN="pubstage"
MYSQLHOST="mysql.db.db.silkroad.news.cn"
MYSQLROOT="cmstop"
MYSQLPASS="wEAegnZk9XVnzlie3oyuPw"
MYSQLPORT="3306"
MYSQLDB="cmstop"
BACKUP="/backup"
CMSTOPDIR="/data/www/cmstop"
NGINXCONF="/usr/local/server/nginx/conf/vhosts"
HTTPDCONF="/etc/httpd/vhosts"
DATETIME=`date -d now +%Y-%m-%d_%H-%M`

OLDDOMAIN="${1:-db.silkroad.news.cn}"
NEWDOMAIN="${2:-db.db.silkroad.news.cn}"




# ----------------------------------------------------------------- replace in cmstop path
cd $CMSTOPDIR
sed -i 's#'$OLDDOMAIN'#'$NEWDOMAIN'#g' `grep $OLDDOMAIN -rl --exclude=notes.xml --exclude=hearst/* ./`

# 遇到奇葩机器，sed不能批量执行的试试perl
#grep grep $OLDDOMAIN -rl ./|xargs perl -pi -e 's|'$OLDDOMAIN'|'$NEWDOMAIN'|g'

# ----------------------------------------------------------------- replace in database
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_psn SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_page SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_category SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_content SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_comment_topic SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_special_page SET url = REPLACE(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_widget SET data = REPLACE(`data`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_article SET content = REPLACE(`content`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_section SET data = REPLACE(`data`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_section SET origdata = REPLACE(`origdata`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_setting SET value = REPLACE(`value`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_style SET data = REPLACE(`data`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_app SET iconurl = REPLACE(`iconurl`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_content_log set url = replace(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_content set url = replace(`url`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_category set iconurl = replace(`iconurl`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_mobile_moreapp set iconurl = replace(`iconurl`, "'$OLDDOMAIN'", "'$NEWDOMAIN'")'
mysql -h$MYSQLHOST -u$MYSQLROOT -p$MYSQLPASS -P$MYSQLPORT -e 'UPDATE '$MYSQLDB'.cmstop_setting set value = replace(`value`, "'$OLDDOMAIN'", "'$NEWDOMAIN'") where app = "mobile"'










