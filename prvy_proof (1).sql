-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: So 12.Apr 2025, 04:00
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `prvy_proof`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `meno` varchar(255) NOT NULL,
  `heslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `admin`
--

INSERT INTO `admin` (`id`, `meno`, `heslo`) VALUES
(1, 'admin', '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `typ_osoby` enum('dedincan','cudzinec') NOT NULL COMMENT 'Určuje, či je osoba dedinčan alebo cudzinec',
  `typ_recenzie` enum('pozitivna','negativna') NOT NULL COMMENT 'Určuje, či je recenzia pozitívna alebo negatívna',
  `text_recenzie` text NOT NULL COMMENT 'Text recenzie od osoby',
  `datum` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Dátum a čas pridania recenzie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `feedback`
--

INSERT INTO `feedback` (`id`, `typ_osoby`, `typ_recenzie`, `text_recenzie`, `datum`) VALUES
(3, 'dedincan', 'pozitivna', 'tesim sa ako nasa obec napreduje', '2025-04-12 01:25:15'),
(5, 'cudzinec', 'negativna', 'smrdi tu', '2025-04-12 01:25:27');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `hlasovanie`
--

CREATE TABLE `hlasovanie` (
  `id` int(11) NOT NULL,
  `nazov` varchar(255) NOT NULL,
  `info` text DEFAULT NULL,
  `popis` text DEFAULT NULL,
  `moznost1` varchar(255) DEFAULT NULL,
  `moznost2` varchar(255) DEFAULT NULL,
  `moznost3` varchar(255) DEFAULT NULL,
  `moznost4` varchar(255) DEFAULT NULL,
  `datum_od` date DEFAULT NULL,
  `datum_do` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `hlasovanie`
--

INSERT INTO `hlasovanie` (`id`, `nazov`, `info`, `popis`, `moznost1`, `moznost2`, `moznost3`, `moznost4`, `datum_od`, `datum_do`) VALUES
(1, 'Hlasovanie o nových chodníkoch', 'Hlasovanie o tom, či by sa mali opraviť chodníky v meste. budu to chodniky buducna', 'Vyberte, či chcete nové chodníky.', 'Áno', 'Nie', NULL, NULL, '2025-04-01', '2025-04-10'),
(2, 'Hlasovanie o výsadbe stromov', 'Hlasovanie o výsadbe nových stromov v parkoch. budu sa sadit tujky', 'Vyberte, koľko stromov chcete vysadiť.', 'Jedna', 'Dve', 'Tri', NULL, '2025-04-05', '2025-04-15');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `hlasovanie_vysledky`
--

CREATE TABLE `hlasovanie_vysledky` (
  `id` int(11) NOT NULL,
  `id_hlasovanie` int(11) NOT NULL,
  `id_obcan` int(11) NOT NULL,
  `moznost` varchar(255) NOT NULL,
  `cas_hlasovania` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `hlasovanie_vysledky`
--

INSERT INTO `hlasovanie_vysledky` (`id`, `id_hlasovanie`, `id_obcan`, `moznost`, `cas_hlasovania`) VALUES
(9, 1, 7, 'Áno', '2025-04-11 20:14:24'),
(10, 2, 7, 'Dve', '2025-04-11 20:14:27'),
(11, 1, 8, 'Áno', '2025-04-11 20:40:27'),
(12, 1, 7, 'Nie', '2025-04-11 20:40:30'),
(13, 2, 3, 'Jedna', '2025-04-11 20:40:52'),
(14, 1, 2, 'Nie', '2025-04-11 20:41:50'),
(15, 1, 4, 'Áno', '2025-04-11 20:41:53');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `typ` varchar(100) NOT NULL,
  `nazov` varchar(200) NOT NULL,
  `text` text DEFAULT NULL,
  `datum_od` date DEFAULT NULL,
  `datum_do` date DEFAULT NULL,
  `id_obec` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `news`
--

INSERT INTO `news` (`id`, `typ`, `nazov`, `text`, `datum_od`, `datum_do`, `id_obec`) VALUES
(1, 'oznam', 'Rekonštrukcia chodníkov', 'Opravíme poškodené úseky v strede obce.', '2024-04-01', '2024-04-10', 1),
(2, 'kultura', 'Deň obce Čierne', 'Oslavujeme s kultúrnym programom a koncertom.', '2024-04-05', '2024-04-05', 1),
(3, 'zmena', 'Zmena zberu odpadu', 'Zber plastov bude každý pondelok.', '2024-04-07', '2024-04-30', 1),
(4, 'oznam', 'Stavba cyklotrasy', 'Začína výstavba novej cyklotrasy.', '2024-04-02', '2024-04-20', 2),
(5, 'kultura', 'Beh zdravia Svrčinovec', 'Zúčastnite sa komunitného behu pre všetkých.', '2024-04-08', '2024-04-08', 2),
(6, 'oznam', 'Výpadok elektriny', 'Elektrina nepôjde v časti obce od 10:00.', '2024-04-04', '2024-04-04', 2),
(7, 'oznam', 'Zber elektroodpadu', 'V sobotu prebehne zber starých spotrebičov.', '2024-04-06', '2024-04-06', 3),
(8, 'kultura', 'Koncert v Skalitom', 'Kapela vystúpi túto sobotu večer.', '2024-04-09', '2024-04-09', 3),
(9, 'oznam', 'Uzávierka cesty', 'Cesta bude dočasne uzavretá kvôli oprave.', '2024-04-03', '2024-04-07', 3),
(10, 'zmena', 'Zmena cestovných poriadkov', 'Autobusové spoje budú premávať inak.', '2024-04-10', '2024-04-15', 1),
(14, 'zmena', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(15, 'zmena', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(16, 'zmena', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(17, 'sport', 'beh', 'beh', '2025-04-12', '2025-04-12', 1),
(18, 'kultura', 'pride mikulas', 'gckvvuzv', '2025-04-27', '2025-04-27', 1),
(19, 'sport', 'Futbalovy zapas', 'Futbalovy štadión', '2025-04-12', '2025-04-12', 1),
(20, 'sport', 'Futbalovy zapas', 'Futbalovy štadión', '2025-04-12', '2025-04-12', 1),
(21, 'sport', 'Futbalovy zapas', 'Futbalovy štadión', '2025-04-12', '2025-04-12', 1),
(22, 'sport', 'Futbalovy zapas', 'Futbalovy štadión', '2025-04-12', '2025-04-12', 1),
(23, 'sport', 'Futbalovy zapas', 'Futbalovy štadión', '2025-04-12', '2025-04-12', 1),
(24, 'sport', 'Hokejbal', 'HIHIHIHAAAAAAA', '2025-07-05', '2026-10-05', 1),
(25, 'kultura', 'zelo si honi', 'unikatny pohlad na gazela v jeho plnej krase na namesti obce cierne', '2025-04-12', '2025-04-12', 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `obcan`
--

CREATE TABLE `obcan` (
  `id` int(11) NOT NULL,
  `meno` varchar(100) NOT NULL,
  `priezvisko` varchar(100) NOT NULL,
  `id_obec` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `obcan`
--

INSERT INTO `obcan` (`id`, `meno`, `priezvisko`, `id_obec`) VALUES
(1, 'Peter', 'Novák', 1),
(2, 'Jana', 'Kováčová', 1),
(3, 'Martin', 'Horváth', 1),
(4, 'Lucia', 'Zelená', 2),
(5, 'Tomáš', 'Polák', 2),
(6, 'Eva', 'Mlynárová', 2),
(7, 'Michal', 'Kučera', 3),
(8, 'Anna', 'Jankovičová', 3),
(9, 'Roman', 'Urban', 3),
(10, 'Simona', 'Králová', 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `obec`
--

CREATE TABLE `obec` (
  `id` int(11) NOT NULL,
  `nazov` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `obec`
--

INSERT INTO `obec` (`id`, `nazov`) VALUES
(1, 'Čierne'),
(2, 'Svrčinovec'),
(3, 'Skalité');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `office_hours`
--

CREATE TABLE `office_hours` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `cas` time NOT NULL,
  `dostupne` tinyint(1) DEFAULT 1,
  `obec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `office_hours`
