-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Pi 11.Apr 2025, 23:54
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
(2, 'udalosť', 'Deň obce Čierne', 'Oslavujeme s kultúrnym programom a koncertom.', '2024-04-05', '2024-04-05', 1),
(3, 'oznam', 'Zmena zberu odpadu', 'Zber plastov bude každý pondelok.', '2024-04-07', '2024-04-30', 1),
(4, 'oznam', 'Stavba cyklotrasy', 'Začína výstavba novej cyklotrasy.', '2024-04-02', '2024-04-20', 2),
(5, 'udalosť', 'Beh zdravia Svrčinovec', 'Zúčastnite sa komunitného behu pre všetkých.', '2024-04-08', '2024-04-08', 2),
(6, 'oznam', 'Výpadok elektriny', 'Elektrina nepôjde v časti obce od 10:00.', '2024-04-04', '2024-04-04', 2),
(7, 'oznam', 'Zber elektroodpadu', 'V sobotu prebehne zber starých spotrebičov.', '2024-04-06', '2024-04-06', 3),
(8, 'udalosť', 'Koncert v Skalitom', 'Kapela vystúpi túto sobotu večer.', '2024-04-09', '2024-04-09', 3),
(9, 'oznam', 'Uzávierka cesty', 'Cesta bude dočasne uzavretá kvôli oprave.', '2024-04-03', '2024-04-07', 3),
(10, 'oznam', 'Zmena cestovných poriadkov', 'Autobusové spoje budú premávať inak.', '2024-04-10', '2024-04-15', 1),
(14, 'udalosť', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(15, 'udalosť', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(16, 'udalosť', 'Uprava kostolnych hodin', 'zmeni sa to z 18.00 na 19.00', '2025-04-12', '2025-04-13', 1),
(17, 'športová udalosť', 'beh', 'beh', '2025-04-12', '2025-04-12', 1),
(18, 'hudba', 'pride mikulas', 'gckvvuzv', '2025-04-27', '2025-04-27', 1);

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

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meno` varchar(100) NOT NULL,
  `heslo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `admin`
--

INSERT INTO `admin` (`meno`, `heslo`) VALUES
('admin', SHA2('Heslo', 256)); -- Heslo je zahashované pomocou SHA-256

--
-- Kľúče pre exportované tabuľky
--

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
-- Indexy pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obcan` (`id_obcan`),
  ADD KEY `stav_id` (`stav_id`);

--
-- Indexy pre tabuľku `stav`
--
ALTER TABLE `stav`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- AUTO_INCREMENT pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Obmedzenie pre tabuľku `podnet`
--
ALTER TABLE `podnet`
  ADD CONSTRAINT `podnet_ibfk_1` FOREIGN KEY (`id_obcan`) REFERENCES `obcan` (`id`),
  ADD CONSTRAINT `podnet_ibfk_2` FOREIGN KEY (`stav_id`) REFERENCES `stav` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
