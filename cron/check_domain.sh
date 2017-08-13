#!/bin/bash
#Program:
#	check apache/nginx execute php privilege
#
# Usage:
# 0 2 * * * /bin/bash /path/sh/check_domain.sh | php /path/sh/check_domain.php
# History:
# 2013/12/10 xudianang 

PATH="/sbin:/usr/sbin:/bin:/usr/bin:/usr/local/bin"
export PATH

DEFAULT_WEB_SERVER_PORT="80"
DEFAULT_WEB_SERVER_NAME="httpd"

WEB_SERVER_PORT=""
WEB_SERVER_NAME=""

WEB_SERVER_RUNNING_FLAG=""
WEB_SERVER_BIN=""

WEB_SERVER_ROOT=""
WEB_SERVER_CONFIG_FILE=""

while getopts ":p:n:" arg;
do
	case $arg in
		p)
			WEB_SERVER_PORT=$OPTARG
			WEB_SERVER_PORT=`echo ${WEB_SERVER_PORT} | sed s/[[:space:]]//g`
		;;
		n)
			WEB_SERVER_NAME=$OPTARG
			WEB_SERVER_NAME=`echo ${WEB_SERVER_NAME} | sed s/[[:space:]]//g`
		;;
		?)
			echo "Sorry!Nnkown Argument Such As: check.sh -p 80 -n httpd"
			exit 1
		;;
	esac
done

setOpts() {

	if [ "$WEB_SERVER_PORT" == "" ]; then
		WEB_SERVER_PORT=$DEFAULT_WEB_SERVER_PORT
	fi
	
	checkServerRunning
	
	if [ "$WEB_SERVER_RUNNING_FLAG" == "1" ]; then 	
		WEB_SERVER_NAME=`netstat -tnlp | grep "0.0.0.0:${WEB_SERVER_PORT}" | cut -d "/" -f 2`
		WEB_SERVER_NAME=`echo ${WEB_SERVER_NAME} | sed s/[[:space:]]//g`
	elif [ "$WEB_SERVER_NAME" == "" ]; then
		WEB_SERVER_NAME=$DEFAULT_WEB_SERVER_NAME
		WEB_SERVER_NAME=`echo ${WEB_SERVER_NAME} | sed s/[[:space:]]//g`
	fi

}

checkServerRunning() {
	WEB_SERVER_RUNNING_FLAG=`netstat -tnlp | grep "0.0.0.0:${WEB_SERVER_PORT}" | wc -l`
}