--

INSERT INTO `office_hours` (`id`, `datum`, `cas`, `dostupne`, `obec_id`) VALUES
(1, '2025-04-15', '08:00:00', 1, 1),
(2, '2025-04-15', '09:00:00', 1, 1),
(3, '2025-04-15', '10:00:00', 1, 1),
(4, '2025-04-15', '11:00:00', 1, 1),
(5, '2025-04-15', '12:00:00', 1, 1),
(6, '2025-04-15', '13:00:00', 1, 2),
(7, '2025-04-15', '14:00:00', 1, 2),
(8, '2025-04-15', '15:00:00', 1, 2),
(9, '2025-04-16', '08:00:00', 1, 2),
(10, '2025-04-16', '09:00:00', 1, 2),
(11, '2025-04-16', '10:00:00', 1, 3),
(12, '2025-04-16', '11:00:00', 1, 3),
(13, '2025-04-16', '12:00:00', 1, 3),
(14, '2025-04-16', '13:00:00', 1, 3),
(15, '2025-04-17', '08:00:00', 1, 1),
(16, '2025-04-17', '09:00:00', 1, 1),
(17, '2025-04-17', '10:00:00', 1, 1),
(18, '2025-04-17', '11:00:00', 1, 2),
(19, '2025-04-17', '12:00:00', 1, 2),
(20, '2025-04-17', '13:00:00', 1, 2),
(21, '2025-04-17', '14:00:00', 1, 3),
(22, '2025-04-17', '15:00:00', 1, 3),
(23, '2025-04-17', '16:00:00', 1, 3);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `podnet`
--

