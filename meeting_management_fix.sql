-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2025 pada 16.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_management`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descriptions` text DEFAULT NULL,
  `dates` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `locations` varchar(255) DEFAULT NULL,
  `leader` varchar(100) DEFAULT NULL,
  `status_meetings` enum('upcoming','ongoing','completed') DEFAULT 'upcoming',
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `meetings`
--

INSERT INTO `meetings` (`id`, `title`, `descriptions`, `dates`, `start_time`, `end_time`, `locations`, `leader`, `status_meetings`, `created_by`, `created_at`) VALUES
(3, 'rapat umum', 'rapat umum', '2025-11-23', '20:25:00', '22:27:00', 'Ruang 704', 'jaki', '', 'Admin', '2025-11-23 07:25:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `meetings_participant`
--

CREATE TABLE `meetings_participant` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `participant_id` int(11) NOT NULL,
  `attendance_status` enum('accepted','declined','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `meetings_participant`
--

INSERT INTO `meetings_participant` (`id`, `meeting_id`, `participant_id`, `attendance_status`) VALUES
(2, 3, 5, 'pending'),
(3, 3, 6, 'accepted');

-- --------------------------------------------------------

--
-- Struktur dari tabel `minutes`
--

CREATE TABLE `minutes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `agenda` text DEFAULT NULL,
  `discussion` text DEFAULT NULL,
  `decisions` text DEFAULT NULL,
  `follow_up` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('draft','review','completed') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `minutes`
--

INSERT INTO `minutes` (`id`, `title`, `agenda`, `discussion`, `decisions`, `follow_up`, `notes`, `status`, `created_at`, `created_by`) VALUES
(1, 'Notulensi Rapat IT', 'untuk mengetahui ansdklawd', NULL, 'adawdsdwwadsdw', 'dadsdwaswad', 'dawdasdawdaddwaawd', 'draft', '2025-11-23 14:43:10', 'Zaky Fajar Permana');

-- --------------------------------------------------------

--
-- Struktur dari tabel `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif','pending') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `participant`
--

INSERT INTO `participant` (`id`, `name`, `email`, `department`, `position`, `phone`, `status`, `created_at`) VALUES
(5, 'Zaky Fajar Permana', '192003ZakyFajar@gmail.com', 'it', 'Front End Developer', '1234566', 'aktif', '2025-11-23 08:46:46'),
(6, 'afung', 'afung@mail.com', 'marketing', 'menengah', '08219334381', 'aktif', '2025-12-03 14:35:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','','') NOT NULL,
  `participant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `participant_id`) VALUES
(4, 'admin', 'admin@mail.com', '$2y$10$g9r/aVFk3kRZvEGL9/LpZ.bHsomgMThUb1whBxiClRqmY8iRperJm', 'admin', NULL),
(7, 'ojan', 'ojan1@gmail.com', '$2y$10$QbUAhn8qKTPMyFLJYAnMh.s4AAHoFch.RThyFZLowh9MnoUyXZUxW', 'user', 5),
(9, 'afung', 'afung@mail.com', '$2y$10$Z.TcBHNEz0FxDqyD5CaJu.Tjgp3Q6btubss2zwRvwugKNvgLQDFsm', 'user', 6);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `meetings_participant`
--
ALTER TABLE `meetings_participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indeks untuk tabel `minutes`
--
ALTER TABLE `minutes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `participant_id` (`participant_id`),
  ADD UNIQUE KEY `participant_id_2` (`participant_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `meetings_participant`
--
ALTER TABLE `meetings_participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `minutes`
--
ALTER TABLE `minutes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `meetings_participant`
--
ALTER TABLE `meetings_participant`
  ADD CONSTRAINT `fk_participant_meeting` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meetings_participant_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_participant` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
