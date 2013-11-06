-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 11 月 05 日 08:50
-- 服务器版本: 5.5.29
-- PHP 版本: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tang`
--

-- --------------------------------------------------------

--
-- 表的结构 `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE IF NOT EXISTS `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '0代表未定义。正式商圈从1开始。',
  `name` varchar(64) NOT NULL DEFAULT '',
  `county_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `area`
--

INSERT INTO `area` (`id`, `name`, `county_id`) VALUES
(0, '其他', -1),
(1, '上海市场', 1),
(2, '广州市场', 1),
(3, '新都汇', 2),
(4, '万达广场', 1),
(5, '谷水', 1),
(7, '西苑公园', 1);

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `content`, `restaurant_id`, `create_datetime`) VALUES
(31, 5, '配的饼最近质量下降了，原来很酥脆，现在很软，估计供不应求。', 1, '2013-09-23 14:30:47'),
(32, 4, '敖德萨发顺丰\r\n', 4, '2013-10-14 10:03:32'),
(33, 6, '之前没有喝过丸子汤，前段时间去喝了一次，感觉很好喝，排队人超长。注：只晚上卖丸子汤。', 10, '2013-10-15 17:50:44'),
(34, 6, '我现在基本喝牛肉汤都到这家，汤喝起来清淡，料味没那么重。这家汤馆开了应该有10年吧。', 16, '2013-10-15 23:17:44'),
(35, 6, '驴肉汤我平时喝的少，但是匡家的汤喝起来比怀府的要油点。这里的油旋馍吃起来不赖。', 15, '2013-10-15 23:20:59'),
(36, 4, '这家汤连续来过几次，豆腐非常嫩，配上每天自己炸的面丸子，属于涧西吃不着的风味。\r\n另外，紧挨着那家的胡辣汤也不错，4块钱一碗，牛肉的，配上2块钱一张的油饼，或水煎包，都不错。', 14, '2013-10-15 23:31:33'),
(37, 4, '七一路大张超时西门外，偶尔喝一个，没啥特色，喜欢豆腐汤的可以当做日常饮食。', 9, '2013-10-15 23:34:06'),
(38, 4, '今天早上又去喝了一次，有几个发现：\r\n1，原来旁边那家开了好几年的白沙羊肉汤关门了。囧。不过旁边的匡家驴肉汤还在。\r\n2，选用的肉有一些筋头巴脑，这跟龙鳞路的白沙比起来差远了。\r\n3，下作料的时候注意了一下，放了一勺味精，太不健康了。\r\n4，门口的饼质量持续走低，已经变成普通烧饼了。', 1, '2013-10-24 17:27:54'),
(39, 6, '今天早上去喝汤，发现平时舀汤的师傅在喝汤，我顿时感觉这家汤绝对能放心喝。', 16, '2013-10-24 21:17:36'),
(40, 4, '今天早上又来了一次，不能忍了，总结一下最近不好的地方：\r\n1，汤里面味精太多，喝完渴得很。\r\n2，八点半去已经吃不到现做的油酥烧饼了，都放在泡沫盒子里，潮潮的，软软的，一点口感都没有。（但还是比烧饼好吃）', 14, '2013-10-25 10:27:37'),
(41, 7, '这是我在洛阳喝的最好喝的丸子汤了，每次去都没有位置，都是搬着小凳子坐到外面喝的，就那都可爽。', 10, '2013-10-29 13:15:50'),
(42, 4, '丸子——大、焦脆！\r\n就是人太多，都要挤到门口街道上挨着坐，夏天喝汤不出三层汉不算完。\r\n一开始去只喝三块的，后来喝过五块和十块的，并不感觉单调，剔骨肉和丸子配合的相当好。', 10, '2013-10-30 13:24:44'),
(43, 4, '老店了，货真价实，但是要提前说好少放点调料。丸子和肉还是不错的。', 3, '2013-11-04 17:32:26');

-- --------------------------------------------------------

--
-- 表的结构 `county`
--

DROP TABLE IF EXISTS `county`;
CREATE TABLE IF NOT EXISTS `county` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 区；1 县',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `county`
--

INSERT INTO `county` (`id`, `name`, `type`) VALUES
(1, '涧西区', 0),
(2, '西工区', 0),
(3, '老城区', 0),
(4, '洛龙区', 0),
(5, '伊川县', 1),
(6, '宜阳县', 1),
(7, '孟津县', 1);

-- --------------------------------------------------------

--
-- 表的结构 `feature`
--

