-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2021 年 05 月 14 日 20:28
-- 伺服器版本： 5.6.51
-- PHP 版本： 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `anbon_sales`
--

-- --------------------------------------------------------

--
-- 資料表結構 `customer_mgr`
--

CREATE TABLE `customer_mgr` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_no` int(11) NOT NULL COMMENT '客戶編號',
  `photo` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_id` int(11) NOT NULL COMMENT '來源，參照來源選項表',
  `relation` enum('S','A','B','C','D') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('S','A','B','C','D') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `city` int(3) NOT NULL DEFAULT '0',
  `dist` int(5) NOT NULL DEFAULT '100',
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '詳細地址',
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '經度',
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '緯度',
  `text` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客戶概況',
  `status` enum('inform','reservation','visit','propose','deal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inform' COMMENT '已告知,已約訪,已拜訪,已建議,已成交',
  `is_field` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(目前這個沒用了)是否有額外自訂義的欄位',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `customer_mgr`
--

INSERT INTO `customer_mgr` (`id`, `user_id`, `customer_no`, `photo`, `name`, `source_id`, `relation`, `level`, `job`, `birthday`, `city`, `dist`, `address`, `latitude`, `longitude`, `text`, `status`, `is_field`, `create_date`, `is_delete`) VALUES
(1, 1, 20, 'uploads/customer_manage/img3.png', '陳美麗啊', 6, 'B', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 1, '2021-04-01 11:57:37', 0),
(2, 1, 20, 'uploads/customer_manage/img6.png', '吳嘉華', 2, 'A', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'reservation', 1, '2021-04-01 11:57:37', 0),
(3, 1, 0, NULL, '帥哥嘟嘟', 2, 'A', 'B', '老師', '0000-00-00', 0, 100, '', NULL, NULL, 'adad', 'inform', 0, '2021-04-01 11:57:37', 0),
(4, 1, 0, NULL, '帥哥嘟嘟', 2, 'A', 'B', '老師', '0000-00-00', 0, 100, '', NULL, NULL, 'adad', 'inform', 0, '2021-04-01 11:57:37', 0),
(5, 1, 0, NULL, '帥哥嘟嘟', 2, 'A', 'B', '老師', '0000-00-00', 0, 100, '', NULL, NULL, 'adad', 'inform', 0, '2021-04-01 11:57:37', 0),
(6, 1, 0, NULL, '帥哥嘟嘟', 2, 'A', 'B', '老師', '0000-00-00', 0, 100, '', NULL, NULL, 'adad', 'inform', 0, '2021-04-01 11:57:37', 0),
(12, 1, 0, NULL, '帥哥嘟嘟3號', 8, 'S', 'S', '老師', NULL, 2, 241, NULL, NULL, NULL, 'adad', 'inform', 0, '2021-04-01 12:32:00', 0),
(13, 1, 0, NULL, '帥哥嘟嘟4號', 2, 'S', 'S', '老師', NULL, 7, 400, NULL, NULL, NULL, 'adad', 'inform', 0, '2021-04-01 12:34:42', 0),
(35, 16, 4, NULL, '王彥雄', 1, 'B', 'C', '保全', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:27:10', 0),
(15, 1, 0, NULL, '帥哥嘟嘟5號', 2, 'S', 'S', '老師', NULL, 0, 100, NULL, NULL, NULL, 'adad', 'inform', 0, '2021-04-01 18:48:49', 0),
(17, 1, 0, NULL, '帥哥嘟嘟5號', 2, 'B', 'A', '老師', NULL, 0, 100, NULL, NULL, NULL, 'adad', 'inform', 0, '2021-04-01 18:53:14', 0),
(28, 1, 0, NULL, '黃浩哲', 5, 'A', 'A', '同事', NULL, 2, 241, NULL, NULL, NULL, '嗯嗯嗯嗯嗯', 'inform', 0, '2021-04-01 20:58:12', 0),
(29, 1, 0, NULL, '媽媽嗎', 1, 'S', 'S', '喔喔喔喔', '2011-04-01', 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 21:30:56', 0),
(30, 1, 0, NULL, 'xxxxx', 4, 'C', 'D', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 21:50:06', 0),
(31, 1, 0, NULL, 'AAA', 1, 'S', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 22:47:56', 0),
(32, 16, 1, 'uploads/customer_manage/img14.png', 'Peter', 6, 'S', 'S', NULL, NULL, 0, 106, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:13:14', 0),
(33, 16, 2, NULL, 'Jenny', 6, 'C', 'B', '生物科技', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:14:12', 0),
(34, 16, 3, NULL, '王千佳', 5, 'C', 'C', '檳榔', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:26:16', 0),
(26, 1, 20, 'uploads/customer_manage/1621.jpg', '王浩浩0', 6, 'A', 'S', '同事', NULL, 2, 241, NULL, NULL, NULL, 'adadsadasdsasadsadsad', 'inform', 0, '2021-04-01 19:25:15', 0),
(27, 1, 0, NULL, 'CTFA', 2, 'D', 'D', '老師dk kd', NULL, 0, 241, NULL, NULL, NULL, 'adad', 'inform', 0, '2021-04-01 19:29:26', 0),
(36, 16, 5, NULL, '王治承', 1, 'C', 'B', '土地開發', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:28:28', 0),
(37, 16, 6, NULL, '劉興曄', 6, 'B', 'C', '傳產', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-01 23:32:44', 0),
(38, 16, 7, NULL, '朱亭慈', 1, 'B', 'D', '旅遊', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:50:17', 0),
(39, 16, 8, NULL, '朱姵菱', 1, 'A', 'C', '博弈', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:51:14', 0),
(40, 16, 9, NULL, '朱淑賢', 1, 'A', 'B', '早餐', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:52:02', 0),
(41, 16, 10, NULL, '李志平', 1, 'C', 'B', '復健師', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:52:52', 0),
(42, 16, 11, NULL, '李愷悌', 1, 'C', 'A', '銀行', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:53:33', 0),
(43, 16, 12, NULL, '李韋德', 1, 'C', 'B', '工程師', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:58:11', 0),
(44, 16, 13, NULL, '何佩儀', 1, 'S', 'C', '電器', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 10:58:59', 0),
(45, 16, 14, NULL, '何佩瑜', 5, 'D', 'C', '牙助', NULL, 2, 231, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-02 11:00:12', 0),
(46, 16, 15, NULL, '李佩佳', 24, 'B', 'B', '銀行', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 11:56:00', 0),
(47, 16, 16, NULL, '林雅婷', 1, 'B', 'A', '旅遊', NULL, 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 11:57:05', 0),
(48, 16, 17, NULL, '林欣穎', 5, 'C', 'C', '窗簾', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:09:48', 0),
(49, 16, 18, NULL, '陳承侯', 6, 'A', 'B', '食品', NULL, 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:10:28', 0),
(50, 16, 19, NULL, '邱翔', 1, 'B', 'C', '消防器材', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:11:09', 0),
(51, 16, 20, NULL, '邱倍儀', 1, 'B', 'B', '藥局', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:11:48', 0),
(52, 16, 21, NULL, '周維昱', 1, 'A', 'C', '木工', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:12:58', 0),
(53, 16, 204, 'uploads/customer_manage/img42.png', '林志恆', 1, 'B', 'C', '海運', NULL, 0, 110, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:13:30', 0),
(54, 16, 204, 'uploads/customer_manage/img41.png', '徐若帆', 1, 'A', 'C', '舞蹈藝術', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:16:13', 0),
(55, 16, 24, 'uploads/customer_manage/img8.png', '徐順儒', 1, 'A', 'A', '臍帶血', NULL, 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:17:00', 0),
(56, 16, 25, NULL, '侯景翔', 5, 'B', 'C', '消防隊', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:17:43', 0),
(57, 16, 204, 'uploads/customer_manage/img40.png', '高翊馨', 1, 'S', 'B', '電子', NULL, 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:18:39', 0),
(58, 16, 27, NULL, '莊嘉智', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:21:19', 0),
(59, 16, 204, 'uploads/customer_manage/img39.png', '郭韋汝', 24, 'C', 'B', '玉山銀行', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:22:37', 0),
(60, 16, 29, NULL, '何佩玲', 5, 'C', 'B', '房仲', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:23:12', 0),
(61, 16, 30, NULL, '張家瑋', 1, 'C', 'D', '三商美邦', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:23:54', 0),
(62, 16, 31, NULL, '張紡瑛', 3, 'B', 'B', '台積電', NULL, 7, 406, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:24:54', 0),
(63, 16, 32, NULL, '張瑋峰', 5, 'C', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:25:56', 0),
(64, 16, 33, NULL, '黃思愷', 1, 'S', 'A', '汽車包膜', NULL, 0, 116, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:26:41', 0),
(65, 16, 34, NULL, '黃瑞民', 5, 'A', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:28:15', 0),
(66, 16, 35, NULL, '楊立德', 1, 'S', 'A', '工程師', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:32:36', 0),
(67, 16, 36, NULL, '鄭憶雯', 2, 'B', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:33:12', 0),
(68, 16, 37, NULL, '劉品宏', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '國中同學', 'inform', 0, '2021-04-05 12:33:40', 0),
(69, 16, 212, NULL, '許恒韶', 5, 'C', 'C', NULL, NULL, 3, 320, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:34:16', 0),
(70, 16, 39, NULL, '戴啟宇', 1, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:34:36', 0),
(71, 16, 40, NULL, '蕭文浩', 2, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:34:59', 0),
(72, 16, 41, NULL, '簡淑惠', 1, 'A', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:35:27', 0),
(73, 16, 42, NULL, '龔正綱', 1, 'C', 'C', '工程師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:36:22', 0),
(74, 16, 43, NULL, '陳名揚', 1, 'A', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:37:15', 0),
(75, 16, 297, NULL, '陳皇旭', 6, 'S', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:37:54', 0),
(76, 16, 45, NULL, '琮涵', 2, 'A', 'B', NULL, NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:38:41', 0),
(77, 16, 46, NULL, '鍾詩晟', 1, 'A', 'B', '中國時報', NULL, 2, 221, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:39:53', 0),
(78, 16, 47, NULL, '吳任仙', 5, 'B', 'B', '華碩', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:41:23', 0),
(79, 16, 48, NULL, '吳依潔', 1, 'A', 'B', '大倉久和 餐飲', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:42:28', 0),
(80, 16, 49, NULL, '林志勳', 1, 'B', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:43:05', 0),
(81, 16, 50, 'uploads/customer_manage/img20.png', '陳昱任', 5, 'S', 'S', '汽車材料', NULL, 0, 100, '新北市蘆洲區民族路422巷', NULL, NULL, '勝美汽車', 'inform', 0, '2021-04-05 12:44:26', 0),
(82, 16, 51, NULL, '賈翔傑', 1, 'S', 'C', '按摩', NULL, 0, 104, '台北市中山區林森北路68號', NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:46:25', 0),
(83, 16, 52, 'uploads/customer_manage/img21.png', '陳科源', 5, 'A', 'S', '元大證券', '2021-04-05', 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:53:30', 0),
(84, 16, 53, NULL, '陳敬文', 3, 'A', 'A', '餐飲', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:53:57', 0),
(85, 16, 54, NULL, '林芳如', 6, 'A', 'B', NULL, NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:54:25', 0),
(86, 16, 55, NULL, '劉淑鏗', 5, 'B', 'B', '餐飲', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:55:22', 0),
(87, 16, 56, NULL, '劉淑伶', 5, 'C', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:58:36', 0),
(88, 16, 57, NULL, '周美玲', 5, 'C', 'B', '清潔', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 12:59:06', 0),
(89, 16, 58, NULL, '蔡明忻', 5, 'C', 'A', '直銷', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:00:30', 0),
(90, 16, 59, NULL, '林京諭', 5, 'B', 'A', '清潔', NULL, 7, 406, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:01:16', 0),
(91, 16, 60, 'uploads/customer_manage/img22.png', '陳世宗', 5, 'B', 'S', NULL, NULL, 2, 231, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:02:13', 0),
(92, 16, 61, NULL, '黃耀萱', 2, 'A', 'A', '華碩電腦', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:02:53', 0),
(93, 16, 263, NULL, '廖慧珍', 1, 'B', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:04:02', 0),
(94, 16, 63, NULL, '李啟郁', 1, 'S', 'B', '咖啡', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:04:42', 0),
(95, 16, 64, NULL, '張維雅', 1, 'B', 'B', '服飾', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:05:14', 0),
(96, 16, 65, NULL, '賈翔云', 5, 'B', 'B', '華碩電腦', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:05:59', 0),
(97, 16, 66, NULL, '王宸婕', 5, 'B', 'C', '台北捷運', NULL, 0, 115, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:06:33', 0),
(98, 16, 67, NULL, '張偉倫', 2, 'S', 'B', '永慶房屋', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:07:18', 0),
(99, 16, 68, NULL, '金玉宇', 2, 'S', 'B', '永慶房屋', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:07:48', 0),
(100, 16, 69, NULL, '張紡綾', 5, 'S', 'B', '造紙業', NULL, 2, 231, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:09:04', 0),
(101, 16, 70, NULL, '李培豪', 5, 'S', 'B', '造紙業', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:09:32', 0),
(102, 16, 71, NULL, '李依純', 6, 'A', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:10:34', 0),
(103, 16, 72, NULL, '吳崇佑', 6, 'S', 'B', '豆桑豆漿', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:12:39', 0),
(104, 16, 73, NULL, '陳宣名', 5, 'S', 'A', '影像', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:13:05', 0),
(105, 16, 74, NULL, '胡紹緯', 2, 'A', 'B', '客運', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:14:35', 0),
(106, 16, 75, NULL, '黃金華', 5, 'B', 'B', '飯店', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:15:16', 0),
(107, 16, 76, NULL, '吳佳珣', 24, 'S', 'A', '兆豐銀行', NULL, 4, 300, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:16:07', 0),
(108, 16, 77, NULL, '柯俊廷', 1, 'A', 'A', '工程師', NULL, 0, 114, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:16:45', 0),
(109, 16, 78, NULL, '林哲仲', 2, 'A', 'A', '永和豆漿', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:17:58', 0),
(110, 16, 79, NULL, '林京樺', 2, 'A', 'B', '補習班', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:18:31', 0),
(111, 16, 80, NULL, '周子傑', 1, 'A', 'D', '友邦人壽', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:19:14', 0),
(112, 16, 81, NULL, '張又生', 2, 'S', 'S', '烘焙', NULL, 3, 334, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:19:42', 0),
(113, 16, 82, 'uploads/customer_manage/img24.png', '陳博棋', 3, 'S', 'S', '餐飲', NULL, 0, 114, NULL, NULL, NULL, '金春發', 'inform', 0, '2021-04-05 13:20:25', 0),
(114, 16, 83, NULL, '姜健民', 2, 'A', 'A', '退休', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:21:22', 0),
(115, 16, 84, NULL, '馬詩屏', 2, 'S', 'A', '退休', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:22:15', 0),
(116, 16, 85, NULL, '趙曏祐', 5, 'A', 'B', 'Skoda汽車', NULL, 0, 114, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:23:03', 0),
(117, 16, 86, 'uploads/customer_manage/img15.png', '姚俊宇', 1, 'S', 'S', '按摩', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:23:36', 0),
(118, 16, 87, NULL, '連世博', 6, 'S', 'S', '阿田麵 餐飲', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:25:22', 0),
(119, 16, 88, NULL, '連信安', 6, 'B', 'B', '阿田麵 餐飲', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:25:51', 0),
(120, 16, 89, NULL, '鄭保珠', 6, 'S', 'S', '阿田麵 餐飲', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:26:41', 0),
(121, 16, 90, NULL, '連怡均', 6, 'B', 'B', NULL, NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:28:34', 0),
(122, 16, 91, 'uploads/customer_manage/img16.png', '邱麗純', 2, 'A', 'S', '美容', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:33:54', 0),
(123, 16, 92, NULL, '王荷青', 5, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:35:00', 0),
(124, 16, 93, NULL, '莊秀娥', 5, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:35:38', 0),
(125, 16, 94, NULL, '謝月娥', 3, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:36:12', 0),
(126, 16, 95, NULL, '熱炒邱', 5, 'A', 'A', '客來熱炒 餐飲', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:37:31', 0),
(127, 16, 96, 'uploads/customer_manage/img17.png', '蔡杰軒', 5, 'B', 'S', '外勞仲介', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:38:11', 0),
(128, 16, 97, NULL, '盧惠玉', 6, 'C', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:38:39', 0),
(129, 16, 98, NULL, '劉鴻喜', 6, 'A', 'B', '清潔', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:39:11', 0),
(130, 16, 99, 'uploads/customer_manage/img18.png', '羅景家', 5, 'C', 'S', '醫美醫師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:40:07', 0),
(131, 16, 100, NULL, '陳一誠', 6, 'S', 'B', '刑警', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:40:38', 0),
(132, 16, 101, NULL, '高俐娟', 5, 'A', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:41:17', 0),
(133, 16, 102, NULL, '駱俊賢', 6, 'B', 'B', '教師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:41:41', 0),
(134, 16, 103, NULL, '黃健佑', 1, 'S', 'B', '英文教師', NULL, 2, 207, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:42:38', 0),
(135, 16, 104, NULL, '洪碩凱', 2, 'S', 'B', '轉雞 炸雞', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:43:43', 0),
(136, 16, 105, NULL, '許嘉恩', 1, 'S', 'B', '五十嵐 飲料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:44:09', 0),
(137, 16, 106, NULL, '李宗學', 2, 'C', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:44:38', 0),
(138, 16, 107, NULL, '邱弘甫', 2, 'C', 'B', '海運', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:45:10', 0),
(139, 16, 108, NULL, '林曉玫', 2, 'A', 'B', '出版社', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:46:00', 0),
(140, 16, 109, NULL, '簡懿驛', 2, 'S', 'B', '電影院', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:46:51', 0),
(141, 16, 110, NULL, '花花', 2, 'C', 'C', '電影院', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:47:11', 0),
(142, 16, 111, NULL, '陳彥翔', 2, 'C', 'C', '房仲', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:47:44', 0),
(143, 16, 112, NULL, '陳彥旭', 1, 'B', 'C', '房仲', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:48:09', 0),
(144, 16, 113, NULL, '曾婉婷', 1, 'A', 'B', '國票', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:48:43', 0),
(145, 16, 114, NULL, '柯佳妤', 1, 'S', 'C', '服飾', NULL, 0, 100, NULL, NULL, NULL, '好好。', 'inform', 0, '2021-04-05 13:52:05', 0),
(146, 16, 115, NULL, '林洛安', 1, 'B', 'C', '出版社', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:52:44', 0),
(147, 16, 116, NULL, '李睿原', 2, 'S', 'S', '禮贈品', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:53:21', 0),
(148, 16, 117, NULL, '李有恭', 5, 'A', 'S', '退休', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:53:54', 0),
(149, 16, 118, NULL, '李佳燕', 1, 'S', 'B', '廣告', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:54:19', 0),
(150, 16, 119, NULL, '施欽佩', 1, 'A', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:55:07', 0),
(151, 16, 120, NULL, '鄭仁豪', 6, 'B', 'C', '玉山銀行', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:56:12', 0),
(152, 16, 121, NULL, 'Alvin', 5, 'B', 'A', '財會', NULL, 0, 100, NULL, NULL, NULL, '學弟', 'inform', 0, '2021-04-05 13:57:01', 0),
(153, 16, 122, NULL, '陳家愷', 1, 'B', 'B', '工程師', NULL, 0, 100, NULL, NULL, NULL, '學弟', 'inform', 0, '2021-04-05 13:57:36', 0),
(154, 16, 123, NULL, '徐帆', 1, 'A', 'B', '寵物美容', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:58:12', 0),
(155, 16, 195, 'uploads/customer_manage/img35.png', '邱凱貞', 1, 'S', 'B', '室內設計', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:58:49', 0),
(156, 16, 125, 'uploads/customer_manage/img27.png', '鄭云婷', 1, 'S', 'A', '藥廠', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:59:17', 0),
(157, 16, 126, 'uploads/customer_manage/img34.png', '吳宗祐', 5, 'S', 'A', '健身教練', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 13:59:47', 0),
(158, 16, 127, 'uploads/customer_manage/img13.png', '翁瑞華', 5, 'A', 'S', '退休', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:00:11', 0),
(159, 16, 128, 'uploads/customer_manage/img33.png', '鄭云筑', 5, 'S', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:00:55', 0),
(160, 16, 129, 'uploads/customer_manage/img31.png', '簡志安', 5, 'S', 'C', '膳魔斯 ', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:01:37', 0),
(161, 16, 130, 'uploads/customer_manage/img32.png', '鄭云捷', 5, 'A', 'C', NULL, NULL, 0, 114, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:02:16', 0),
(162, 16, 131, NULL, '楊忠翰', 6, 'B', 'C', '服飾', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:03:05', 0),
(163, 16, 132, NULL, '廖泓嘉', 2, 'C', 'C', '飲料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:03:37', 0),
(164, 16, 133, NULL, '許淙皓', 1, 'A', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:04:15', 0),
(165, 16, 134, NULL, '楊于欣', 5, 'C', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:04:44', 0),
(166, 16, 135, NULL, '吳孟育', 1, 'S', 'B', '教師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:05:07', 0),
(167, 16, 136, NULL, '高雨霈', 1, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:05:39', 0),
(168, 16, 137, NULL, '陳思翰', 1, 'C', 'C', '補習班', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:09:29', 0),
(169, 16, 138, NULL, '黄烽如', 2, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:12:03', 0),
(170, 16, 139, NULL, '陳弘殷', 2, 'S', 'S', '汽車材料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:13:58', 0),
(171, 16, 140, NULL, '陳弘睿', 5, 'C', 'S', '汽車材料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:15:11', 0),
(172, 16, 141, 'uploads/customer_manage/img11.png', '鄭富美', 1, 'S', 'S', '汽車材料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:15:34', 0),
(173, 16, 142, NULL, '游含玉', 5, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:16:09', 0),
(174, 16, 143, NULL, '姜強家', 2, 'A', 'B', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:16:47', 0),
(175, 16, 144, NULL, '林色美', 5, 'A', 'A', '五金射出', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:17:24', 0),
(176, 16, 145, NULL, '許智翔', 1, 'B', 'C', '酒商', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:17:53', 0),
(177, 16, 146, NULL, '周儀宣', 1, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:18:23', 0),
(178, 16, 147, NULL, '沈乾媽', 6, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:18:51', 0),
(179, 16, 148, NULL, '林涵雯', 1, 'A', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:20:53', 0),
(180, 16, 149, NULL, '李純美', 1, 'S', 'B', '室內設計', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:21:31', 0),
(181, 16, 150, NULL, '李昆儒', 5, 'C', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:22:02', 0),
(182, 16, 151, NULL, '章筠庭', 1, 'B', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:22:39', 0),
(183, 16, 152, NULL, '濱本敦子', 1, 'B', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:23:02', 0),
(184, 16, 153, NULL, '林昱廷', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:23:39', 0),
(185, 16, 154, NULL, '吳正偉', 1, 'C', 'C', '旅遊業', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:25:15', 0),
(186, 16, 155, NULL, '李建勳', 1, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:25:37', 0),
(187, 16, 156, NULL, '陳俊同', 2, 'B', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:26:08', 0),
(188, 16, 157, NULL, '李有謙', 5, 'C', 'A', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:26:45', 0),
(189, 16, 158, NULL, '黃勇仁', 1, 'A', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-05 14:27:18', 0),
(190, 16, 159, NULL, '林鴻仲', 5, 'C', 'B', '室內設計裝潢', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:26:37', 0),
(191, 16, 160, NULL, '黃以涵', 1, 'C', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:29:55', 0),
(192, 16, 161, NULL, 'Andy Wu', 5, 'B', 'B', '按摩', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:31:08', 0),
(193, 16, 162, NULL, '傅皓呆', 24, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:32:39', 0),
(194, 16, 163, NULL, '陳琳', 1, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:33:05', 0),
(195, 16, 164, NULL, '江俐穎', 5, 'C', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:33:49', 0),
(196, 16, 165, NULL, '廖為鈞', 1, 'B', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:34:08', 0),
(197, 16, 166, NULL, '段逸婷', 1, 'B', 'B', '華碩電腦', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:36:06', 0),
(198, 16, 167, NULL, '王俊傑', 1, 'B', 'C', '海運', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-06 15:44:50', 0),
(199, 16, 168, NULL, '萬禕庭', 1, 'A', 'C', '中華電信', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:43:37', 0),
(200, 16, 169, NULL, '穆昌翰', 2, 'B', 'B', '電影', NULL, 0, 114, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:44:31', 0),
(201, 16, 170, 'uploads/customer_manage/img19.png', '藍政偉', 2, 'A', 'S', '松山割包', NULL, 0, 105, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:45:27', 0),
(202, 16, 171, NULL, '王榆升', 2, 'A', 'B', '博弈', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:46:20', 0),
(203, 16, 172, NULL, '呂澤霖', 5, 'C', 'B', '仲介', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:47:10', 0),
(204, 16, 173, NULL, '莊宇鎮', 2, 'A', 'B', '系統櫃家具', NULL, 3, 320, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:48:34', 0),
(205, 16, 174, NULL, '黃藝雅', 1, 'C', 'A', '音樂老師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:50:24', 0),
(206, 16, 175, NULL, '盧以強', 1, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:51:03', 0),
(207, 16, 176, NULL, '張繻月', 3, 'S', 'A', '餐飲', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:51:39', 0),
(208, 16, 177, NULL, '張繻紋', 3, 'S', 'A', '餐飲', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:52:23', 0),
(209, 16, 178, NULL, '鄭仕鴻', 2, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:55:22', 0),
(210, 16, 179, NULL, '伍驊嶸', 2, 'B', 'A', '發肉燒肉', NULL, 0, 106, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:56:15', 0),
(211, 16, 180, NULL, '李怡婷', 2, 'S', 'B', NULL, NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:57:12', 0),
(212, 16, 181, NULL, '李佳華', 5, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:57:43', 0),
(213, 16, 182, NULL, '吳政諭', 1, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:58:13', 0),
(214, 16, 183, NULL, '王彙承', 1, 'A', 'C', '餐飲', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:58:42', 0),
(215, 16, 184, NULL, '張德敏', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:59:18', 0),
(216, 16, 185, NULL, '張德㦤', 1, 'A', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 14:59:42', 0),
(217, 16, 186, NULL, '林盟源', 1, 'S', 'B', '國泰信貸', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:00:43', 0),
(218, 16, 187, NULL, '黃泰勳', 2, 'A', 'A', '餐飲 ', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:01:36', 0),
(219, 16, 188, NULL, '王劍華', 2, 'A', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:02:16', 0),
(220, 16, 189, NULL, 'Jessie Lin', 24, 'C', 'B', '兆豐同事', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:03:00', 0),
(221, 16, 190, NULL, '王怡方', 2, 'A', 'A', '餐飲', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:04:30', 0),
(222, 16, 191, NULL, '謝宏源', 2, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:08:20', 0),
(223, 16, 192, NULL, '呂明杰', 1, 'B', 'B', '警察', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-04-07 15:09:04', 0),
(224, 12, 0, NULL, 'aaaa', 1, 'S', 'S', '學生', '2006-04-12', 0, 100, '哈哈路', NULL, NULL, '哈哈哈哈哈哈', 'inform', 0, '2021-04-12 10:56:23', 0),
(225, 1, 0, NULL, '王浩浩', 7, 'S', 'S', '同事', NULL, 2, 241, NULL, NULL, NULL, 'adadsadasdsasadsadsad', 'inform', 0, '2021-04-12 16:12:30', 0),
(226, 1, 17, NULL, '王浩浩', 7, 'S', 'S', '同事', NULL, 2, 241, NULL, NULL, NULL, 'adadsadasdsasadsadsad', 'inform', 0, '2021-04-12 16:24:07', 0),
(227, 1, 20, NULL, '王浩浩3', 6, 'S', 'S', '同事', NULL, 2, 241, NULL, NULL, NULL, 'adadsadasdsasadsadsad', 'inform', 0, '2021-04-12 16:24:40', 0),
(228, 1, 19, NULL, '王浩浩4', 7, 'S', 'S', '同事', NULL, 2, 241, NULL, NULL, NULL, 'adadsadasdsasadsadsad', 'inform', 0, '2021-04-12 16:24:43', 0),
(229, 16, 193, 'uploads/customer_manage/img9.png', '黃國瑋', 1, 'S', 'S', '電子', NULL, 0, 115, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-05 11:36:47', 0),
(230, 16, 194, 'uploads/customer_manage/img25.png', '林妍秀', 5, 'S', 'S', '放款', NULL, 2, 251, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 17:24:43', 0),
(231, 16, 195, 'uploads/customer_manage/img26.png', '林金漢', 5, 'S', 'S', '放款', NULL, 2, 207, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 17:30:01', 0),
(300, 16, 263, NULL, '廖苡伶', 5, 'A', 'A', '宏碁', NULL, 0, 111, '社子', NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:09:13', 0),
(232, 16, 195, NULL, '陳文棋', 5, 'A', 'B', '科技', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:29:51', 0),
(233, 16, 196, NULL, '翟紅梅', 6, 'A', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:30:57', 0),
(234, 16, 197, NULL, '蔡婉華', 1, 'S', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:32:57', 0),
(235, 16, 198, NULL, '許秀華', 5, 'B', 'B', '退休', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:53:41', 0),
(236, 16, 199, NULL, '艷姐', 1, 'D', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '翟紅梅朋友認識十幾年大陸路買很多保險', 'inform', 0, '2021-05-06 18:54:53', 0),
(237, 16, 204, 'uploads/customer_manage/img37.png', '劉軒辰', 1, 'S', 'S', '電子材料', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:56:11', 0),
(238, 16, 204, 'uploads/customer_manage/img38.png', '官明諭', 5, 'B', 'C', '家庭主婦', NULL, 2, 244, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 18:57:08', 0),
(239, 16, 202, NULL, '張嘉筠', 5, 'B', 'C', NULL, NULL, 2, 251, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-06 19:00:35', 0),
(240, 16, 203, 'uploads/customer_manage/img36.png', '簡懿驛', 5, 'S', 'C', '電影', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:10:14', 0),
(241, 16, 204, NULL, '邱美諺', 5, 'B', 'C', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:36:15', 0),
(242, 16, 205, NULL, '李佳禾', 5, 'A', 'B', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:37:57', 0),
(243, 16, 206, NULL, '謝宏堃', 5, 'A', 'B', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:38:40', 0),
(244, 16, 207, NULL, '姜強家', 5, 'B', 'B', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:39:49', 0),
(245, 16, 208, NULL, '游含玉', 5, 'C', 'C', '美髮', NULL, 2, 242, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:41:36', 0),
(246, 16, 209, NULL, '陳俊男', 5, 'B', 'B', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:42:05', 0),
(247, 16, 210, NULL, '陳詩玲', 5, 'A', 'C', '美髮', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:48:14', 0),
(248, 16, 212, NULL, '程子寧', 1, 'B', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 20:56:31', 0),
(249, 16, 212, NULL, '方以琳', 1, 'A', 'B', '博弈', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:07:26', 0),
(250, 16, 213, NULL, '陳翊', 1, 'S', 'C', '電信', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:08:53', 0),
(251, 16, 214, NULL, '陳欣', 5, 'A', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:09:19', 0),
(252, 16, 215, NULL, '陳亭如', 1, 'C', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:09:49', 0),
(253, 16, 216, NULL, '林欣穎', 5, 'C', 'C', '窗簾', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:10:22', 0),
(254, 16, 217, NULL, '曾凱莉', 1, 'B', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:11:22', 0),
(255, 16, 218, NULL, '陳冠廷', 1, 'A', 'B', '工程師', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:11:47', 0),
(256, 16, 219, NULL, 'Ivy Lee', 6, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:13:13', 0),
(257, 16, 220, NULL, '張之瑋', 1, 'A', 'C', '工程', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:14:20', 0),
(258, 16, 221, NULL, '郭峙鈞', 1, 'A', 'C', '工程師', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:15:08', 0),
(259, 16, 222, NULL, '劉佩綺', 1, 'A', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:15:30', 0),
(260, 16, 223, NULL, '陳乃寧', 1, 'A', 'C', '銀行', NULL, 2, 234, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:16:33', 0),
(261, 16, 224, NULL, '曾詩閔', 1, 'C', 'C', '房地產', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:19:17', 0),
(262, 16, 225, NULL, '賴建良', 5, 'A', 'C', '餐飲', NULL, 0, 100, NULL, NULL, NULL, '鬍鬚張', 'inform', 0, '2021-05-07 21:19:59', 0),
(263, 16, 226, NULL, '董鈞偉', 1, 'B', 'B', '水電', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:24:12', 0),
(264, 16, 227, NULL, 'Estella Ku', 5, 'C', 'C', NULL, NULL, 0, 115, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:24:58', 0),
(265, 16, 228, NULL, '周子翔', 1, 'B', 'C', '健身教練', NULL, 0, 106, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:29:35', 0),
(266, 16, 229, NULL, '朱浩賢', 1, 'C', 'C', '保全公司', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:30:07', 0),
(267, 16, 230, NULL, '王聖博', 1, 'C', 'C', NULL, NULL, 2, 251, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:30:39', 0),
(268, 16, 231, NULL, '孫英傑', 1, 'C', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:31:14', 0),
(269, 16, 232, NULL, '陳彥旭', 1, 'B', 'C', '房地產', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:31:47', 0),
(270, 16, 233, NULL, '陳彥翔', 5, 'C', 'C', '房地產', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:32:19', 0),
(271, 16, 234, NULL, 'Love Sarah', 3, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '表妹', 'inform', 0, '2021-05-07 21:32:57', 0),
(272, 16, 235, NULL, 'Luke Chang ', 2, 'C', 'A', '醫美', NULL, 0, 110, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:33:37', 0),
(273, 16, 236, NULL, '邱泓偉', 6, 'A', 'B', '美髮', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:34:12', 0),
(274, 16, 237, NULL, '胡芯愉', 1, 'B', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:35:07', 0),
(275, 16, 238, NULL, '彭亮軒', 2, 'A', 'B', NULL, NULL, 0, 106, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:35:46', 0),
(276, 16, 239, NULL, '蕭文浩', 2, 'C', 'C', NULL, NULL, 3, 330, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:36:45', 0),
(277, 16, 240, NULL, '李冠龍', 2, 'C', 'C', '電信', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:37:11', 0),
(278, 16, 241, NULL, '李燕亭', 2, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:37:45', 0),
(279, 16, 242, NULL, '李語潔', 1, 'S', 'S', '便利商店', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:39:59', 0),
(280, 16, 243, NULL, '唐佩雯', 1, 'C', 'C', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:40:23', 0),
(281, 16, 244, NULL, '李依純', 2, 'A', 'B', NULL, NULL, 0, 110, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:40:54', 0),
(282, 16, 245, NULL, '吳崇佑', 2, 'S', 'B', '餐飲', NULL, 0, 100, NULL, NULL, NULL, '豆桑豆漿', 'inform', 0, '2021-05-07 21:41:30', 0),
(283, 16, 246, NULL, 'Peggy Lee', 5, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '李依純姊姊', 'inform', 0, '2021-05-07 21:46:47', 0),
(284, 16, 247, NULL, '李玟霈', 5, 'B', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:47:36', 0),
(285, 16, 248, NULL, '于雍', 1, 'C', 'C', '房地產', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:48:12', 0),
(286, 16, 249, NULL, '賴映秀', 24, 'B', 'B', '投信', NULL, 0, 110, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:48:48', 0),
(287, 16, 250, NULL, '林書禾', 1, 'B', 'C', '服飾', NULL, 0, 112, NULL, NULL, NULL, '鞋子', 'inform', 0, '2021-05-07 21:49:28', 0),
(288, 16, 251, NULL, '陳寶兒', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:49:49', 0),
(289, 16, 252, NULL, '陳樂樂', 2, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:52:18', 0),
(290, 16, 253, NULL, '原微', 2, 'A', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '91APP', 'inform', 0, '2021-05-07 21:52:54', 0),
(291, 16, 254, NULL, '許智翔', 1, 'A', 'C', '酒商', NULL, 3, 330, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:53:27', 0),
(292, 16, 255, NULL, '余昊益', 1, 'B', 'C', '音樂', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:54:11', 0),
(293, 16, 256, NULL, '詹哲源', 1, 'B', 'C', '室內設計', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 21:54:47', 0),
(294, 16, 257, NULL, '劉依菁', 5, 'B', 'C', '電信', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:01:01', 0),
(295, 16, 258, NULL, '古雪貞', 1, 'A', 'B', '美髮', NULL, 7, 400, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:02:50', 0),
(296, 16, 259, NULL, '吳孟軒', 2, 'B', 'C', '軍人', NULL, 14, 800, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:04:37', 0),
(297, 16, 260, NULL, '吳宗諭', 2, 'A', 'C', '軍人', NULL, 2, 231, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:05:57', 0),
(298, 16, 261, NULL, '廖家新', 6, 'B', 'C', '餐飲', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:06:29', 0),
(299, 16, 262, NULL, '朱淑賢', 6, 'A', 'B', '餐飲', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:06:55', 0),
(301, 16, 264, NULL, '廖苡伶', 5, 'A', 'A', '宏碁', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:11:33', 0),
(302, 16, 265, NULL, '朱冠名', 1, 'B', 'C', '運輸', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:12:06', 0),
(303, 16, 266, NULL, '李佩怡', 1, 'B', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:14:08', 0),
(304, 16, 267, NULL, '鮑弘宣', 2, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:14:39', 0),
(305, 16, 268, NULL, '黃梓寧', 6, 'C', 'C', '美髮', NULL, 0, 103, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:15:12', 0),
(306, 16, 269, NULL, '柯家凱', 1, 'S', 'C', '按摩', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:22:07', 0),
(307, 16, 270, NULL, '蔡昀羲', 1, 'B', 'C', '模特兒', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:24:02', 0),
(308, 16, 271, NULL, '蔡緯冠', 1, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:25:37', 0),
(309, 16, 272, NULL, '周維昱', 1, 'B', 'C', '木工', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:37:18', 0),
(310, 16, 273, NULL, '陳穫文', 5, 'C', 'B', NULL, NULL, 2, 220, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:37:55', 0),
(311, 16, 275, NULL, '金惟凌', 6, 'C', 'C', '百貨', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:38:37', 0),
(312, 16, 275, NULL, '陳名揚', 6, 'A', 'C', NULL, NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:40:11', 0),
(313, 16, 276, NULL, '謝函靜', 6, 'B', 'C', NULL, NULL, 13, 708, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:41:11', 0),
(314, 16, 277, NULL, '李研緣', 5, 'B', 'C', NULL, NULL, 2, 241, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:45:16', 0),
(315, 16, 278, NULL, '吳永然', 6, 'C', 'C', NULL, NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:47:23', 0),
(316, 16, 279, NULL, '陳莉惠', 2, 'C', 'B', NULL, NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 22:48:47', 0),
(317, 16, 280, NULL, '謝毅翔', 6, 'A', 'C', '餐飲', NULL, 0, 103, NULL, NULL, NULL, '早餐店', 'inform', 0, '2021-05-07 23:02:48', 0),
(318, 16, 281, NULL, '張珉瑋', 6, 'C', 'C', '娛樂', NULL, 0, 100, NULL, NULL, NULL, 'EZ5', 'inform', 0, '2021-05-07 23:03:25', 0),
(319, 16, 282, NULL, '郭天行', 5, 'A', 'S', NULL, NULL, 0, 115, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:04:02', 0),
(320, 16, 283, NULL, '陳宏凱', 5, 'B', 'C', '影音後製', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:07:56', 0),
(321, 16, 284, NULL, '周子鈞', 1, 'C', 'B', '建築', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:08:44', 0),
(322, 16, 285, NULL, '林崑傑', 6, 'B', 'A', '博弈', NULL, 0, 100, NULL, NULL, NULL, '潛在VIP父親從事博弈開瑪莎', 'inform', 0, '2021-05-07 23:09:51', 0),
(323, 16, 286, NULL, '小林', 5, 'B', 'B', '銀行業', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:37:07', 0),
(324, 16, 287, NULL, '吳宗達', 5, 'C', 'C', '教育', NULL, 0, 114, NULL, NULL, NULL, '實踐助教', 'inform', 0, '2021-05-07 23:38:02', 0),
(325, 16, 288, NULL, '林揚凱', 5, 'A', 'B', '殯葬業', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:38:48', 0),
(326, 16, 289, NULL, '張巧雯', 5, 'C', 'C', NULL, NULL, 0, 100, NULL, NULL, NULL, '林揚凱朋友', 'inform', 0, '2021-05-07 23:39:18', 0),
(327, 16, 290, NULL, '王承偉', 5, 'B', 'A', '舞台設備', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:40:45', 0),
(328, 16, 291, NULL, '方文如', 1, 'B', 'C', '空服員', NULL, 2, 235, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:42:45', 0),
(329, 16, 292, NULL, '林乃文', 5, 'A', 'B', '空服員', NULL, 1, 202, '信三路19號', NULL, NULL, '開早午餐店', 'inform', 0, '2021-05-07 23:44:35', 0),
(330, 16, 293, NULL, '陳奕安', 5, 'B', 'A', '建築', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:45:10', 0),
(331, 16, 294, NULL, '林柔伊', 5, 'B', 'A', '服飾', NULL, 2, 220, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:46:05', 0),
(332, 16, 295, NULL, '鐵木', 5, 'A', 'A', '皮件服飾', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-07 23:47:46', 0),
(333, 16, 296, NULL, '陳頡', 2, 'S', 'A', '服飾', NULL, 0, 104, NULL, NULL, NULL, '服飾殯葬烘焙', 'inform', 0, '2021-05-07 23:53:20', 0),
(334, 16, 297, NULL, '陳敬文', 3, 'A', 'A', '餐飲', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:30:56', 0),
(335, 16, 298, NULL, '劉淑鏗', 5, 'B', 'B', '餐飲', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:31:35', 0),
(336, 16, 299, NULL, '林京諭', 5, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:32:04', 0),
(337, 16, 300, NULL, '陳世宗', 6, 'B', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:32:37', 0),
(338, 16, 301, NULL, '謝月娥', 5, 'B', 'S', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:33:31', 0),
(339, 16, 302, NULL, '黃泰勳', 2, 'A', 'A', '餐飲', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:34:46', 0),
(340, 16, 303, NULL, '王劍華', 2, 'B', 'A', NULL, NULL, 0, 108, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:35:23', 0),
(341, 16, 304, NULL, '趙培均', 1, 'A', 'A', '攝影', NULL, 0, 112, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:36:20', 0),
(342, 16, 305, NULL, '楊依靜', 24, 'A', 'A', '銀行業', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:37:01', 0),
(343, 16, 306, NULL, '呂美惠', 5, 'A', 'A', '智強科技', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:38:44', 0),
(344, 16, 307, NULL, '林月琴', 5, 'B', 'B', '智強科技', NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:39:28', 0),
(345, 16, 308, NULL, '楊晴安', 5, 'B', 'B', NULL, NULL, 0, 106, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:40:17', 0),
(346, 16, 309, NULL, '戴渝宗', 2, 'A', 'S', '電子業', NULL, 0, 104, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:41:53', 0),
(347, 16, 310, NULL, '謝淑麗', 2, 'A', 'S', NULL, NULL, 7, 404, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:43:45', 0),
(348, 16, 311, NULL, '陳欣伶', 5, 'B', 'A', '醫師', NULL, 0, 111, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:45:23', 0),
(349, 16, 312, NULL, '葉瓊虹', 5, 'B', 'A', NULL, NULL, 0, 100, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-08 00:46:38', 0),
(350, 16, 313, NULL, '蔡沅廷', 6, 'A', 'A', '行李箱', NULL, 7, 411, NULL, NULL, NULL, NULL, 'inform', 0, '2021-05-10 19:52:42', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `customer_mgr_field`
--

CREATE TABLE `customer_mgr_field` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT '客戶id',
  `field_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '對應firld_value的名稱',
  `field_value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '那個欄位名稱對應的值',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者的每個客戶額外自訂義的客戶資料名稱與值';

--
-- 傾印資料表的資料 `customer_mgr_field`
--

INSERT INTO `customer_mgr_field` (`id`, `customer_id`, `field_name`, `field_value`, `is_delete`) VALUES
(4, 1, 'phone', '0912345678', 0),
(5, 1, 'email', 'chenzenyang2021@gmail.com', 0),
(6, 2, 'phone', '0912345678', 0),
(9, 12, 'email', '0933333333', 0),
(10, 12, 'dd', 'dddddddd', 0),
(11, 13, 'email', '0933333333', 0),
(12, 13, 'fb', '肥嘟嘟右衛門', 0),
(13, 15, 'email', '0933333333', 0),
(14, 15, 'fb', '肥嘟嘟右衛門', 0),
(15, 17, 'email', '0933333333', 0),
(16, 17, 'fb', '肥嘟嘟右衛門', 0),
(17, 26, 'email', '0933333333', 1),
(18, 26, 'fb', '肥嘟嘟右衛門', 1),
(19, 27, 'phone', '0933333333', 0),
(20, 27, 'ig', '蠟筆小新一點俱樂部', 0),
(21, 26, 'phone', '0933333333', 1),
(22, 26, 'ig', '蠟筆小新一點俱樂部', 1),
(23, 26, 'phone', '0933333333', 1),
(24, 26, 'ig', '蠟筆小新一點俱樂部', 1),
(25, 26, 'phone', '0933333333', 1),
(26, 26, 'ig', '蠟筆小新一點俱樂部', 1),
(27, 26, 'google', 'abc', 1),
(28, 28, 'phone', '0933333333', 0),
(29, 28, 'ig', '蠟筆小新一點俱樂部', 0),
(30, 28, 'google', 'abc', 0),
(31, 26, 'phone', '0933333333', 1),
(32, 26, 'ig', '蠟筆小新一點俱樂部', 1),
(33, 26, 'google', 'abc', 1),
(34, 26, 'GG', 'ss', 1),
(35, 26, 'google', 'abc', 1),
(36, 26, 'GG', 'ss', 1),
(37, 26, 'google', 'abc', 1),
(38, 26, 'GG', 'ss', 1),
(39, 26, '123', 'abc', 1),
(40, 30, 'aaa', 'bbb', 1),
(41, 30, 'BBB', 'CCCC', 0),
(42, 225, 'google', 'abc', 0),
(43, 225, 'GG', 'ss', 0),
(44, 226, 'google', 'abc', 0),
(45, 226, 'GG', 'ss', 0),
(46, 227, 'google', 'abc', 0),
(47, 227, 'GG', 'ss', 0),
(48, 228, 'google', 'abc', 0),
(49, 228, 'GG', 'ss', 0),
(50, 19, 'google', 'abc', 0),
(51, 19, 'GG', 'ss', 0),
(52, 26, 'google', 'abc', 1),
(53, 26, 'GG', 'ss', 1),
(54, 26, 'google', 'abc', 1),
(55, 26, 'GG', 'ss', 1),
(56, 35, '增員', '', 0),
(57, 26, 'google', 'abc', 1),
(58, 26, 'GG', 'ss', 1),
(59, 26, 'google', 'abc', 0),
(60, 26, 'GG', 'ss', 0),
(61, 33, '增員', '增員', 0),
(62, 156, '增員', '增員', 0),
(63, 159, '增員', '', 0),
(64, 234, '增員', '增員', 0),
(65, 240, '增員', '增員', 0),
(66, 311, '增員', '增員', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `flow_record`
--

CREATE TABLE `flow_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '= 0 則為訪客',
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '瀏覽者ip',
  `enter` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '哪個檔案(或api)被瀏覽(開啟)',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '瀏覽時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ip瀏覽紀錄表';

--
-- 傾印資料表的資料 `flow_record`
--

INSERT INTO `flow_record` (`id`, `user_id`, `ip`, `enter`, `create_date`) VALUES
(1, 0, '61.222.191.73', 'api', '2021-03-31 18:05:55'),
(2, 0, '61.222.191.73', 'api', '2021-04-01 10:57:53'),
(3, 0, '61.222.191.75', 'api', '2021-04-01 21:34:45'),
(4, 0, '220.129.70.120', 'api', '2021-04-01 22:46:07'),
(5, 0, '36.224.37.121', 'api', '2021-04-01 23:12:18'),
(6, 0, '223.140.179.1', 'api', '2021-04-02 10:47:59'),
(7, 0, '223.136.125.203', 'api', '2021-04-05 11:52:12'),
(8, 0, '59.115.220.219', 'api', '2021-04-05 11:54:20'),
(9, 0, '211.72.253.47', 'api', '2021-04-06 13:30:48'),
(10, 0, '223.136.125.203', 'api', '2021-04-06 15:25:55'),
(11, 0, '223.136.102.33', 'api', '2021-04-06 16:48:19'),
(12, 0, '223.140.125.99', 'api', '2021-04-07 14:25:42'),
(13, 0, '223.136.56.84', 'api', '2021-04-09 15:24:02'),
(14, 0, '61.222.191.73', 'api', '2021-04-09 17:24:38'),
(15, 0, '36.224.50.132', 'api', '2021-04-10 00:47:59'),
(16, 0, '36.224.50.132', 'api', '2021-04-10 00:47:59'),
(17, 0, '36.224.50.132', 'api', '2021-04-10 00:47:59'),
(18, 0, '36.224.50.132', 'api', '2021-04-10 00:48:06'),
(19, 0, '36.224.50.132', 'api', '2021-04-10 00:48:24'),
(20, 0, '36.224.50.132', 'api', '2021-04-10 00:48:24'),
(21, 0, '36.224.50.132', 'api', '2021-04-10 00:48:24'),
(22, 0, '36.224.50.132', 'api', '2021-04-10 00:48:31'),
(23, 0, '36.224.50.132', 'api', '2021-04-10 00:48:31'),
(24, 0, '36.224.50.132', 'api', '2021-04-10 00:48:31'),
(25, 0, '211.72.253.38', 'api', '2021-04-10 17:28:26'),
(26, 0, '211.72.253.34', 'api', '2021-04-12 09:13:42'),
(27, 0, '61.222.191.73', 'api', '2021-04-12 10:33:09'),
(28, 0, '111.71.108.101', 'api', '2021-04-12 14:40:24'),
(29, 0, '36.224.32.41', 'api', '2021-04-12 23:03:17'),
(30, 0, '61.222.191.73', 'api', '2021-04-13 11:05:38'),
(31, 0, '61.222.191.73', '', '2021-04-13 12:08:40'),
(32, 0, '211.72.253.34', '', '2021-04-13 12:11:43'),
(33, 0, '61.222.191.73', 'add', '2021-04-13 12:24:06'),
(34, 0, '61.222.191.73', 'del', '2021-04-13 16:18:58'),
(35, 0, '61.222.191.73', 'a', '2021-04-13 17:58:36'),
(36, 0, '61.222.191.73', '2021', '2021-04-13 18:43:10'),
(37, 0, '61.222.191.78', '', '2021-04-14 10:43:28'),
(38, 0, '61.222.191.73', '', '2021-04-14 10:52:57'),
(39, 0, '61.222.191.73', '2021', '2021-04-14 11:53:22'),
(40, 0, '61.222.191.73', 'add', '2021-04-14 15:48:34'),
(41, 0, '42.77.238.241', '', '2021-04-14 16:04:00'),
(42, 0, '61.222.191.73', '4', '2021-04-14 16:33:39'),
(43, 0, '61.222.191.73', 'del', '2021-04-14 16:53:23'),
(44, 0, '61.222.191.75', '', '2021-04-14 19:40:15'),
(45, 0, '61.222.191.75', '2', '2021-04-14 19:45:34'),
(46, 0, '61.222.191.73', '9', '2021-04-14 19:46:03'),
(47, 0, '61.222.191.75', '17', '2021-04-14 19:47:35'),
(48, 0, '61.222.191.73', '17', '2021-04-14 19:50:59'),
(49, 0, '61.222.191.75', 'add', '2021-04-14 19:55:14'),
(50, 0, '61.222.191.75', '2021', '2021-04-14 19:57:15'),
(51, 0, '61.222.191.75', '19', '2021-04-14 20:16:00'),
(52, 0, '61.222.191.75', 'del', '2021-04-14 20:18:12'),
(53, 0, '61.222.191.73', '20', '2021-04-14 20:24:01'),
(54, 0, '61.222.191.73', '21', '2021-04-14 21:02:36'),
(55, 0, '61.222.191.73', '2021', '2021-04-15 11:45:28'),
(56, 0, '61.222.191.73', '', '2021-04-15 11:45:33'),
(57, 0, '61.222.191.73', 'add', '2021-04-15 11:45:50'),
(58, 0, '61.222.191.73', 'del', '2021-04-15 11:53:24'),
(59, 0, '61.222.191.73', 'edit', '2021-04-15 12:00:07'),
(60, 0, '61.222.191.73', '91', '2021-04-15 14:27:09'),
(61, 0, '61.222.191.73', '92', '2021-04-15 14:32:22'),
(62, 0, '61.222.191.73', '95', '2021-04-15 15:49:21'),
(63, 0, '61.222.191.73', '98', '2021-04-15 16:10:11'),
(64, 0, '61.222.191.73', '94', '2021-04-15 16:12:01'),
(65, 0, '61.222.191.73', '', '2021-04-16 10:33:49'),
(66, 0, '61.222.191.73', '', '2021-04-16 10:33:49'),
(67, 0, '61.222.191.73', 'add', '2021-04-16 12:55:31'),
(68, 0, '61.222.191.73', '2021', '2021-04-16 12:57:17'),
(69, 0, '223.137.161.85', '', '2021-04-16 16:31:39'),
(70, 0, '223.137.161.85', 'add', '2021-04-16 16:34:50'),
(71, 0, '223.137.161.85', 'del', '2021-04-16 17:30:40'),
(72, 0, '36.224.52.92', '', '2021-04-17 08:54:49'),
(73, 0, '36.224.52.92', '', '2021-04-17 08:54:49'),
(74, 0, '36.224.52.92', '', '2021-04-17 08:54:49'),
(75, 0, '36.224.52.92', 'add', '2021-04-17 09:33:32'),
(76, 0, '36.224.52.92', 'del', '2021-04-17 09:33:55'),
(77, 0, '36.224.52.92', '31', '2021-04-17 09:39:58'),
(78, 0, '36.224.52.92', '32', '2021-04-17 09:41:20'),
(79, 0, '61.222.191.73', 'edit', '2021-04-19 11:56:17'),
(80, 0, '61.222.191.73', '', '2021-04-19 12:34:10'),
(81, 0, '61.222.191.73', '', '2021-04-22 19:49:59'),
(82, 0, '61.222.191.73', '', '2021-04-23 11:06:01'),
(83, 0, '223.136.25.56', '', '2021-04-23 18:01:17'),
(84, 0, '61.222.191.73', '2021', '2021-04-23 18:07:48'),
(85, 0, '61.222.191.73', 'add', '2021-04-23 19:55:13'),
(86, 0, '36.224.53.14', '', '2021-04-23 23:26:41'),
(87, 0, '36.224.53.14', '', '2021-04-23 23:26:41'),
(88, 0, '36.224.53.14', '', '2021-04-23 23:26:41'),
(89, 0, '36.224.53.14', 'del', '2021-04-23 23:33:07'),
(90, 0, '36.224.57.21', '', '2021-04-27 21:34:49'),
(91, 0, '36.224.57.21', 'add', '2021-04-27 21:42:00'),
(92, 0, '61.222.191.73', '', '2021-04-29 16:46:16'),
(93, 0, '223.141.54.197', '', '2021-04-29 19:07:46'),
(94, 0, '223.141.54.197', 'add', '2021-04-29 19:08:37'),
(95, 0, '61.222.191.73', '', '2021-05-03 10:29:00'),
(96, 0, '61.222.191.73', '2021', '2021-05-03 10:30:41'),
(97, 0, '61.222.191.73', 'add', '2021-05-03 11:50:58'),
(98, 0, '223.137.130.146', '', '2021-05-03 18:42:48'),
(99, 0, '223.137.130.146', '2021', '2021-05-03 18:43:17'),
(100, 0, '223.137.130.146', 'add', '2021-05-03 18:51:37'),
(101, 0, '211.72.253.36', '', '2021-05-04 11:23:19'),
(102, 0, '211.72.253.36', '2021', '2021-05-04 11:24:23'),
(103, 0, '211.72.253.36', 'add', '2021-05-04 11:25:33'),
(104, 0, '211.72.253.36', 'del', '2021-05-04 11:29:52'),
(105, 0, '61.222.191.73', '', '2021-05-04 15:49:30'),
(106, 0, '61.222.191.75', '2021', '2021-05-04 18:23:44'),
(107, 0, '211.72.253.36', '2021', '2021-05-05 11:35:23'),
(108, 0, '211.72.253.36', '', '2021-05-05 11:35:23'),
(109, 0, '211.72.253.36', 'add', '2021-05-05 11:38:55'),
(110, 0, '61.222.191.73', '2021', '2021-05-05 17:02:39'),
(111, 0, '61.222.191.73', '', '2021-05-05 17:02:39'),
(112, 0, '61.222.191.75', '2021', '2021-05-05 17:46:36'),
(113, 0, '114.136.248.203', '', '2021-05-05 19:19:14'),
(114, 0, '114.136.248.203', '2021', '2021-05-05 19:19:16'),
(115, 0, '114.136.248.203', 'add', '2021-05-05 19:43:42'),
(116, 0, '61.222.191.73', '2021', '2021-05-06 14:43:08'),
(117, 0, '61.222.191.73', '', '2021-05-06 14:43:09'),
(118, 0, '114.136.33.138', '', '2021-05-06 17:19:19'),
(119, 0, '114.136.33.138', '2021', '2021-05-06 17:19:39'),
(120, 0, '114.136.33.138', '38', '2021-05-06 17:20:00'),
(121, 0, '114.136.33.138', 'add', '2021-05-06 17:22:43'),
(122, 0, '36.224.38.197', '', '2021-05-06 23:03:45'),
(123, 0, '36.224.38.197', 'add', '2021-05-06 23:09:32'),
(124, 0, '211.72.253.36', '', '2021-05-07 11:08:21'),
(125, 0, '211.72.253.36', '2021', '2021-05-07 11:08:22'),
(126, 0, '61.222.191.73', '', '2021-05-07 12:20:01'),
(127, 0, '61.222.191.73', '2021', '2021-05-07 13:39:26'),
(128, 0, '61.222.191.75', '2021', '2021-05-07 17:10:49'),
(129, 0, '61.222.191.75', '', '2021-05-07 17:49:22'),
(130, 0, '36.224.38.197', '', '2021-05-07 19:58:42'),
(131, 0, '36.224.38.197', '2021', '2021-05-07 19:59:09'),
(132, 0, '118.167.55.74', '', '2021-05-08 00:30:54'),
(133, 0, '118.167.55.74', '', '2021-05-08 00:30:54'),
(134, 0, '118.167.55.74', '', '2021-05-08 00:30:54'),
(135, 0, '118.167.55.74', '2021', '2021-05-08 00:31:40'),
(136, 0, '118.167.55.74', '', '2021-05-08 00:31:40'),
(137, 0, '118.167.55.74', '', '2021-05-08 00:31:41'),
(138, 0, '118.167.55.74', '', '2021-05-08 00:31:41'),
(139, 0, '118.167.55.74', '2021', '2021-05-08 00:35:32'),
(140, 0, '118.167.55.74', '', '2021-05-08 00:35:32'),
(141, 0, '118.167.55.74', '', '2021-05-08 00:35:32'),
(142, 0, '118.167.55.74', '', '2021-05-08 00:35:32'),
(143, 0, '118.167.55.74', '2021', '2021-05-08 00:37:00'),
(144, 0, '118.167.55.74', '', '2021-05-08 00:37:00'),
(145, 0, '118.167.55.74', '', '2021-05-08 00:37:00'),
(146, 0, '118.167.55.74', '', '2021-05-08 00:37:00'),
(147, 0, '220.129.73.17', '2021', '2021-05-08 09:43:00'),
(148, 0, '220.129.73.17', '', '2021-05-08 09:43:00'),
(149, 0, '61.222.191.73', '2021', '2021-05-10 11:17:37'),
(150, 0, '61.222.191.73', '', '2021-05-10 11:17:37'),
(151, 0, '61.222.191.73', 'add', '2021-05-10 11:54:17'),
(152, 0, '61.222.191.75', 'add', '2021-05-10 12:05:05'),
(153, 0, '61.222.191.73', 'del', '2021-05-10 12:15:03'),
(154, 0, '61.222.191.75', '2021', '2021-05-10 12:19:51'),
(155, 0, '61.222.191.75', 'del', '2021-05-10 12:20:16'),
(156, 0, '61.222.191.73', 'edit', '2021-05-10 12:30:41'),
(157, 0, '223.136.161.103', '2021', '2021-05-10 18:54:02'),
(158, 0, '223.136.161.103', '', '2021-05-10 18:54:02'),
(159, 0, '223.136.161.103', 'edit', '2021-05-10 19:48:00'),
(160, 0, '223.136.161.103', 'del', '2021-05-10 19:50:53'),
(161, 0, '223.136.161.103', 'add', '2021-05-10 19:58:03'),
(162, 0, '36.224.36.78', '', '2021-05-10 20:54:08'),
(163, 0, '36.224.36.78', '2021', '2021-05-10 21:15:07'),
(164, 0, '36.224.36.78', 'add', '2021-05-10 21:20:54'),
(165, 0, '220.136.54.131', '2021', '2021-05-11 20:48:12'),
(166, 0, '220.136.54.131', '', '2021-05-11 20:48:12'),
(167, 0, '223.136.150.104', '2021', '2021-05-12 15:14:49'),
(168, 0, '223.136.150.104', '', '2021-05-12 15:14:49'),
(169, 0, '223.136.150.104', '', '2021-05-12 15:14:49');

-- --------------------------------------------------------

--
-- 資料表結構 `goal`
--

CREATE TABLE `goal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(4) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  `total_money` int(13) NOT NULL COMMENT '目標金額',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `goal`
--

INSERT INTO `goal` (`id`, `user_id`, `name`, `year`, `month`, `total_money`, `create_date`) VALUES
(1, 1, '壽險期繳追蹤名單I', 2021, 4, 99999, '2021-04-13 18:34:07'),
(2, 1, 'AH追蹤名單', 2021, 4, 1034, '2021-04-13 19:45:39'),
(3, 1, '代繳追蹤名單', 2021, 4, 500, '2021-04-16 12:56:58'),
(4, 1, '代繳追蹤名單', 2021, 4, 500, '2021-04-16 12:58:14'),
(5, 1, '武漢肺炎保險單', 2021, 4, 50000, '2021-04-16 13:00:50'),
(6, 1, '武漢肺炎保險單', 2021, 4, 50000, '2021-04-16 13:01:03'),
(7, 1, '武漢肺炎保險單2', 2021, 4, 50000, '2021-04-16 13:01:23'),
(8, 1, '武漢肺炎保險(賺爆)', 2021, 4, 999999, '2021-04-16 13:02:21'),
(9, 1, '哈哈哈', 2021, 4, 200, '2021-04-23 19:56:03'),
(10, 16, '壽險期繳', 2021, 5, 100, '2021-05-04 11:29:20'),
(11, 16, '壽險期繳', 2021, 5, 100, '2021-05-04 11:29:26'),
(12, 1, '啦啦啦啦', 2021, 5, 100, '2021-05-10 15:00:31'),
(13, 1, '第五目標', 2021, 5, 999, '2021-05-10 15:10:38'),
(14, 16, '產險', 2021, 5, 10, '2021-05-10 19:58:03'),
(15, 16, '投資型', 2021, 5, 200, '2021-05-10 20:00:42'),
(16, 16, '專案：重大傷病', 2021, 5, 30, '2021-05-10 21:20:54');

-- --------------------------------------------------------

--
-- 資料表結構 `goal_customer`
--

CREATE TABLE `goal_customer` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL COMMENT '對應到的目標id',
  `customer_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品代碼',
  `estimate_money` int(11) NOT NULL COMMENT '預估金額',
  `deal_money` int(11) NOT NULL COMMENT '成交金額',
  `is_complete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否完成',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `goal_customer`
--

INSERT INTO `goal_customer` (`id`, `goal_id`, `customer_name`, `no`, `estimate_money`, `deal_money`, `is_complete`, `is_delete`) VALUES
(1, 1, '劉德華', '1', 999, 999, 0, 0),
(2, 1, '劉美麗', '1ky4sadaa', 23, 25, 1, 0),
(3, 1, '劉矻矻', 'ash14sa', 30, 25, 1, 0),
(4, 2, '帥哥嘟嘟', '4dsvq23', 989, 564, 0, 0),
(5, 2, '帥哥嘟嘟', '4dsvq23', 989, 564, 0, 1),
(6, 2, '帥哥嘟嘟', '4dsvq23', 989, 564, 0, 1),
(7, 2, '帥哥嘟嘟GOOGO', 'sssjj5466', 100, 76, 0, 0),
(12, 9, '啦啦啦啦', '1', 2, 20, 0, 0),
(9, 0, '也員新之助', 'ssr531', 999, 999, 0, 0),
(10, 6, '也員新之助xs', 'ssr531', 999, 999, 0, 0),
(13, 10, '黃國瑋', 'AHUPL', 10, 2, 0, 0),
(14, 11, '黃國瑋', 'AHUPL', 10, 2, 0, 0),
(15, 1, 'abc', 'qwe', 100, 100, 0, 0),
(16, 2, '帥哥嘟嘟GOOGO', '4dsvq23', 100, 76, 0, 0),
(17, 1, 'ddddd', 'ggggg', 1000, 1000, 0, 0),
(18, 1, 'ppp', 'oooo', 123, 123, 0, 1),
(19, 1, 'pppp', 'kkkk', 500, 500, 0, 0),
(20, 1, 'g', 'h', 200, 200, 0, 0),
(21, 1, 'cc', 'Ed', 500, 500, 0, 0),
(22, 1, 'p', 'p', 50, 50, 0, 1),
(23, 12, '周杰倫', '10', 999, 999, 0, 0),
(24, 13, '王力宏', '啦啦啦啦', 900, 900, 0, 0),
(25, 14, '佳迪恩', '金頭家', 10, 2, 0, 0),
(26, 15, '陳俊男', 'BVA', 20, 10, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `login_token`
--

CREATE TABLE `login_token` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `os` enum('ios','android') COLLATE utf8mb4_unicode_ci NOT NULL,
  `push_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('normal','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `login_token`
--

INSERT INTO `login_token` (`id`, `user_id`, `token`, `os`, `push_token`, `status`, `create_date`) VALUES
(1, 10, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTAiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMjQgMDU6NTg6MjkiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTI0IDE3OjU4OjI5In0.QsXRVSwZDQ5eRoYpF62U0VOAQ0LvkOlwbECsvfJNf1w', 'ios', 'qq1fsadsads', 'normal', '2021-03-24 13:58:29'),
(2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wMy0yNCAwNTo1OToxMiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDMtMjQgMTc6NTk6MTIifQ.Y9lPtoEmcx9KAsyuPBMix-MJ9W4eQik5QTVqS8n_iwM', 'ios', 'dsajkl', 'expired', '2021-03-24 13:59:12'),
(3, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wMy0yNSAwODoxNDo0OSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDMtMjUgMjA6MTQ6NDkifQ.1203GLpq5UW7nSbHSEDHwukOwB_5i3aTIgSNO5oDtaI', 'ios', 'dsajkl', 'expired', '2021-03-25 16:14:49'),
(4, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMjUgMTE6MzA6MDQiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTI1IDIzOjMwOjA0In0.eJwS5X0XZXzsarPTqJlfb9qIolyQZi0u6Fo7kPV8f4o', 'ios', '123', 'expired', '2021-03-25 19:30:04'),
(5, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTMiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMjUgMTE6MzM6NTQiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTI1IDIzOjMzOjU0In0.8GUtwQYS0mvXr2z1_poT-wY5aHyBQBvrFoeAH2NU2Xc', 'ios', '21', 'normal', '2021-03-25 19:33:54'),
(6, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wMy0yNiAxMToxOToyNiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDMtMjYgMjM6MTk6MjYifQ.NLd26kK_LRiFFi6i2uD8SUM9-ta6YT5Mt1UyLTFEKek', 'ios', 'dsajkl', 'expired', '2021-03-26 19:19:26'),
(7, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMjYgMTE6Mjg6MjYiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTI2IDIzOjI4OjI2In0.imJDVHs5Bp8xpOen7iQS1fdYFtlNHZ4KeiiqekC5DCU', 'ios', '123', 'expired', '2021-03-26 19:28:26'),
(8, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMjkgMDM6MjY6MDQiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTI5IDE1OjI2OjA0In0.sME4vRg2Xj8SnO407QJdZX08JzxFzklVsoNWMEp-gSs', 'ios', '123', 'expired', '2021-03-29 11:26:04'),
(9, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wMy0yOSAwNTowNjoxOSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDMtMjkgMTc6MDY6MTkifQ.Elftj6HsaufoQpVbfpYpENx_2ACRteVjS4SeTvvqXRQ', 'ios', 'dsajkl', 'expired', '2021-03-29 13:06:19'),
(10, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMzAgMDI6Mjg6MTgiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTMwIDE0OjI4OjE4In0.3HLE_83mooC9G9k6aEwsyCzSZ9pjQ5G-NNqrJPd3_w8', 'ios', '123', 'expired', '2021-03-30 10:28:18'),
(11, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wMy0zMCAxMTo1OToyMCIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDMtMzAgMjM6NTk6MjAifQ.rpeox2EtNSO97pW30_dAKpM52fqhlA_hGVUMPTzJgzw', 'ios', 'dsajkl', 'expired', '2021-03-30 19:59:20'),
(12, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDMtMzEgMDY6Mjg6MTYiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTAzLTMxIDE4OjI4OjE2In0.2BExOn_9tSI3HypJ_z1e4QEaFVsjMEQ9WyXUiwD83H8', 'ios', '123', 'expired', '2021-03-31 14:28:16'),
(14, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0wMSAwMjo1Nzo0MiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMDIgMDI6NTc6NDIifQ.UPWyZetWEvY9o_rBjuU3RBug3Z44RnsvYGwtuwCdgvU', 'ios', 'dsajkl', 'expired', '2021-04-01 10:57:42'),
(15, 14, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTQiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDEgMTI6Mzg6NDAiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTAyIDEyOjM4OjQwIn0.Is-uD48FFASyxTdeqG-DdME0VzRJd_Zzz4lmqwG-1_k', 'ios', '123', 'normal', '2021-04-01 20:38:40'),
(16, 15, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTUiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDEgMTI6Mzk6NTUiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTAyIDEyOjM5OjU1In0.CgZnTys_EtD0vdK4hlUzUAQN7TTNJN3eaGI2SrorhY8', 'ios', '123', 'normal', '2021-04-01 20:39:55'),
(17, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDEgMTU6MTI6MTgiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTAyIDE1OjEyOjE4In0.cYmLV_CJ-17bskiB4UfeEFw9wHr-tKgvYebnAV_y8hs', 'ios', '123', 'expired', '2021-04-01 23:12:18'),
(18, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDUgMDM6NTI6MTEiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTA2IDAzOjUyOjExIn0.IGzX92gQHG2831I2AMWsXAzSLP-ErGY47zhGA4B5rRM', 'ios', '123', 'expired', '2021-04-05 11:52:11'),
(19, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDYgMDU6MzA6NDciLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTA3IDA1OjMwOjQ3In0.oSmHzIXSprbx0E8MHXYHo2G6_g8FZd32xCEWG0i7uJQ', 'ios', '123', 'expired', '2021-04-06 13:30:47'),
(20, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDcgMDY6MjU6NDEiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTA4IDA2OjI1OjQxIn0.OOy9AJN94efY6FwXIqJwlNoxvsnLNiSdy3HH9vnU070', 'ios', '123', 'expired', '2021-04-07 14:25:41'),
(21, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMDkgMDc6MjQ6MDIiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTEwIDA3OjI0OjAyIn0.TTxqkrlnEZpW5fEFOmn4hSZfU-dypfajZ4GlUiHBnY8', 'ios', '123', 'expired', '2021-04-09 15:24:02'),
(23, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0wOSAxMDo0MDo0MiIsImV4cGlyZWRfdGltZSI6IjIwMjItMDQtMDkgMTA6NDA6NDIifQ.92WPMB0xqi8TP5EgDM-3w0nkbfKv6aOh5pHKHVsNpmk', 'ios', 'dsajkl', 'expired', '2021-04-09 18:40:42'),
(24, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTAgMDk6Mjg6MjYiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTExIDA5OjI4OjI2In0.DX2u0XPCN2nkE7uziUn8ecpmeq4ZQ1yDbpnjB57khss', 'ios', '123', 'expired', '2021-04-10 17:28:26'),
(25, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTIgMDE6MTM6NDIiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTEzIDAxOjEzOjQyIn0.joZ6PJI5oZ2sV6QjA7UG79iLHmTH1Yv2cXbYDz5J1Dw', 'ios', '123', 'expired', '2021-04-12 09:13:42'),
(26, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTIiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTIgMDI6MzM6MDkiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTEzIDAyOjMzOjA5In0.7sxm7zEYJf929BkrlEPz4ncfAXnkPdp_rQTUW2Y5XBk', 'ios', '123', 'normal', '2021-04-12 10:33:09'),
(27, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0xMiAwMjo1ODoxNSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMTMgMDI6NTg6MTUifQ.pBDALPBWAKLTgWEMEisD3wnc3jxpLffafdZsgF5gRwo', 'ios', '123', 'expired', '2021-04-12 10:58:15'),
(28, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0xMyAwMzoxNTo1MyIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMTQgMDM6MTU6NTMifQ.SR3j8ufspoHWAXfR_7KaEx5RQjufc5QCueDnM2h8hfM', 'ios', '123', 'expired', '2021-04-13 11:15:53'),
(29, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTMgMDQ6MTE6NDMiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTE0IDA0OjExOjQzIn0.Q3vXe5_MPUqiTuagDTw9S22fxJepY37Ygsh6dXGbloQ', 'ios', '123', 'expired', '2021-04-13 12:11:43'),
(30, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0xNCAwMzozMjowNCIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMTUgMDM6MzI6MDQifQ.YKg3bPlD2ca-4VxymBtALoTHCi3vjsruAqsjW2HSNp0', 'ios', '123', 'expired', '2021-04-14 11:32:04'),
(31, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTQgMDg6MDQ6MDAiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTE1IDA4OjA0OjAwIn0.JVGHXyRidx8VEY2z9BNAC9rag3bhqZAm6Jo9kio9mN0', 'ios', '123', 'expired', '2021-04-14 16:04:00'),
(32, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0xNSAwMzo0NTozMiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMTYgMDM6NDU6MzIifQ.ifqBWpPsyK8AFeHmfgUFYiFlAB4YzgG25fxzVDCw3X4', 'ios', '123', 'expired', '2021-04-15 11:45:32'),
(33, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0xNiAwNDoxNTozNiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMTcgMDQ6MTU6MzYifQ.jrT5F5lsvVSilLjfdUHGsCdET1Dy1t1rsxlfIxRXe8U', 'ios', '123', 'expired', '2021-04-16 12:15:36'),
(34, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTYgMDg6MzE6MzkiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTE3IDA4OjMxOjM5In0.zOP_TSdrVFCkroTjEfs_ccWRGXlvX_Cf2L3rzaxULXc', 'ios', '123', 'expired', '2021-04-16 16:31:39'),
(35, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0yMiAxMTo0OTo1OSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMjMgMTE6NDk6NTkifQ.YBo-a3sNOjocuyMTdGJh-WXA8WBZaeULUtHB3XgLzxE', 'ios', '123', 'expired', '2021-04-22 19:49:59'),
(36, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0yMyAwODoyNzoyOSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMjQgMDg6Mjc6MjkifQ.QaJ2BPQYbA96bBocSqi43jfd4WLFHFiUFTqCvazktgY', 'ios', 'dsajkl', 'expired', '2021-04-23 16:27:29'),
(37, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMjMgMDk6Mzc6MjkiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTI0IDA5OjM3OjI5In0.J2DRQzgHuJwyZ-DXmnysGHOvDMNk2TNx7EpIkk3E51Q', 'ios', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMTYgMDg6MzE6MzkiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTE3IDA4OjMxOjM5In0.zOP_TSdrVFCkroTjEfs_ccWRGXlvX_Cf2L3rzaxULXc', 'expired', '2021-04-23 17:37:29'),
(38, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNC0yNyAxMzozNDo0OSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDQtMjggMTM6MzQ6NDkifQ.zD-askxZhkIAbP5v7EcOWxlhZm-gxVEZ9QLV5aGWZ1s', 'ios', '123', 'expired', '2021-04-27 21:34:49'),
(39, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMjcgMTM6MzY6MDAiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTI4IDEzOjM2OjAwIn0.aZUyajoc9LlI7CYCz4DNfeh7fQFlA05OBkbr641-M_8', 'ios', '123', 'expired', '2021-04-27 21:36:00'),
(40, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDQtMjkgMTE6MDc6NDUiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA0LTMwIDExOjA3OjQ1In0.iO_mS5soAOwzvHaZ4Yq_PeOs6VAnaYAc1wXHm31Jfv4', 'ios', '123', 'expired', '2021-04-29 19:07:45'),
(41, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNS0wMyAwMjoyOTowMCIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDUtMDQgMDI6Mjk6MDAifQ.D9v7eYpT4rCxiTO_4626Co5BfwZsIlzg2TiMlFUpfRk', 'ios', '123', 'expired', '2021-05-03 10:29:00'),
(42, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMDMgMTA6NDI6NDgiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTA0IDEwOjQyOjQ4In0.HhLm4G4XS_6aepZ0scgaMMZfbs7OXcUrLLxJdR6n8Mk', 'ios', '123', 'expired', '2021-05-03 18:42:48'),
(43, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNS0wNCAwNzo1Mjo1NSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDUtMDUgMDc6NTI6NTUifQ.Kdx4lDmWQT7uIcMnXFjMOUzxBXTKTHyz7Ykqz4MJXw0', 'ios', '123', 'expired', '2021-05-04 15:52:55'),
(44, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMDUgMDM6MzU6MjMiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTA2IDAzOjM1OjIzIn0.IOKm--0BJwC0jQZYprMMG6XWmWcF1iKwoDS6g9Ybtz8', 'ios', '123', 'expired', '2021-05-05 11:35:23'),
(45, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNS0wNSAwOTowMjozOSIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDUtMDYgMDk6MDI6MzkifQ.ep9HoTI3BSIIh5yWb1skAkdRShROdyrK-htUTizwoUw', 'ios', '123', 'expired', '2021-05-05 17:02:39'),
(46, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMDYgMDk6MTk6MTgiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTA3IDA5OjE5OjE4In0.7mHmNFzoN_kLq_anx_Se5Txk-cazyrxJmfBdCKXJqIw', 'ios', '123', 'expired', '2021-05-06 17:19:18'),
(47, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNS0wNiAwOTozNTozMiIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDUtMDcgMDk6MzU6MzIifQ.RQexDSflEhWgonxdYO2YSKT0Ku1sX57ai6XvAR2j4Aw', 'ios', '123', 'expired', '2021-05-06 17:35:32'),
(48, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImNyZWF0ZV90aW1lIjoiMjAyMS0wNS0wNyAwOTo0NTo1MCIsImV4cGlyZWRfdGltZSI6IjIwMjEtMDUtMDggMDk6NDU6NTAifQ.pVxrSknF-lXIygHil8gaABUHYdMCK8XP3mWyGUjpp0I', 'ios', '123', 'normal', '2021-05-07 17:45:50'),
(49, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMDcgMTE6NTg6NDIiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTA4IDExOjU4OjQyIn0.lXwEtOi20m9eDLq0TDzGRqmz91v6ag96P_MAMUzkjz8', 'ios', '123', 'expired', '2021-05-07 19:58:42'),
(50, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMTAgMTA6NTQ6MDIiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTExIDEwOjU0OjAyIn0.b6b1_f4FJuuBJSeRK89I_FWGcGjvk7qYo-MYb_7L7qY', 'ios', '123', 'expired', '2021-05-10 18:54:02'),
(51, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTYiLCJjcmVhdGVfdGltZSI6IjIwMjEtMDUtMTEgMTI6NDg6MTIiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA1LTEyIDEyOjQ4OjEyIn0.zPDldtqW2Z3oOgDF0SKehgoD_b7O4QZ59UyN4B3gw40', 'ios', '123', 'normal', '2021-05-11 20:48:12');

-- --------------------------------------------------------

--
-- 資料表結構 `memo`
--

CREATE TABLE `memo` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` enum('yellow','blue','red','green','purple','gray') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yellow',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `memo`
--

INSERT INTO `memo` (`id`, `user_id`, `text`, `color`, `is_delete`) VALUES
(1, 1, 'sadsadasdd123', 'red', 1),
(2, 1, 'newnenwnenwnwenn', 'blue', 1),
(3, 1, '編輯1', 'gray', 1),
(4, 1, 'sadsadasdd123sadas', 'red', 1),
(5, 1, '哈哈哈哈哈哈哈嗯嗯嗯嗯', 'green', 1),
(6, 1, '這是星期一', 'red', 1),
(7, 1, '第一個', 'yellow', 1),
(8, 1, '第二個', 'blue', 1),
(9, 1, '第三個', 'purple', 1),
(10, 1, '一', 'yellow', 1),
(11, 1, '一', 'yellow', 1),
(12, 1, '二', 'green', 1),
(13, 1, '三', 'blue', 1),
(14, 1, '四', 'red', 1),
(15, 1, '五', 'purple', 1),
(16, 1, '六', 'gray', 1),
(17, 1, '七', 'yellow', 1),
(18, 1, '八', 'yellow', 1),
(19, 1, '九', 'blue', 1),
(20, 1, '十', 'purple', 1),
(21, 1, '啦啦啦啦啦啦啦啦啦啦啦啦啦啦', 'red', 1),
(22, 1, '哈哈哈哈哈哈哈哈哈哈哈哈哈哈', 'purple', 1),
(23, 1, '哈哈哈一', 'yellow', 1),
(24, 16, '', 'yellow', 1),
(25, 16, '契變-盧愛玲', 'yellow', 1),
(26, 16, '契變-盧愛玲', 'yellow', 1),
(27, 16, '產物-十一茶屋', 'yellow', 1),
(28, 16, '產物-十一茶屋', 'purple', 1),
(29, 16, '考試-杜家玲', 'blue', 1),
(30, 16, 'ddd', 'red', 1),
(31, 16, 'rr', 'gray', 1),
(32, 16, 'quick', 'green', 1),
(33, 16, 'quick', 'green', 1),
(34, 16, 'quic', 'green', 1),
(35, 16, 'aa', 'red', 1),
(36, 16, 'kayak', 'yellow', 1),
(37, 16, 'zzz', 'yellow', 1),
(38, 16, 'pppp', 'green', 1),
(39, 16, 'dock', 'blue', 1),
(40, 16, 'd', 'red', 1),
(41, 16, 'd天恩', 'purple', 1),
(42, 16, '大愛電視台報導引述消息人士', 'purple', 1),
(43, 16, '河谷沖積扇⋯⋯我覺得自己好像有點說不過去欸欸哈拉沙的感覺真的不錯耶，我們的確是這樣的人生觀光區的人生觀光科', 'gray', 1),
(44, 16, '喔婷婷喔忒厄特的名字啊。有意無意的不一樣了。黃國瑋也很好耶哈拉區都可以看到他們來了，我們的朋友們都在努力的努力完成夢想成真', 'yellow', 1),
(45, 16, '我', 'yellow', 1),
(46, 16, '我', 'yellow', 1),
(47, 16, '我', 'yellow', 1),
(48, 16, 'Peter', 'red', 1),
(49, 16, 'Peter', 'blue', 1),
(50, 16, 'Peter', 'purple', 1),
(51, 16, 'Peter', 'green', 1),
(52, 16, 'Peter', 'gray', 1),
(53, 16, 'Peter', 'gray', 1),
(54, 16, 'Peter', 'gray', 1),
(55, 16, 'Peter', 'gray', 1),
(56, 16, 'Peter', 'gray', 1),
(57, 16, 'Peter', 'gray', 1),
(58, 16, 'Peter', 'red', 1),
(59, 16, 'Peter', 'red', 1),
(60, 16, 'Peter', 'red', 1),
(61, 16, 'Peter', 'red', 1),
(62, 16, 'Peter', 'red', 1),
(63, 16, 'Peter', 'red', 1),
(64, 16, 'Peter', 'red', 1),
(65, 16, 'Peter', 'red', 1),
(66, 16, 'Peter', 'red', 1),
(67, 1, '', 'yellow', 1),
(68, 1, 'kkkk', 'blue', 1),
(69, 1, 'kkkk', 'blue', 1),
(70, 1, 'kkkk', 'blue', 1),
(71, 1, 'kkkk', 'blue', 1),
(72, 1, 'kkkk', 'blue', 1),
(73, 1, 'kkkk', 'blue', 1),
(74, 1, 'kkkk', 'blue', 1),
(75, 1, 'kkkk', 'blue', 1),
(76, 1, 'kkkk', 'blue', 1),
(77, 1, 'kkkk', 'blue', 1),
(78, 1, 'kkkk', 'blue', 1),
(79, 1, 'kkkk', 'blue', 1),
(80, 1, 'kkkk', 'blue', 1),
(81, 1, 'kkkk', 'blue', 1),
(82, 1, 'kkkk', 'blue', 1),
(83, 1, 'kkkk', 'blue', 1),
(84, 1, 'kkkk', 'blue', 1),
(85, 1, 'kkkk', 'blue', 1),
(86, 1, 'kkkk', 'blue', 1),
(87, 1, 'kkkk', 'blue', 1),
(88, 1, 'kkkk', 'blue', 1),
(89, 1, 'kkkk', 'blue', 1),
(90, 1, '啦啦啦啦啦啦啦', 'red', 1),
(91, 1, '喔喔喔喔喔', 'red', 1),
(92, 1, '嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯欸欸欸餓餓餓餓喔喔喔一座在生生世世此一唷喔喔餓喔嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯欸欸欸餓餓餓餓喔喔喔一座在生生世世此一唷喔喔餓喔喔猜猜錯愕愕喔喔喔喔喔喔喔喔猜猜錯愕愕喔喔喔喔喔喔喔', 'yellow', 1),
(93, 1, '嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯欸欸欸餓餓餓餓喔喔喔一座在生生世世此一唷喔喔餓喔嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯嗯欸欸欸餓餓餓餓喔喔喔一座在生生世世此一唷喔喔餓喔喔猜猜錯愕愕喔喔喔喔喔喔喔喔猜猜錯愕愕喔喔喔喔喔喔喔', 'yellow', 1),
(94, 1, '123', 'yellow', 1),
(115, 1, '啦啦啦啦啦', 'yellow', 1),
(116, 1, '喔喔喔喔喔', 'yellow', 1),
(117, 1, '10', 'yellow', 1),
(118, 1, '11', 'green', 1),
(119, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'green', 1),
(95, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'blue', 1),
(96, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'green', 1),
(97, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現嗯嗯二二二', 'red', 1),
(98, 1, '1', 'purple', 1),
(99, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'gray', 1),
(100, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'yellow', 1),
(101, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'yellow', 1),
(102, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'blue', 1),
(103, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'red', 1),
(104, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'gray', 1),
(105, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'purple', 1),
(106, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'green', 1),
(107, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'gray', 1),
(108, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'blue', 1),
(109, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'red', 1),
(110, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'gray', 1),
(111, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'purple', 1),
(112, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'blue', 1),
(113, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'yellow', 1),
(114, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'blue', 1),
(120, 1, '喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現喔喔喔村上春樹實現', 'red', 1),
(121, 16, 'also I', 'blue', 1),
(122, 16, '車險', 'yellow', 1),
(123, 16, '車險', 'blue', 1),
(124, 16, '外科', 'yellow', 1),
(125, 16, '打球', 'yellow', 1),
(126, 16, '打球', 'red', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `month_goal`
--

CREATE TABLE `month_goal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(4) NOT NULL COMMENT '西元',
  `month` int(2) NOT NULL COMMENT '月份',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `now_num` int(20) NOT NULL,
  `total_num` int(20) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `month_goal`
--

INSERT INTO `month_goal` (`id`, `user_id`, `year`, `month`, `name`, `now_num`, `total_num`, `create_date`, `is_delete`) VALUES
(1, 1, 2021, 3, 'FYP(元)', 1000000, 1000000, '2021-04-01 15:03:20', 0),
(2, 1, 2021, 3, 'FYC(元)', 21312, 1000000, '2021-04-01 15:03:20', 0),
(3, 1, 2021, 4, 'FYC', 4, 5, '2021-04-01 19:01:07', 0),
(4, 1, 2021, 4, '增員', 1, 2, '2021-04-16 13:07:52', 0),
(5, 1, 2021, 4, '增員', 1, 4, '2021-04-23 11:36:33', 0),
(6, 1, 2021, 4, '嗯嗯嗯嗯嗯', 6, 60000, '2021-04-23 12:27:27', 0),
(7, 1, 2021, 4, '姊姊在', 0, 0, '2021-04-23 12:30:55', 0),
(8, 1, 2021, 4, 'FYC', 8, 800, '2021-04-23 12:35:04', 0),
(9, 1, 2021, 4, '增員', 1, 2, '2021-04-23 23:30:24', 0),
(10, 1, 2021, 4, 'FYP', 0, 50, '2021-04-23 23:30:47', 0),
(11, 1, 2021, 4, '名片', 8, 80, '2021-04-23 23:37:26', 0),
(12, 1, 2021, 4, '跑步', 1, 4, '2021-04-24 00:19:27', 0),
(13, 1, 2021, 4, '家族聚餐', 1, 4, '2021-04-24 00:19:59', 0),
(14, 1, 2021, 4, '在一起', 0, 0, '2021-04-24 00:35:46', 0),
(15, 1, 2021, 4, '但還是', 0, 0, '2021-04-24 00:35:52', 0),
(16, 1, 2021, 4, '他自己都沒有', 0, 0, '2021-04-24 00:35:59', 0),
(17, 1, 2021, 4, '他自己是什麼', 0, 0, '2021-04-24 00:36:05', 0),
(18, 1, 2021, 4, '不同角度', 0, 0, '2021-04-24 00:36:12', 0),
(19, 1, 2021, 4, '在一起也不會', 0, 0, '2021-04-24 00:36:15', 0),
(20, 1, 2021, 4, '這樣就會', 0, 0, '2021-04-24 00:36:21', 0),
(21, 1, 2021, 4, '不僅為未來將繼續保持經濟繁榮安定和平進程會繼續努力呀⋯⋯桃子桃子桃子桃子媽媽姊姊姊姊有鴨子姊姊天', 0, 0, '2021-04-24 00:36:46', 0),
(22, 16, 2021, 4, 'FYP', 0, 60, '2021-04-27 21:40:25', 0),
(23, 16, 2021, 4, 'FYC', 10, 8, '2021-04-27 21:40:42', 0),
(24, 16, 2021, 4, '增員', 1, 4, '2021-04-27 21:41:25', 0),
(25, 16, 2021, 4, '跑步', 1, 4, '2021-04-29 19:07:57', 0),
(26, 16, 2021, 5, '跑步', 4, 4, '2021-05-03 20:11:43', 0),
(27, 16, 2021, 5, '增員', 1, 4, '2021-05-04 11:24:57', 0),
(28, 16, 2021, 5, 'FYP', 0, 10, '2021-05-04 11:25:12', 0),
(29, 16, 2021, 5, '減重', 0, 5, '2021-05-05 19:27:16', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `send_id` int(11) NOT NULL COMMENT '寄發者的user_id或member_id',
  `receive_id` int(11) NOT NULL COMMENT '收件者的user_id',
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` enum('system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '寄發通知的時間',
  `is_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `notice`
--

INSERT INTO `notice` (`id`, `send_id`, `receive_id`, `subject`, `class`, `create_date`, `is_read`) VALUES
(1, 0, 1, '嗨', 'system', '2021-03-23 17:56:54', 1),
(2, 0, 12, '21212', 'system', '2021-03-26 19:37:03', 0),
(3, 0, 12, 'wqeqwe12e1212e21e', 'system', '2021-03-26 19:37:27', 0),
(4, 0, 1, 'awedqw1212', 'system', '2021-03-26 19:38:22', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL COMMENT '!=(1~7)表示對應的使用者自訂義的item',
  `alert` tinyint(1) NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_complete` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `schedule`
--

INSERT INTO `schedule` (`id`, `user_id`, `customer_id`, `item_id`, `alert`, `note`, `create_date`, `start_date`, `end_date`, `is_complete`, `is_delete`) VALUES
(1, 1, 225, 9, 0, 'nottetetetetet', '2021-04-12 17:30:49', '2021-04-12 17:48:58', '2021-04-12 18:48:58', 0, 1),
(2, 1, 225, 9, 1, 'xdd', '2021-04-12 17:53:48', '2021-04-12 18:51:32', '2021-04-12 20:48:58', 0, 1),
(17, 1, 225, 9, 1, 'xdd', '2021-04-12 18:07:29', '2021-04-14 18:50:32', '2021-04-15 18:48:58', 0, 1),
(16, 12, 225, 9, 0, 'nottetetetetet', '2021-04-12 18:06:14', '2021-04-14 18:50:32', '2021-04-15 18:48:58', 0, 0),
(18, 1, 225, 3, 0, 'nottetetetetet', '2021-04-13 11:54:43', '2021-04-17 18:50:32', '2021-04-18 18:48:58', 0, 1),
(19, 1, 1, 1, 0, '0', '2021-04-13 20:07:05', '2021-04-13 20:00:00', '2021-04-13 21:00:00', 0, 1),
(20, 1, 31, 7, 1, 'Pzzzzz', '2021-04-14 15:48:34', '2021-04-14 16:00:00', '2021-04-14 17:00:00', 0, 1),
(21, 1, 27, 33, 1, 'Oooooooo', '2021-04-14 20:01:27', '2021-04-14 17:13:00', '2021-04-14 18:13:00', 0, 1),
(22, 1, 225, 3, 0, 'nottetetetetet', '2021-04-14 20:13:11', '2021-04-17 18:50:32', '2021-04-18 18:48:58', 0, 1),
(23, 1, 31, 1, 1, 'Aaaaa', '2021-04-14 20:29:10', '2021-04-14 19:02:00', '2021-04-14 20:02:00', 0, 1),
(24, 1, 31, 7, 1, 'Xxxxx', '2021-04-14 20:33:24', '2021-04-14 21:00:00', '2021-04-14 22:00:00', 0, 1),
(25, 1, 225, 3, 0, 'nottetetetetet', '2021-04-15 12:15:27', '2021-04-17 18:50:32', '2021-04-17 19:00:58', 0, 0),
(26, 1, 225, 4, 0, 'nottetetetetet', '2021-04-15 12:15:45', '2021-04-17 19:20:32', '2021-04-17 19:30:58', 1, 0),
(27, 1, 225, 1, 0, 'nottetetetetet', '2021-04-15 12:15:51', '2021-04-17 19:40:32', '2021-04-17 19:50:58', 1, 0),
(28, 1, 31, 3, 1, 'Cccccc', '2021-04-15 13:28:30', '2021-04-15 00:00:00', '2021-04-15 01:00:00', 1, 0),
(29, 1, 31, 3, 0, 'Ccccc', '2021-04-15 13:32:24', '2021-04-15 14:01:00', '2021-04-15 15:01:00', 0, 0),
(34, 1, 225, 3, 0, '0', '2021-05-03 18:52:51', '2021-05-03 18:18:00', '2021-05-03 19:18:00', 1, 0),
(30, 16, 35, 1, 0, '0', '2021-04-16 16:35:34', '2021-04-16 15:48:00', '2021-04-16 16:48:00', 0, 0),
(31, 16, 35, 1, 0, '0', '2021-04-17 09:39:36', '2021-04-16 10:02:00', '2021-04-16 11:02:00', 1, 0),
(32, 16, 65, 1, 0, '0', '2021-04-17 09:41:02', '2021-04-14 10:00:00', '2021-04-14 11:00:00', 0, 0),
(33, 16, 216, 1, 0, '0', '2021-04-17 09:49:00', '2021-04-13 10:25:00', '2021-04-13 11:25:00', 1, 0),
(35, 1, 225, 2, 0, '0', '2021-05-03 18:53:21', '2021-05-04 19:01:00', '2021-05-04 20:01:00', 1, 0),
(36, 1, 26, 4, 0, '0', '2021-05-03 18:54:08', '2021-05-05 19:19:00', '2021-05-05 20:19:00', 1, 0),
(37, 16, 55, 3, 0, '0', '2021-05-05 11:38:55', '2021-05-04 18:12:00', '2021-05-04 19:12:00', 1, 0),
(38, 16, 229, 3, 0, '看房', '2021-05-05 11:39:24', '2021-05-05 13:13:00', '2021-05-05 14:13:00', 1, 0),
(39, 16, 236, 1, 0, '0', '2021-05-06 23:09:32', '2021-05-06 12:23:00', '2021-05-06 13:23:00', 0, 0),
(40, 16, 234, 3, 0, '0', '2021-05-06 23:10:09', '2021-05-06 14:21:00', '2021-05-06 15:21:00', 1, 0),
(41, 16, 230, 3, 0, '0', '2021-05-06 23:12:25', '2021-05-06 17:09:00', '2021-05-06 18:09:00', 1, 0),
(42, 16, 69, 3, 0, '0', '2021-05-06 23:15:11', '2021-05-07 15:06:00', '2021-05-07 16:06:00', 1, 0),
(43, 16, 237, 3, 0, '0', '2021-05-06 23:16:28', '2021-05-03 12:27:00', '2021-05-03 13:27:00', 1, 0),
(44, 16, 235, 3, 0, '0', '2021-05-06 23:17:26', '2021-05-08 12:55:00', '2021-05-08 13:55:00', 1, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `upgrad`
--

CREATE TABLE `upgrad` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upgrad_stroe` enum('full_features') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_features' COMMENT '加值方案的主要系統(full_feature = 所有功能皆可使用方案)',
  `upgrad_plans` enum('full_1','full_3','full_12') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '使用者購買方案,1個月,3個月,12個月',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '購買日(可能不等於使用開始日,看系統設計,例如今天買3次1個月,開始時間就不同)',
  `start_date` datetime DEFAULT NULL COMMENT '預計方案開始日,從使用者的privilege_end_date複製過來',
  `end_date` datetime DEFAULT NULL COMMENT '預計方案截止日,從start_date加上購買時間的到期日,並且一併更新使用者的privilege_end_date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者購買的加值方案';

--
-- 傾印資料表的資料 `upgrad`
--

INSERT INTO `upgrad` (`id`, `user_id`, `upgrad_stroe`, `upgrad_plans`, `create_date`, `start_date`, `end_date`) VALUES
(5, 1, 'full_features', 'full_3', '2021-04-09 09:57:04', '2021-04-17 17:48:58', '2021-07-16 17:48:58'),
(6, 1, 'full_features', 'full_12', '2021-04-12 02:36:01', '2021-07-16 17:48:58', '2022-07-16 17:48:58');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `atid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '註冊登錄的email',
  `mobile_id` longtext COLLATE utf8mb4_unicode_ci COMMENT '無註冊登錄',
  `g_id` longtext COLLATE utf8mb4_unicode_ci,
  `fb_id` longtext COLLATE utf8mb4_unicode_ci,
  `apple_id` longtext COLLATE utf8mb4_unicode_ci,
  `password` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_face` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否開啟使用face id 登入',
  `ai_Interview` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否開啟智能約訪',
  `privilege_end_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '預設過期,系統新增使用者時要記得幫此欄位+30天',
  `user_code` text COLLATE utf8mb4_unicode_ci COMMENT '轉換資料用',
  `seal_code` text COLLATE utf8mb4_unicode_ci COMMENT '成為別人的業務',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `atid`, `email`, `mobile_id`, `g_id`, `fb_id`, `apple_id`, `password`, `name`, `avatar`, `create_date`, `is_face`, `ai_Interview`, `privilege_end_date`, `user_code`, `seal_code`, `is_delete`) VALUES
(1, NULL, 'chenzenyang2021@gmail.com', NULL, 'asdaad', NULL, 'ad', '20737de1ffc196843a2001ffe6e86a20756e7d290385af8a3d79a1fa6d4373d46768689213f84a7416c71d8b1764acd08a73a6571367929e06a24be42cccd52fu6jnrlHnTB0/vbz1cCJIVfaOZASgbaEnEBGurSt3pHPfzrW7DH8oc8zguv0Sft07jaQpRbPn9glhvtSBEe2Arg==', '帥哥嘟嘟', NULL, '2021-03-17 17:51:33', 0, 1, '2022-07-16 17:48:58', NULL, NULL, 0),
(10, NULL, 'chaenzenyang2021d@gmail.com', NULL, 'ad', NULL, NULL, '3dc23419999faa4c95546b3b038e9ce32e24305238c53e65ac63129c55ef491beb92eaeed8bfdbd5396a1c17b0eec9696cb425c095419de18b124ebf4ead5903PTdE0d0uuAoOZCA4dHX3i0sJUP0L9sHVYYunk0IJk0jT47zEqvBS1jGvNMKRZ0YcC8/oGj2pv9AdyuDRMoyxQw==', '嘟嘟請嗆請嗆', NULL, '2021-03-18 20:18:25', 0, 1, '2021-03-18 20:18:25', NULL, NULL, 0),
(11, NULL, 'chenzeeeen@gmail.com', NULL, NULL, NULL, NULL, '2b7fb604a000f275c49f2f2cdda48345bb06ae775e6df216de32cfdeff71d10587caafd6900ac40700458915ba1e556ed2e32bd59c521211dea9cba923de21d1Gz0ez/z3dCtQVLXFHAb2wgOwYuhe3wXc/Y1c7m3kiSA7NgYUeFAF0nV1breBGFtzyxFsTb1Jl4Kv3+TLG8MBtQ==', 'dd', NULL, '2021-03-25 18:41:30', 0, 1, '2021-03-25 18:41:30', NULL, NULL, 0),
(12, NULL, 'aaa', NULL, NULL, NULL, NULL, 'f9b637d2c384f8ba23217bcad08e777d9a50b40207722fa9e343bd1b5644ee7a55c7bd826abe6a7b90e519d516955cefff3980f91a8b09317ef53fb5d9fd1bfaajOkFcSfSoKm5zTJjC1ORHROn+pf25QNMT8UUvDGibQNkuZ2o5A+vsHjEHZiVcQqi6/gZta7uZ7t2QoYGjUEXA==', 'aaa', NULL, '2021-03-25 19:09:35', 0, 1, '2021-03-25 19:09:35', NULL, NULL, 0),
(13, NULL, 'chenzeedeen@gmail.com', NULL, NULL, NULL, NULL, 'c77f20055a34b1f1ee40c3efb3889f4f0ccde9100f0534300cda54f92d47697dc3dca7ffdc2c2bdd23baf482a3c3e4a1d3726498eda3d4ac0aecc7fb40f217c4AgTGHoeeou//QWQxoQLnI7Rw8nwLzppUlUw9SsHP+sfEZfuBl8ahnXOBaui3u2riJ8mE4Iv5+zs4+PPahsFSCA==', 'dd', NULL, '2021-03-25 19:32:17', 1, 1, '2021-03-25 19:32:17', NULL, NULL, 0),
(14, NULL, 'ccc', NULL, NULL, NULL, NULL, '0b4bd7f6c7e718a0e4cf5911e9bc3380cdf7ace33b0dad625f4a3d58dd059ef10329c87cf939f057d95fe619f2c5aba8604ec6a1812730c31256bbf8f87c99a43ndBrc4HWkr+78pR/yVW4MYppKpIXiIRY/3w/UecCbALHVVIwhkA2Of0PbMnpQzdvrVQ42UT777mi74aSo/hbg==', 'ccc', NULL, '2021-04-01 20:38:07', 1, 1, '2021-04-01 20:38:07', NULL, NULL, 0),
(15, NULL, 'gggg', NULL, NULL, NULL, NULL, '5b11bb9006e8bbf1d563a2f446b8c402a9fe667e198b57ce8655bfcc5c151415f2b19fa56a51bbf933069fb1366a8ff30efb5ad7cf7dfc78c3cd41a301c0ea378TtZYAbxzwC+vfpflEr15djHdV82VjPpAevUJ/KFocMzmy8hRZsK7K4Ab/A4d+qNCZXYMgQ5/G/oVWW2ynkdzg==', 'gggg', NULL, '2021-04-01 20:39:44', 1, 1, '2021-04-01 20:39:44', NULL, NULL, 0),
(16, NULL, 'leewang0916@gmail.com', NULL, NULL, NULL, '001909.037f9d2920f645b09379144a0005085e.0642', '67b996372bee5df7cbdf46bd48394bc53d4a1b2be19acf723a88ad6677af37ff66aeb33869e579e0ed24656f6d5d0c3da9d5003d63e6832eb89f96a3f7a66115ewP0WzMw79OOK1IWcx8Qxovz8MjL02ZMofNtjiOIEScqKkPvprfmyNQpJwBub9cU3iD0eihxVskZWlkP5QnPxw==', '王尚禮', NULL, '2021-04-01 23:11:22', 1, 1, '2021-04-01 23:11:22', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `user_customer_field`
--

CREATE TABLE `user_customer_field` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '欄位名稱',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者自訂義的客戶資料欄位';

--
-- 傾印資料表的資料 `user_customer_field`
--

INSERT INTO `user_customer_field` (`id`, `user_id`, `field_name`, `is_delete`) VALUES
(1, 1, '電話', 0),
(2, 1, '信箱', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `user_customer_source`
--

CREATE TABLE `user_customer_source` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '對應使用者自訂義的source',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者自訂義的source';

--
-- 傾印資料表的資料 `user_customer_source`
--

INSERT INTO `user_customer_source` (`id`, `user_id`, `source_name`, `is_delete`) VALUES
(1, 0, '同學', 0),
(2, 0, '朋友', 0),
(3, 0, '親戚', 0),
(4, 0, '社團', 0),
(5, 0, '轉介', 0),
(6, 0, '隨緣', 0),
(7, 1, '同事', 0),
(8, 1, '大學同學', 0),
(9, 1, '大學同學啦', 0),
(10, 1, '大學同學啦啦', 0),
(11, 1, '錢包', 0),
(12, 1, '女朋友', 0),
(13, 1, '老婆', 0),
(14, 1, '喔喔喔喔喔喔', 0),
(15, 1, '嗷嗷嗷嗷嗷嗷', 0),
(16, 1, '喔喔喔喔喔', 0),
(17, 1, '女朋友', 0),
(18, 1, '啦啦啦啦', 0),
(19, 1, '昂昂昂昂', 0),
(20, 1, '咿咿呀呀', 0),
(21, 1, '123', 0),
(22, 1, 'aaa', 0),
(23, 1, 'BBB', 0),
(24, 16, '同事', 0),
(25, 16, '師長', 0),
(26, 16, '增員', 0),
(27, 16, '增員', 0),
(28, 16, '增員', 0),
(29, 16, '增員', 0),
(30, 16, '增員', 0),
(31, 16, '增員', 0),
(32, 16, '增員', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `user_digital`
--

CREATE TABLE `user_digital` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('year','month') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '是年目標還是月目標',
  `this` int(4) NOT NULL COMMENT 'type是年就寫幾年(2021)，類型是月就寫幾月',
  `FYP` decimal(19,4) NOT NULL,
  `FYC` decimal(19,4) NOT NULL,
  `lead_goal` int(5) NOT NULL COMMENT '帶領新進人員的目標數',
  `lead_actual` int(5) DEFAULT NULL COMMENT '實際的帶領新進人員數'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者的年度目標與月目標設定';

-- --------------------------------------------------------

--
-- 資料表結構 `user_privilege_bill`
--

CREATE TABLE `user_privilege_bill` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scheme` enum('1','3','12') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '使用者購買方案,1個月,3個月,12個月',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '購買日(可能不等於使用開始日,看系統設計,例如今天買3次1個月,開始時間就不同)',
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '預計方案開始日，預設當前時間表示預設顧客過期才購買方案',
  `end_date` datetime NOT NULL COMMENT '預計方案截止日'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者購買的加值方案';

-- --------------------------------------------------------

--
-- 資料表結構 `user_schedule_item`
--

CREATE TABLE `user_schedule_item` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '對應使用者自訂義的item',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每個使用者自訂義的行事曆item';

--
-- 傾印資料表的資料 `user_schedule_item`
--

INSERT INTO `user_schedule_item` (`id`, `user_id`, `item_name`, `is_delete`) VALUES
(1, 0, '新增', 0),
(2, 0, '約訪', 0),
(3, 0, '面談', 0),
(4, 0, '建議書', 0),
(5, 0, '簽約', 0),
(6, 0, '收費', 0),
(7, 0, '其他', 0),
(8, 1, 'qq', 1),
(9, 1, 'qqss', 1),
(10, 1, 'ruqwpo', 1),
(11, 1, 'qqss', 1),
(12, 1, 'qqssdadadad', 1),
(13, 1, 'qqssdadadad', 1),
(14, 1, '第一行程', 1),
(15, 1, '哈哈哈哈哈', 1),
(16, 1, 'qqssdadadad', 1),
(17, 1, 'qqssdadadad', 1),
(18, 1, 'qqssdadadad', 1),
(19, 1, '惺惺相惜', 1),
(20, 1, 'a', 1),
(21, 1, 'b', 1),
(22, 1, 'c', 1),
(23, 1, 'd', 1),
(24, 1, 'a', 1),
(25, 1, 'b', 1),
(26, 1, 'c', 1),
(27, 1, 'd', 1),
(28, 1, 'e', 1),
(29, 1, 'a', 1),
(30, 1, 'b', 1),
(31, 1, 'a', 1),
(32, 1, 'b ', 1),
(33, 1, 'aaaa ', 0),
(34, 16, '理賠', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `year_goal`
--

CREATE TABLE `year_goal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(4) NOT NULL COMMENT '西元',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `now_num` int(20) NOT NULL,
  `total_num` int(20) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `year_goal`
--

INSERT INTO `year_goal` (`id`, `user_id`, `year`, `name`, `now_num`, `total_num`, `create_date`, `is_delete`) VALUES
(3, 1, 2021, 'FYP', 0, 300, '2021-04-01 15:02:59', 0),
(6, 1, 2021, 'FYC', 10, 50, '2021-04-01 18:43:33', 0),
(5, 1, 2021, '增員', 4, 6, '2021-04-01 18:24:33', 0),
(7, 1, 2021, '增員啦ss', 0, 30, '2021-04-16 13:07:05', 0),
(8, 1, 2021, '增員啦ssw', 0, 30, '2021-04-16 13:07:34', 0),
(9, 1, 2021, '新增年1', 10, 5, '2021-04-23 11:32:05', 0),
(10, 1, 2021, 'FYC', 10, 100, '2021-04-23 12:19:06', 0),
(11, 1, 2021, 'FYP', 0, 1000, '2021-04-23 12:19:59', 0),
(12, 16, 2021, '增員', 4, 8, '2021-05-05 12:48:19', 0),
(13, 16, 2021, 'FYC', 20, 80, '2021-05-05 12:49:49', 0),
(14, 16, 2021, 'FYP', 0, 250, '2021-05-05 12:50:01', 0),
(15, 16, 2021, '跑步', 1, 4, '2021-05-05 19:26:59', 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `customer_mgr`
--
ALTER TABLE `customer_mgr`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `customer_mgr_field`
--
ALTER TABLE `customer_mgr_field`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `flow_record`
--
ALTER TABLE `flow_record`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `goal_customer`
--
ALTER TABLE `goal_customer`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `login_token`
--
ALTER TABLE `login_token`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `memo`
--
ALTER TABLE `memo`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `month_goal`
--
ALTER TABLE `month_goal`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `upgrad`
--
ALTER TABLE `upgrad`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 資料表索引 `user_customer_field`
--
ALTER TABLE `user_customer_field`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user_customer_source`
--
ALTER TABLE `user_customer_source`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user_digital`
--
ALTER TABLE `user_digital`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user_privilege_bill`
--
ALTER TABLE `user_privilege_bill`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user_schedule_item`
--
ALTER TABLE `user_schedule_item`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `year_goal`
--
ALTER TABLE `year_goal`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `customer_mgr`
--
ALTER TABLE `customer_mgr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `customer_mgr_field`
--
ALTER TABLE `customer_mgr_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `flow_record`
--
ALTER TABLE `flow_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `goal`
--
ALTER TABLE `goal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `goal_customer`
--
ALTER TABLE `goal_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `login_token`
--
ALTER TABLE `login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `memo`
--
ALTER TABLE `memo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `month_goal`
--
ALTER TABLE `month_goal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `upgrad`
--
ALTER TABLE `upgrad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_customer_field`
--
ALTER TABLE `user_customer_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_customer_source`
--
ALTER TABLE `user_customer_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_schedule_item`
--
ALTER TABLE `user_schedule_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `year_goal`
--
ALTER TABLE `year_goal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
