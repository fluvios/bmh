-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2018 at 01:56 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bmh`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `alamat` text CHARACTER SET utf8 NOT NULL,
  `telepon` varchar(15) CHARACTER SET utf8 NOT NULL,
  `ext` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kodepos` int(6) NOT NULL,
  `provinsi` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kabupaten` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kelurahan` varchar(255) CHARACTER SET utf8 NOT NULL,
  `jenis` enum('rumah','kantor') CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `alamat`, `telepon`, `ext`, `kodepos`, `provinsi`, `kabupaten`, `kecamatan`, `kelurahan`, `jenis`) VALUES
(5, 3, '', '', '', 0, '', '', '', '', 'rumah'),
(6, 3, '', '', '', 0, '', '', '', '', 'kantor'),
(7, 4, '', '', '', 0, '', '', '', '', 'rumah'),
(8, 4, '', '', '', 0, '', '', '', '', 'kantor'),
(9, 5, '', '', '', 0, '', '', '', '', 'rumah'),
(10, 5, '', '', '', 0, '', '', '', '', 'kantor');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `welcome_text` varchar(200) NOT NULL,
  `welcome_subtitle` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `result_request` int(10) UNSIGNED NOT NULL COMMENT 'The max number of shots per request',
  `status_page` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Offline, 1 Online',
  `email_verification` enum('0','1') NOT NULL COMMENT '0 Off, 1 On',
  `email_no_reply` varchar(200) NOT NULL,
  `email_admin` varchar(200) NOT NULL,
  `captcha` enum('on','off') NOT NULL DEFAULT 'on',
  `file_size_allowed` int(11) UNSIGNED NOT NULL COMMENT 'Size in Bytes',
  `google_analytics` text NOT NULL,
  `paypal_account` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `googleplus` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `google_adsense` text NOT NULL,
  `currency_symbol` char(10) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `min_donation_amount` int(15) UNSIGNED NOT NULL,
  `min_campaign_amount` int(15) UNSIGNED NOT NULL,
  `max_campaign_amount` int(15) UNSIGNED NOT NULL,
  `payment_gateway` enum('Paypal','Stripe') NOT NULL DEFAULT 'Paypal',
  `paypal_sandbox` enum('true','false') NOT NULL DEFAULT 'true',
  `min_width_height_image` varchar(100) NOT NULL,
  `fee_donation` int(10) UNSIGNED NOT NULL,
  `auto_approve_campaigns` enum('0','1') NOT NULL DEFAULT '1',
  `stripe_secret_key` varchar(255) NOT NULL,
  `stripe_public_key` varchar(255) NOT NULL,
  `max_donation_amount` int(15) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `title`, `description`, `welcome_text`, `welcome_subtitle`, `keywords`, `result_request`, `status_page`, `email_verification`, `email_no_reply`, `email_admin`, `captcha`, `file_size_allowed`, `google_analytics`, `paypal_account`, `twitter`, `facebook`, `googleplus`, `instagram`, `google_adsense`, `currency_symbol`, `currency_code`, `min_donation_amount`, `min_campaign_amount`, `max_campaign_amount`, `payment_gateway`, `paypal_sandbox`, `min_width_height_image`, `fee_donation`, `auto_approve_campaigns`, `stripe_secret_key`, `stripe_public_key`, `max_donation_amount`) VALUES
