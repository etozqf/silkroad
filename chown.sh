	# @start Reset Permition 2016-02-03_12:59:43
	# 如果项目挂载了网络存储或者NFS，请谨慎，务必提前确认
	chown    root:nginx ./
	chown    root:nginx ./*
	chown -R root:nginx ./apps
	chown -R root:nginx ./config
	chown -R root:nginx ./cron
	chown -R root:nginx ./framework
	chown -R root:nginx ./resources
	chown -R root:nginx ./sql
	chown -R root:nginx ./templates
	chown    root:nginx ./public/*
	chown -R root:nginx ./public/admin
	chown -R root:nginx ./public/api
	chown -R root:nginx ./public/app
	chown -R root:nginx ./public/img
	chown -R root:nginx ./public/mobile
	chown -R root:nginx ./public/space
	chown -R root:nginx ./public/wap

	chown -R nginx:nginx ./data
	chown -R nginx:nginx ./public/www
	chown -R nginx:nginx ./public/upload
	chown -R nginx:nginx ./public/img/apps/special/templates
	chown -R nginx:nginx ./public/img/apps/special/ui/themes
	chown -R nginx:nginx ./public/img/apps/special/scheme

	find ./ -type d -exec chmod 550 {} \;
	find ./ -type f -exec chmod 440 {} \;

	chmod -R 700 ./data/
	chmod -R 700 ./public/www/
	chmod -R 700 ./public/upload/
	chmod -R 700 ./public/img/apps/special/templates
	chmod -R 700 ./public/img/apps/special/ui/themes
	chmod -R 700 ./public/img/apps/special/scheme

	# Deny: thirdpart API(CTMobile, DiscuzX2.5, Enorth, Founder, PhpWind9, TRS, pw_api, pw_client, uc_client)
	chmod -R 000 ./public/app/api/
	# Alow: thirdpart API Demo
	# chmod 550 ./public/app/api/
	# find ./public/app/api/uc_client -type d -exec chmod 550 {} \;
	# find ./public/app/api/uc_client -type f -exec chmod 440 {} \;
	# @end Reset Permition
