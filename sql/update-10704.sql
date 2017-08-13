SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;
SET time_zone = "+00:00";

-- 会员是否进行了手机号认证
alter table cmstop_member_detail add mobileauth tinyint(1);