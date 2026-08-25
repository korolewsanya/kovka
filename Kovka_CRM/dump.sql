-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Авг 22 2026 г., 18:04
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kovka`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cod`
--

CREATE TABLE `cod` (
  `id` int(11) NOT NULL,
  `cod` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cod`
--

INSERT INTO `cod` (`id`, `cod`) VALUES
(6, 3333);

-- --------------------------------------------------------

--
-- Структура таблицы `dostup`
--

CREATE TABLE `dostup` (
  `id` int(11) NOT NULL,
  `class_work` int(11) NOT NULL,
  `prof` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `cod` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `dostup`
--

INSERT INTO `dostup` (`id`, `class_work`, `prof`, `name`, `cod`) VALUES
(1, 1, 'Админ', 'Алесандр', '1111'),
(2, 2, 'Дизайнер', 'Светлана', '2222'),
(3, 3, 'Сварщик', 'Алексей', '3333'),
(4, 4, 'Слесарь', 'Максим', '4444'),
(5, 5, 'Маляр', 'Надежда', '5555'),
(6, 6, 'Водитель', 'Борис', '6666'),
(7, 7, 'Грузчик', 'Андрей', '7777');

-- --------------------------------------------------------

--
-- Структура таблицы `fin`
--

CREATE TABLE `fin` (
  `id` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `dohod` int(11) DEFAULT NULL,
  `rashod` int(11) DEFAULT NULL,
  `prib` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `fin`
--

INSERT INTO `fin` (`id`, `date`, `dohod`, `rashod`, `prib`) VALUES
(1, '2023-06-03 10:49:45', 1000000, 200000, 800000),
(2, '2023-07-03 10:49:45', 11000, 1200, 9800),
(3, '2023-08-03 10:49:45', 500000, 25000, 475000),
(5, '2023-08-03 20:07:30', 5000, 3000, 2000),
(6, '2023-08-03 20:09:10', 200000, 110000, 90000),
(7, '2023-08-04 22:33:44', 116000, 32000, 84000),
(9, '2023-08-05 20:33:44', 12000, 4000, 8000),
(10, '2023-08-19 16:11:54', 14000, 2000, 12000);

-- --------------------------------------------------------

--
-- Структура таблицы `img`
--

CREATE TABLE `img` (
  `id` int(11) NOT NULL,
  `img` varchar(50) NOT NULL DEFAULT 'Лавочка_1.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `img`
--

INSERT INTO `img` (`id`, `img`) VALUES
(1, 'мангал1.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `kozirek`
--

CREATE TABLE `kozirek` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) NOT NULL DEFAULT '1200 ??',
  `Shirina` varchar(20) NOT NULL DEFAULT '1200 ??',
  `Visota` varchar(20) NOT NULL DEFAULT '1000 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `kozirek`
--

INSERT INTO `kozirek` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Козырек_1', 'Козырек_1.png', '1200 мм', '1200 мм', '1000 мм', 15000),
(2, 'Козырек_2', 'Козырек_2.png', '1200 мм', '1200 мм', '1000 мм', 10000),
(3, 'Козырек_3', 'Козырек_3.png', '1200 мм', '1200 мм', '1000 мм', 12000),
(4, 'Козырек_4', 'Козырек_4.png', '1200 мм', '1200 мм', '1000 мм', 15000),
(5, 'Козырек_5', 'Козырек_5.png', '1200 мм', '1200 мм', '1000 мм', 20000),
(6, 'Козырек_6', 'Козырек_6.png', '1200 мм', '1200 мм', '1000 мм', 25000),
(7, 'Козырек_7', 'Козырек_7.png', '1200 мм', '1200 мм', '1000 мм', 20000),
(8, 'Козырек_8', 'Козырек_8.png', '1200 мм', '1200 мм', '1000 мм', 17000),
(9, 'Козырек_9', 'Козырек_9.png', '1200 мм', '1200 мм', '1000 мм', 14000),
(10, 'Козырек_10', 'Козырек_10.png', '1200 мм', '1200 мм', '1000 мм', 14000);

-- --------------------------------------------------------

--
-- Структура таблицы `lavo4ki`
--

CREATE TABLE `lavo4ki` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) DEFAULT '1500 ??',
  `Shirina` varchar(20) DEFAULT '550 ??',
  `Visota` varchar(20) DEFAULT '850 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `lavo4ki`
--

INSERT INTO `lavo4ki` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Лавочка_2', 'Лавочка_2.png', '1500 мм', '550 мм', '850 мм', 15000),
(2, 'Лавочка_3', 'Лавочка_3.png', '1500 мм', '550 мм', '850 мм', 10000),
(3, 'Лавочка_4', 'Лавочка_4.png', '1500 мм', '550 мм', '850 мм', 12000),
(4, 'Лавочка_5', 'Лавочка_5.png', '1500 мм', '550 мм', '850 мм', 15000),
(5, 'Лавочка_6', 'Лавочка_6.png', '1500 мм', '550 мм', '850 мм', 20000),
(6, 'Лавочка_7', 'Лавочка_7.png', '1500 мм', '550 мм', '850 мм', 25000),
(7, 'Лавочка_8', 'Лавочка_8.png', '1500 мм', '550 мм', '850 мм', 13000),
(8, 'Лавочка_9', 'Лавочка_9.png', '1500 мм', '550 мм', '850 мм', 17000),
(9, 'Лавочка_10', 'Лавочка_10.png', '1500 мм', '550 мм', '850 мм', 19000);

-- --------------------------------------------------------

--
-- Структура таблицы `mangal`
--

CREATE TABLE `mangal` (
  `Id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) DEFAULT '700 ??',
  `Shirina` varchar(20) DEFAULT '350 ??',
  `Visota` varchar(20) DEFAULT '700 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mangal`
