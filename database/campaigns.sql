-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 23 Feb 2018 pada 07.59
-- Versi Server: 5.6.16
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bmh`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','pending','finish') NOT NULL DEFAULT 'pending',
  `token_id` varchar(255) NOT NULL,
  `goal` int(11) UNSIGNED NOT NULL,
  `location` varchar(200) NOT NULL,
  `finalized` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No 1 Yes',
  `categories_id` int(10) UNSIGNED NOT NULL,
  `province_id` int(11) UNSIGNED NOT NULL,
  `city_id` int(11) UNSIGNED NOT NULL,
  `cabang_id` int(10) UNSIGNED NOT NULL,
  `akun_transaksi_id` int(11) UNSIGNED NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `campaigns`
--

INSERT INTO `campaigns` (`id`, `small_image`, `large_image`, `title`, `description`, `user_id`, `date`, `status`, `token_id`, `goal`, `location`, `finalized`, `categories_id`, `province_id`, `city_id`, `cabang_id`, `akun_transaksi_id`, `featured`, `deadline`, `tags`) VALUES
(2, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test', 'test', 1, '2017-12-27 17:01:27', 'active', '1', 10000, 'Jakarta', '1', 18, 32, 3216, 0, 0, '0', '0000-00-00 00:00:00', ''),
(3, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test 2', 'test 2', 1, '2017-12-27 17:01:27', 'active', '3', 10000, 'Jakarta', '1', 18, 32, 3216, 0, 0, '0', '0000-00-00 00:00:00', ''),
(4, '21514389896yaoskfv6hcscremxj3gel83kcverev2s8rbhdicm.jpg', '../small/1151439114265gjbwetzyqqz1lcp50zyi6dlnltxhqanpdisfor.png', 'test 3', 'test 3', 1, '2017-12-27 17:01:27', 'active', '4', 10000, 'Jakarta', '1', 18, 32, 3216, 0, 0, '0', '0000-00-00 00:00:00', ''),
(5, '11514422137dmruw869mshioutx0lctks0bdtrcee6f8ouuxp8c.jpg', '11514422137sfqvg354anszzsa7xgjolau2burab7a1dvw1nzwq.jpg', 'Pembanguan Masjid Perumnas 3', 'Akan dibangun masjid di perumnas 3', 1, '2017-12-28 00:48:57', 'active', 'lq7OwKgquyGXBl3L7VZIda9dzuJHrsvNP7AjyNdG5luB86B306L7l2wwW7UQEeJjSOQM7v0rcbM1l3xyklqIsHUv6U6wIR9HooDhA1Pra7TpnoQBnxGr3SbljTp6gra390xOBMEdTKRVzQMhYK6wvDTGEkpXexK52jxdekq9rwdVx8dVxehUgLmcR6Uuy7J6mYuF4tEK', 10000, 'Bekasi timur', '1', 21, 32, 3216, 0, 0, '1', '2018-02-19 17:00:00', ''),
(6, '31518642964gfcgbsphldfxnmw0nthga7ggx5ageqzvidg9l7ei.jpg', '31518642964ugt33k3cfnblk5bhgoz8tcswgn30pll7hrmncksv.jpg', 'Banjir Sumatra', 'Terjadi banjir bandang di sumatera selama 5 hari berturut turut. korban diperkirakan mencapai 50 juta orang', 3, '2018-02-14 14:16:04', 'active', '3UDtJoDKZ10xGcAoggpXbc7tzba3mjUj8YMQmMsTuSiTCspnH2i0j6TgAGXoEB7YaHnNDNJ65VytOGNmF6tKODB8smjrZrklkPq1PRnVcZMYE5k9KCt9mVMYk1sHo2PriFxmzVWVy3frbcA2c5pUFW2W40fsgJbL4BAs3uUZyp5damvFohUumhJ3cO3BTv0IU8OqYSkv', 10000, 'KAB. ACEH SELATAN', '0', 22, 11, 1101, 0, 0, '0', '2018-05-15 21:16:04', ''),
(7, '41518732750rprqwkhopi7b6scrjwjonekkrofvpos6pfraoj1d.jpg', '41518732750ttfzanconf1jsaoxougjqh3krjsbvjkplyaxnp4t.jpg', 'Banjir Bekasi', 'Banjir menghampiri kota bekasi selama 5 hari, menyebabkan akses jalan yang sulit dilewati', 4, '2018-02-15 15:12:30', 'active', 'RbUHc1AoIwhcFSS8KM7m7gP1iUUVZyTJOZY28Rzng9O8naDxN3dQ76W3esJ0SftQa3C5JbsJdB7IvXZZOqwNQkWqpcBKW1si5lSAJu4y5v3lR1HXz0H6yqvywhMa4hKvPRUenY4gmO7Va8Vl4BNfkk91OsEpViPIDTdpyEJake6nR5XW7kPM4pMuowrSwLPSgzoO51eU', 5000, 'KOTA BEKASI', '0', 18, 32, 3275, 0, 0, '0', '2018-03-17 22:12:30', 'Bencana Alam,Banjir'),
(8, '11518753092cea251bzyigwfdjqxfedvvlqkjaizzzqtkmvizmu.jpg', '11518753092arz3ubx8nuqir3jf0bf3vuc5v7svajzykdavt4jl.jpg', 'Bantuan Untuk Sekolah Dasar Aren Jaya', 'safadasdasdadasdasdasdsadasdasdasdasdasdasdasd', 1, '2018-02-16 03:51:32', 'active', 'hPdBNWNg5D8BSPLfYMw4iBrLX6tvnZTLjap7MCmbtb0JLYHlermOja8H042tAanvxhn81fK6IlA93lW3BdPZfwkjDOgRJZmK1Yuyrps3rsJ0eFRVAizRbbbPnW7d3TvrlefH49MnnieenvCyglIRI5t7zh6Kv5DAX9mWCuWt6REUhUAHOMvfXp9y5Teu0N5FqU9KjimA', 10000000, 'KAB. ACEH SELATAN', '1', 21, 11, 1101, 0, 0, '0', '2018-02-17 03:51:32', 'KEMANUSIAAN'),
(9, '111518773239fiw9jbjo7cyw1glrsatadmbkr2fskxoq3faxnvm8.jpg', '1115187732399tzgz5q54h7xz0112m55vgebuixkwmgxfqtqgswd.jpg', 'Anak Ini butuh bantuan', 'Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!<br /><br />Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!<br /><br />Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!<br /><br />Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!Panti Asuhan Roslin saat ini menjadi rumah dan tempat tinggal bagi 138 anak di Kupang, NTT. Help us to facilitate, educate and empower many more!', 11, '2018-02-16 09:27:21', 'active', 'jHqxKDtlXTGX4BGIXG14wYXHb0aItbPYmmsXNBQYD1DnzwN90SnNjWJq2JXzoy648BqKkwlxA6gONN9oe8sb7FX8MJEy9oaSsGCsoouDoDqvaeQGDWVy32llExbG9rwg6SJhM2nwY9C7BjO9iC4gOTZPCakbnhkjRFFKS9n7chfd49ujB0063xn1En86v2eTMD8E6g7r', 100000000, 'KAB. ACEH SELATAN', '0', 18, 11, 1101, 0, 0, '0', '2018-05-17 09:27:21', 'Sosial'),
(10, '101518880436vohmmmyt9q6ljhoyzlk33l8erhd4fy10sp1l97uz.jpg', '1015188804369gfrxvbsu07hbqoe4myww7ew60ujyguolptkztsq.jpg', 'Bantuan Fakir & Miskin', 'Untuk membantu fakir miskin', 10, '2018-02-17 15:13:57', 'active', 'YgRjDP8VCkKknNrnBY4qwbctIHL8xMMB1OVNTcptgFdXemDrpecgzDImXeypPNSr1soop0rrEkQ3rhvmyNYjz5xs1qG7gMyZLNNTCqKJtVuXaLNTURC17Fi3lKh4gzhZSJDLtsOh5itX0AruuWzJM7tTRM7rR5xKXPWt1vtB40OX8gdGITufLoUxIr5ZtjzeVH5X0hVH', 65000000, 'KOTA SABANG', '0', 18, 11, 1172, 0, 0, '0', '2018-03-19 15:13:57', ''),
(11, '11518975107fjgsd9fos36se8b2l8641votnwicrufeympcupau.jpg', '11518975107enog2jvbvpyf9t4zvpc6pchjk9tvsonyzos1ulwd.jpg', 'Test', 'ulala aja nih yak kok kamu gitu sich', 1, '2018-02-18 10:31:48', 'active', 'T1ANtIBUYnYwUYzRcuOMivjLfGAgvu5iofxrdEuPuRxydxaxoMEwceWlH6HnqOJjnCURsqnnXEKthr6Gi9j1yFZaAI1myt5a74nFT93i7uXNab0Xrdij3AXIcK0Cv4TINzlYUXgPcVkodKstLGa0umTn1OPBGiYpOtuOByIELJjwMSDFbNRveR1jDobny7MVws1LxcyY', 1000000, 'KAB. ACEH SELATAN', '0', 18, 11, 1101, 1, 0, '0', '2019-02-18 17:31:48', ''),
(12, '11519162138dpajb8kodgaqkazlrods4fs5x03aojgdu3vkgukj.png', '11519162138lmffffebpskgcwuzyzyumik23hucwnk7im61zq8s.png', 'Dudududu', '$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds', 1, '2018-02-20 14:28:58', 'pending', 'jxZXSqeLyyjeZYGxkyIbt6RYGfCoYYQCqbb0TPU4CHRagxhQROR1yHmUHy1ykzWCaZjtaSlECKGIHOgKA2AUftGOR0BPejUdzZ6pabLymi5qawHOd24pJRcZcH8yVNhuvLwDp1AzAe8cVOBM7YfD6p1enZ8m58ISyxwTed4pd8gAlYAMdhyMaXoYyjaoKiDFrX9cdOWO', 10000000, 'KAB. ACEH SELATAN', '0', 18, 11, 1101, 2, 0, '0', '2018-03-22 21:28:58', ''),
(13, '11519162188puqry8shvaenp6v6duqacicrzt9woe94hh9h87ak.png', '115191621889tshyafoqzkjzuuoux3icryc3qlcyklqxtutgs8h.png', 'asdasdasd', '$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds', 1, '2018-02-20 14:29:48', 'pending', '51q4RCkFSqHiAUt65gzrEWp0jqJDOgl33g6YITC3X0Bgf6DrbEdFP0wLjLYzKjJEEFfZmBi178FG8HOmBTEhHniBTfIh8mn0unG2MybC0ta9HMXZdEuzOB5Pf4KDwRuG2vhzR534pUIaaKYRptjZTmp00ZrkfGtQ0Cb6P5n2nNTPwxMNLQ7y48HNuZZBK0tIkjrPgeQH', 1000000, 'KAB. ACEH SELATAN', '0', 18, 11, 1101, 1, 0, '0', '2018-03-22 21:29:48', ''),
(14, '11519162220mw7ftwedvietp9tz8x6fdakznj8nt5i8kuczwdnz.png', '11519162220jdwfieeeqwrxtyr9lgrvob6r3xvvz67uap9yifqt.png', 'asdasdasd', '$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds$campaignId, $kategoriIds', 1, '2018-02-20 14:30:21', 'active', 'ZhNwVTvChD5b3EEoM5ixqpEjKfn5e6XTbRV4LLa3zT3j8Y8tZmuKKUfGk51D9cB3qUYZUrfjFIwRxwtDzk3nywLUxDqJrc5rFIdGZbRIyUN9LZiuaJc8n46rsIANEsjFADtl4S6WFUDw24iEpSAMm4mHZ3mlkk8P7rfE5e4B413Z0kAN9iJfGvBRyzGItp1GXEy6OLyH', 1000000, 'KAB. ACEH SELATAN', '0', 18, 11, 1101, 1, 2, '0', '2018-03-22 21:30:21', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_id` (`token_id`),
  ADD KEY `author_id` (`user_id`,`status`,`token_id`),
  ADD KEY `image` (`small_image`),
  ADD KEY `goal` (`goal`),
  ADD KEY `categories_id` (`categories_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
