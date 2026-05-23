-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 04:23 AM
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
-- Database: `rey_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', '$2y$10$e.w1sP84b3q5J2l6n3n.1e2.85t2m9iG/Ue8568/U2Z475681q.62', 'Reynaldo Estandarte Jr.');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `issued_by` varchar(150) DEFAULT NULL,
  `issue_month` varchar(50) DEFAULT NULL,
  `issue_year` varchar(4) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `tech_stack` varchar(255) DEFAULT NULL,
  `project_link` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `project_date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `image`, `category`, `tech_stack`, `project_link`, `client`, `project_date`) VALUES
(1, 'ANVIL Veterinary Clinic Management System UI/UX Prototype', 'Designed a complete UI/UX prototype for a veterinary clinic management system using Figma. The prototype includes user flows and interfaces for dashboard analytics, customer management, pet profiles, medical records, inventory management, billing, sales tracking, reports, and receipt generation. The design focused on creating an organized, user-friendly, and efficient experience for clinic staff and administrators.', 'main_6a110df015068.png', 'Portfolio', 'Figma, UI Design, UX Design, Wireframing, Prototyping', 'https://www.figma.com/proto/VNXjk9z8NwxzhU6BtdPaJl/Dekstop-Version?node-id=10-638&p=f&t=6rjUgd38R06RnUjy-0&scaling=scale-down&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=10%3A638', 'Academic Project / System Prototype', '');

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_images`
--

INSERT INTO `project_images` (`id`, `project_id`, `image_path`) VALUES
(1, 1, 'add_6a110df018998.png'),
(2, 1, 'add_6a110df018c49.png'),
(3, 1, 'add_6a110df018f06.png'),
(4, 1, 'add_6a110df02e899.png'),
(5, 1, 'add_6a110df02ec34.png'),
(6, 1, 'add_6a110df02eef0.png'),
(7, 1, 'add_6a110df02f0a9.png'),
(8, 1, 'add_6a110df02f264.png'),
(9, 1, 'add_6a110df02f4a9.png'),
(10, 1, 'add_6a110df02f6b0.png'),
(11, 1, 'add_6a110df02f8ac.png'),
(12, 1, 'add_6a110df02faf5.png'),
(13, 1, 'add_6a110df02fd54.png'),
(14, 1, 'add_6a110df03015d.png'),
(15, 1, 'add_6a110df030523.png'),
(16, 1, 'add_6a110df030733.png'),
(17, 1, 'add_6a110df030930.png'),
(18, 1, 'add_6a110df030b22.png'),
(19, 1, 'add_6a110df030d39.png');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `icon_image` varchar(255) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `icon`, `icon_image`, `title`, `description`) VALUES
(1, 'bi-keyboard', NULL, 'Data Entry', 'Accurate and efficient deal entry and data management services.'),
(2, 'bi-headset', NULL, 'Customer Service', 'Providing direct service to guests, resolving inquiries, and ensuring satisfaction.'),
(3, 'bi-file-earmark-text', NULL, 'Administrative Support', 'Managing tasks efficiently and maintaining accurate records for streamlined operations.');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(100) NOT NULL,
  `hero_title` varchar(255) NOT NULL,
  `hero_tagline` text NOT NULL,
  `about_text` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `linkedin_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `hero_title`, `hero_tagline`, `about_text`, `email`, `phone`, `location`, `linkedin_url`) VALUES
(1, 'ReyEstandarte', 'Virtual Assistant', 'Motivated Virtual Assistant delivering reliable support and quality results in fast-paced environments.', 'Motivated and detail-oriented Virtual Assistant with experience in data entry, administrative support, and customer service. Skilled in managing tasks efficiently, maintaining accurate records, and communicating professionally with clients and team members. Possesses strong adaptability, time management, and teamwork skills developed through both academic and work experiences.', 'reynaldoestandarte8@gmail.com', '+639127251505', 'Purok Aries, Lituan Lasang Davao City, Davao Del Sur', 'https://www.linkedin.com/in/reynaldo-estandarte-801120409/');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `icon_image` varchar(255) DEFAULT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `level`, `icon_image`, `icon_class`, `created_at`) VALUES
(1, 'HTML', 95, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg', '2026-05-23 01:19:55'),
(2, 'CSS', 90, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg', '2026-05-23 01:19:55'),
(3, 'Bootstrap 5', 90, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg', '2026-05-23 01:19:55'),
(4, 'System Analyst', 85, NULL, 'bi bi-diagram-3-fill', '2026-05-23 01:19:55'),
(5, 'Docs', 85, NULL, 'bi bi-file-earmark-text-fill', '2026-05-23 01:19:56'),
(6, 'Figma', 80, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg', '2026-05-23 01:19:56'),
(7, 'PHP', 80, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg', '2026-05-23 01:19:56'),
(8, 'JavaScript', 85, NULL, 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg', '2026-05-23 01:19:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
