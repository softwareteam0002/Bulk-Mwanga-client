-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2020 at 08:58 AM
-- Server version: 8.0.11
-- PHP Version: 7.2.21

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET
AUTOCOMMIT = 0;
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vodacom_disbursement`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_activities`
--

CREATE TABLE `audit_activities`
(
    `id`                   int(20) NOT NULL,
    `user_id`              int(20) NOT NULL,
    `activity_description` varchar(200)       DEFAULT NULL,
    `created_at`           timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logins`
--

CREATE TABLE `audit_logins`
(
    `id`         int(20) NOT NULL,
    `device`     varchar(55)        DEFAULT NULL,
    `ip_address` varchar(20)        DEFAULT NULL,
    `user_id`    int(20) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches`
(
    `id`              int(11) NOT NULL,
    `batch_no`        varchar(20) NOT NULL,
    `short_code`      bigint(20) NOT NULL,
    `amount_paid`     float        DEFAULT NULL,
    `total_amount`    float        DEFAULT NULL,
    `batch_status_id` int(1) DEFAULT '0' COMMENT '0 means pending',
    `batch_file_url`  varchar(100) DEFAULT NULL,
    `created_by`      int(11) DEFAULT NULL,
    `created_at`      timestamp NULL DEFAULT NULL,
    `updated_at`      timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `batch_no`, `short_code`, `amount_paid`, `total_amount`, `batch_status_id`,
                       `batch_file_url`, `created_by`, `created_at`, `updated_at`)
VALUES (8, '20200117152424', 200, NULL, 26700, 2, NULL, NULL, '2020-01-17 09:24:24', '2020-01-17 09:24:24'),
       (9, '20200117161155', 1, NULL, 26700, 2, NULL, NULL, '2020-01-17 10:11:55', '2020-01-17 10:11:55'),
       (10, '20200117170433', 1, NULL, 26700, 2, NULL, NULL, '2020-01-17 11:04:33', '2020-01-17 11:04:33'),
       (11, '20200120140044', 200, NULL, 26700, 2, NULL, NULL, '2020-01-20 08:00:44', '2020-01-20 08:00:44'),
       (12, '20200121102258', 200, NULL, 26700, 2, NULL, NULL, '2020-01-21 04:22:58', '2020-01-27 07:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `batch_payments`
--

CREATE TABLE `batch_payments`
(
    `id`              int(11) NOT NULL,
    `batch_no`        varchar(20) NOT NULL,
    `short_code`      bigint(20) NOT NULL,
    `amount_paid`     float        DEFAULT NULL,
    `total_amount`    float        DEFAULT NULL,
    `batch_status_id` int(1) DEFAULT '0' COMMENT '0 means pending',
    `batch_file_url`  varchar(100) DEFAULT NULL,
    `created_by`      int(11) DEFAULT NULL,
    `created_at`      timestamp NULL DEFAULT NULL,
    `updated_at`      timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batch_payments`
--

INSERT INTO `batch_payments` (`id`, `batch_no`, `short_code`, `amount_paid`, `total_amount`, `batch_status_id`,
                              `batch_file_url`, `created_by`, `created_at`, `updated_at`)
VALUES (8, '20200117152424', 12300, NULL, 26700, 2, NULL, NULL, '2020-01-17 09:24:24', '2020-01-17 09:24:24'),
       (9, '20200117161155', 1, NULL, 26700, 2, NULL, NULL, '2020-01-17 10:11:55', '2020-01-17 10:11:55'),
       (10, '20200117170433', 200, NULL, 26700, 2, NULL, NULL, '2020-01-17 11:04:33', '2020-01-17 11:04:33'),
       (11, '20200120140044', 200, NULL, 26700, 2, NULL, NULL, '2020-01-20 08:00:44', '2020-01-20 08:00:44');

-- --------------------------------------------------------

--
-- Table structure for table `batch_staus`
--

CREATE TABLE `batch_staus`
(
    `id`         int(1) NOT NULL,
    `name`       varchar(35) NOT NULL COMMENT 'approved , not approved, awaiting approval, rejected',
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `callbacks`
--

CREATE TABLE `callbacks`
(
    `id`         int(11) DEFAULT NULL,
    `callback`   text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `callbacks`
--

INSERT INTO `callbacks` (`id`, `callback`, `created_at`, `updated_at`)
VALUES (NULL, '\n<dd>\n	edfdsfdf\n	<dddd>\n		sferer\n	</dddd>\n</dd>', '2020-01-14 10:47:48',
        '2020-01-14 10:47:48'),
       (NULL, '\n<dd>\n	edfdsfdf\n	<dddd>\n		sferer\n	</dddd>\n</dd>', '2020-01-14 10:51:38',
        '2020-01-14 10:51:38'),
       (NULL, '\n<dd>\n	edfdsfdf\n	<dddd>\n		sferer\n	</dddd>\n</dd>', '2020-01-14 10:53:27',
        '2020-01-14 10:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `contact_persons`
--

CREATE TABLE `contact_persons`
(
    `id`              int(11) NOT NULL,
    `organization_id` int(11) DEFAULT NULL,
    `fullname`        varchar(100) NOT NULL,
    `position`        varchar(100) NOT NULL,
    `email`           varchar(100) NOT NULL,
    `phone_number`    varchar(15)  NOT NULL,
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`      timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_persons`
--

INSERT INTO `contact_persons` (`id`, `organization_id`, `fullname`, `position`, `email`, `phone_number`, `created_at`,
                               `updated_at`)
VALUES (4, 1, 'baraka machumu', 'software', 'baraka@gmail.com', '33452177', '2020-01-15 12:26:50',
        '2020-01-14 09:24:24'),
       (5, 2, 'gao', 'meja', 'gao@coca.com', '08642214', '2020-01-15 12:26:53', '2020-01-14 18:06:37'),
       (6, 3, 'gao', 'meja', 'gao@coca.com', '08642214', '2020-01-15 12:26:56', '2020-01-14 18:09:11'),
       (7, 1, 'baraka machumu', 'software', 'baraka@gmail.com', '33452177', '2020-01-15 09:27:18',
        '2020-01-15 09:27:18'),
       (8, 1, 'baraka machumu', 'software', 'baraka@gmail.com', '33452177', '2020-01-15 09:27:49',
        '2020-01-15 09:27:49'),
       (9, 4, 'baraka machumu', 'software', 'baraka@gmail.com', '07129023', '2020-01-16 07:38:23',
        '2020-01-16 07:38:23'),
       (10, 5, 'hamis', 'accountant', 'hamis@gmail.com', '0754997494', '2020-01-17 04:13:07', '2020-01-17 04:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `disbursements`
--

CREATE TABLE `disbursements`
(
    `id`             bigint(20) NOT NULL,
    `batch_no`       varchar(30)        DEFAULT NULL,
    `first_name`     varchar(35)        DEFAULT NULL,
    `last_name`      varchar(35)        DEFAULT NULL,
    `full_name`      varchar(100)       DEFAULT NULL,
    `phone_number`   varchar(15)        DEFAULT NULL,
    `network_name`   varchar(20)        DEFAULT NULL,
    `amount`         float              DEFAULT NULL,
    `payment_status` int(11) DEFAULT '0' COMMENT '0 not paid, 1 paid, 2 error.',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`     timestamp NULL DEFAULT NULL,
    `zone`           varchar(100)       DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disbursements`
--

INSERT INTO `disbursements` (`id`, `batch_no`, `first_name`, `last_name`, `full_name`, `phone_number`, `network_name`,
                             `amount`, `payment_status`, `created_at`, `updated_at`, `zone`)
VALUES (58, '20200117152424', 'baraka', 'machumu', NULL, '0716235698', 'vodacom', 8900, 0, '2020-01-26 22:24:12',
        '2020-01-17 09:24:24', NULL),
       (59, '20200117152424', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 09:24:24',
        '2020-01-17 09:24:24', NULL),
       (60, '20200117152424', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-17 09:24:24',
        '2020-01-17 09:24:24', NULL),
       (61, '20200117161155', 'baraka', 'machumu', NULL, '0716235698', NULL, 8900, 0, '2020-01-17 10:11:55',
        '2020-01-17 10:11:55', NULL),
       (62, '20200117161155', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 10:11:55',
        '2020-01-17 10:11:55', NULL),
       (63, '20200117161155', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-17 10:11:55',
        '2020-01-17 10:11:55', NULL),
       (64, '20200117170433', 'baraka', 'machumu', NULL, '0716235698', NULL, 8900, 0, '2020-01-17 11:04:33',
        '2020-01-17 11:04:33', NULL),
       (65, '20200117170433', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 11:04:33',
        '2020-01-17 11:04:33', NULL),
       (66, '20200117170433', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-17 11:04:33',
        '2020-01-17 11:04:33', NULL),
       (67, '20200120140044', 'baraka', 'machumu', NULL, '0716235698', NULL, 8900, 0, '2020-01-20 08:00:44',
        '2020-01-20 08:00:44', NULL),
       (68, '20200120140044', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-20 08:00:44',
        '2020-01-20 08:00:44', NULL),
       (69, '20200120140044', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-20 08:00:44',
        '2020-01-20 08:00:44', NULL),
       (70, '20200121102258', 'Mason', 'Klocko', NULL, '0716235698', 'vodacom', 8900, 0, '2020-01-26 22:40:32',
        '2020-01-26 19:40:32', NULL),
       (71, '20200121102258', 'Yazmin',
        'D\'Amore', NULL, '0716235611', 'tigo', 8900, 0, '2020-01-26 22:40:32', '2020-01-26 19:40:32', NULL),
(72, '20200121102258', 'Jonathan', 'Terry', NULL, '071623569811', 'vodacom', 8900, 0, '2020-01-26 22:40:32', '2020-01-26 19:40:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disbursement_payments`
--

CREATE TABLE `disbursement_payments` (
                                         `id` bigint(20) NOT NULL,
                                         `batch_no` varchar(30) DEFAULT NULL,
                                         `first_name` varchar(35) DEFAULT NULL,
                                         `last_name` varchar(35) DEFAULT NULL,
                                         `full_name` varchar(100) DEFAULT NULL,
                                         `phone_number` varchar(15) DEFAULT NULL,
                                         `network_name` varchar(20) DEFAULT NULL,
                                         `amount` float DEFAULT NULL,
                                         `payment_status` int(11) DEFAULT '0' COMMENT '0 not paid, 1 paid, 2 error.',
                                         `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                         `updated_at` timestamp NULL DEFAULT NULL,
                                         `zone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disbursement_payments`
--

INSERT INTO `disbursement_payments` (`id`, `batch_no`, `first_name`, `last_name`, `full_name`, `phone_number`, `network_name`, `amount`, `payment_status`, `created_at`, `updated_at`, `zone`) VALUES
(58, '20200117152424', 'baraka', 'machumu', NULL, '0716235698', 'Vodacom', 8900, 0, '2020-01-28 10:50:32', '2020-01-17 09:24:24', NULL),
(59, '20200117152424', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 09:24:24', '2020-01-17 09:24:24', NULL),
(60, '20200117152424', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-17 09:24:24', '2020-01-17 09:24:24', NULL),
(61, '20200117161155', 'baraka', 'machumu', NULL, '0716235698', NULL, 8900, 0, '2020-01-17 10:11:55', '2020-01-17 10:11:55', NULL),
(62, '20200117161155', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 10:11:55', '2020-01-17 10:11:55', NULL),
(63, '20200117161155', 'vaileth', 'machumu', NULL, '071623569811', NULL, 8900, 0, '2020-01-17 10:11:55', '2020-01-17 10:11:55', NULL),
(64, '20200117170433', 'baraka', 'machumu', NULL, '0716235698', NULL, 8900, 0, '2020-01-17 11:04:33', '2020-01-17 11:04:33', NULL),
(65, '20200117170433', 'denyo', 'machumu', NULL, '0716235611', NULL, 8900, 0, '2020-01-17 11:04:33', '2020-01-17 11:04:33', NULL),
(66, '20200121102258', 'vaileth', 'machumu', NULL, '071623569811', 'vodacom', 8900, 0, '2020-01-27 08:22:17', '2020-01-17 11:04:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
                             `id` int(11) DEFAULT NULL,
                             `region_id` int(11) DEFAULT NULL,
                             `name` varchar(35) DEFAULT NULL,
                             `created_at` timestamp NULL DEFAULT NULL,
                             `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `region_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kinondoni', NULL, NULL),
(2, 1, 'Ilala', NULL, NULL),
(3, 1, 'Temeke', NULL, NULL),
(4, 1, 'Ubungo', NULL, NULL),
(5, 3, 'Bahi', NULL, NULL),
(6, 3, 'Chamwino', NULL, NULL),
(7, 3, 'Chemba', NULL, NULL),
(8, 3, 'Kondoa', NULL, NULL),
(9, 4, 'Kilombero', NULL, NULL),
(10, 4, 'Kilosa', NULL, NULL),
(11, 4, 'Morogoro Mjini', NULL, NULL),
(12, 4, 'Morogoro Vijijini', NULL, NULL),
(13, 4, 'Mvomero', NULL, NULL),
(15, 1, 'Kigamboni', NULL, NULL),
(16, 6, 'Arumeru Magharibi', NULL, NULL),
(17, 6, 'Arumeru Mashariki', NULL, NULL),
(18, 6, 'Arusha Mjini', NULL, NULL),
(19, 6, 'Karatu', NULL, NULL),
(20, 6, 'Longido', NULL, NULL),
(21, 6, 'Monduli', NULL, NULL),
(22, 6, 'Ngorongoro', NULL, NULL),
(23, 17, 'Bukene', NULL, NULL),
(24, 17, 'Igalula', NULL, NULL),
(25, 17, 'Igunga', NULL, NULL),
(26, 17, 'Kaliua', NULL, NULL),
(27, 17, 'Manonga', NULL, NULL),
(28, 17, 'Nzega Mjini', NULL, NULL),
(29, 17, 'Nzega Vijijini', NULL, NULL),
(30, 17, 'Sikonge', NULL, NULL),
(31, 17, 'Tabora Mjini', NULL, NULL),
(32, 17, 'Ulyankulu', NULL, NULL),
(33, 17, 'Urambo', NULL, NULL),
(34, 17, 'Uyui', NULL, NULL),
(35, 1, 'Kawe', NULL, NULL),
(36, 1, 'Ukonga', NULL, NULL),
(37, 3, 'Dodoma Mjini', NULL, NULL),
(38, 3, 'Kibakwe', NULL, NULL),
(39, 3, 'Kondoa Mjini', NULL, NULL),
(40, 3, 'Kondoa Vijijini', NULL, NULL),
(41, 3, 'Kongwa', NULL, NULL),
(42, 3, 'Mpwapwa', NULL, NULL),
(43, 3, 'Mtera', NULL, NULL),
(44, 10, 'Tanga Mjini', NULL, NULL),
(45, 10, 'Bumbuli', NULL, NULL),
(46, 10, 'Mlalo', NULL, NULL),
(47, 10, 'Pangani', NULL, NULL),
(48, 10, 'Kilindi', NULL, NULL),
(49, 10, 'Mkinga', NULL, NULL),
(50, 10, 'Handeni Vijijini', NULL, NULL),
(51, 10, 'Muheza', NULL, NULL),
(52, 10, 'Korogwe Mjini', NULL, NULL),
(53, 10, 'Korogwe Vijijini', NULL, NULL),
(54, 10, 'Lushoto', NULL, NULL),
(55, 10, 'Handeni Mjini', NULL, NULL),
(56, 8, 'Chalinze', NULL, NULL),
(57, 8, 'Bagamoyo', NULL, NULL),
(58, 8, 'Kibaha Mjini', NULL, NULL),
(59, 8, 'Kibaha Vijijini', NULL, NULL),
(60, 8, 'Kisarawe', NULL, NULL),
(61, 8, 'Mafia', NULL, NULL),
(62, 8, 'Mkuranga', NULL, NULL),
(63, 8, 'Kibiti', NULL, NULL),
(64, 8, 'Rufiji', NULL, NULL),
(65, 11, 'Kilwa Kusini', NULL, NULL),
(66, 11, 'Kilwa Kaskazini', NULL, NULL),
(67, 11, 'Lindi Mjini', NULL, NULL),
(69, 11, 'Liwale', NULL, NULL),
(70, 11, 'Mtama', NULL, NULL),
(71, 11, 'Mchinga', NULL, NULL),
(72, 11, 'Nachingwea', NULL, NULL),
(73, 11, 'Ruangwa', NULL, NULL),
(74, 12, 'Mtwara Mjini', NULL, NULL),
(75, 12, 'Mtwara Vijijini', NULL, NULL),
(76, 12, 'Nanyamba', NULL, NULL),
(77, 12, 'Nanyumbu', NULL, NULL),
(78, 12, 'Ndanda', NULL, NULL),
(79, 11, 'Newala Mjini', NULL, NULL),
(80, 12, 'Newala Vijijini', NULL, NULL),
(81, 12, 'Tandahimba', NULL, NULL),
(82, 12, 'Masasi', NULL, NULL),
(83, 12, 'Lulindi', NULL, NULL),
(84, 1, 'Kigamboni', NULL, NULL),
(85, 8, 'Rufiji', NULL, NULL),
(86, 8, 'Kimanzichana', NULL, NULL),
(87, 8, 'Kibiti', NULL, NULL),
(88, 26, 'Bukombe', NULL, NULL),
(89, 26, 'Chato', NULL, NULL),
(90, 26, 'Geita', NULL, NULL),
(91, 26, 'Mbogwe', NULL, NULL),
(92, 14, 'Iringa Vijijini', NULL, NULL),
(93, 14, 'Iringa Mjini', NULL, NULL),
(94, 14, 'Kilolo', NULL, NULL),
(95, 14, 'Mufindi', NULL, NULL),
(96, 14, 'Mafinga Mjini', NULL, NULL),
(97, 7, 'Biharamulo', NULL, NULL),
(98, 7, 'Bukoba Vijijini', NULL, NULL),
(99, 7, 'Bukoba Mjini', NULL, NULL),
(100, 7, 'Karagwe', NULL, NULL),
(101, 7, 'Kyerwa', NULL, NULL),
(102, 7, 'Misenyi', NULL, NULL),
(103, 7, 'Muleba', NULL, NULL),
(104, 7, 'Ngara', NULL, NULL),
(105, 24, 'Mlele', NULL, NULL),
(106, 24, 'Mpanda Mjini', NULL, NULL),
(107, 24, 'Mpanda Vijijini', NULL, NULL),
(108, 19, 'Buhigwe', NULL, NULL),
(109, 19, 'Kakonko', NULL, NULL),
(110, 19, 'Kasulu Vijijini', NULL, NULL),
(111, 19, 'Kasulu Mjini', NULL, NULL),
(112, 19, 'Kibondo', NULL, NULL),
(113, 19, 'Kigoma Vijijini', NULL, NULL),
(114, 19, 'Kigoma Ujiji', NULL, NULL),
(115, 19, 'Uvinza', NULL, NULL),
(116, 9, 'Hai', NULL, NULL),
(117, 9, 'Moshi Mjini', NULL, NULL),
(118, 9, 'Moshi Vijijini', NULL, NULL),
(119, 9, 'Mwanga', NULL, NULL),
(120, 9, 'Rombo', NULL, NULL),
(121, 9, 'Same', NULL, NULL),
(122, 9, 'Siha', NULL, NULL),
(123, 22, 'Babati Mjini', NULL, NULL),
(124, 22, 'Babati Vijijini', NULL, NULL),
(125, 22, 'Hanang', NULL, NULL),
(126, 22, 'Kiteto', NULL, NULL),
(127, 22, 'Mbulu', NULL, NULL),
(128, 22, 'Simanjiro', NULL, NULL),
(129, 21, 'Bunda', NULL, NULL),
(130, 21, 'Butiama', NULL, NULL),
(131, 21, 'Musoma Mjini', NULL, NULL),
(132, 21, 'Musoma Vijijini', NULL, NULL),
(133, 21, 'Rorya', NULL, NULL),
(134, 21, 'Serengeti', NULL, NULL),
(135, 21, 'Tarime', NULL, NULL),
(136, 15, 'Chunya', NULL, NULL),
(137, 15, 'Ileje', NULL, NULL),
(138, 15, 'Kyela', NULL, NULL),
(139, 15, 'Mbarali', NULL, NULL),
(140, 15, 'Mbeya Vijijini', NULL, NULL),
(141, 15, 'Mbeya Mjini', NULL, NULL),
(142, 15, 'Mbozi', NULL, NULL),
(143, 15, 'Momba', NULL, NULL),
(144, 15, 'Rungwe', NULL, NULL),
(145, 15, 'Tunduma', NULL, NULL),
(146, 4, 'Gairo', NULL, NULL),
(147, 2, 'Ilemela', NULL, NULL),
(148, 2, 'Nyamagana', NULL, NULL),
(149, 2, 'Sengerema', NULL, NULL),
(150, 2, 'Kwimba', NULL, NULL),
(151, 2, 'Magu', NULL, NULL),
(152, 2, 'Misungwi', NULL, NULL),
(153, 2, 'Ukerewe', NULL, NULL),
(154, 23, 'Ludewa', NULL, NULL),
(155, 23, 'Makambako', NULL, NULL),
(156, 23, 'Makete', NULL, NULL),
(157, 23, 'Njombe Vijijini', NULL, NULL),
(158, 23, 'Njombe Mjini', NULL, NULL),
(159, 28, 'Wete', NULL, NULL),
(160, 28, 'Micheweni', NULL, NULL),
(161, 29, 'Chake Chake', NULL, NULL),
(162, 29, 'Mkoani', NULL, NULL),
(163, 18, 'Kalambo', NULL, NULL),
(164, 18, 'Nkasi', NULL, NULL),
(165, 18, 'Sumbawanga Vijijini', NULL, NULL),
(166, 18, 'Sumbawanga Mjini', NULL, NULL),
(167, 13, 'Mbinga', NULL, NULL),
(168, 13, 'Namtumbo', NULL, NULL),
(169, 13, 'Nyasa', NULL, NULL),
(170, 13, 'Songea Vijijini', NULL, NULL),
(171, 13, 'Songea Mjini', NULL, NULL),
(172, 13, 'Tunduru', NULL, NULL),
(173, 20, 'Kahama Mjini', NULL, NULL),
(174, 20, 'Kahama Vijijini', NULL, NULL),
(175, 20, 'Kishapu', NULL, NULL),
(176, 20, 'Shinyanga Vijijini', NULL, NULL),
(177, 20, 'Shinyanga Mjini', NULL, NULL),
(178, 25, 'Bariadi', NULL, NULL),
(179, 25, 'Busega', NULL, NULL),
(180, 25, 'Itilima', NULL, NULL),
(181, 25, 'Maswa', NULL, NULL),
(182, 25, 'Meatu', NULL, NULL),
(183, 16, 'Iramba', NULL, NULL),
(184, 16, 'Manyoni', NULL, NULL),
(185, 16, 'Singida Vijijini', NULL, NULL),
(186, 16, 'Singida Mjini', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                               `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                               `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                               `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                               `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(2, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:21:30.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'ErrorException: Trying to get property \'email\' of non-object in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php:584\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(584): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, \'Trying to get p...\', \'C:\\\\xampp\\\\htdocs...\', 584, Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(480): Illuminate\\Mail\\Mailable->setAddress(Array, NULL, \'to\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(168): Illuminate\\Mail\\Mailable->to(Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\PendingMail->fill(Object(App\\Mail\\SendMail))\n#4 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#5 [internal function]: App\\Jobs\\SendMailJob->handle()\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#12 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#26 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#33 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 {main}', '2020-01-23 09:36:33'),
(3, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":8:{s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:24:11.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'ErrorException: Trying to get property \'email\' of non-object in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php:584\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(584): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, \'Trying to get p...\', \'C:\\\\xampp\\\\htdocs...\', 584, Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(480): Illuminate\\Mail\\Mailable->setAddress(Array, NULL, \'to\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(168): Illuminate\\Mail\\Mailable->to(Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\PendingMail->fill(Object(App\\Mail\\SendMail))\n#4 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#5 [internal function]: App\\Jobs\\SendMailJob->handle()\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#12 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#26 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#33 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 {main}', '2020-01-23 09:36:34'),
        (4, 'database', 'default',
         '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:32:52.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
         'ErrorException: Trying to get property \'email\' of non-object in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php:584\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(584): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, \'Trying to get p...\', \'C:\\\\xampp\\\\htdocs...\', 584, Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(480): Illuminate\\Mail\\Mailable->setAddress(Array, NULL, \'to\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(168): Illuminate\\Mail\\Mailable->to(Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\PendingMail->fill(Object(App\\Mail\\SendMail))\n#4 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#5 [internal function]: App\\Jobs\\SendMailJob->handle()\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#12 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#26 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#33 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 {main}',
         '2020-01-23 09:36:34'),
        (5, 'database', 'default',
         '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";s:22:\\\"barakabryson@gmail.com\\\";s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";s:5:\\\"login\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:42:09.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
         'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
         '2020-01-23 09:42:11'),
        (6, 'database', 'default',
         '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";s:22:\\\"barakabryson@gmail.com\\\";s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";s:5:\\\"login\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:48:07.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
         'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
         '2020-01-23 09:48:08');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES (7, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";s:22:\\\"barakabryson@gmail.com\\\";s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";s:5:\\\"login\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:54:19.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 09:54:21'),
       (8, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";s:2:\\\"22\\\";s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";s:2:\\\"22\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 12:56:19.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 09:56:20'),
       (9, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:04:37.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:04:40'),
       (10, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:06:09.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:06:09');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES (11, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:13:50.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:13:50'),
       (12, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:14:03.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:14:06'),
       (13, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:16:09.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:16:09'),
       (14, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:17:08.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(253): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:17:10');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES (15, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:17:59.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'InvalidArgumentException: View [mail.send_mail] not found. in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:137\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths(\'mail.send_mail\', Array)\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(131): Illuminate\\View\\FileViewFinder->find(\'mail.send_mail\')\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(355): Illuminate\\View\\Factory->make(\'mail.send_mail\', Array)\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(328): Illuminate\\Mail\\Mailer->renderView(\'mail.send_mail\', Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(246): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'mail.send_mail\', NULL, NULL, Array)\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:18:00'),
       (16, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:20:03.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials 18sm2506566wmf.1 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials 18sm2506566wmf.1 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials 18sm2506566wmf.1 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:20:10'),
       (17, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:22:31.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials c195sm3004027wmd.45 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials c195sm3004027wmd.45 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials c195sm3004027wmd.45 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:22:36'),
       (18, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:22:50.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials b17sm3036185wrx.15 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials b17sm3036185wrx.15 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials b17sm3036185wrx.15 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:22:56');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES (19, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":10:{s:27:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000email\\\";N;s:30:\\\"\\u0000App\\\\Jobs\\\\SendMailJob\\u0000mailType\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:23:14.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials t81sm2808936wmg.6 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials t81sm2808936wmg.6 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials t81sm2808936wmg.6 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:23:19'),
       (20, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":8:{s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:31:27.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials z11sm3106923wrt.82 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials z11sm3106923wrt.82 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials z11sm3106923wrt.82 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:31:34'),
       (21, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":8:{s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:33:57.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials p26sm2487118wmc.24 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials p26sm2487118wmc.24 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials p26sm2487118wmc.24 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:34:06'),
       (22, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":8:{s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-11-21 09:07:30.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials w19sm2580271wmc.22 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials w19sm2580271wmc.22 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials w19sm2580271wmc.22 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:36:58');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES (23, 'database', 'default',
        '{\"displayName\":\"App\\\\Jobs\\\\SendMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SendMailJob\\\":8:{s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-01-23 13:38:24.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}',
        'Swift_TransportException: Failed to authenticate on SMTP server with username \"barakabryson@gmail.com\" using 3 possible authenticators. Authenticator LOGIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials q3sm3110400wrn.33 - gsmtp\r\n\". Authenticator PLAIN returned Expected response code 235 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials q3sm3110400wrn.33 - gsmtp\r\n\". Authenticator XOAUTH2 returned Expected response code 250 but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. Learn more at\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials q3sm3110400wrn.33 - gsmtp\r\n\". in C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\Esmtp\\AuthHandler.php:191\nStack trace:\n#0 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\EsmtpTransport.php(371): Swift_Transport_Esmtp_AuthHandler->afterEhlo(Object(Swift_SmtpTransport))\n#1 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Transport\\AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()\n#2 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()\n#3 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(486): Swift_Mailer->send(Object(Swift_Message), Array)\n#4 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(261): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#5 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(159): Illuminate\\Mail\\Mailer->send(\'mail.send_mail\', Array, Object(Closure))\n#6 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(160): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(277): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\Mailer))\n#9 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(231): Illuminate\\Mail\\Mailer->sendMailable(Object(App\\Mail\\SendMail))\n#10 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\PendingMail.php(122): Illuminate\\Mail\\Mailer->send(Object(App\\Mail\\SendMail))\n#11 C:\\xampp\\htdocs\\disbursement-voda\\app\\Jobs\\SendMailJob.php(41): Illuminate\\Mail\\PendingMail->send(Object(App\\Mail\\SendMail))\n#12 [internal function]: App\\Jobs\\SendMailJob->handle()\n#13 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#14 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#19 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMailJob))\n#20 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#21 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMailJob), false)\n#23 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMailJob))\n#24 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMailJob))\n#25 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMailJob))\n#27 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(88): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(354): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(300): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(134): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)\n#35 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(36): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(590): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(201): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(188): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(1011): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(272): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\disbursement-voda\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(131): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\disbursement-voda\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}',
        '2020-01-23 10:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs`
(
    `id`           bigint(20) UNSIGNED NOT NULL,
    `queue`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload`      longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `attempts`     tinyint(3) UNSIGNED NOT NULL,
    `reserved_at`  int(10) UNSIGNED DEFAULT NULL,
    `available_at` int(10) UNSIGNED NOT NULL,
    `created_at`   int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations`
(
    `id`        int(10) UNSIGNED NOT NULL,
    `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch`     int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES (1, '2014_10_12_100000_create_password_resets_table', 1),
       (2, '2019_08_19_000000_create_failed_jobs_table', 1),
       (3, '2019_11_21_081901_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations`
(
    `id`               int(11) NOT NULL,
    `short_code`       int(11) NOT NULL,
    `name`             varchar(100) NOT NULL,
    `district_id`      int(11) NOT NULL,
    `number_approval`  int(1) DEFAULT NULL,
    `minimum_approver` int(1) DEFAULT NULL,
    `created_by`       int(11) NOT NULL,
    `created_at`       timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `zone`             varchar(100)          DEFAULT NULL,
    `email`            varchar(100)          DEFAULT NULL,
    `phone_number`     varchar(15)           DEFAULT NULL,
    `region_id`        int(11) DEFAULT NULL,
    `status`           int(1) DEFAULT '0' COMMENT '1 active, 0 inactivate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `short_code`, `name`, `district_id`, `number_approval`, `minimum_approver`,
                             `created_by`, `created_at`, `updated_at`, `zone`, `email`, `phone_number`, `region_id`,
                             `status`)
VALUES (1, 200, 'Mwananyamala shop', 147, 2, NULL, 1, '2020-01-23 10:47:24', '2020-01-15 09:27:49', NULL,
        'mwana@gmail.com', '0765334521', 2, 0),
       (2, 300, 'Cocalcola', 3, 4, NULL, 1, '2020-01-23 06:58:36', '2020-01-14 18:06:37', NULL, 'cocal@gmail.com',
        '078334422', 1, 0),
       (3, 12300, 'Cocalcola6', 147, 2, NULL, 1, '2020-01-28 10:51:34', '2020-01-14 18:09:11', NULL, 'cocal@gmail.com',
        '078334422', 2, 0),
       (4, 900900, 'pepsi', 147, 1, NULL, 1, '2020-01-23 06:58:41', '2020-01-16 07:38:23', NULL, 'mwanza@pepsi.co.tz',
        '0765334521', 2, 0),
       (5, 211700, 'Track company', 1, 3, NULL, 1, '2020-01-22 07:37:47', '2020-01-17 04:13:07', NULL,
        'track@gmail.com', '0712897634', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `organization_approval`
--

CREATE TABLE `organization_approval`
(
    `organization_id` int(11) NOT NULL,
    `user_id`         int(11) NOT NULL,
    `approval_number` int(11) DEFAULT NULL,
    `created_by`      int(11) DEFAULT NULL,
    `created_at`      timestamp NULL DEFAULT NULL,
    `updated_at`      timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organization_approval`
--

INSERT INTO `organization_approval` (`organization_id`, `user_id`, `approval_number`, `created_by`, `created_at`,
                                     `updated_at`)
VALUES (5, 11, 1, 4, NULL, NULL),
       (2, 12, 1, 2, NULL, NULL),
       (1, 14, 1, 4, NULL, NULL),
       (1, 15, 2, 4, NULL, NULL),
       (1, 16, 3, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets`
(
    `email`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `token`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

CREATE TABLE `payment_status`
(
    `id`         int(1) NOT NULL,
    `name`       varchar(35) NOT NULL,
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`id`, `name`, `created_at`, `updated_at`)
VALUES (0, 'Not Paid', '2019-11-20 11:34:48', NULL),
       (1, 'Paid', '2019-11-20 11:34:35', NULL),
       (2, 'Error', '2019-11-20 11:34:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions`
(
    `id`         int(11) NOT NULL,
    `name`       varchar(35) NOT NULL,
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL,
    `type`       int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`, `type`)
VALUES (100, 'Create User', '2019-12-10 07:26:50', NULL, NULL),
       (200, 'Create Roles', '2019-12-10 07:27:05', NULL, NULL),
       (300, 'Organization Approval', '2019-12-10 07:52:15', NULL, 2),
       (400, 'Initiator', '2019-12-10 07:31:55', NULL, 2),
       (500, 'Create Organization Users', '2019-12-11 11:20:30', NULL, 2),
       (600, 'View All Report', '2019-12-10 07:52:40', NULL, NULL),
       (700, 'View Organization Report', '2019-12-11 11:20:32', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions`
(
    `id`         int(11) DEFAULT NULL,
    `name`       varchar(35) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `created_at`, `updated_at`)
VALUES (1, 'Dar es Salaam', NULL, NULL),
       (2, 'Mwanza', NULL, NULL),
       (3, 'Dodoma', NULL, NULL),
       (4, 'Morogoro', NULL, NULL),
       (6, 'Arusha', NULL, NULL),
       (7, 'Kagera', NULL, NULL),
       (8, 'Pwani', NULL, NULL),
       (9, 'KIlimanjaro', NULL, NULL),
       (10, 'Tanga', NULL, NULL),
       (11, 'Lindi', NULL, NULL),
       (12, 'Mtwara', NULL, NULL),
       (13, 'Ruvuma', NULL, NULL),
       (14, 'Iringa', NULL, NULL),
       (15, 'Mbeya', NULL, NULL),
       (16, 'Singida', NULL, NULL),
       (17, 'Tabora', NULL, NULL),
       (18, 'Rukwa', NULL, NULL),
       (19, 'Kigoma', NULL, NULL),
       (20, 'Shinyanga', NULL, NULL),
       (21, 'Mara', NULL, NULL),
       (22, 'Manyara', NULL, NULL),
       (23, 'Njombe', NULL, NULL),
       (24, 'Katavi', NULL, NULL),
       (25, 'Simiyu', NULL, NULL),
       (26, 'Geita', NULL, NULL),
       (28, 'Pemba Kaskazini', NULL, NULL),
       (29, 'Pemba Kusini', NULL, NULL),
       (30, 'Moshi', NULL, NULL),
       (31, 'Unguja', NULL, NULL),
       (32, 'Pemba', NULL, NULL),
       (1, NULL, '2019-08-25 09:14:38', '2019-08-25 09:14:38'),
       (1, NULL, '2019-08-25 19:03:45', '2019-08-25 19:03:45'),
       (1, NULL, '2019-10-04 05:18:49', '2019-10-04 05:18:49'),
       (1, NULL, '2019-10-08 06:55:11', '2019-10-08 06:55:11'),
       (1, NULL, '2019-10-08 09:15:27', '2019-10-08 09:15:27'),
       (1, NULL, '2019-10-09 15:40:56', '2019-10-09 15:40:56'),
       (1, NULL, '2019-10-10 08:17:46', '2019-10-10 08:17:46'),
       (1, NULL, '2019-10-10 09:54:05', '2019-10-10 09:54:05');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles`
(
    `id`           int(11) NOT NULL,
    `name`         varchar(35) NOT NULL,
    `role_type_id` tinyint(2) DEFAULT NULL COMMENT '1 for vodacom, 2 for organization',
    `status`       tinyint(1) NOT NULL DEFAULT '1',
    `created_at`   timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`   timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `role_type_id`, `status`, `created_at`, `updated_at`)
VALUES (4, 'Administrators', NULL, 1, '2019-12-11 11:10:58', '2019-12-11 04:08:04'),
       (5, 'Vodacom Manager', NULL, 1, '2019-12-11 04:18:51', '2019-12-11 04:18:51'),
       (7, 'Finance', NULL, 1, '2020-01-16 07:43:14', '2020-01-16 07:43:14'),
       (8, 'Finance report', NULL, 1, '2020-01-17 04:20:04', '2020-01-17 04:20:04'),
       (9, 'Initiator', 2, 1, '2020-01-17 07:17:16', '2020-01-17 07:17:16'),
       (10, 'Organization Admin', 2, 1, '2020-01-17 07:18:17', '2020-01-17 07:18:17'),
       (11, 'Auditors', 2, 1, '2020-01-23 08:12:07', '2020-01-23 05:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions`
(
    `id`            int(11) NOT NULL,
    `role_id`       int(11) DEFAULT NULL,
    `permission_id` int(11) DEFAULT NULL,
    `created_at`    timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`    timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`)
VALUES (44, 4, 100, '2019-12-11 04:08:55', '2019-12-11 04:08:55'),
       (45, 4, 200, '2019-12-11 04:08:55', '2019-12-11 04:08:55'),
       (46, 4, 300, '2019-12-11 04:08:56', '2019-12-11 04:08:56'),
       (47, 4, 400, '2019-12-11 04:08:56', '2019-12-11 04:08:56'),
       (48, 4, 500, '2019-12-11 04:08:56', '2019-12-11 04:08:56'),
       (49, 4, 600, '2019-12-11 04:08:56', '2019-12-11 04:08:56'),
       (50, 4, 700, '2019-12-11 04:08:56', '2019-12-11 04:08:56'),
       (51, 5, 600, '2019-12-11 04:18:51', '2019-12-11 04:18:51'),
       (52, 6, 400, '2019-12-11 08:11:26', '2019-12-11 08:11:26'),
       (53, 6, 500, '2019-12-11 08:11:26', '2019-12-11 08:11:26'),
       (54, 6, 700, '2019-12-11 08:11:26', '2019-12-11 08:11:26'),
       (55, 7, 600, '2020-01-16 07:43:14', '2020-01-16 07:43:14'),
       (56, 8, 600, '2020-01-17 04:20:05', '2020-01-17 04:20:05'),
       (57, 9, 400, '2020-01-17 07:17:16', '2020-01-17 07:17:16'),
       (58, 9, 700, '2020-01-17 07:17:17', '2020-01-17 07:17:17'),
       (59, 10, 300, '2020-01-17 07:18:17', '2020-01-17 07:18:17'),
       (60, 10, 400, '2020-01-17 07:18:17', '2020-01-17 07:18:17'),
       (61, 10, 700, '2020-01-17 07:18:17', '2020-01-17 07:18:17'),
       (63, 11, 700, '2020-01-23 05:12:07', '2020-01-23 05:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_type`
--

CREATE TABLE `role_type`
(
    `id`         int(1) NOT NULL,
    `name`       varchar(100) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_type`
--

INSERT INTO `role_type` (`id`, `name`, `created_at`, `updated_at`)
VALUES (1, 'Vodacom role', '2019-11-20 10:41:47', NULL),
       (2, 'Orginzation role type', '2019-11-20 10:42:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tx_customer_name_search`
--

CREATE TABLE `tx_customer_name_search`
(
    `id`               int(11) NOT NULL,
    `entry_id`         int(11) NOT NULL,
    `reference_number` varchar(64)                       NOT NULL,
    `phone_number`     varchar(15)                       NOT NULL,
    `network_name`     varchar(20) CHARACTER SET utf8mb4 NOT NULL,
    `status`           enum('PENDING','SUCCESS','FAILED') NOT NULL,
    `failure_reason`   enum('AUTHENTICATION','NETWORK','TIMEOUT') CHARACTER SET utf8mb4 DEFAULT NULL,
    `request_dump`     varchar(5000)                     NOT NULL,
    `response_dump`    varchar(5000) CHARACTER SET utf8mb4        DEFAULT NULL,
    `created_at`       datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tx_customer_name_search`
--

INSERT INTO `tx_customer_name_search` (`id`, `entry_id`, `reference_number`, `phone_number`, `network_name`, `status`,
                                       `failure_reason`, `request_dump`, `response_dump`, `created_at`, `updated_at`)
VALUES (3, 70, '86MTU4MDA3ODQzMS42OTE2', '255716235698', 'vodacom', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><PIN>3BCXMpsa@2019</PIN><TYPE>QueryCustomerName</TYPE><REFERENCEID>86MTU4MDA3ODQzMS42OTE2</REFERENCEID><MSISDN>255716235698</MSISDN><MSISDN1>255716235698</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>86MTU4MDA3ODQzMS42OTE2</TXNID><REFERENCEID>86MTU4MDA3ODQzMS42OTE2</REFERENCEID><MSISDN>255716235698</MSISDN><FIRSTNAME>Mason</FIRSTNAME><LASTNAME>Klocko</LASTNAME><FULLNAME>Mason Klocko</FULLNAME></COMMAND>\n',
        '2020-01-26 22:40:31', '2020-01-26 22:40:32'),
       (4, 71, 'a6MTU4MDA3ODQzMi4zNDkz', '255716235611', 'tigo', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QueryCustomerName</TYPE><REFERENCEID>a6MTU4MDA3ODQzMi4zNDkz</REFERENCEID><MSISDN>255716235611</MSISDN><NETWORK>tigo</NETWORK><PIN>3BCXMpsa@2019</PIN><MSISDN1>255716235611</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>a6MTU4MDA3ODQzMi4zNDkz</TXNID><REFERENCEID>a6MTU4MDA3ODQzMi4zNDkz</REFERENCEID><MSISDN>255716235611</MSISDN><FIRSTNAME>Yazmin</FIRSTNAME><LASTNAME>D\'Amore</LASTNAME><FULLNAME>Yazmin D\'Amore</FULLNAME></COMMAND>\n',
        '2020-01-26 22:40:32', '2020-01-26 22:40:32'),
       (5, 72, '80MTU4MDA3ODQzMi43NTkx', '25571623569811', 'vodacom', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><PIN>3BCXMpsa@2019</PIN><TYPE>QueryCustomerName</TYPE><REFERENCEID>80MTU4MDA3ODQzMi43NTkx</REFERENCEID><MSISDN>25571623569811</MSISDN><MSISDN1>25571623569811</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>80MTU4MDA3ODQzMi43NTkx</TXNID><REFERENCEID>80MTU4MDA3ODQzMi43NTkx</REFERENCEID><MSISDN>25571623569811</MSISDN><FIRSTNAME>Jonathan</FIRSTNAME><LASTNAME>Terry</LASTNAME><FULLNAME>Jonathan Terry</FULLNAME></COMMAND>\n',
        '2020-01-26 22:40:32', '2020-01-26 22:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `tx_disbursement`
--

CREATE TABLE `tx_disbursement`
(
    `id`               int(11) NOT NULL,
    `entry_id`         int(11) NOT NULL,
    `short_code`       int(11) NOT NULL,
    `reference_number` varchar(64)                       NOT NULL,
    `phone_number`     varchar(15)                       NOT NULL,
    `network_name`     varchar(20) CHARACTER SET utf8mb4 NOT NULL,
    `amount`           decimal(12, 2)                    NOT NULL,
    `mpesa_receipt`    varchar(20)                                DEFAULT NULL,
    `status`           enum('PENDING','SUCCESS','FAILED') NOT NULL,
    `failure_reason`   enum('AUTHENTICATION','NETWORK','TIMEOUT','INVALID_RESPONSE') CHARACTER SET utf8mb4 DEFAULT NULL,
    `request_dump`     varchar(5000)                     NOT NULL,
    `response_dump`    varchar(5000) CHARACTER SET utf8mb4        DEFAULT NULL,
    `callback_dump`    varchar(5000)                              DEFAULT NULL,
    `created_at`       datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tx_disbursement`
--

INSERT INTO `tx_disbursement` (`id`, `entry_id`, `short_code`, `reference_number`, `phone_number`, `network_name`,
                               `amount`, `mpesa_receipt`, `status`, `failure_reason`, `request_dump`, `response_dump`,
                               `callback_dump`, `created_at`, `updated_at`)
VALUES (1, 58, 12300, '66MTU4MDIwODcwMy4xNTQ1', '255716235698', 'Vodacom', '8900.00', NULL, 'FAILED', 'NETWORK',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>66MTU4MDIwODcwMy4xNTQ1</conversationID><service>disbursement</service><recipient>255716235698</recipient><network>Vodacom</network><amount>8900</amount><orgAccount>12300</orgAccount></request>\n',
        NULL, NULL, '2020-01-28 10:51:43', '2020-01-28 10:52:58'),
       (2, 58, 12300, '8aMTU4MDIwODc4MC40NjM=', '255716235698', 'Vodacom', '8900.00', NULL, 'FAILED', 'NETWORK',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>8aMTU4MDIwODc4MC40NjM=</conversationID><service>disbursement</service><recipient>255716235698</recipient><network>Vodacom</network><amount>8900</amount><orgAccount>12300</orgAccount></request>\n',
        NULL, NULL, '2020-01-28 10:53:00', '2020-01-28 10:54:15'),
       (3, 58, 12300, '78MTU4MDIwODkwMy4yOTg5', '255716235698', 'Vodacom', '8900.00', NULL, 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>78MTU4MDIwODkwMy4yOTg5</conversationID><service>disbursement</service><recipient>255716235698</recipient><network>Vodacom</network><amount>8900</amount><orgAccount>12300</orgAccount></request>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>disbursement</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>78MTU4MDIwODkwMy4yOTg5</TXNID><REFERENCEID>c1MTU4MDIwODkwNy43MTE2</REFERENCEID></COMMAND>\n',
        NULL, '2020-01-28 10:55:03', '2020-01-28 10:55:07'),
       (4, 58, 12300, 'd4MTU4MDIwODkwOC45MDEz', '255716235698', 'Vodacom', '8900.00', NULL, 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>d4MTU4MDIwODkwOC45MDEz</conversationID><service>disbursement</service><recipient>255716235698</recipient><network>Vodacom</network><amount>8900</amount><orgAccount>12300</orgAccount></request>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>disbursement</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>d4MTU4MDIwODkwOC45MDEz</TXNID><REFERENCEID>03MTU4MDIwODkxMy4yOTY=</REFERENCEID></COMMAND>\n',
        NULL, '2020-01-28 10:55:08', '2020-01-28 10:55:13'),
       (5, 58, 12300, '02MTU4MDIwOTMyNi41NDc3', '255716235698', 'Vodacom', '8900.00', NULL, 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>02MTU4MDIwOTMyNi41NDc3</conversationID><service>disbursement</service><recipient>255716235698</recipient><network>Vodacom</network><amount>8900</amount><orgAccount>12300</orgAccount></request>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>disbursement</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>02MTU4MDIwOTMyNi41NDc3</TXNID><REFERENCEID>28MTU4MDIwOTMyOS4xMzM5</REFERENCEID></COMMAND>\n',
        NULL, '2020-01-28 11:02:06', '2020-01-28 11:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `tx_network_name_search`
--

CREATE TABLE `tx_network_name_search`
(
    `id`               int(11) NOT NULL,
    `entry_id`         int(11) NOT NULL,
    `reference_number` varchar(64)   NOT NULL,
    `phone_number`     varchar(15)   NOT NULL,
    `network_name`     varchar(20) CHARACTER SET utf8mb4   DEFAULT NULL,
    `status`           enum('PENDING','SUCCESS','FAILED') NOT NULL,
    `failure_reason`   enum('AUTHENTICATION','NETWORK','TIMEOUT') CHARACTER SET utf8mb4 DEFAULT NULL,
    `request_dump`     varchar(5000) NOT NULL,
    `response_dump`    varchar(5000) CHARACTER SET utf8mb4 DEFAULT NULL,
    `created_at`       datetime      NOT NULL              DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime      NOT NULL              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tx_network_name_search`
--

INSERT INTO `tx_network_name_search` (`id`, `entry_id`, `reference_number`, `phone_number`, `network_name`, `status`,
                                      `failure_reason`, `request_dump`, `response_dump`, `created_at`, `updated_at`)
VALUES (37, 70, '79MTU4MDA3ODQzMS4zOTAy', '255716235698', 'vodacom', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberReq</TYPE><REFERENCEID>79MTU4MDA3ODQzMS4zOTAy</REFERENCEID><MSISDN>255716235698</MSISDN><PIN>3BCXMpsa@2019</PIN><MSISDN1>255716235698</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>79MTU4MDA3ODQzMS4zOTAy</TXNID><REFERENCEID>79MTU4MDA3ODQzMS4zOTAy</REFERENCEID><MSISDN>255716235698</MSISDN><NETWORK>vodacom</NETWORK></COMMAND>\n',
        '2020-01-26 22:40:31', '2020-01-26 22:40:31'),
       (38, 71, '70MTU4MDA3ODQzMi4wOTE0', '255716235611', 'tigo', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberReq</TYPE><REFERENCEID>70MTU4MDA3ODQzMi4wOTE0</REFERENCEID><MSISDN>255716235611</MSISDN><PIN>3BCXMpsa@2019</PIN><MSISDN1>255716235611</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>70MTU4MDA3ODQzMi4wOTE0</TXNID><REFERENCEID>70MTU4MDA3ODQzMi4wOTE0</REFERENCEID><MSISDN>255716235611</MSISDN><NETWORK>tigo</NETWORK></COMMAND>\n',
        '2020-01-26 22:40:32', '2020-01-26 22:40:32'),
       (39, 72, '37MTU4MDA3ODQzMi41NDg5', '25571623569811', 'vodacom', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberReq</TYPE><REFERENCEID>37MTU4MDA3ODQzMi41NDg5</REFERENCEID><MSISDN>25571623569811</MSISDN><PIN>3BCXMpsa@2019</PIN><MSISDN1>25571623569811</MSISDN1></COMMAND>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<COMMAND><TYPE>QuerySubscriberResp</TYPE><MESSAGE>Success</MESSAGE><TXNSTATUS>0</TXNSTATUS><TXNID>37MTU4MDA3ODQzMi41NDg5</TXNID><REFERENCEID>37MTU4MDA3ODQzMi41NDg5</REFERENCEID><MSISDN>25571623569811</MSISDN><NETWORK>vodacom</NETWORK></COMMAND>\n',
        '2020-01-26 22:40:32', '2020-01-26 22:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `tx_organization_kyc_result`
--

CREATE TABLE `tx_organization_kyc_result`
(
    `id`                            int(11) NOT NULL,
    `tx_organization_kyc_search_id` int(11) NOT NULL,
    `short_code`                    varchar(64) CHARACTER SET utf8mb4 NOT NULL,
    `organization_name`             varchar(15) CHARACTER SET utf8mb4 NOT NULL,
    `created_at`                    datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                    datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tx_organization_kyc_search`
--

CREATE TABLE `tx_organization_kyc_search`
(
    `id`              int(11) NOT NULL,
    `conversation_id` varchar(128)                      NOT NULL,
    `short_code`      varchar(15) CHARACTER SET utf8mb4 NOT NULL,
    `status`          enum('PENDING','SUCCESS','FAILED') NOT NULL,
    `failure_reason`  enum('AUTHENTICATION','NETWORK','TIMEOUT') CHARACTER SET utf8mb4 DEFAULT NULL,
    `request_dump`    varchar(5000)                     NOT NULL,
    `response_dump`   varchar(5000) CHARACTER SET utf8mb4        DEFAULT NULL,
    `created_at`      datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      datetime                          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tx_organization_kyc_search`
--

INSERT INTO `tx_organization_kyc_search` (`id`, `conversation_id`, `short_code`, `status`, `failure_reason`,
                                          `request_dump`, `response_dump`, `created_at`, `updated_at`)
VALUES (6, 'f3MTU4MDIwODAzNC45MDU3', '123000', 'SUCCESS', NULL,
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Request><username>444555</username><password>3BCXMpsa@2019</password><conversationID>2dMTU4MDIwODAzNC45MDc4</conversationID><service>QueryOrgKYC</service><orgCode>123000</orgCode></Request>\n',
        '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<response><responseCode>1</responseCode><responseDesc>Request received successfully</responseDesc></response>\n',
        '2020-01-28 10:40:35', '2020-01-28 10:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users`
(
    `id`              int(11) NOT NULL,
    `first_name`      varchar(35)  NOT NULL,
    `last_name`       varchar(35)  NOT NULL,
    `email`           varchar(60)  NOT NULL,
    `phone_number`    varchar(12)  NOT NULL,
    `district_id`     int(11) DEFAULT NULL,
    `location`        varchar(35)           DEFAULT NULL,
    `is_first_login`  tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 means not yet logged in (first) 1 not first',
    `password`        varchar(255) NOT NULL,
    `user_type`       int(11) NOT NULL COMMENT '1 for internal, 2 for organization',
    `loggin_attempts` int(1) DEFAULT NULL,
    `is_active`       int(1) DEFAULT '1',
    `organization_id` int(11) DEFAULT NULL,
    `created_by`      int(11) DEFAULT NULL,
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`      timestamp NULL DEFAULT NULL,
    `token`           varchar(4)            DEFAULT NULL,
    `token_verified`  int(1) DEFAULT '0' COMMENT 'o means not, 1 means verified'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `district_id`, `location`,
                     `is_first_login`, `password`, `user_type`, `loggin_attempts`, `is_active`, `organization_id`,
                     `created_by`, `created_at`, `updated_at`, `token`, `token_verified`)
VALUES (2, 'baraka Nagabon', 'machumu', 'barakabryson@gmail.com', '0756443322', NULL, NULL, 0,
        '$2y$10$5ZV2Kwkbo4r6m/O3YptMSek7yafJar93D8x0NEa5q0lHL8GvSiXJK', 1, NULL, 1, 1, 1, '2020-01-26 12:20:20',
        '2019-12-12 09:15:50', '6431', 1),
       (3, 'John', 'Asat', 'john@gmail.com', '0754997498', NULL, NULL, 1,
        '$2y$10$RUzb1qJ82FYGRYJOtIYMQ.5H4mirZOsosKO7Yb0O4k7gHTrjR/kRe', 1, NULL, 1, NULL, 1, '2020-01-16 10:44:57',
        '2020-01-16 07:44:57', NULL, NULL),
       (4, 'denyo', 'denyo', 'denyo@gmail.com', '0754997494', NULL, NULL, 0,
        '$2y$10$MKlWEdaEIrGMFLnpoMqmX.pyhZWuW0rOuBDfQQA9OjvecNfG7PbQm', 2, NULL, 1, 1, 1, '2020-01-23 10:44:46',
        '2020-01-16 07:46:44', '1344', 1),
       (5, 'ebenezer', 'ebenezer', 'ebenezer@yahoo.com', '0637129037', NULL, NULL, 1,
        '$2y$10$TpWNbhH8h14rqVVoYfEozuiUIEeFcmpL/wDcX5QupIlK8Yah7OkAq', 2, NULL, 1, NULL, 1, '2020-01-17 04:33:34',
        '2020-01-17 04:33:34', NULL, NULL),
       (8, 'gao', 'gao', 'gao@gmail.com', '0765998877', NULL, NULL, 0,
        '$2y$10$eRqybDAYJ7vMiCLZwxj.ger4yw6GFRmHyVquUQi1jGS/lxxfZdlOO', 2, NULL, 1, 5, 1, '2020-01-17 12:23:48',
        '2020-01-17 07:25:53', NULL, NULL),
       (10, 'gaop', 'gaop', 'gaop@gmail.com', '0765222222', NULL, NULL, 1,
        '$2y$10$qxUpOSXEcW3/ckZBmEtoEes0vOQ3MXtS./CF1NysiKJfjG/jygXUC', 2, NULL, 1, 5, 1, '2020-01-21 10:41:30',
        '2020-01-21 10:41:30', NULL, NULL),
       (11, 'hellp', 'hellp', 'hellp@gmail.com', '0765334500', NULL, NULL, 0,
        '$2y$10$zb6icCZTm8mvnZ3NLGvtkudN0tp5oN0dgiz7C1G8PqkCk7OhhHAF.', 2, NULL, 1, 5, 1, '2020-01-21 13:52:26',
        '2020-01-21 10:44:34', NULL, NULL),
       (12, 'daud', 'daud', 'daud@gmail.com', '0718273738', NULL, NULL, 1,
        '$2y$10$AEM6ouqiv9hbnLhp0xFGLujkwxdfsMZelCIw2zEv0lQRkc/xB4bCK', 2, NULL, 1, 2, 1, '2020-01-22 04:36:57',
        '2020-01-22 04:36:57', NULL, NULL),
       (14, 'hamis', 'hamis', 'hamis@gmail', '0754321289', NULL, NULL, 1,
        '$2y$10$HpY1fWCLe3qOEcD9LQkpAuQtsBT.FI6HC0dyesY1W9wHVgKLHpG9S', 2, NULL, 1, 1, 1, '2020-01-22 10:57:14',
        '2020-01-22 10:57:14', NULL, NULL),
       (15, 'hamis', 'hamis', 'hamis@gmail', '0765334521', NULL, NULL, 1,
        '$2y$10$Z4cq9P8Taiv/nPeUDXI6hu80CK2bodRxcqbjn65U1YsjnezM4aM2y', 2, NULL, 1, 1, 1, '2020-01-22 11:26:40',
        '2020-01-22 11:26:40', NULL, NULL),
       (16, 'hawa', 'hawa', 'hawa@gmail.com', '0874422119', NULL, NULL, 1,
        '$2y$10$j/xL.3aS6cpZxnj1C8AB0OnzFSQHYvham0OOlJtaQ6LpXRxMtl/ZK', 2, NULL, 1, 1, 4, '2020-01-22 11:30:04',
        '2020-01-22 11:30:04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles`
(
    `id`         int(11) NOT NULL,
    `user_id`    int(11) DEFAULT NULL,
    `role_id`    int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`)
VALUES (9, 2, 4, '2019-12-13 04:30:12', '2019-12-13 04:30:12'),
       (11, 3, 6, '2020-01-16 07:44:58', '2020-01-16 07:44:58'),
       (12, 4, 6, '2020-01-16 07:46:44', '2020-01-16 07:46:44'),
       (13, 5, 6, '2020-01-17 04:33:34', '2020-01-17 04:33:34'),
       (15, 7, 9, '2020-01-17 07:24:39', '2020-01-17 07:24:39'),
       (16, 7, 10, '2020-01-17 07:24:39', '2020-01-17 07:24:39'),
       (17, 8, 9, '2020-01-17 07:25:53', '2020-01-17 07:25:53'),
       (18, 8, 10, '2020-01-17 07:25:53', '2020-01-17 07:25:53'),
       (20, 6, 9, '2020-01-17 07:41:08', '2020-01-17 07:41:08'),
       (21, 9, 9, '2020-01-21 10:29:07', '2020-01-21 10:29:07'),
       (22, 10, 9, '2020-01-21 10:41:30', '2020-01-21 10:41:30'),
       (23, 11, 9, '2020-01-21 10:44:34', '2020-01-21 10:44:34'),
       (24, 12, 9, '2020-01-22 04:36:59', '2020-01-22 04:36:59'),
       (25, 13, 9, '2020-01-22 10:56:05', '2020-01-22 10:56:05'),
       (26, 14, 9, '2020-01-22 10:57:14', '2020-01-22 10:57:14'),
       (27, 15, 9, '2020-01-22 11:26:40', '2020-01-22 11:26:40'),
       (28, 16, 9, '2020-01-22 11:30:04', '2020-01-22 11:30:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_activities`
--
ALTER TABLE `audit_activities`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_logins`
--
ALTER TABLE `audit_logins`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch_payments`
--
ALTER TABLE `batch_payments`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch_staus`
--
ALTER TABLE `batch_staus`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_persons`
--
ALTER TABLE `contact_persons`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursements`
--
ALTER TABLE `disbursements`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursement_payments`
--
ALTER TABLE `disbursement_payments`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
    ADD PRIMARY KEY (`id`),
    ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
    ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_status`
--
ALTER TABLE `payment_status`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_type`
--
ALTER TABLE `role_type`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tx_customer_name_search`
--
ALTER TABLE `tx_customer_name_search`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tx_disbursement`
--
ALTER TABLE `tx_disbursement`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tx_network_name_search`
--
ALTER TABLE `tx_network_name_search`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tx_organization_kyc_result`
--
ALTER TABLE `tx_organization_kyc_result`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tx_organization_kyc_search`
--
ALTER TABLE `tx_organization_kyc_search`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_activities`
--
ALTER TABLE `audit_activities`
    MODIFY `id` int (20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `batch_payments`
--
ALTER TABLE `batch_payments`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact_persons`
--
ALTER TABLE `contact_persons`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `disbursements`
--
ALTER TABLE `disbursements`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `disbursement_payments`
--
ALTER TABLE `disbursement_payments`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
    MODIFY `id` int (10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tx_customer_name_search`
--
ALTER TABLE `tx_customer_name_search`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tx_disbursement`
--
ALTER TABLE `tx_disbursement`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tx_network_name_search`
--
ALTER TABLE `tx_network_name_search`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tx_organization_kyc_result`
--
ALTER TABLE `tx_organization_kyc_result`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tx_organization_kyc_search`
--
ALTER TABLE `tx_organization_kyc_search`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