(1, 'BMH | Crowdfunding Platform', 'Baitul Maal Hidayatullah Melakukan pemberdayaan umat dengan meningkatkan kuantitas dan kualitas pendidikan dan dakwah. Meningkatkan kesadaran umat untuk', 'Sempurnakan Akhir Tahun Dengan Zakat', 'Baitul Maal Hidayatullah', 'Baitul Maal Hidayatullah, BMH, Crowdfunding', 8, '1', '0', 'noraplay@bdv-hostmaster.com', 'noraplay@bdv-hostmaster.com', 'on', 2048, '', 'paypal@yousite.com', 'https://www.twitter.com/', 'https://www.facebook.com/', 'https://plus.google.com/', 'https://www.instagram.com/', '<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>\r\n<ins class=\"adsbygoogle\"\r\nstyle=\"display:block\"\r\ndata-ad-client=\"ca-pub-4300901855004979\"\r\ndata-ad-slot=\"7623553448\"\r\ndata-ad-format=\"auto\"></ins> <script>(adsbygoogle=window.adsbygoogle||[]).push({});</script>', 'Rp.', 'IDR', 5, 100, 10000, 'Paypal', 'true', '800x400', 5, '1', '', '', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `amils`
--

CREATE TABLE `amils` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cabang_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `weight` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `birthplace` varchar(255) CHARACTER SET utf8 NOT NULL,
  `birthdate` date NOT NULL,
  `address` text CHARACTER SET utf8 NOT NULL,
  `postalcode` int(11) NOT NULL,
  `kelurahan` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kota` varchar(255) CHARACTER SET utf8 NOT NULL,
  `provinsi` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phonenumber` varchar(15) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `amil_type` enum('tetap','tidak_tetap','relawan') NOT NULL,
  `home_type` enum('sendiri','sewa','keluarga') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `amils`
--

INSERT INTO `amils` (`id`, `user_id`, `cabang_id`, `registration_date`, `name`, `gender`, `weight`, `height`, `birthplace`, `birthdate`, `address`, `postalcode`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `phonenumber`, `email`, `amil_type`, `home_type`) VALUES
(1, 6, 12, '2018-02-07 17:47:35', 'Gisela Lowe', 'male', 30, 121, 'Jakarta', '1993-02-12', 'Mollitia nihil aute qui ducimus qui aut culpa ducimus amet nesciunt quibusdam rem quasi quia tempore', 124114, 'Salero', 'Padang Panjang', 'Sukabumi', 'Jawa Timur', '+246-49-7086919', 'ribatofer@gmail.com', 'tetap', 'sendiri');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `account_number` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `logo`, `name`, `account_number`) VALUES
(1, '', 'BNI', '123456789'),
(2, '', 'BRI', '123456789'),
(3, '', 'Mandiri', '321321333');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','pending','finish') NOT NULL DEFAULT 'pending',
  `token_id` varchar(255) NOT NULL,
  `goal` int(11) UNSIGNED NOT NULL,
  `location` varchar(200) NOT NULL,
  `finalized` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No 1 Yes',
  `categories_id` int(10) UNSIGNED NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `deadline` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `small_image`, `large_image`, `title`, `description`, `user_id`, `date`, `status`, `token_id`, `goal`, `location`, `finalized`, `categories_id`, `featured`, `deadline`) VALUES
