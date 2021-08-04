-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 07, 2021 at 01:09 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patools_webby`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advanced_unit_scans`
--

CREATE TABLE `advanced_unit_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` int(11) NOT NULL,
  `ship_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alliances`
--

CREATE TABLE `alliances` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hidden_resources` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `avg_size` bigint(20) NOT NULL DEFAULT 0,
  `avg_value` bigint(20) NOT NULL DEFAULT 0,
  `avg_score` bigint(20) NOT NULL DEFAULT 0,
  `members` int(11) NOT NULL DEFAULT 0,
  `total_score` bigint(20) NOT NULL DEFAULT 0,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_size` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_avg_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_avg_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_avg_size` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_avg_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_avg_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_avg_size` double(8,2) NOT NULL DEFAULT 0.00,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relation` enum('neutral','friendly','hostile') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_locked` int(11) NOT NULL DEFAULT 0,
  `day_members` int(11) NOT NULL DEFAULT 0,
  `growth_members` int(11) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `size_diff` bigint(20) NOT NULL DEFAULT 0,
  `xp_diff` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alliance_history`
--

CREATE TABLE `alliance_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `rank` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  `counted_score` bigint(20) NOT NULL,
  `points` bigint(20) NOT NULL,
  `total_score` bigint(20) NOT NULL,
  `total_value` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `alliance_id` int(11) NOT NULL DEFAULT 0,
  `avg_size` bigint(20) NOT NULL,
  `avg_value` bigint(20) NOT NULL,
  `avg_score` bigint(20) NOT NULL,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `change_members` int(11) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attacks`
--

CREATE TABLE `attacks` (
  `id` int(10) UNSIGNED NOT NULL,
  `land_tick` int(11) NOT NULL,
  `waves` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `scheduled_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_opened` tinyint(1) NOT NULL DEFAULT 1,
  `attack_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attack_bookings`
--

CREATE TABLE `attack_bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `attack_id` int(11) NOT NULL,
  `attack_target_id` int(11) NOT NULL,
  `land_tick` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `battle_calc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attack_booking_users`
--

CREATE TABLE `attack_booking_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attack_targets`
--

