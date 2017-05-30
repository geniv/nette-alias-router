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
-- Struktura tabulky `prefix_router_alias`
--

CREATE TABLE `prefix_router_alias` (
  `id` int(11) NOT NULL,
  `id_router` int(11) NOT NULL COMMENT 'vazba na router',
  `id_locale` int(11) DEFAULT NULL COMMENT 'vazba na jazyk',
  `id_item` int(11) DEFAULT NULL COMMENT 'id polozky',
  `alias` varchar(255) DEFAULT NULL COMMENT 'textovy alias',
  `added` datetime DEFAULT NULL COMMENT 'datum pridani slouzici jako poradi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='route alias';

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_router_alias`
--
ALTER TABLE `prefix_router_alias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locale_alias_UNIQUE` (`id_locale`,`alias`),
  ADD KEY `fk_router_alias_route_idx` (`id_router`),
  ADD KEY `fk_router_alias_locale_idx` (`id_locale`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_router_alias`
--
ALTER TABLE `prefix_router_alias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `prefix_router_alias`
--
ALTER TABLE `prefix_router_alias`
  ADD CONSTRAINT `fk_router_alias_locale` FOREIGN KEY (`id_locale`) REFERENCES `prefix_locale` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_router_alias_router` FOREIGN KEY (`id_router`) REFERENCES `prefix_router` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