(2, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test', 'test', 1, '2017-12-27 17:01:27', 'active', '1', 10000, 'Jakarta', '1', 18, '0', '10-01-2018'),
(3, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test 2', 'test 2', 1, '2017-12-27 17:01:27', 'active', '3', 10000, 'Jakarta', '1', 18, '0', '10-01-2018'),
(4, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test 3', 'test 3', 1, '2017-12-27 17:01:27', 'active', '4', 10000, 'Jakarta', '1', 18, '0', '10-01-2018'),
(5, '11514422137dmruw869mshioutx0lctks0bdtrcee6f8ouuxp8c.jpg', '11514422137sfqvg354anszzsa7xgjolau2burab7a1dvw1nzwq.jpg', 'Pembanguan Masjid Perumnas 3', 'Akan dibangun masjid di perumnas 3', 1, '2017-12-28 00:48:57', 'active', 'lq7OwKgquyGXBl3L7VZIda9dzuJHrsvNP7AjyNdG5luB86B306L7l2wwW7UQEeJjSOQM7v0rcbM1l3xyklqIsHUv6U6wIR9HooDhA1Pra7TpnoQBnxGr3SbljTp6gra390xOBMEdTKRVzQMhYK6wvDTGEkpXexK52jxdekq9rwdVx8dVxehUgLmcR6Uuy7J6mYuF4tEK', 10000, 'Bekasi timur', '0', 21, '1', '11-05-2018');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_reported`
--

CREATE TABLE `campaigns_reported` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `mode` enum('on','off') NOT NULL DEFAULT 'on',
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `mode`, `image`) VALUES
(18, 'Infaq', 'infaq', 'on', ''),
(19, 'Qurban', 'qurban', 'on', ''),
(20, 'Wakaf', 'wakaf', 'on', ''),
(21, 'Zakat', 'zakat', 'on', ''),
(22, 'Public', 'public', 'on', '');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People\'s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People\'s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_logs`
--

CREATE TABLE `deposit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `amount` int(11) UNSIGNED NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `transfer_evidance` varchar(255) NOT NULL,
  `transfer_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('unpaid','paid','expired','denied') NOT NULL DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `postal_code` varchar(100) NOT NULL,
  `donation` int(11) UNSIGNED NOT NULL,
  `donation_type` enum('Routine','Isidentil') NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `payment_status` enum('paid','unpaid','expired','denied') NOT NULL DEFAULT 'unpaid',
  `oauth_uid` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `anonymous` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No, 1 Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `campaigns_id`, `txn_id`, `user_id`, `fullname`, `email`, `country`, `postal_code`, `donation`, `donation_type`, `payment_gateway`, `payment_status`, `oauth_uid`, `comment`, `payment_date`, `expired_date`, `anonymous`) VALUES
(1, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '401222', 5000, 'Isidentil', 'Deposit', 'unpaid', '', 'Percobaan', '2018-02-04 16:38:12', '0000-00-00 00:00:00', '1'),
(2, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Deposit', 'unpaid', '', 'Anyong', '2018-02-04 16:41:30', '0000-00-00 00:00:00', '1'),
(3, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Deposit', 'unpaid', '', 'Coba', '2018-02-04 16:43:43', '0000-00-00 00:00:00', '1'),
(4, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Deposit', 'unpaid', '', '124114', '2018-02-04 16:45:11', '0000-00-00 00:00:00', '1'),
(5, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Deposit', 'unpaid', '', '124114', '2018-02-04 16:52:25', '0000-00-00 00:00:00', '0'),
(6, 5, 'null', 3, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Deposit', 'unpaid', '', '124114', '2018-02-04 16:58:53', '0000-00-00 00:00:00', '0'),
(7, 5, 'null', 0, 'Percobaan', 'Test@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Deposit', 'paid', '', 'Coba', '2018-02-06 07:53:08', '0000-00-00 00:00:00', ''),
(8, 5, 'null', 0, 'Percobaan', 'Test@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 02:11:34', '0000-00-00 00:00:00', ''),
(9, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 3000, 'Isidentil', 'Transfer', 'unpaid', '', 'Hello Guys', '2018-02-07 18:38:47', '0000-00-00 00:00:00', '1'),
(10, 5, 'null', 0, 'Damian Summers', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 18:39:34', '0000-00-00 00:00:00', '0'),
(11, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Blackbird', '2018-02-07 18:54:11', '0000-00-00 00:00:00', '0'),
(12, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Jojoba', '2018-02-07 18:56:58', '0000-00-00 00:00:00', '0'),
(13, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Percobaan', '2018-02-07 18:58:09', '0000-00-00 00:00:00', '0'),
(14, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 1000, 'Isidentil', 'Transfer', 'unpaid', '', 'jova', '2018-02-07 18:59:28', '0000-00-00 00:00:00', '0'),
(15, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 3000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 19:00:22', '0000-00-00 00:00:00', '0'),
(16, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 1000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 19:02:14', '0000-00-00 00:00:00', '0'),
(17, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 1000, 'Isidentil', 'Transfer', 'unpaid', '', 'Jojoba oil', '2018-02-07 19:06:37', '0000-00-00 00:00:00', '0'),
(18, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 1000, 'Isidentil', 'Transfer', 'unpaid', '', 'asdfg', '2018-02-07 19:10:24', '0000-00-00 00:00:00', '0'),
(19, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Routine', 'Transfer', 'unpaid', '', 'Coba dulu', '2018-02-07 19:12:15', '0000-00-00 00:00:00', '0'),
(20, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 5000, 'Isidentil', 'Transfer', 'unpaid', '', 'Percobaan', '2018-02-07 19:16:03', '0000-00-00 00:00:00', '0'),
(21, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 19:16:44', '0000-00-00 00:00:00', '0'),
(22, 5, 'null', 0, 'Damian Summers', 'fachri@mailnesia.com', 'Indonesia', '124114', 3000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 19:18:17', '0000-00-00 00:00:00', '0'),
(23, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 10, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba dulu', '2018-02-07 19:19:22', '0000-00-00 00:00:00', '0'),
(24, 5, 'null', 0, 'Damian Summers', 'fachri@mailnesia.com', 'Indonesia', '124114', 12, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-07 19:20:17', '0000-00-00 00:00:00', '0'),
(25, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 10, 'Isidentil', 'Transfer', 'paid', '', 'Coba', '2018-02-07 19:21:07', '0000-00-00 00:00:00', '0'),
(26, 5, 'null', 0, 'Admin Alpha', 'fachri@mailnesia.com', 'Indonesia', '124114', 2000, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba terus', '2018-02-07 23:27:19', '0000-00-00 00:00:00', '0'),
(27, 5, 'null', 4, 'Damian Summers', 'betauser@mailnesia.com', 'Australia', '124114', 5, 'Isidentil', 'Transfer', 'unpaid', '', 'Coba', '2018-02-08 12:47:40', '0000-00-00 00:00:00', '0');

-- --------------------------------------------------------

--
-- Table structure for table `donations_logs`
--

CREATE TABLE `donations_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `donations_id` int(11) UNSIGNED NOT NULL,
  `bank_id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `amount` int(11) UNSIGNED NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `transfer_evidance` varchar(255) NOT NULL,
  `transfer_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `donations_logs`
--

INSERT INTO `donations_logs` (`id`, `donations_id`, `bank_id`, `user_id`, `amount`, `payment_gateway`, `transfer_evidance`, `transfer_date`) VALUES
(1, 7, 0, 3, 2000, 'Deposit', '31517967224ekr3elo6mjqoswf.jpg', '2018-02-07 01:33:44'),
(2, 7, 0, 3, 2000, 'Deposit', '31517967300xpyuwppsfd3xmqc.jpg', '2018-02-07 01:35:00'),
(3, 25, 2, 3, 10, 'Transfer', '315180328319p29n2ycohj5dds.png', '2018-02-07 19:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `educations`
--

CREATE TABLE `educations` (
  `amil_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `education` varchar(255) CHARACTER SET utf8 NOT NULL,
  `notes` text NOT NULL,
  `certificate` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `amil_id` int(11) NOT NULL,
  `relation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `education` varchar(255) CHARACTER SET utf8 NOT NULL,
  `birthplace` varchar(255) CHARACTER SET utf8 NOT NULL,
  `birthdate` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `amil_id` int(11) NOT NULL,
  `langitude` varchar(255) CHARACTER SET utf8 NOT NULL,
  `longitude` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `amil_id`, `langitude`, `longitude`, `status`) VALUES
(1, 1, '', '', 'off');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('news','inspiration','consultation','magazine') NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','pending','finish') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(100) NOT NULL,
  `show_navbar` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No, 1 Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `show_navbar`) VALUES
(2, 'Terms', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \r\n\r\n<br/><br/>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \r\n\r\n<br/><br/>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets ', 'terms-of-service', '0'),
(3, 'Privacy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \n\n<br/><br/>\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'privacy', '0'),
(5, 'About us', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'about', '0'),
(7, 'Support', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'support', '0'),
(8, 'Bagaimana Caranya ??', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'how-it-works', '1'),
(10, 'Contoh', '<p>lorem ipsum</p>\r\n', 'contoh', '1');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `amil_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `starting_position` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_position` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `location` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reserved`
--

CREATE TABLE `reserved` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reserved`
--

INSERT INTO `reserved` (`id`, `name`) VALUES
(14, 'account'),
(31, 'api'),
(2, 'app'),
(30, 'bootstrap'),
(37, 'campaigns'),
(34, 'categories'),
(36, 'collections'),
(29, 'comment'),
(42, 'config'),
(25, 'contact'),
(41, 'database'),
(35, 'featured'),
(32, 'freebies'),
(9, 'goods'),
(1, 'gostock1'),
(11, 'jobs'),
(21, 'join'),
(16, 'latest'),
(20, 'login'),
(33, 'logout'),
(27, 'members'),
(13, 'messages'),
(19, 'notifications'),
(15, 'popular'),
(6, 'porn'),
(26, 'programs'),
(12, 'projects'),
(3, 'public'),
(23, 'register'),
(40, 'resources'),
(39, 'routes'),
(17, 'search'),
(7, 'sex'),
(44, 'storage'),
(8, 'tags'),
(38, 'tests'),
(24, 'upgrade'),
(28, 'upload'),
(4, 'vendor'),
(5, 'xxx');

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `countries_id` char(25) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number_1` varchar(255) NOT NULL,
  `phone_number_2` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(70) NOT NULL,
  `status` enum('pending','active','suspended','delete') NOT NULL DEFAULT 'active',
  `role` enum('normal','admin','amil') NOT NULL DEFAULT 'normal',
  `remember_token` varchar(100) NOT NULL,
  `token` varchar(80) NOT NULL,
  `confirmation_code` varchar(125) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `born_date` date NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paypal_account` varchar(200) NOT NULL,
  `payment_gateway` varchar(50) NOT NULL,
  `bank` text NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `type`, `name`, `countries_id`, `password`, `email`, `phone_number_1`, `phone_number_2`, `date`, `avatar`, `status`, `role`, `remember_token`, `token`, `confirmation_code`, `updated_at`, `created_at`, `born_date`, `registration_date`, `paypal_account`, `payment_gateway`, `bank`, `saldo`) VALUES
(1, 0, '', 'BMH', '', '$2y$10$XzUZMAN.KUwPkpg0xSIDi.GOvyF1JDeciikz.lZJvwgMVzQqKP6uu', 'admin@example.com', '', '', '2016-09-09 11:04:42', '11475147757yvjfoku2pktmkia.jpg', 'active', 'admin', '0sbLsTO48gxTwdf2dk0JNUxtjaTSr3IXgC0M15IZ0I7jMOqtHtTGAyqmrGYt', 'Wy4VkAl2dxHb9WHoXjTowSGPXFPnEQHca6RBe2yeqqmRafs0hSbCEobhNkZZAbCDIru60ceLzAAOI3fj', '', '2017-12-28 08:02:07', '2016-09-09 15:34:42', '0000-00-00', '2018-02-07 07:19:15', '', '', '', 0),
(2, 0, '', 'Maulidiyanto', '100', '$2y$10$kK/gPoJ8a4sg9yY83LwJ3O3FiOwKdPIq36lqifF36QShsrkq8WYY.', 'olidbomber@gmail.com', '', '', '2017-12-27 14:57:22', 'default.jpg', 'active', 'normal', 'LjgF7TKxWKjKbIGzwRiizUcafbdL6oqOod61CaYLu9It3baqtONgv0tKIenV', 'XVV3VX7pJ5GteyJwLZxWvde6TnN8wcXF91dPY0Mdtw8ihknaHYAaoA2W9f7Gebge69CpzRyqry6', '', '2017-12-28 08:32:54', '2017-12-27 14:57:22', '0000-00-00', '2018-02-07 07:19:15', '', 'Bank', 'BCA - 10202929301', 0),
(3, 0, '', 'Admin Alpha', '100', '$2y$10$cL1G8P2lsLUa3KJxDrsj6OVIkxiOJBTvxGaeZwbqWFbPx3enPSDXy', 'fachri@mailnesia.com', '', '', '2018-01-27 04:33:28', 'default.jpg', 'active', 'admin', '4NMV6uVpblpAtR8TxVo0LEaS5iRKgGmPh37NHvN3FESpDEHAGAiAMy7AWSfr', 'byb0G8Q99ZMBOlfNfqJv7EzUbIey0f5ljM7vZB5dnRFwpLYVOmY5JoZvYSKFJhn3mnaXDvi92KV', '', '2018-02-08 05:51:08', '2018-01-26 21:33:28', '0000-00-00', '2018-02-07 07:19:15', '', '', '', 488000),
(4, 0, '', 'Beta User', '15', '$2y$10$BUwS62EuQaXLN59eAcBY/O4qk978cogMJD03LMTKkp/AbmAa.f54.', 'betauser@mailnesia.com', '', '', '2018-02-01 09:03:14', 'default.jpg', 'active', 'normal', 'RJPLhyoXV97ZMOiJ7mRLxy7DHTCFHdoZnJCrNGDql55EPKJHUPXK9rE2e0oI', 'g6eRMotxnegWftSLPqsxxnX36ojEeFuD4ODt7IfXiCqB4mhuEwf1XBcsfgdaankVDHlEjkchMxN', '', '2018-02-08 05:48:08', '2018-02-01 02:03:14', '0000-00-00', '2018-02-07 07:19:15', '', '', '', 0),
(5, 1002121, 'personal', 'Kim Campos', '61', '$2y$10$5xM3jsh8L6vh2VTfQl9iOuUou1EjQt27cR05gQs2x9xsrAc1FFToq', 'pekyrihak@mailnesia.com', '+169-31-9204055', '+531-64-9840888', '2018-02-07 14:28:22', 'default.jpg', 'active', 'normal', 'Wp8tzVEsh3TJL2qakAUNmXReici12Gh2slny8PHQ0PsKcLg9rTY6qSy2RXmD', 'CGWQy5Tp6cJy42Ol5w4yWrYsDhPtkzI2l50dATfqJGEkk6UFoefbBrzpGy0TfoEP7959g5Iv0g5', '', '2018-02-07 08:56:15', '2018-02-07 07:28:22', '1993-07-23', '2018-02-07 14:28:22', '', '', '', 0),
(6, 0, '', 'Gisela Lowe', '', '$2y$10$GPbgM9QKo/wK.njQt2U41u0XhANmYf.h68xlIafm2eEytoBmR7e/e', 'ribatofer@gmail.com', '', '', '2018-02-07 17:47:35', 'default.jpg', 'active', 'amil', '', 'oWTyzIUSeP3tcWulxy6vOaddszH0iucqvx7jIQkFFBMYKWgUriJSSIOP0wyffUGNmv099E4l3tMOh1gl', '', '2018-02-07 10:47:35', '2018-02-07 10:47:35', '0000-00-00', '2018-02-07 17:47:35', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `variables`
--

CREATE TABLE `variables` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `amount` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gateway` varchar(100) NOT NULL,
  `account` text NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `txn_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `max_campaign_amount` (`max_campaign_amount`);

--
-- Indexes for table `amils`
--
ALTER TABLE `amils`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cabang_id` (`cabang_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_id` (`token_id`),
  ADD KEY `author_id` (`user_id`,`status`,`token_id`),
  ADD KEY `image` (`small_image`),
  ADD KEY `goal` (`goal`),
  ADD KEY `categories_id` (`categories_id`);

--
-- Indexes for table `campaigns_reported`
--
ALTER TABLE `campaigns_reported`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_logs`
--
ALTER TABLE `deposit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_id` (`campaigns_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donations_logs`
--
ALTER TABLE `donations_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_id` (`donations_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bank_id` (`bank_id`);

--
-- Indexes for table `educations`
--
ALTER TABLE `educations`
  ADD KEY `amil_id` (`amil_id`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD KEY `amil_id` (`amil_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `amil_id` (`amil_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`user_id`,`status`),
  ADD KEY `image` (`small_image`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hash` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD KEY `amil_id` (`amil_id`);

--
-- Indexes for table `reserved`
--
ALTER TABLE `reserved`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_id` (`token_id`),
  ADD KEY `author_id` (`token_id`),
  ADD KEY `image` (`image`),
  ADD KEY `category_id` (`campaigns_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `username` (`status`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `variables`
--
ALTER TABLE `variables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaings_id` (`campaigns_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amils`
--
ALTER TABLE `amils`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `campaigns_reported`
--
ALTER TABLE `campaigns_reported`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `deposit_logs`
--
ALTER TABLE `deposit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `donations_logs`
--
ALTER TABLE `donations_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reserved`
--
ALTER TABLE `reserved`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
