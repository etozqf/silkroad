-- 微信
INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES
('wechat', '微信', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1386140482, 1386140482, 0);
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, 5, '5', NULL, '微信', '?app=wechat', NULL, 0);