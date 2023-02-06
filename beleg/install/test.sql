SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `anzahl_pers` int(11) NOT NULL,
  `anzahl_erw` int(11) NOT NULL,
  `anzahl_kind` int(11) NOT NULL,
  `anzahl_tier` int(11) NOT NULL,
  `text` text NOT NULL,
  `guest_id` int(11) NOT NULL,
  `times_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `color` (
  `id` int(10) NOT NULL,
  `cal_month` varchar(7) NOT NULL,
  `cal_beleg` varchar(7) NOT NULL,
  `form_back` varchar(7) NOT NULL,
  `cal_back` varchar(7) NOT NULL,
  `cal_head` varchar(7) NOT NULL,
  `cal_days` varchar(7) NOT NULL,
  `cal_we` varchar(7) NOT NULL,
  `back` varchar(7) NOT NULL,
  `font` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `color` (`id`, `cal_month`, `cal_beleg`, `form_back`, `cal_back`, `cal_head`, `cal_days`, `cal_we`, `back`, `font`) VALUES
(1, '#ffff00', '#ff0000', '#ffff00', '#ffffff', '#ffff00', '#ffffff', '#ffffff', '#ffffff', '#000000');

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `json_code` text CHARACTER SET utf8 NOT NULL,
  `objektid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `anrede` text NOT NULL,
  `vorname` text NOT NULL,
  `name` text NOT NULL,
  `str` text NOT NULL,
  `plz` int(5) NOT NULL,
  `ort` text NOT NULL,
  `tel` int(15) NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `mail_text` (
  `id` int(11) NOT NULL,
  `best_text` longtext NOT NULL,
  `buch_text` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `mail_text` (`id`, `best_text`, `buch_text`) VALUES
(1, 'Geben Sie hier den Text für die Bestätigungsmail ein!', 'Geben Sie hier den Text für die Buchungsmail ein');

CREATE TABLE `objekt` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `max_people` int(2) DEFAULT NULL,
  `max_tier` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `settings` (
  `id` int(1) NOT NULL,
  `cal_typ` int(1) DEFAULT NULL,
  `cal_m_zahl` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `settings` (`id`, `cal_typ`, `cal_m_zahl`) VALUES
(1, 1, 3);

CREATE TABLE `times` (
  `id` int(11) NOT NULL,
  `datean` date NOT NULL,
  `dateab` date NOT NULL,
  `user` int(11) DEFAULT NULL,
  `confirmed` int(11) NOT NULL,
  `objekt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `times_id` (`times_id`);

ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `objektid` (`objektid`);

ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mail_text`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `objekt`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objekt_id` (`objekt_id`),
  ADD KEY `user` (`user`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `color`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mail_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `objekt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;
ALTER TABLE `times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`times_id`) REFERENCES `times` (`id`);

ALTER TABLE `forms`
  ADD CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`objektid`) REFERENCES `objekt` (`id`);

ALTER TABLE `times`
  ADD CONSTRAINT `times_ibfk_1` FOREIGN KEY (`objekt_id`) REFERENCES `objekt` (`id`),
  ADD CONSTRAINT `times_ibfk_2` FOREIGN KEY (`user`) REFERENCES `guests` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