DROP TABLE IF EXISTS `feature`;
CREATE TABLE IF NOT EXISTS `feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `feature`
--

INSERT INTO `feature` (`id`, `restaurant_id`, `feature_id`) VALUES
(1, 1, 1),
(7, 15, 4),
(8, 16, 3),
(9, 2, 3),
(10, 2, 5),
(11, 2, 6),
(12, 14, 1),
(13, 14, 4);

-- --------------------------------------------------------

--
-- 表的结构 `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
CREATE TABLE IF NOT EXISTS `restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(64) NOT NULL DEFAULT '',
  `business_hour` varchar(128) NOT NULL DEFAULT '6:00 - 20:00',
  `address` varchar(128) NOT NULL,
  `county_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属县、区',
  `area_id` int(11) NOT NULL DEFAULT '9999' COMMENT '商圈范围。',
  `is_shutdown` tinyint(4) NOT NULL DEFAULT '0',
  `is_checked` int(11) NOT NULL DEFAULT '0' COMMENT '是否经过审核',
  `image_url` varchar(256) NOT NULL DEFAULT '',
  `coordinate` varchar(64) NOT NULL DEFAULT '0' COMMENT '经度在前',
  `description` varchar(256) NOT NULL DEFAULT '',
  `votes` int(11) NOT NULL DEFAULT '0' COMMENT '投票总数',
  `average_points` float NOT NULL DEFAULT '0' COMMENT '平均分',
  `weighted_points` float NOT NULL DEFAULT '0',
  `creator` int(11) NOT NULL COMMENT '汤馆创建者id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `restaurant`
--

INSERT INTO `restaurant` (`id`, `name`, `type_id`, `phone`, `business_hour`, `address`, `county_id`, `area_id`, `is_shutdown`, `is_checked`, `image_url`, `coordinate`, `description`, `votes`, `average_points`, `weighted_points`, `creator`) VALUES
(1, '大石桥羊肉汤', 2, '13849996518', '6:00 - 12:00', '建设路24号', 1, 2, 0, 1, '/images/restaurant/profile_1.png', '34.6600647082123,112.399540274208', '好喝', 1, 0, 2.36364, 4),
(2, '清真牛肉汤', 1, '', '6:00 - 20:00', '涧西区西苑路2号', 1, 1, 0, 1, '/images/restaurant/profile_2.png', '', '好地方。', 2, 4, 2.83333, 6),
(3, '怀府驴肉汤', 3, '15038595869', '6:00 - 20:00', '洛阳市西工区健康路8号', 2, 1, 0, 1, '/images/restaurant/profile_3.png', '', '好地方。', 2, 5.05, 3, 7),
(4, '白沙羊肉汤', 2, '15038595869', '6:00 - 20:00', '洛阳市西工区七一路30号', 4, 1, 1, 1, '/images/restaurant/profile_4.jpg', '34.6678708489134,112.450282304352', '好地方。', 2, 4, 2.83333, 4),
(5, '大众牛肉汤', 1, '15038595869', '6:00 - 20:00', '洛阳市洛龙区龙翔路99号', 4, 0, 0, 1, '', '34.6233340894468,112.426142423218', '好地方。', 1, 4, 2.72727, 6),
(6, '大众牛肉汤', 1, '15038595869', '6:00 - 20:00', '洛阳市涧西区湖北路87号', 1, 1, 0, 1, '', '34.6601023605574,112.393049328392', '好地方。', 1, 3, 2.63636, 7),
(7, '百碗羊汤', 2, '1', '6:00 - 20:00', '洛阳市西工区解放路80号', 2, 0, 0, 1, '', '', '好地方。', 0, 0, 2.6, 4),
(8, '百碗羊汤', 2, '15038595869', '6:00 - 20:00', '洛阳市老城区金业路11号', 1, 0, 0, 1, '', '34.6813641077852,112.470345227783', '好地方。', 0, 0, 2.6, 6),
(9, '桥头豆腐汤', 4, '15038595869', '6:00 - 20:00', '洛阳市西工区七一路11号', 1, 0, 0, 1, '', '34.6686355990339,112.44952592141', '好地方。', 1, 2, 2.54545, 7),
(10, '赵记丸子汤', 4, '15038595869', '6:00 - 20:00', '洛阳市老城区民主街881号', 3, 0, 0, 1, '', '34.6795942705833,112.479513018196', '好地方。', 3, 4.33333, 2.72727, 4),
(11, '大众牛肉汤', 1, '', '6:00 - 20:00', '西工区九都路98号', 0, 0, 0, 1, '', '', '好地方。', 0, 0, 0, 6),
(14, '老城豆腐汤', 5, '', '6:00 - 20:00', '长安路景华路交叉口南200米，路东', 1, 1, 0, 1, '', '34.6652647659882,112.377492516106', '好地方。', 1, 4, 0, 7),
(15, '匡家驴肉汤', 3, '', '6:00 - 12:00', '中医院对面，银川路往北30米', 1, 7, 0, 1, '', '34.6484217829363,112.402442424362', '', 1, 3, 0, 4),
(16, '孙家牛肉汤', 1, '', '6:00 - 12:00', '南昌路与九都路交叉口，往东50米', 1, 7, 0, 1, '', '34.6490881540684,112.406508653229', '好地方。', 1, 4, 0, 6),
(17, '白沙羊肉汤', 2, '', '6:00 - 13:00', '建设路与中州路交叉口东100米，路南', 1, 5, 0, 1, '', '34.6774061814842,112.356533734863', '好地方。', 1, 3, 0, 7),
(18, '马记第一家牛肉汤', 1, '', '6:00 - 20:00', '龙鳞路西苑路交叉口南200米，路东', 1, 1, 0, 1, '', '34.6607598129607,112.376435725754', '好地方。', 1, 4, 0, 5),
(19, '华山路小碗驴肉', 3, '', '6:00 -13:00 ', '龙鳞路西苑路交叉口南200米，路东', 1, 1, 0, 0, '', '34.6605656664837,112.376140682762', '好地方。', 1, 4, 0, 5);