--

INSERT INTO `mangal` (`Id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'мангал1', 'мангал1.jpg', '700 мм', '350 мм', '700 мм', 15000),
(2, 'мангал2', 'мангал2.jpg', '700 мм', '350 мм', '700 мм', 10000),
(3, 'мангал3', 'мангал3.jpg', '700 мм', '350 мм', '700 мм', 12000),
(4, 'мангал4', 'мангал4.jpg', '700 мм', '350 мм', '700 мм', 15000),
(5, 'мангал5', 'мангал5.jpg', '700 мм', '350 мм', '700 мм', 20000),
(6, 'мангал6', 'мангал6.jpg', '700 мм', '350 мм', '700 мм', 25000),
(7, 'мангал7', 'мангал7.png', '700 мм', '350 мм', '700 мм', 30000),
(8, 'мангал8', 'мангал8.jpg', '700 мм', '350 мм', '700 мм', 35000),
(9, 'мангал9', 'мангал9.jpg', '700 мм', '350 мм', '700 мм', 40000),
(10, 'мангал10', 'мангал10.png', '700 мм', '350 мм', '700 мм', 45000),
(11, 'мангал11', 'мангал11.png', '700 мм', '350 мм', '700 мм', 50000),
(12, 'мангал12', 'мангал12.jpg', '700 мм', '350 мм', '700 мм', 15000),
(19, 'турник', '8813_Турник_из_бруса_(3).png', '', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mater`
--

CREATE TABLE `mater` (
  `id` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `kup` varchar(50) DEFAULT NULL,
  `izras` varchar(50) DEFAULT NULL,
  `ost` varchar(50) DEFAULT NULL,
  `prise` varchar(50) DEFAULT NULL,
  `itogo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mater`
--

INSERT INTO `mater` (`id`, `date`, `name`, `kup`, `izras`, `ost`, `prise`, `itogo`) VALUES
(1, '2023-08-03 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(5, '2023-08-02 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(6, '2023-08-04 09:55:20', 'Плитка Кирпич', '1000', '1000', '0', '5 руб/шт', 5000),
(7, '2023-07-25 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(8, '2023-07-26 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(9, '2023-07-27 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(14, '2023-07-28 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(16, '2023-07-29 17:18:03', 'Труба 1,5*20*40*6000 мм', '10 *6=60м', '15 м', '45 м', '110 руб/м', 6600),
(18, '2023-08-04 09:47:01', 'Доска 10*1500*1500 мм', '10 шт.', '2 м', '8 м', '500 руб/шт', 5000),
(19, '2023-08-04 09:50:48', 'Доска 25*100*6000 мм', '1м3', '0', '1м3', '12000р/м3', 12000),
(20, '2023-08-15', 'Кирпич', '3333', '2222', '1111', '4444', 8888),
(22, '2023-08-16', 'Швеллер ', '1 м', '0', '1 м', '200', 200),
(23, '2023-08-04 09:50:48', 'Доска 25*100*6000 мм', '1м3', '0', '1м3', '12000р/м3', 12000),
(24, '2023-08-04 09:50:48', 'Доска 50*150*6000 мм', '1м3', '0', '1м3', '22000р/м3', 22000);

-- --------------------------------------------------------

--
-- Структура таблицы `mebel`
--

CREATE TABLE `mebel` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) DEFAULT NULL,
  `Shirina` varchar(20) DEFAULT NULL,
  `Visota` varchar(20) DEFAULT NULL,
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mebel`
--

INSERT INTO `mebel` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Кровать_1', 'Кровать_1.png', '1600 мм', '800 мм', '700 мм', 15000),
(2, 'Кровать_2', 'Кровать_2.png', '1600 мм', '800 мм', '700 мм', 18000),
(3, 'Кровать_3', 'Кровать_3.png', '1600 мм', '800 мм', '700 мм', 17000),
(4, 'Стол_1', 'Стол_1.png', '2000 мм', '1600 мм', '600 мм', 15000),
(5, 'Стол_2', 'Стол_2.png', '2000 мм', '1600 мм', '600 мм', 28000),
(6, 'Стол_3', 'Стол_3.png', '2000 мм', '1600 мм', '600 мм', 25000),
(7, 'Стул_1', 'Стул_1.png', '400 мм', '400 мм', '800 мм', 14000);

-- --------------------------------------------------------

--
-- Структура таблицы `melo4i`
--

CREATE TABLE `melo4i` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) DEFAULT NULL,
  `Shirina` varchar(20) DEFAULT NULL,
  `Visota` varchar(20) DEFAULT NULL,
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `melo4i`
--

INSERT INTO `melo4i` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Подсвечник_1', 'Подсвечник_1.png', NULL, NULL, NULL, 1000),
(2, 'Подсвечник_2', 'Подсвечник_2.png', NULL, NULL, NULL, 2000),
(3, 'Подсвечник_3', 'Подсвечник_3.png', NULL, NULL, NULL, 3000),
(4, 'Подставка_1', 'Подставка_1.png', NULL, NULL, NULL, 5000),
(5, 'Подставка_2', 'Подставка_2.png', NULL, NULL, NULL, 6000),
(6, 'Подставка_3', 'Подставка_3.png', NULL, NULL, NULL, 7000),
(7, 'Подставка_для_ёлки_1', 'Подставка_для_ёлки_1.png', NULL, NULL, NULL, 1000),
(8, 'Подставка_для_ёлки_2', 'Подставка_для_ёлки_2.png', NULL, NULL, NULL, 1500),
(9, 'Урна', 'Урна.png', NULL, NULL, NULL, 5000);

-- --------------------------------------------------------

--
-- Структура таблицы `ogradki`
--

CREATE TABLE `ogradki` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) NOT NULL DEFAULT '2000 ??',
  `Shirina` varchar(20) NOT NULL DEFAULT '40 ??',
  `Visota` varchar(20) NOT NULL DEFAULT '600 ??',
  `Prise` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `ogradki`
--

INSERT INTO `ogradki` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Оградка_1', 'Оградка_1.png', '2000 мм', '40 мм', '600 мм', '15000'),
(2, 'Оградка_2', 'Оградка_2.png', '2000 мм', '40 мм', '600 мм', '14000'),
(3, 'Оградка_3', 'Оградка_3.png', '2000 мм', '40 мм', '600 мм', '12000'),
(4, 'Оградка_4', 'Оградка_4.png', '2000 мм', '40 мм', '600 мм', '15000'),
(5, 'Оградка_5', 'Оградка_5.png', '2000 мм', '40 мм', '600 мм', '20000'),
(6, 'Оградка_6', 'Оградка_6.png', '2000 мм', '40 мм', '600 мм', '25000'),
(7, 'Оградка_7', 'Оградка_7.png', '2000 мм', '40 мм', '600 мм', '30000'),
(8, 'Оградка_8', 'Оградка_8.png', '2000 мм', '40 мм', '600 мм', '35000'),
(9, 'Оградка_9', 'Оградка_9.png', '2000 мм', '40 мм', '600 мм', '40000');

-- --------------------------------------------------------

--
-- Структура таблицы `otchet`
--

CREATE TABLE `otchet` (
  `id` int(11) NOT NULL,
  `class_work` int(11) DEFAULT NULL,
  `prof` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `tz` text DEFAULT NULL,
  `otchet` text DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `cod` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `otchet`
--

INSERT INTO `otchet` (`id`, `class_work`, `prof`, `name`, `tz`, `otchet`, `date`, `image`, `cod`) VALUES
(426, 2, 'Дизайнер', 'Светлана', 'Создание эскизов кованых элементов', 'Эскизы переданы в производство', '2024-05-12', 'ворота6.png', '2222'),
(431, 5, 'Маляр', 'Надежда', 'Покраска готовых изделий', 'Покраска выполнена, цвет соответствует ТЗ', '2024-05-25', NULL, '5555'),
(435, 6, 'Водитель', 'Борис', 'Доставка материалов на склад', 'Все материалы доставлены вовремя', '2024-05-20', NULL, '6666'),
(436, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'Изделия готовы к покраске', '2024-05-18', 'мангал5.jpg', '3333'),
(439, 4, 'Слесарь', 'Максим', 'Обработка металла', 'Металл обработан качественно', '2024-05-22', NULL, '4444'),
(487, 1, 'Админ', 'Александр', 'Разработка панели администратора', 'Работа выполнена, панель готова', '2024-05-15', 'ворота8.png', '1111'),
(501, 4, 'Слесарь', 'Максим', 'Заготовки на беседку', 'Разметка сделана', '2026-05-20 20:08:07', NULL, '4444'),
(503, 6, 'Водитель', 'Борис', 'Oтвези материалы на склад', 'Материалы отвез', '2026-05-26 17:59:01', NULL, '6666'),
(504, 2, 'Дизайнер', 'Светлана', 'Создание эскизов кованых элементов', 'делаю', '2026-05-26 21:44:55', 'ворота2.png', '2222'),
(517, 7, 'Грузчик', 'Андрей', 'Разгрузить машину', 'СДЕЛАНО', '2026-05-27 12:02:12', NULL, '7777'),
(525, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'ВЫПОЛНЕНО', '2026-05-27 14:32:37', NULL, '3333'),
(533, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'ВЫПОЛНЕНО', '2026-05-27 14:33:19', NULL, '3333'),
(534, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'СДЕЛАНО', '2026-05-27 14:33:23', NULL, '3333'),
(543, 2, 'Дизайнер', 'Светлана', 'Нужны эскизы ворот', 'ВЫПОЛНЕНО', '2026-05-27 15:09:23', 'ворота8.png', '2222'),
(547, 2, 'Дизайнер', 'Светлана', 'Мангал по проекту 5 нужно доработать', 'ВЫПОЛНЕНО', '2026-05-27 15:09:37', 'мангал5.jpg', '2222'),
(548, 2, 'Дизайнер', 'Светлана', 'Общий вид ворот и забора', 'делаю', '2026-05-27 15:09:40', 'ворота5.png', '2222'),
(554, 1, 'Админ', 'Алесандр', 'Согласовать проект', 'СДЕЛАНО', '2026-05-28 08:59:09', 'ворота7.png', '1111'),
(555, 1, 'Админ', 'Алесандр', 'Заказать материалы', 'делаю', '2026-05-28 08:59:16', 'Козырек_1.png', '1111'),
(560, 5, 'Маляр', 'Надежда', 'Покрась ворота5 ', 'Загрунтовано', '2026-05-28 09:31:32', NULL, '5555'),
(562, 4, 'Слесарь', 'Максим', 'Заготовки на беседку', 'делаю', '2026-05-28 19:41:09', NULL, '4444'),
(568, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'ВЫПОЛНЕНО', '2026-05-29 09:39:35', NULL, '3333'),
(570, 3, 'Сварщик', 'Алексей', 'МАНГАЛ по проекту 5', 'почти готов', '2026-05-30 10:19:30', 'IMG_20260523_070403048.jpg', '3333'),
(571, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'делаю', '2026-05-30 12:05:36', 'IMG_20260523_070403048.jpg', '3333'),
(572, 3, 'Сварщик', 'Алексей', 'ЗАБОР по проекту 3', 'делаю', '2026-05-30 12:11:07', 'IMG-20260506-WA0000.jpg', '3333'),
(573, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'ВЫПОЛНЕНО', '2026-05-30 12:13:49', 'IMG-20260506-WA0000.jpg', '3333'),
(574, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'ВЫПОЛНЕНО', '2026-05-30 12:18:53', 'IMG-20260506-WA0000.jpg', '3333'),
(575, 3, 'Сварщик', 'Алексей', 'Сварка кованых изделий', 'в процессе', '2026-05-30 12:43:03', 'IMG_20260527_133236.jpg', '3333'),
(578, 1, 'Админ', 'Алесандр', 'Нужны деньги на материал', 'выдано', '2026-05-31 12:27:07', 'мангал3.jpg', '1111'),
(579, 1, 'Админ', 'Алесандр', 'Оплати работу людям', 'выдан аванс', '2026-05-31 12:32:04', 'мангал5.jpg', '1111'),
(581, 3, 'Сварщик', 'Алексей', 'сварка забора', 'прихвачено', '2026-06-01 11:39:11', 'IMG_20260523_070330734.jpg', '3333'),
(582, 3, 'Сварщик', 'Алексей', 'сварка мангала 6', 'начато', '2026-06-01 11:41:57', 'IMG_20260527_133236.jpg', '3333');

-- --------------------------------------------------------

--
-- Структура таблицы `rashod`
--

CREATE TABLE `rashod` (
  `id` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `kup` varchar(50) DEFAULT NULL,
  `izras` varchar(50) DEFAULT NULL,
  `ost` varchar(50) DEFAULT NULL,
  `prise` varchar(50) DEFAULT NULL,
  `itogo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `rashod`
--

INSERT INTO `rashod` (`id`, `date`, `name`, `kup`, `izras`, `ost`, `prise`, `itogo`) VALUES
(1, '2023-08-01 17:31:44', 'Электроды', '2 кг', '0.5 кг', '1.5 кг', '500 руб/кг', 1000),
(2, '2023-08-01 17:32:53', 'Труба профильная', '5 м', '1 м', '4 м', '100 руб/м', 500),
(4, '2023-08-03 16:53:40', 'Труба круглая', '5 м', '0', '5 м', '500 руб/м', 2500),
(6, '2023-08-04 09:55:20', 'Краска металлическая', '1000 шт', '1000 шт', '0', '5 руб/шт', 5000),
(7, '2023-08-16', 'Краска-грунт', '1.5 л', '0.5 л', '1 л', '200 руб/л', 220),
(8, '2026-05-20 22:36:14', 'Диски на болгарку', '100 шт', '10', '90', '20 руб/шт', 2000);

-- --------------------------------------------------------

--
-- Структура таблицы `reshetki`
--

CREATE TABLE `reshetki` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) NOT NULL DEFAULT '1200 ??',
  `Shirina` varchar(20) NOT NULL DEFAULT '20 ??',
  `Visota` varchar(20) NOT NULL DEFAULT '1500 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reshetki`
--

INSERT INTO `reshetki` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Решётка_1', 'Решётка_1.png', '1200 мм', '20 мм', '1500 мм', 15000),
(2, 'Решётка_2', 'Решётка_2.png', '1200 мм', '20 мм', '1500 мм', 10000),
(3, 'Решётка_3', 'Решётка_3.png', '1200 мм', '20 мм', '1500 мм', 12000),
(4, 'Решётка_4', 'Решётка_4.png', '1200 мм', '20 мм', '1500 мм', 15000),
(5, 'Решётка_5', 'Решётка_5.png', '1200 мм', '20 мм', '1500 мм', 14000),
(6, 'Решётка_6', 'Решётка_6.png', '1200 мм', '20 мм', '1500 мм', 12000),
(7, 'Решётка_7', 'Решётка_7.png', '1200 мм', '20 мм', '1500 мм', 13000),
(8, 'Решётка_8', 'Решётка_8.png', '1200 мм', '20 мм', '1500 мм', 13500),
(9, 'Решётка_9', 'Решётка_9.png', '1200 мм', '20 мм', '1500 мм', 14000),
(10, 'Решётка_10', 'Решётка_10.png', '1200 мм', '20 мм', '1500 мм', 15000);

-- --------------------------------------------------------

--
-- Структура таблицы `vorota`
--

CREATE TABLE `vorota` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) NOT NULL DEFAULT '4000 ??',
  `Shirina` varchar(20) NOT NULL DEFAULT '50 ??',
  `Visota` varchar(20) NOT NULL DEFAULT '2000 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `vorota`
--

INSERT INTO `vorota` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Ворота1', 'Ворота1.png', '4000 мм', '50 мм', '2000 мм', 55000),
(2, 'Ворота2', 'Ворота2.png', '4000 мм', '50 мм', '2000 мм', 100000),
(3, 'Ворота3', 'Ворота3.png', '4000 мм', '50 мм', '2000 мм', 120000),
(4, 'Ворота4', 'Ворота4.png', '4000 мм', '50 мм', '2000 мм', 150000),
(5, 'Ворота5', 'Ворота5.png', '4000 мм', '50 мм', '2000 мм', 140000),
(6, 'Ворота6', 'Ворота6.png', '4000 мм', '50 мм', '2000 мм', 125000),
(7, 'Ворота7', 'Ворота7.png', '4000 мм', '50 мм', '2000 мм', 130000),
(8, 'Ворота8', 'Ворота8.png', '4000 мм', '50 мм', '2000 мм', 135000);

-- --------------------------------------------------------

--
-- Структура таблицы `workes`
--

CREATE TABLE `workes` (
  `id` int(11) NOT NULL,
  `spec` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `data` varchar(50) DEFAULT NULL,
  `proch` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `workes`
--

INSERT INTO `workes` (`id`, `spec`, `name`, `tel`, `email`, `adres`, `data`, `proch`) VALUES
(1, 'Админ', 'Александр', '+79045081752', 'sanekkorolew@gmail.com', 'Заводская 30', NULL, ''),
(2, 'Сварщик', 'Алексей', ' +79064224577', 'sanekkorolew@gmail.com', 'Заводская 30', NULL, ''),
(3, 'Дизайнер', 'Светлана', '+790889000990', 'sнкеdj@dggh.sdg', 'Заводская 30', '20 июня ', ''),
(7, 'Водитель', 'Борис', '+790889000990', 'werеdj@gh.sg', 'Заводская 39', '23 июня ', ''),
(8, 'Маляр', 'Надежда', '+7908670009', 'wrеdj@gоh.sg', 'Заводская 87', '29 июня ', '');

-- --------------------------------------------------------

--
-- Структура таблицы `zabor`
--

CREATE TABLE `zabor` (
  `id` int(11) NOT NULL,
  `izdelie` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) NOT NULL DEFAULT '2500 ??',
  `Shirina` varchar(20) NOT NULL DEFAULT '20 ??',
  `Visota` varchar(20) NOT NULL DEFAULT '1500 ??',
  `Prise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `zabor`
--

INSERT INTO `zabor` (`id`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES
(1, 'Забор_1', 'забор_1.png', '2500 мм', '20 мм', '1500 мм', 23000),
(2, 'Забор_2', 'забор_2.png', '2500 мм', '20 мм', '1500 мм', 20000),
(3, 'Забор_3', 'забор_3.png', '2500 мм', '20 мм', '1500 мм', 12000),
(4, 'Забор_4', 'забор_4.png', '2500 мм', '20 мм', '1500 мм', 15000),
(5, 'Забор_5', 'забор_5.png', '2500 мм', '20 мм', '1500 мм', 20000),
(6, 'Забор_6', 'забор_6.png', '2500 мм', '20 мм', '1500 мм', 25000);

-- --------------------------------------------------------

--
-- Структура таблицы `zakaz`
--

CREATE TABLE `zakaz` (
  `Id` int(11) NOT NULL,
  `date` varchar(20) DEFAULT NULL,
  `izdelie` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `Dlina` varchar(20) DEFAULT NULL,
  `Shirina` varchar(20) DEFAULT NULL,
  `Visota` varchar(20) DEFAULT NULL,
  `Prise` int(11) DEFAULT NULL,
  `Pay` int(11) DEFAULT 0,
  `Proces` text DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Tel` varchar(30) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Coment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `zakaz`
--

INSERT INTO `zakaz` (`Id`, `date`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`, `Pay`, `Proces`, `Name`, `Tel`, `Email`, `Coment`) VALUES
(72, '2026-05-19 10:44:25', 'мангал1', 'мангал1.jpg', '700 мм', '350 мм', '700 мм', 5000, 3500, '', 'Александр', '+79045081752', 'sanek@gm.com', ''),
(73, '2026-05-19 11:27:46', 'мангал2', 'мангал2.jpg', '700 мм', '350 мм', '700 мм', 10000, 1000, '', 'Леха', '+79000342441', 'srfwe@sdf.we', ''),
(74, '2026-05-19 11:36:03', 'мангал1', 'мангал1.jpg', '700 мм', '350 мм', '700 мм', 5000, 5000, '', 'Маша', '+7908765544', 'werwe@we.wr', ''),
(75, '2026-05-19 11:41:04', 'мангал2', 'мангал2.jpg', '700 мм', '350 мм', '700 мм', 10000, 10000, '', 'Саша', '+7323423433', 'asdasdas@fdf.sdf', ''),
(77, '2026-05-20 12:06:09', 'Лавочка_2', 'Лавочка_2.png', '1500 мм', '550 мм', '850 мм', 5000, 3000, '', 'Маша', '+7800098880', 'kjolki@df.ty', ''),
(78, '2026-05-20 12:06:09', 'Лавочка_3', 'Лавочка_3.png', '1500 мм', '550 мм', '850 мм', 5000, 3000, '', 'Маша', '+7800098880', 'kjolki@df.ty', ''),
(81, '2026-05-21 19:45:16', 'мангал1', 'мангал1.jpg', '700 мм', '350 мм', '700 мм', 5000, 2000, NULL, 'Александр', '+79045081752', 'qwq@dsd.sd', ''),
(82, '2026-05-21 19:48:06', 'Подставка_для_ёлки_1', 'Подставка_для_ёлки_1.png', '', '', '', 1500, 1000, NULL, 'Лиза', '+79001316418', 'qws@as.sd', ''),
(83, '2026-05-21 19:48:06', 'Подставка_для_ёлки_1', 'Подставка_для_ёлки_1.png', '', '', '', 1500, 1000, NULL, 'Олег', '+79001316418', 'qws@as.sd', ''),
(84, '2026-05-21 19:53:45', 'Забор_2', 'забор_2.png', '2500 мм', '20 мм', '1500 мм', 10000, 0, '', 'Лёха', '+79064567890', 'fhgkjn@sad.sd', ''),
(85, '2026-05-23 21:39:20', 'Забор_5', 'забор_5.png', '2500 мм', '20 мм', '1500 мм', 20000, 0, '', 'Александр Викторович', '+79001316418', 'sanekkorolew@gmail.com', ''),
(86, '2026-05-23 21:41:12', 'Подставка_для_ёлки_1', 'Подставка_для_ёлки_1.png', '', '', '', 1000, 0, NULL, 'Александр Викторович', '+79001316418', 'sanekkorolew@gmail.com', ''),
(87, '2026-05-23 21:42:30', 'Лавочка_3', 'Лавочка_3.png', '1500 мм', '550 мм', '850 мм', 10000, 0, NULL, 'Александр', '+79876789056', 'dojhre@mail.ru', ''),
(88, '2026-05-26 12:08:19', 'Ворота2', 'Ворота2.png', '4000 мм', '50 мм', '2000 мм', 100000, 0, NULL, 'Алекс', '+78900988909', 'qwert@sd.sd', ''),
(90, '2026-05-29 08:01:56', 'мангал1', 'мангал1.jpg', '700 мм', '350 мм', '700 мм', 15000, 0, NULL, 'Алекс', '+79001316418', 'qwer@qw.er', '');

-- --------------------------------------------------------

--
-- Структура таблицы `zp`
--

CREATE TABLE `zp` (
  `id` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `spec` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `nachis` int(11) DEFAULT NULL,
  `poluch` int(11) DEFAULT NULL,
  `names` varchar(50) DEFAULT 'Зарплата'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `zp`
--

INSERT INTO `zp` (`id`, `date`, `spec`, `name`, `nachis`, `poluch`, `names`) VALUES
(1, '2023-08-01 18:13:02', 'Админ', 'Алесандр', 50000, 50000, 'Начисление заработной платы'),
(2, '2023-08-01 18:13:47', 'Слесарь', 'Максим', 100000, 50000, 'Начисление заработной платы'),
(5, '2023-08-03 16:18:40', 'Дизайнер', 'Светлана', 200000, 200000, 'Начисление заработной платы'),
(6, '2023-08-03 16:23:29', 'Водитель', 'Борис', 30000, 30000, 'Начисление заработной платы'),
(7, '2023-08-03 16:24:09', 'Маляр', 'Надежда', 50000, 40000, 'Начисление заработной платы'),
(11, '2026-05-18 16:10:04', 'Сварщик', 'Алексей', 150000, 150000, 'Начисление заработной платы'),
(12, '2026-05-18 16:30:17', 'грузчик', 'Миша', 25000, 25000, 'Зарплата');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cod`
--
ALTER TABLE `cod`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dostup`
--
ALTER TABLE `dostup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cod` (`cod`);

--
-- Индексы таблицы `fin`
--
ALTER TABLE `fin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kozirek`
--
ALTER TABLE `kozirek`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lavo4ki`
--
ALTER TABLE `lavo4ki`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mangal`
--
ALTER TABLE `mangal`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `mater`
--
ALTER TABLE `mater`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mebel`
--
ALTER TABLE `mebel`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `melo4i`
--
ALTER TABLE `melo4i`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ogradki`
--
ALTER TABLE `ogradki`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `otchet`
--
ALTER TABLE `otchet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otchet_ibfk_1` (`cod`);

--
-- Индексы таблицы `rashod`
--
ALTER TABLE `rashod`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reshetki`
--
ALTER TABLE `reshetki`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `vorota`
--
ALTER TABLE `vorota`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `workes`
--
ALTER TABLE `workes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `zabor`
--
ALTER TABLE `zabor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `izdelie` (`izdelie`);

--
-- Индексы таблицы `zakaz`
--
ALTER TABLE `zakaz`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `zp`
--
ALTER TABLE `zp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cod`
--
ALTER TABLE `cod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `dostup`
--
ALTER TABLE `dostup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `fin`
--
ALTER TABLE `fin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `kozirek`
--
ALTER TABLE `kozirek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `lavo4ki`
--
ALTER TABLE `lavo4ki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `mangal`
--
ALTER TABLE `mangal`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `mater`
--
ALTER TABLE `mater`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `mebel`
--
ALTER TABLE `mebel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `melo4i`
--
ALTER TABLE `melo4i`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `ogradki`
--
ALTER TABLE `ogradki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `otchet`
--
ALTER TABLE `otchet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=583;

--
-- AUTO_INCREMENT для таблицы `rashod`
--
ALTER TABLE `rashod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `reshetki`
--
ALTER TABLE `reshetki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `vorota`
--
ALTER TABLE `vorota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `workes`
--
ALTER TABLE `workes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `zabor`
--
ALTER TABLE `zabor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `zakaz`
--
ALTER TABLE `zakaz`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT для таблицы `zp`
--
ALTER TABLE `zp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `otchet`
--
ALTER TABLE `otchet`
  ADD CONSTRAINT `otchet_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `dostup` (`cod`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
