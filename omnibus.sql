-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2026 at 07:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnibus`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `media_id`, `action_type`, `created_at`) VALUES
(1, 1, 4, 'completed', '2026-01-28 18:08:20'),
(2, 1, 4, 'want', '2026-01-28 18:11:46'),
(3, 1, 5, 'completed', '2026-01-28 18:13:20'),
(4, 1, 2, 'completed', '2026-02-04 17:14:26'),
(5, 1, 5, 'completed', '2026-02-04 17:14:52'),
(6, 1, 4, 'want', '2026-02-04 17:27:10'),
(7, 1, 5, 'completed', '2026-02-04 17:27:23'),
(8, 1, 1, 'wrote a review', '2026-02-04 17:32:17'),
(9, 1, 2, 'completed', '2026-02-04 17:47:18'),
(10, 1, 4, 'want', '2026-02-04 17:54:35'),
(11, 1, 6, 'completed', '2026-02-04 18:00:20'),
(12, 1, 7, 'completed', '2026-02-04 18:02:55'),
(13, 1, 5, 'completed', '2026-02-04 18:13:16'),
(14, 1, 1, 'completed', '2026-02-04 18:13:47'),
(15, 1, 3, 'completed', '2026-02-04 18:14:12'),
(16, 1, 4, 'want', '2026-02-09 17:08:25'),
(17, 1, 5, 'completed', '2026-02-09 17:31:46'),
(18, 1, 4, 'want', '2026-02-09 17:32:01'),
(19, 1, 7, 'completed', '2026-02-09 17:33:45'),
(20, 1, 2, 'completed', '2026-02-09 17:37:16'),
(21, 1, 2, 'completed', '2026-02-09 17:37:19'),
(22, 1, 5, 'completed', '2026-02-09 17:40:57'),
(23, 1, 2, 'completed', '2026-02-09 17:44:27'),
(24, 1, 6, 'completed', '2026-02-09 17:44:36'),
(25, 1, 2, 'completed', '2026-02-09 17:44:44'),
(26, 1, 2, 'completed', '2026-02-09 17:44:51'),
(27, 1, 4, 'want', '2026-02-09 17:50:25'),
(28, 1, 8, 'completed', '2026-02-09 17:57:10'),
(29, 1, 8, 'completed', '2026-02-09 17:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `type` enum('book','movie','show') NOT NULL,
  `title` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `release_year` int(11) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `episodes_total` int(11) DEFAULT NULL,
  `seasons_total` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `type`, `title`, `creator`, `release_year`, `cover_image`, `description`, `duration_minutes`, `pages`, `episodes_total`, `seasons_total`, `created_at`, `created_by`) VALUES
(1, 'movie', 'The Dark Knight', 'Christopher Nolan', 2008, 'https://www.themoviedb.org/t/p/original/pKKvCaL1TPTVtbI6EeliyND3api.jpg', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham...', NULL, NULL, NULL, NULL, '2026-01-28 17:43:59', NULL),
(2, 'book', 'Project Hail Mary', 'Andy Weir', 2021, 'https://tse3.mm.bing.net/th/id/OIP.PkeHIkVrp9MqMfRP3eQPYwHaLL?cb=defcache2defcache=1&rs=1&pid=ImgDetMain&o=7&rm=3', 'Ryland Grace is the sole survivor on a desperate, last-chance mission...', NULL, NULL, NULL, NULL, '2026-01-28 17:43:59', NULL),
(3, 'show', 'The Last of Us', 'Craig Mazin', 2023, 'https://assets.mycast.io/posters/the-last-of-us-90s-fan-casting-poster-280569-medium.jpg?1673982736', 'After a global pandemic destroys civilization, a hardened survivor takes charge of a 14-year-old girl.', NULL, NULL, NULL, NULL, '2026-01-28 17:43:59', NULL),
(4, 'movie', 'Project Hail Mary', 'Andy Weir', 2026, 'https://reelreviews.com/images/stories/trailers/project-hail-mary-poster.jpg', 'Ryland Grace is the sole survivor on a desperate, last-chance mission - and if he fails, humanity will perish!', NULL, NULL, NULL, NULL, '2026-01-28 18:06:41', NULL),
(5, 'show', 'Invincible', 'Robert Kirkman', 2019, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2023/07/invincible-franchise-poster.jpg', 'Son of a superhero by the name Omni Man has to live up to the title of Invincible.', NULL, NULL, NULL, NULL, '2026-01-28 18:13:10', NULL),
(6, 'movie', 'Lord of The Rings: Fellowship of the Ring', 'Peter Jackson, J.R.R Tolkien', 2001, 'https://static.miraheze.org/allthetropeswiki/thumb/4/44/Lord_of_the_rings_the_fellowship_of.jpg/350px-Lord_of_the_rings_the_fellowship_of.jpg', 'A young Frodo Baggins is gifted the ring of power by his uncle. He has to destroy it in order to kill a bigger evil.', NULL, NULL, NULL, NULL, '2026-02-04 17:59:40', NULL),
(7, 'movie', 'The Thing', 'John Carpenter', 1982, 'https://alchetron.com/cdn/The-Thing-1982-film-images-fa3539fc-7eb1-42cf-830b-fe05feabd89.jpg', 'A research team in Antarctica is hunted by a shape-shifting alien that assumes the appearance of its victims.\r\nA US research station, Antarctica, early-winter 1982. The base is suddenly buzzed by a helicopter from the nearby Norwegian research station. They are trying to kill a dog that has escaped from their base. After the destruction of the Norwegian chopper the members of the US team fly to the Norwegian base, only to discover them all dead or missing. They do find the remains of a strange creature the Norwegians burned. The Americans take it to their base and deduce that it is an alien life form. After a while it is apparent that the alien can take over and assimilate into other life forms, including humans, and can spread like a virus. This means that anyone at the base could be inhabited by The Thing, and tensions escalate.', NULL, NULL, NULL, NULL, '2026-02-04 18:02:51', NULL),
(8, 'movie', 'Interstelalr', 'Christopher Nolan', 2014, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwLxpRYgsPOliVFNFWURt16sM3GS0boxruFvrsOCGPVJDyzefdlol78fgU9J9icT0Vw2os&s=10', 'In the near future around the American Midwest, Cooper, an ex-science engineer and pilot, is tied to his farming land with his daughter Murph and son Tom. As devastating sandstorms ravage Earth&#039;s crops, the people of Earth realize their life here is coming to an end as food begins to run …', NULL, NULL, NULL, NULL, '2026-02-09 17:57:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `media_id`, `content`, `created_at`) VALUES
(1, 1, 1, 'It&#039;s alright.', '2026-02-04 18:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `avatar_url`, `bio`, `created_at`, `is_admin`) VALUES
(1, 'geatzeneli', 'geatzeneli03@gmail.com', '$2y$10$flVOHwaDUgR.YHYAeKjA9em4st947113xavrx0aGBuXKdTppE/PkC', 'https://www.picclickimg.com/KdMAAOxyFjNSRYml/Clint-Eastwood-As-Joe-Lee-Van-Cleef-As.webp', 'The admin.', '2026-01-28 17:34:39', 1),
(5, 'geatii', 'geatzeneli067@gmail.com', '$2y$10$S.iq9k5lMf2Qyh6KWVk1y.euJK8YNO5N8.xbhW7PWElNtKMY4iD0W', NULL, NULL, '2026-02-09 17:58:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_library`
--

