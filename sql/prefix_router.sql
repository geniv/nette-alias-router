-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Ned 28. kvě 2017, 23:50
-- Verze serveru: 10.0.29-MariaDB-0ubuntu0.16.04.1
-- Verze PHP: 7.0.15-0ubuntu0.16.04.4

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
  `id` int(11) NOT NULL,
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