CREATE TABLE `attack_targets` (
  `id` int(10) UNSIGNED NOT NULL,
  `attack_id` int(11) NOT NULL,
  `planet_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `battlegroups`
--

CREATE TABLE `battlegroups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator_id` int(11) NOT NULL,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_xp` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `battlegroup_users`
--

CREATE TABLE `battlegroup_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `battlegroup_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_pending` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `botan_shortener`
--

CREATE TABLE `botan_shortener` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this entry',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original URL',
  `short_url` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Shortened URL',
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `callback_query`
--

CREATE TABLE `callback_query` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Identifier of the message sent via the bot in inline mode, that originated the query',
  `data` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Data associated with the callback button',
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` bigint(20) NOT NULL COMMENT 'Unique user or chat identifier',
  `type` enum('private','group','supergroup','channel') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Chat type, either private, group, supergroup or channel',
  `title` char(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Chat (group) title, is null if chat type is private',
  `username` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Username, for private chats, supergroups and channels if available',
  `all_members_are_administrators` tinyint(1) DEFAULT 0 COMMENT 'True if a all members of this group are admins',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `old_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, this is filled when a group is converted to a supergroup',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chosen_inline_result`
--

CREATE TABLE `chosen_inline_result` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this entry',
  `result_id` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Identifier for this result',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `location` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Location object, user''s location',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `query` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The query that was used to obtain the result',
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this entry',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique user or chat identifier',
  `status` enum('active','cancelled','stopped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Conversation state',
  `command` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Default command to execute',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Data stored from command',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `development_scans`
--

CREATE TABLE `development_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` int(11) NOT NULL,
  `light_factory` int(11) NOT NULL,
  `medium_factory` int(11) NOT NULL,
  `heavy_factory` int(11) NOT NULL,
  `wave_amplifier` int(11) NOT NULL,
  `wave_distorter` int(11) NOT NULL,
  `metal_refinery` int(11) NOT NULL,
  `crystal_refinery` int(11) NOT NULL,
  `eonium_refinery` int(11) NOT NULL,
  `research_lab` int(11) NOT NULL,
  `finance_centre` int(11) NOT NULL,
  `security_centre` int(11) NOT NULL,
  `military_centre` int(11) NOT NULL,
  `structure_defence` int(11) NOT NULL,
  `travel` int(11) NOT NULL,
  `infrastructure` int(11) NOT NULL,
  `hulls` int(11) NOT NULL,
  `waves` int(11) NOT NULL,
  `core` int(11) NOT NULL,
  `covert_op` int(11) NOT NULL,
  `mining` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edited_message`
--

CREATE TABLE `edited_message` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this entry',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `edit_date` datetime DEFAULT NULL COMMENT 'Date the message was edited in timestamp format',
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8',
  `entities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `caption` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For message with caption, the actual UTF-8 text of the caption'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fleet_movements`
--

CREATE TABLE `fleet_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `launch_tick` int(11) DEFAULT NULL,
  `fleet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_from_id` int(11) NOT NULL,
  `planet_to_id` int(11) NOT NULL,
  `mission_type` enum('Attack','Defend') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `land_tick` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `eta` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ship_count` bigint(20) DEFAULT NULL,
  `source` enum('incoming','launch','jgp','parser','notification') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galaxies`
--

CREATE TABLE `galaxies` (
  `id` int(10) UNSIGNED NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hidden_resources` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_xp` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_rank_xp` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `ratio` double(8,2) NOT NULL DEFAULT 0.00,
  `planet_count` int(11) NOT NULL DEFAULT 0,
  `day_planet_count` int(11) NOT NULL DEFAULT 0,
  `growth_planet_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_history`
--

CREATE TABLE `galaxy_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `galaxy_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `galaxy_id` int(11) NOT NULL DEFAULT 0,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `planet_count` int(11) NOT NULL DEFAULT 0,
  `change_planet_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inline_query`
--

CREATE TABLE `inline_query` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `location` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Location of the user',
  `query` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Text of the query',
  `offset` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Offset of the result',
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `intel_change`
--

CREATE TABLE `intel_change` (
  `id` int(10) UNSIGNED NOT NULL,
  `planet_id` int(11) NOT NULL,
  `alliance_from_id` int(11) DEFAULT NULL,
  `alliance_to_id` int(11) DEFAULT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jgp_scans`
--

CREATE TABLE `jgp_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique chat identifier',
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `date` datetime DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, sender of the original message',
  `forward_from_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, chat the original message belongs to',
  `forward_from_message_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier of the original message in the channel',
  `forward_date` datetime DEFAULT NULL COMMENT 'date the original message was sent in timestamp format',
  `reply_to_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `reply_to_message` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Message that this message is reply to',
  `media_group_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The unique identifier of a media message group this message belongs to',
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4',
  `entities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `audio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Document object. Message is a general file, information about the file',
  `photo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Video object. Message is a video, information about the video',
  `voice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `video_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'VoiceNote Object. Message is a Video Note, information about the Video Note',
  `contact` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Location object. Message is a shared location, information about the location',
  `venue` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Venue object. Message is a Venue, information about the Venue',
  `caption` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For message with caption, the actual UTF-8 text of the caption',
  `new_chat_members` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)',
  `left_chat_member` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)',
  `new_chat_title` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A chat title was changed to this value',
  `new_chat_photo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Array of PhotoSize objects. A chat photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the chat photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_to_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier',
  `migrate_from_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier',
  `pinned_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Message object. Specified message was pinned',
  `connected_website` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The domain name of the website on which the user has logged in.',
  `forward_signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `forward_sender_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `edit_date` bigint(20) NOT NULL,
  `author_signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption_entities` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `successful_payment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `game` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `poll` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_markup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_scans`
--

CREATE TABLE `news_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

CREATE TABLE `planets` (
  `id` int(10) UNSIGNED NOT NULL,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alliance_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `galaxy_id` int(11) NOT NULL DEFAULT 0,
  `latest_p` int(11) DEFAULT NULL,
  `latest_d` int(11) DEFAULT NULL,
  `latest_u` int(11) DEFAULT NULL,
  `latest_au` int(11) DEFAULT NULL,
  `amps` int(11) NOT NULL DEFAULT 0,
  `dists` int(11) NOT NULL DEFAULT 0,
  `waves` int(11) NOT NULL DEFAULT 0,
  `min_alert` int(11) NOT NULL DEFAULT 0,
  `max_alert` int(11) NOT NULL DEFAULT 0,
  `total_cons` int(11) NOT NULL DEFAULT 0,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  `planet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruler_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `covop_hit` tinyint(1) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `stock_resources` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `government` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_xp` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` int(11) NOT NULL DEFAULT 0,
  `growth_score` int(11) NOT NULL DEFAULT 0,
  `growth_size` int(11) NOT NULL DEFAULT 0,
  `growth_xp` int(11) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_rank_xp` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `round_roids` bigint(20) NOT NULL DEFAULT 0,
  `tick_roids` bigint(20) NOT NULL DEFAULT 0,
  `lost_roids` bigint(20) NOT NULL DEFAULT 0,
  `ticks_roided` int(11) NOT NULL DEFAULT 0,
  `ticks_roiding` int(11) NOT NULL DEFAULT 0,
  `rank_round_roids` int(11) NOT NULL DEFAULT 0,
  `rank_lost_roids` int(11) NOT NULL DEFAULT 0,
  `day_rank_round_roids` int(11) NOT NULL DEFAULT 0,
  `day_rank_lost_roids` int(11) NOT NULL DEFAULT 0,
  `latest_j` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_covopped` int(11) DEFAULT NULL,
  `latest_n` int(11) DEFAULT NULL,
  `age` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planet_history`
--

CREATE TABLE `planet_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  `planet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruler_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planet_movements`
--

CREATE TABLE `planet_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planet_id` int(11) NOT NULL,
  `from_x` int(11) DEFAULT NULL,
  `from_y` int(11) DEFAULT NULL,
  `from_z` int(11) DEFAULT NULL,
  `to_x` int(11) NOT NULL,
  `to_y` int(11) NOT NULL,
  `to_z` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planet_scans`
--

CREATE TABLE `planet_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_metal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_crystal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_eonium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_metal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_crystal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_eonium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_light` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_medium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_heavy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prod_res` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agents` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guards` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_limiter`
--

CREATE TABLE `request_limiter` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for this entry',
  `chat_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique chat identifier',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `method` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Request method',
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scans`
--

CREATE TABLE `scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pa_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_id` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` time NOT NULL DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scan_queue`
--

CREATE TABLE `scan_queue` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scan_requests`
--

CREATE TABLE `scan_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `scan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ships`
--

CREATE TABLE `ships` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `init` int(11) NOT NULL,
  `guns` int(11) NOT NULL,
  `armor` int(11) NOT NULL,
  `damage` int(11) DEFAULT NULL,
  `empres` int(11) NOT NULL,
  `metal` int(11) NOT NULL,
  `crystal` int(11) NOT NULL,
  `eonium` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `eta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_update`
--

CREATE TABLE `telegram_update` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Update''s unique identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_query_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Unique inline query identifier',
  `chosen_inline_result_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Local chosen inline result identifier',
  `callback_query_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Unique callback query identifier',
  `edited_message_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Local edited message identifier',
  `channel_post_id` bigint(20) DEFAULT NULL,
  `edited_channel_post_id` bigint(20) DEFAULT NULL,
  `shipping_query_id` bigint(20) DEFAULT NULL,
  `pre_checkout_query_id` bigint(20) DEFAULT NULL,
  `poll_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticks`
--

CREATE TABLE `ticks` (
  `id` int(10) UNSIGNED NOT NULL,
  `tick` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `planet_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `galaxy_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `alliance_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_scans`
--

CREATE TABLE `unit_scans` (
  `id` int(10) UNSIGNED NOT NULL,
  `scan_id` int(11) NOT NULL,
  `ship_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL COMMENT 'Unique user identifier',
  `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True if this user is a bot',
  `first_name` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'User''s first name',
  `last_name` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s last name',
  `username` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s username',
  `language_code` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s system language',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distorters` int(11) DEFAULT NULL,
  `military_centres` int(11) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `tg_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stealth` int(11) NOT NULL DEFAULT 0,
  `allow_calls` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_chat`
--

CREATE TABLE `user_chat` (
  `user_id` bigint(20) NOT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique user or chat identifier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_user_id_index` (`user_id`),
  ADD KEY `activity_created_at_index` (`created_at`);

--
-- Indexes for table `advanced_unit_scans`
--
ALTER TABLE `advanced_unit_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliances`
--
ALTER TABLE `alliances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliance_history`
--
ALTER TABLE `alliance_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attacks`
--
ALTER TABLE `attacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attacks_attack_id_index` (`attack_id`);

--
-- Indexes for table `attack_bookings`
--
ALTER TABLE `attack_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attack_bookings_attack_id_index` (`attack_id`),
  ADD KEY `attack_bookings_attack_target_id_index` (`attack_target_id`),
  ADD KEY `attack_bookings_user_id_index` (`user_id`);

--
-- Indexes for table `attack_booking_users`
--
ALTER TABLE `attack_booking_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attack_booking_users_booking_id_index` (`booking_id`),
  ADD KEY `attack_booking_users_user_id_index` (`user_id`);

--
-- Indexes for table `attack_targets`
--
ALTER TABLE `attack_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attack_targets_attack_id_index` (`attack_id`),
  ADD KEY `attack_targets_planet_id_index` (`planet_id`);

--
-- Indexes for table `battlegroups`
--
ALTER TABLE `battlegroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `battlegroup_users`
--
ALTER TABLE `battlegroup_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `botan_shortener`
--
ALTER TABLE `botan_shortener`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `callback_query`
--
ALTER TABLE `callback_query`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id_2` (`chat_id`,`message_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `old_id` (`old_id`);

--
-- Indexes for table `chosen_inline_result`
--
ALTER TABLE `chosen_inline_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `development_scans`
--
ALTER TABLE `development_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_message`
--
ALTER TABLE `edited_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id_2` (`chat_id`,`message_id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fleet_movements`
--
ALTER TABLE `fleet_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galaxies`
--
ALTER TABLE `galaxies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galaxy_history`
--
ALTER TABLE `galaxy_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inline_query`
--
ALTER TABLE `inline_query`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `intel_change`
--
ALTER TABLE `intel_change`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jgp_scans`
--
ALTER TABLE `jgp_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`chat_id`,`id`),
  ADD KEY `reply_to_chat_2` (`reply_to_chat`,`reply_to_message`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `forward_from` (`forward_from`),
  ADD KEY `forward_from_chat` (`forward_from_chat`),
  ADD KEY `reply_to_chat` (`reply_to_chat`),
  ADD KEY `reply_to_message` (`reply_to_message`),
  ADD KEY `left_chat_member` (`left_chat_member`),
  ADD KEY `migrate_to_chat_id` (`migrate_to_chat_id`),
  ADD KEY `migrate_from_chat_id` (`migrate_from_chat_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_scans`
--
ALTER TABLE `news_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `planets`
--
ALTER TABLE `planets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `planets_alliance_id_index` (`alliance_id`),
  ADD KEY `planets_galaxy_id_index` (`galaxy_id`);

--
-- Indexes for table `planet_history`
--
ALTER TABLE `planet_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planet_movements`
--
ALTER TABLE `planet_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planet_scans`
--
ALTER TABLE `planet_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_limiter`
--
ALTER TABLE `request_limiter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scans`
--
ALTER TABLE `scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scan_queue`
--
ALTER TABLE `scan_queue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scan_queue_scan_id_unique` (`scan_id`);

--
-- Indexes for table `scan_requests`
--
ALTER TABLE `scan_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `settings_name_unique` (`name`);

--
-- Indexes for table `ships`
--
ALTER TABLE `ships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram_update`
--
ALTER TABLE `telegram_update`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`chat_id`,`message_id`),
  ADD KEY `inline_query_id` (`inline_query_id`),
  ADD KEY `chosen_inline_result_id` (`chosen_inline_result_id`),
  ADD KEY `callback_query_id` (`callback_query_id`),
  ADD KEY `edited_message_id` (`edited_message_id`);

--
-- Indexes for table `ticks`
--
ALTER TABLE `ticks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_scans`
--
ALTER TABLE `unit_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_chat`
--
ALTER TABLE `user_chat`
  ADD PRIMARY KEY (`user_id`,`chat_id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advanced_unit_scans`
--
ALTER TABLE `advanced_unit_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alliances`
--
ALTER TABLE `alliances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alliance_history`
--
ALTER TABLE `alliance_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attacks`
--
ALTER TABLE `attacks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attack_bookings`
--
ALTER TABLE `attack_bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attack_booking_users`
--
ALTER TABLE `attack_booking_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attack_targets`
--
ALTER TABLE `attack_targets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `battlegroups`
--
ALTER TABLE `battlegroups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `battlegroup_users`
--
ALTER TABLE `battlegroup_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `botan_shortener`
--
ALTER TABLE `botan_shortener`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry';

--
-- AUTO_INCREMENT for table `chosen_inline_result`
--
ALTER TABLE `chosen_inline_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry';

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry';

--
-- AUTO_INCREMENT for table `development_scans`
--
ALTER TABLE `development_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edited_message`
--
ALTER TABLE `edited_message`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry';

--
-- AUTO_INCREMENT for table `fleet_movements`
--
ALTER TABLE `fleet_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galaxies`
--
ALTER TABLE `galaxies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galaxy_history`
--
ALTER TABLE `galaxy_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `intel_change`
--
ALTER TABLE `intel_change`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jgp_scans`
--
ALTER TABLE `jgp_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_scans`
--
ALTER TABLE `news_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planets`
--
ALTER TABLE `planets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planet_history`
--
ALTER TABLE `planet_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planet_movements`
--
ALTER TABLE `planet_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planet_scans`
--
ALTER TABLE `planet_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_limiter`
--
ALTER TABLE `request_limiter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry';

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scans`
--
ALTER TABLE `scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scan_queue`
--
ALTER TABLE `scan_queue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scan_requests`
--
ALTER TABLE `scan_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ships`
--
ALTER TABLE `ships`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticks`
--
ALTER TABLE `ticks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_scans`
--
ALTER TABLE `unit_scans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `botan_shortener`
--
ALTER TABLE `botan_shortener`
  ADD CONSTRAINT `botan_shortener_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `callback_query`
--
ALTER TABLE `callback_query`
  ADD CONSTRAINT `callback_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `callback_query_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`);

--
-- Constraints for table `chosen_inline_result`
--
ALTER TABLE `chosen_inline_result`
  ADD CONSTRAINT `chosen_inline_result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`);

--
-- Constraints for table `edited_message`
--
ALTER TABLE `edited_message`
  ADD CONSTRAINT `edited_message_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  ADD CONSTRAINT `edited_message_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`),
  ADD CONSTRAINT `edited_message_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `inline_query`
--
ALTER TABLE `inline_query`
  ADD CONSTRAINT `inline_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_4` FOREIGN KEY (`forward_from_chat`) REFERENCES `chat` (`id`),
  ADD CONSTRAINT `message_ibfk_5` FOREIGN KEY (`reply_to_chat`) REFERENCES `message` (`chat_id`),
  ADD CONSTRAINT `message_ibfk_6` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_7` FOREIGN KEY (`left_chat_member`) REFERENCES `user` (`id`);

--
-- Constraints for table `telegram_update`
--
ALTER TABLE `telegram_update`
  ADD CONSTRAINT `telegram_update_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`),
  ADD CONSTRAINT `telegram_update_ibfk_2` FOREIGN KEY (`inline_query_id`) REFERENCES `inline_query` (`id`),
  ADD CONSTRAINT `telegram_update_ibfk_3` FOREIGN KEY (`chosen_inline_result_id`) REFERENCES `chosen_inline_result` (`id`),
  ADD CONSTRAINT `telegram_update_ibfk_4` FOREIGN KEY (`callback_query_id`) REFERENCES `callback_query` (`id`),
  ADD CONSTRAINT `telegram_update_ibfk_5` FOREIGN KEY (`edited_message_id`) REFERENCES `edited_message` (`id`);

--
-- Constraints for table `user_chat`
--
ALTER TABLE `user_chat`
  ADD CONSTRAINT `user_chat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_chat_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


INSERT INTO settings (name, value) VALUES ('overview','');
INSERT INTO settings (name, value) VALUES ('attack','');	
INSERT INTO settings (name, value) VALUES ('alliance','');

INSERT INTO roles (name) VALUES('Admin');
INSERT INTO roles (name) VALUES('BC');
INSERT INTO roles (name) VALUES('Member');