CREATE TABLE `user_library` (
  `user_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `status` enum('want','consuming','completed','dropped') NOT NULL,
  `progress` int(11) DEFAULT 0,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `is_favorite` tinyint(1) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_library`
--

INSERT INTO `user_library` (`user_id`, `media_id`, `status`, `progress`, `rating`, `is_favorite`, `last_updated`) VALUES
(1, 1, 'completed', 0, 3, 0, '2026-02-04 18:13:47'),
(1, 2, 'completed', 0, 5, 1, '2026-02-09 17:44:51'),
(1, 3, 'completed', 0, 3, 0, '2026-02-04 18:14:12'),
(1, 4, 'want', 0, NULL, 0, '2026-02-09 17:50:25'),
(1, 5, 'completed', 0, 5, 1, '2026-02-09 17:40:57'),
(1, 6, 'completed', 0, 5, 1, '2026-02-09 17:44:36'),
(1, 7, 'completed', 0, 5, 1, '2026-02-09 17:33:45'),
(1, 8, 'completed', 0, 5, 1, '2026-02-09 17:57:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_library`
--
ALTER TABLE `user_library`
  ADD PRIMARY KEY (`user_id`,`media_id`),
  ADD KEY `media_id` (`media_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_library`
--
ALTER TABLE `user_library`
  ADD CONSTRAINT `user_library_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_library_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
