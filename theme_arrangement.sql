-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2021-06-03 06:00:44
-- 伺服器版本： 10.4.19-MariaDB
-- PHP 版本： 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `theme_arrangement`
--

-- --------------------------------------------------------

--
-- 資料表結構 `account`
--

CREATE TABLE `account` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `sex` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `participation`
--

CREATE TABLE `participation` (
  `theme_id` int(10) UNSIGNED NOT NULL,
  `attendee_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `tags`
--

CREATE TABLE `tags` (
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `tags`
--

INSERT INTO `tags` (`name`) VALUES
('amusement park'),
('art gallery'),
('dinner'),
('game'),
('movie'),
('shopping');

-- --------------------------------------------------------

--
-- 資料表結構 `themes`
--

CREATE TABLE `themes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `img` varchar(100) NOT NULL,
  `host` int(10) UNSIGNED NOT NULL,
  `description` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `theme_tag`
--

CREATE TABLE `theme_tag` (
  `theme_id` int(10) UNSIGNED NOT NULL,
  `tag` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `valid`
--

CREATE TABLE `valid` (
  `valid_id` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `sex` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`theme_id`,`attendee_id`),
  ADD KEY `attendee_id` (`attendee_id`);

--
-- 資料表索引 `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`name`);

--
-- 資料表索引 `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `host` (`host`);

--
-- 資料表索引 `theme_tag`
--
ALTER TABLE `theme_tag`
  ADD PRIMARY KEY (`theme_id`,`tag`),
  ADD KEY `tag` (`tag`);

--
-- 資料表索引 `valid`
--
ALTER TABLE `valid`
  ADD PRIMARY KEY (`valid_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `participation_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`),
  ADD CONSTRAINT `participation_ibfk_2` FOREIGN KEY (`attendee_id`) REFERENCES `account` (`id`);

--
-- 資料表的限制式 `themes`
--
ALTER TABLE `themes`
  ADD CONSTRAINT `themes_ibfk_1` FOREIGN KEY (`host`) REFERENCES `account` (`id`);

--
-- 資料表的限制式 `theme_tag`
--
ALTER TABLE `theme_tag`
  ADD CONSTRAINT `theme_tag_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`),
  ADD CONSTRAINT `theme_tag_ibfk_2` FOREIGN KEY (`tag`) REFERENCES `tags` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
