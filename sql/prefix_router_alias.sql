-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Stř 28. bře 2018, 16:18
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
-- Struktura tabulky `prefix_router_alias`
--

CREATE TABLE `prefix_router_alias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_locale` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'vazba na jazyk',
  `id_router` bigint(20) UNSIGNED NOT NULL COMMENT 'vazba na router',
  `id_item` bigint(20) DEFAULT NULL COMMENT 'id polozky',
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
  ADD UNIQUE KEY `locale_router_alias_UNIQUE` (`id_locale`,`id_router`,`alias`),
  ADD KEY `fk_router_alias_route_idx` (`id_router`),
  ADD KEY `fk_router_alias_locale_idx` (`id_locale`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_router_alias`
--
ALTER TABLE `prefix_router_alias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
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