CREATE TABLE `podnet` (
  `id` int(11) NOT NULL,
  `id_obcan` int(11) DEFAULT NULL,
  `nazov` varchar(200) NOT NULL,
  `text` text DEFAULT NULL,
  `typ` varchar(100) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `stav_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `podnet`
--

INSERT INTO `podnet` (`id`, `id_obcan`, `nazov`, `text`, `typ`, `datum`, `stav_id`) VALUES
(1, 1, 'Chýba osvetlenie', 'Na ulici večer nič nevidno.', 'verejné osvetlenie', '2024-04-01', 1),
(2, 2, 'Zničený chodník', 'Chodník je plný dier a prasklín.', 'infraštruktúra', '2024-04-02', 2),
(3, 3, 'Odpadky v parku', 'Park je veľmi znečistený odpadkami.', 'čistota', '2024-04-03', 3),
(4, 4, 'Hlučný podnik', 'Bar je príliš hlučný počas noci.', 'sťažnosť', '2024-04-04', 4),
(5, 5, 'Problém s parkovaním', 'Na sídlisku nie sú miesta na autá.', 'doprava', '2024-04-05', 5),
(6, 6, 'Nefunkčné semafory', 'Semafor nefunguje už tri dni.', 'bezpečnosť', '2024-04-06', 6),
(7, 7, 'Poškodené lavičky', 'Lavičky sú rozbité a nebezpečné.', 'verejný priestor', '2024-04-07', 7),
(8, 8, 'Chýba zebra', 'Pri škole chýba priechod pre chodcov.', 'dopravné značenie', '2024-04-08', 8),
(9, 9, 'Strom ohrozuje cestu', 'Strom sa nakláňa priamo nad cestu.', 'príroda', '2024-04-09', 9),
(10, 10, 'Tma na ulici', 'Osvetlenie je mimo prevádzky celý týždeň.', 'osvetlenie', '2024-04-10', 10);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `office_hour_id` int(11) NOT NULL,
  `dovod` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `stav`
--

CREATE TABLE `stav` (
  `id` int(11) NOT NULL,
  `nazov_stavu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `stav`
--

INSERT INTO `stav` (`id`, `nazov_stavu`) VALUES
(1, 'Nový'),
(2, 'V riešení'),
(3, 'Vyriešený'),
(4, 'Zamietnutý'),
(5, 'Odoslaný'),
(6, 'Spracovaný'),
(7, 'Zamknutý'),
(8, 'Čaká na odpoveď'),
(9, 'Zverejnený'),
(10, 'Zrušený');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `hlasovanie`
--
ALTER TABLE `hlasovanie`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `hlasovanie_vysledky`
--
ALTER TABLE `hlasovanie_vysledky`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hlasovanie` (`id_hlasovanie`),
  ADD KEY `id_obcan` (`id_obcan`);

--
-- Indexy pre tabuľku `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obec` (`id_obec`);

--
-- Indexy pre tabuľku `obcan`
--
ALTER TABLE `obcan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obec` (`id_obec`);

--
-- Indexy pre tabuľku `obec`
--
ALTER TABLE `obec`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `office_hours`
--
ALTER TABLE `office_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obec_id` (`obec_id`);

--
-- Indexy pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obcan` (`id_obcan`),
  ADD KEY `stav_id` (`stav_id`);

--
-- Indexy pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_hour_id` (`office_hour_id`);

--
-- Indexy pre tabuľku `stav`
--
ALTER TABLE `stav`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pre tabuľku `hlasovanie`
--
ALTER TABLE `hlasovanie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pre tabuľku `hlasovanie_vysledky`
--
ALTER TABLE `hlasovanie_vysledky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pre tabuľku `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pre tabuľku `obcan`
--
ALTER TABLE `obcan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pre tabuľku `obec`
--
ALTER TABLE `obec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pre tabuľku `office_hours`
--
ALTER TABLE `office_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `stav`
--
ALTER TABLE `stav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `hlasovanie_vysledky`
--
ALTER TABLE `hlasovanie_vysledky`
  ADD CONSTRAINT `hlasovanie_vysledky_ibfk_1` FOREIGN KEY (`id_hlasovanie`) REFERENCES `hlasovanie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hlasovanie_vysledky_ibfk_2` FOREIGN KEY (`id_obcan`) REFERENCES `obcan` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`id_obec`) REFERENCES `obec` (`id`);

--
-- Obmedzenie pre tabuľku `obcan`
--
ALTER TABLE `obcan`
  ADD CONSTRAINT `obcan_ibfk_1` FOREIGN KEY (`id_obec`) REFERENCES `obec` (`id`);

--
-- Obmedzenie pre tabuľku `office_hours`
--
ALTER TABLE `office_hours`
  ADD CONSTRAINT `office_hours_ibfk_1` FOREIGN KEY (`obec_id`) REFERENCES `obec` (`id`);

--
-- Obmedzenie pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  ADD CONSTRAINT `podnet_ibfk_1` FOREIGN KEY (`id_obcan`) REFERENCES `obcan` (`id`),
  ADD CONSTRAINT `podnet_ibfk_2` FOREIGN KEY (`stav_id`) REFERENCES `stav` (`id`);

--
-- Obmedzenie pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`office_hour_id`) REFERENCES `office_hours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