getServerConfPath() {
	if [ "$WEB_SERVER_RUNNING_FLAG" == "1" ]; then
		if [ "${WEB_SERVER_NAME}" == "httpd" ]; then
			WEB_SERVER_BIN=`ps -ef | grep ${WEB_SERVER_NAME} | grep -v "grep" | head -n 1 | awk '{print $8}'`
		else
			WEB_SERVER_BIN=`ps -ef | grep ${WEB_SERVER_NAME} | grep -Poi 'master\s+process\s+.+-c' | grep -v "(" | sed -r 's/\s+/ /g' | cut -d " " -f 3`
		fi
	else 
		 WEB_SERVER_BIN=${WEB_SERVER_NAME}
	fi  
	WEB_SERVER_BIN=`echo ${WEB_SERVER_BIN} | sed s/[[:space:]]//g`
	$WEB_SERVER_BIN -V >/dev/null 2>&1

	if [ $? -eq 0 ]; then
		if [ "${WEB_SERVER_NAME}" == "httpd" ]; then
			WEB_SERVER_ROOT=`$WEB_SERVER_BIN -V | awk -F "=" '/HTTPD_ROOT/ {print $2}' | sed s/[[:space:]]//g`
			WEB_SERVER_ROOT=${WEB_SERVER_ROOT//\"/}

			WEB_SERVER_CONFIG_FILE=`${WEB_SERVER_BIN} -V | awk -F "=" '/SERVER_CONFIG_FILE/ {print $2}' | sed s/[[:space:]]//g`
			WEB_SERVER_CONFIG_FILE=${WEB_SERVER_CONFIG_FILE//\"/}
		else
			${WEB_SERVER_BIN} -V 2> conf_check_nginx.tmp
			WEB_SERVER_ROOT=`cat conf_check_nginx.tmp | grep -Poi '\-\-conf-path=[^\s]+' | cut -d "=" -f 2`
			rm -f ./conf_check_nginx.tmp
			WEB_SERVER_CONFIG_FILE=`echo ${WEB_SERVER_ROOT} | awk -F "/" '{print $NF;}' | sed s/[[:space:]]//g`
			WEB_SERVER_ROOT=${WEB_SERVER_ROOT//\/${WEB_SERVER_CONFIG_FILE}/}
			WEB_SERVER_ROOT=`echo ${WEB_SERVER_ROOT} | sed s/[[:space:]]//g`
		fi
	else
		echo "Sorry!Not Fund Any Web Server Program"
		exit 1
	fi
}

setOpts

getServerConfPath

WEB_SERVER_CONFIG_FILE=${WEB_SERVER_ROOT}/${WEB_SERVER_CONFIG_FILE}

declare -a confFiles

confFiles[0]=${WEB_SERVER_CONFIG_FILE}

i=1

for include in `cat ${confFiles[0]} | grep -Poi "Include.+conf" | sed -r 's/\s+/ /g' | cut -d " " -f 2`
do
	subConfPathName=${WEB_SERVER_ROOT}/$include
	for subConfFile in ${subConfPathName}
	do
		confFiles[$i]=${subConfFile}
		i+=1
	done
done

declare -x globalSsi

printf "Check Apache/Nginx Execute PHP Privilege\n"

printf "%s %s %s\n" "-----------------" ${WEB_SERVER_NAME} "-----------------"

printf "%s|%s|%s|%s|%s|%s|%s|%s|%s\n" \
"Hostname" "IP" "Config File" "Domain" "DocumentRoot" "OPEN_BASEDIR" "Execute PHP(Y/N)" "PHP Suffix" "SSI(Y/N)"

if [ "${WEB_SERVER_NAME}" == "httpd" ]; then
	for subConfFile in ${confFiles[@]}
	do
		cat ${subConfFile} | grep -v '#' | tr "\n" "@" | grep -Poi '<VirtualHost\s+.+:\d+>.+</VirtualHost>' | tr  "@" " " | awk -F "</VirtualHost>" '{ i=1;while(i<=NF) {print $i,i++}}' | while read virtualHost;
		do
			printf "%s|" ${HOSTNAME}
			printf "%s|" `ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}' | head -n 1`

			serverName=`echo ${virtualHost} | grep -Poi 'ServerName\s+[^\s]+\s' | sed -r 's/\s+/ /g' |cut -d " " -f 2`
			if [ "${serverName}" == "" ]; then
				continue
			fi
			printf "%s|" ${subConfFile}
			printf "%s|" ${serverName}

			documentRoot=`echo ${virtualHost} | grep -Poi 'DocumentRoot\s+[^\s]+\s' | sed -r 's/\s+/ /g' | cut -d " " -f 2`
			printf "%s|" ${documentRoot}

			openBaseDir=`echo ${virtualHost} | grep -Poi 'php_admin_value\s+open_basedir\s+.+\s' | sed -r 's/\s+/ /g' | cut -d " " -f 3 | sed 's/"//g' | sed "s/'//g"`
			if [ "${openBaseDir}" == "" ]; then
				printf "%s|" "ALL"
			else
				printf "%s|" ${openBaseDir}
			fi

			phpScript=`echo ${virtualHost} | grep -Poi '\s+AddHandler\s+php5-script\s+[^\s]+' \
						| awk -F "php5-script" '{print $2}'`
			if [ "${phpScript}" == "" ]; then
				printf "%s|" "N"
				printf "%s|" "N"
			else 
				printf "%s|" "Y"
				printf "%s|" ${phpScript}
			fi
			serverSsi=`echo ${virtualHost} | grep -Poi 'AddOutputFilter\s+INCLUDES\s+' | sed -r 's/\s+/ /g' |cut -d " " -f 1`
			if [ "${serverSsi}" == "" ]; then
				printf "%s" "N"
			else
				printf "%s" "Y"
			fi
			printf "\n"
		done
	done
elif [ "${WEB_SERVER_NAME}" == "nginx" ]; then
	cat ${confFiles[0]} | grep -v '#' | tr "\n"  " " | sed -r 's/server\s+\{/-----/g' | awk -F "-----" '{i=1;while(i<=NF) {print $i,i+=1;}}' | while read virtualHost;
	do
		serverName=`echo ${virtualHost} | grep -Poi '\s+server_name\s+[^;]+' | sed -r 's/\s+/ /g' | cut -d " " -f 2`
		if [ "${serverName}" == "" ]; then
			globalSsi=`echo ${virtualHost} | grep -Poi 'ssi\s+on;' | sed -r 's/\s+/ /g' | cut -d " " -f 2`
			if [ "${globalSsi}" != "" ]; then
				globalSsi=`echo ${virtualHost} | grep -Poi 'http\s+\{'`
				if [ "${globalSsi}" != "" ]; then
					printf "on"
					break 1
				fi
			fi
		fi
	done > conf_check_nginx.tmp
	globalSsi=`cat conf_check_nginx.tmp`
	rm -f conf_check_nginx.tmp

	for subConfFile in ${confFiles[@]}
	do
		cat ${subConfFile} | grep -v '#' | tr "\n"  " " | sed -r 's/server\s+\{/-----/g' | awk -F "-----" '{i=2;while(i<=NF) {print $i,i+=1;}}' | while read virtualHost;
		do
			printf "%s|" ${HOSTNAME}
			printf "%s|" `ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}' | head -n 1`

			serverName=`echo ${virtualHost} | grep -Poi 'server_name\s+[^;]+' | sed -r 's/\s+/ /g' | cut -d " " -f 2`
			if [ "${serverName}" == "" ]; then
				continue
			fi
			printf "%s|" ${subConfFile}
			printf "%s|" ${serverName}

			documentRoot=`echo ${virtualHost} | grep -Poi 'root\s+[^;]+' | sed -r 's/\s+/ /g' | cut -d " " -f 2`
			printf "%s|" ${documentRoot}

			printf "%s|" "ALL"

			phpScript=`echo ${virtualHost} | grep -Poi 'location\s+~[^\.]*\.php.+include\s+fastcgi.conf;' | sed -r 's/\s+/ /g' | cut -d " " -f 1`
			if [ "${phpScript}" == "" ]; then
				printf "%s|" "N"
				printf "%s|" "N"
			else 
				printf "%s|" "Y"
				printf "%s|" ".php"
			fi

			serverSsi=`echo ${VirtualHost} | grep -Poi 'ssi\s+on;'`
			if [ "${serverSsi}" == "" ]; then
				if [ "${globalSsi}" == "on" ]; then 
					serverSsi=`echo ${virtualHost} | grep -Poi 'ssi\s+off'`
					if [ "${serverSsi}" == "" ]; then
						printf "%s" "Y"
					else
						printf "%s" "N"
					fi
				else
					printf "%s" "N"
				fi
			else
				printf "%s" "Y"
			fi
			printf "\n"
		done
	done
fi

exit $?
