-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2024 a las 11:45:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ddb239154`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemons`
--

CREATE TABLE `pokemons` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `imatge` varchar(255) NOT NULL,
  `descripció` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `pokemons`
--

INSERT INTO `pokemons` (`id`, `nom`, `imatge`, `descripció`, `usuario_id`) VALUES
(91, 'Flareon', 'flareon.jpg', 'Evolución ígnea de Eevee', 1),
(92, 'Mew', 'mew.jpg', 'Pokémon místico ancestral', 1),
(87, 'Lapras', 'lapras.jpg', 'Amable transportador marino que canta melodías', 1),
(90, 'Vaporeon', 'vaporeon.jpg', 'Evolución acuática de Eevee', 1),
(89, 'Articuno', 'articuno.jpg', 'Ave legendaria de hielo', 1),
(88, 'Jolteon', 'jolteon.jpg', 'Evolución eléctrica de Eevee', 1),
(85, 'Alakazam', 'alakazam.jpg', 'Pokémon psíquico de gran inteligencia', 1),
(86, 'Arcanine', 'arcanine.jpg', 'Majestuoso pokémon canino de fuego', 1),
(84, 'Gengar', 'gengar.jpg', 'Pokémon fantasma que acecha en las sombras', 1),
(83, 'Snorlax', 'snorlax.jpg', 'Pokémon dormilón que bloquea caminos', 1),
(80, 'Mewtwo', 'mewtwo.jpg', 'Pokémon legendario creado genéticamente', 1),
(81, 'Dragonite', 'dragonite.jpg', 'Dragón amistoso que vive en el océano', 1),
(82, 'Gyarados', 'gyarados.jpg', 'Feroz pokémon marino evolucionado de Magikarp', 1),
(76, 'Pikachu', 'pikachu.jpg', 'Ratón eléctrico que almacena electricidad en sus mejillas', 1),
(74, 'Hola', 'SV02_ES_63.png', 'Hola', 18),
(77, 'Charizard', 'charizard.jpg', 'Dragón de fuego que vuela por los cielos', 1),
(78, 'Bulbasaur', 'bulbasaur.jpg', 'Pokémon tipo planta con una semilla en su espalda', 1),
(79, 'Squirtle', 'squirtle.jpg', 'Pequeña tortuga que dispara agua a presión', 1),
(75, 'Pokemon 1', 'SV02_ES_63.png', 'Pickatchu', 24),
(93, 'Machamp', 'machamp.jpg', 'Pokémon luchador de cuatro brazos', 1),
(94, 'Golem', 'golem.jpg', 'Pokémon roca que rueda como una bola', 1),
(95, 'Ninetales', 'ninetales.jpg', 'Zorro místico con nueve colas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `expiry`) VALUES
(1, 18, '4057192b936443915543070cd9cac4ab', '2024-12-27 19:28:19'),
(2, 18, '13824285a884c979db8d52245c73119a', '2024-12-27 19:31:00'),
(3, 18, '20ea863d8aa12db4e2fbcd476570241b', '2024-12-27 19:32:06'),
(4, 18, 'c346a14fa633fbeefc24b25d33a22264', '2024-12-27 19:33:18'),
(5, 18, 'b3aee7305ec6b3e8c35a58def17ded7d', '2024-12-27 19:40:08'),
(6, 18, 'fa6e1d59598851c3eade3b2a83dd4e17', '2024-12-27 19:40:25'),
(7, 18, 'e3afaab4467918cb1329945ccd588772', '2024-11-27 19:48:40'),
(8, 18, '0f1f6f4dc38a98fe5005efe1e25ce1c1', '2024-12-28 15:52:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.jpg'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nom`, `email`, `password`, `rol`, `imagen`) VALUES
(22, 'Xavi', 'xavi@gmail.com', '$2y$10$quECAWAVsPHz5YSAhidAoemzxNQf4cTyCP5CYiPEGJduhAFEl67gu', '', '3279462667fb3498a6aa144e7cdea2ae.gif'),
(23, 'Marcos', 'marcos@gmail.com', '$2y$10$X7wnvEh7oisrraS1.4ZW6eyAQcmdN/nUW8n7l7muYtSOqbT9gvGxy', '', 'default.jpg'),
(24, 'Marrkitus04', 'admin@gmail.com', '$2y$10$eITA5ddDKH/isdjENUvD/uj/dXRtEd3qRAZxOXZr6QnkExphxQeo2', '', 'default.jpg'),
(1, 'admin', 'admin@admin.com', '$2y$10$xpOQRkCs42zoU450I7RMnetR/7YTGL5SD4lCYXuXjDdLSrBYxatLe', 'admin', '3279462667fb3498a6aa144e7cdea2ae.gif');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pokemons`
--
ALTER TABLE `pokemons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pokemons`
--
ALTER TABLE `pokemons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
