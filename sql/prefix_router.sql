-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Sob 27. led 2018, 20:24
-- Verze serveru: 10.1.26-MariaDB-0+deb9u1
-- Verze PHP: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `netteweb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_router`
--

CREATE TABLE `prefix_router` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `presenter` varchar(50) DEFAULT NULL COMMENT 'prezenter',
  `action` varchar(50) DEFAULT NULL COMMENT 'akce'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='routy';

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_router`
--
ALTER TABLE `prefix_router`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `presenter_action_UNIQUE` (`presenter`,`action`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_router`
--
ALTER TABLE `prefix_router`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
