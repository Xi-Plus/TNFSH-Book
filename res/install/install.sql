SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `account` (
  `account` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(20) NOT NULL,
  `grade` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bookgroup` (
  `groupid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `grade` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `booklist` (
  `bookid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `money` int(11) NOT NULL,
  `bookgroup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `log` (
  `account` varchar(20) NOT NULL,
  `page` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `detail` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orderlist` (
  `account` varchar(20) NOT NULL,
  `groupid` varchar(20) NOT NULL,
  `books` text NOT NULL,
  `hash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `session` (
  `account` varchar(20) NOT NULL,
  `cookie` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `system` (
  `id` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `value` text NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `system` (`id`, `type`, `value`, `comment`) VALUES
('announcement_admin', 'textarea', '<h1>教科書訂購系統 管理首頁</h1>', '管理員公告'),
('announcement_user', 'textarea', '<h1>教科書訂購系統 首頁</h1>', '使用者公告'),
('reCAPTCHA_secret_key', 'text', 'your_reCAPTCHA_secret_key', '驗證碼 secret key'),
('reCAPTCHA_site_key', 'text', 'your_reCAPTCHA_site_key', '驗證碼 site key'),
('reCAPTCHA_status', 'checkbox', '1', '開啟驗證碼');


ALTER TABLE `account`
  ADD UNIQUE KEY `account` (`account`);

ALTER TABLE `bookgroup`
  ADD PRIMARY KEY (`groupid`);

ALTER TABLE `booklist`
  ADD PRIMARY KEY (`bookid`);

ALTER TABLE `orderlist`
  ADD UNIQUE KEY `hash` (`hash`);

ALTER TABLE `session`
  ADD UNIQUE KEY `cookie` (`cookie`);

ALTER TABLE `system`
  ADD UNIQUE KEY `id` (`id`);


ALTER TABLE `bookgroup`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `booklist`
  MODIFY `bookid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