-- --------------------------------------------------------

--
-- 表的结构 `restaurant_feature`
--

DROP TABLE IF EXISTS `restaurant_feature`;
CREATE TABLE IF NOT EXISTS `restaurant_feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(16) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `restaurant_feature`
--

INSERT INTO `restaurant_feature` (`id`, `type`, `name`, `image_url`) VALUES
(1, 1, '炸面丸子', ''),
(2, 1, '炸豆丸子', ''),
(3, 0, '清真', ''),
(4, 2, '油旋馍', ''),
(5, 2, '饼丝', ''),
(6, 2, '烧饼', '');

-- --------------------------------------------------------

--
-- 表的结构 `restaurant_status`
--

DROP TABLE IF EXISTS `restaurant_status`;
CREATE TABLE IF NOT EXISTS `restaurant_status` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `restaurant_status`
--

INSERT INTO `restaurant_status` (`id`, `name`) VALUES
(0, '正常营业'),
(1, '临时关闭'),
(2, '永久关闭');

-- --------------------------------------------------------

--
-- 表的结构 `restaurant_type`
--

DROP TABLE IF EXISTS `restaurant_type`;
CREATE TABLE IF NOT EXISTS `restaurant_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `restaurant_type`
--

INSERT INTO `restaurant_type` (`id`, `name`) VALUES
(1, '牛肉汤'),
(2, '羊肉汤'),
(3, '驴肉汤'),
(4, '丸子汤'),
(5, '豆腐汤');

-- --------------------------------------------------------

--
-- 表的结构 `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `key` varchar(64) NOT NULL,
  `value` varchar(128) NOT NULL DEFAULT '0',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `setting`
--

INSERT INTO `setting` (`key`, `value`) VALUES
('get_new_vote', '1');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension_user_id` bigint(11) NOT NULL,
  `nick_name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `image_url` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `role` int(11) NOT NULL COMMENT '0 normal 1 admin',
  `source` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `extension_user_id`, `nick_name`, `image_url`, `role`, `source`) VALUES
(4, 1655929253, '刘万伟', 'http://tp2.sinaimg.cn/1655929253/50/5658842323/1', 1, 1),
(5, 2147483647, '比赛闹钟', 'http://tp4.sinaimg.cn/2472803787/50/5620173593/1', 0, 1),
(6, 1733875695, '毛_宇', 'http://tp4.sinaimg.cn/1733875695/50/5647446298/1', 1, 1),
(7, 1914550097, '草-蛋', 'http://tp2.sinaimg.cn/1914550097/50/5669003732/1', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL COMMENT '1-10分',
  `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `vote`
--

INSERT INTO `vote` (`id`, `user_id`, `restaurant_id`, `rating`, `create_datetime`) VALUES
(18, 4, 4, 3, '0000-00-00 00:00:00'),
(19, 4, 1, 0, '0000-00-00 00:00:00'),
(20, 7, 3, 5, '0000-00-00 00:00:00'),
(21, 7, 5, 4, '0000-00-00 00:00:00'),
(22, 7, 4, 5, '0000-00-00 00:00:00'),
(23, 6, 10, 4, '0000-00-00 00:00:00'),
(24, 6, 15, 3, '0000-00-00 00:00:00'),
(25, 6, 16, 4, '0000-00-00 00:00:00'),
(26, 6, 6, 3, '0000-00-00 00:00:00'),
(27, 6, 3, 5, '0000-00-00 00:00:00'),
(28, 6, 2, 4, '0000-00-00 00:00:00'),
(29, 4, 14, 4, '0000-00-00 00:00:00'),
(30, 4, 9, 2, '0000-00-00 00:00:00'),
(31, 7, 10, 4, '2013-10-29 05:14:40'),
(32, 4, 17, 3, '2013-10-29 10:06:25'),
(33, 4, 18, 4, '2013-10-29 10:09:44'),
(34, 4, 19, 4, '2013-10-29 10:11:44'),
(35, 4, 10, 5, '2013-10-30 05:24:51');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
