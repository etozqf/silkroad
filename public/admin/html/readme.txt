CMSTOP命令行HTML静态页面生成器

工具描述：CMSTOP命令行HTML静态页面生成器，主要实现在Linux下，采用PHP命令行模式运行
PHP程序即CMSTOP命令行HTML静态页面生成器，生成文章最终页、组图最终页、视频最终页、列
表页。此工具支持配置文件、命令行参数两种方式生成静态页面，可以配置复杂的生成方式，也
可以方便、快捷的使用命令行参数进行页面生成。

使用方法：

1.将CMSTOP命令行HTML静态页面生成器文件夹上传至cmstop/public/admin

2.可选配置生成页面的方式

3.运行命令（命令请参考“工具说明 2.文件说明 c.exec.php 程序入口文件“的描述，谢谢）

4.完成生成

5.对执行生成所涉及的对应cmstop/public/www下的静态html目录，更改文件及目录的用户及用户组为nginx(或apache),权限为777
如不确定是www下哪些具体目录，可对www下频道目录授权或直接对www目录授权。


工具说明：

1.目录结构
	html-
*********config.php
*********custom.php
*********exec.php
*********html.php
*********readme.txt

2.文件说明：
		a. config.php 配置文件，配置生成文章、组图、视频、列表页的方式
		配置项说明：
		<?php
		return array(
			'show'=>array(
				// 文章配置
				'article'=>array(
					// 生成静态页面的栏目ID列表，多个栏目逗号分隔，不限制栏目配置为0
					'catid'=>0,
					// 开始生成的位置,以contentid升序排序方式，设置limit的offset参数
					'offset'=>0,
					// 单次批量生成的数目
					'pagesize'=>100,
					// 是否关闭此页生成，false将会生成页面，true将不会生成页面
					'off'=>false,
				),
				// 组图配置
				'picture'=>array(
					// 生成静态页面的栏目ID列表，多个栏目逗号分隔，不限制栏目配置为0
					'catid'=>0,
					// 开始生成的位置,以contentid升序排序方式，设置limit的offset参数
					'offset'=>0,
					// 单次批量生成的数目
					'pagesize'=>100,
					// 是否关闭此页生成，false将会生成页面，true将不会生成页面
					'off'=>false,
				),
				// 视频配置
				'video'=>array(
					// 生成静态页面的栏目ID列表，多个栏目逗号分隔，不限制栏目配置为0
					'catid'=>0,
					// 开始生成的位置,以contentid升序排序方式，设置limit的offset参数
					'offset'=>0,
					// 单次批量生成的数目
					'pagesize'=>100,
					// 是否关闭此页生成，false将会生成页面，true将不会生成页面
					'off'=>false,
				),
			),
			// 列表页配置
			'list'=>array(
				// 生成列表页的栏目ID列表，只需配置父栏目ID即可，多个栏目逗号分隔，了限制栏目配置为0
				'catid'=>0,
				// 生成列表页的页数，全部生成，请配置为null
				'maxpage'=>100,
			)
		);
		
		b. custom.php 项目定制内容加载器
		项目中定制的文件加载器，将项目中定制的内容加载至cmstop框架
		
		c.exec.php 程序入口文件
		静态页面生成器入口，在PHP命令行模式下运行，支持的运行方式有：
			1) 根据配置文件生成静态页面
			sudo -u nginx(或者apache) php exec.php
			2) 快捷生成文章、组图、视频、列表页
			sudo -u nginx(或者apache) php exec.php show 0 100
			参数说明：show必选；
					 0为待生成页面的栏目ID列表，多个栏目以逗号分隔，不限制为0，默认为0；
					 100单次批量生成页面的数目，默认为50
			3) 快捷生成文章
			sudo -u nginx(或者apache) php exec.php show_article 0 0 100
			参数说明：show_article必选;
					 第一个0为待生成页面的栏目ID列表，多个栏目以逗号分隔，不限制为0，默认为0；
					 第二个0为开始生成的位置,以contentid升序排序方式，设置limit的offset参数，默认为0；
					 100单次批量生成页面的数目，默认为50
			4) 快捷生成组图
			sudo -u nginx(或者apache) php exec.php show_picture 0 0 100
			参数说明：show_article必选;
					 第一个0为待生成页面的栏目ID列表，多个栏目以逗号分隔，不限制为0，默认为0；
					 第二个0为开始生成的位置,以contentid升序排序方式，设置limit的offset参数，默认为0；
					 100单次批量生成页面的数目，默认为50
			5) 快捷生成视频
			sudo -u nginx(或者apache) php exec.php show_video 0 0 100
			参数说明：show_video必选;
					 第一个0为待生成页面的栏目ID列表，多个栏目以逗号分隔，不限制为0，默认为0；
					 第二个0为开始生成的位置,以contentid升序排序方式，设置limit的offset参数，默认为0；
					 100单次批量生成页面的数目，默认为50
			6）快捷生成列表页
			sudo -u nginx(或者apache) php exec.php show_list 0 100
			参数说明：shot_list必选;
					 第一个0为待生成列表页的栏目ID列表，多个栏目以逗号分隔，不限制为0，默认为0；
					 100生成列表页的数量，设置为0，全部生成
			d. html.php 静态页面生成的功能类文件 		 
