-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 26-Jan-2024 às 11:54
-- Versão do servidor: 8.0.35
-- versão do PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: twig_slim
--
CREATE DATABASE IF NOT EXISTS twig_slim DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE twig_slim;

-- --------------------------------------------------------

--
-- Estrutura da tabela admin
--

DROP TABLE IF EXISTS admin;
CREATE TABLE `admin` (
  id int UNSIGNED NOT NULL,
  name varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  password varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  phone varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  photo varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela posts
--

DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
  id int UNSIGNED NOT NULL,
  title varchar(150) DEFAULT NULL,
  user int DEFAULT NULL,
  photo varchar(255) DEFAULT NULL,
  slug varchar(100) DEFAULT NULL,
  description text,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estrutura da tabela users
--

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id int UNSIGNED NOT NULL,
  name varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  password varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  phone varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  photo varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela admin
--
ALTER TABLE admin
  ADD PRIMARY KEY (id);

--
-- Índices para tabela posts
--
ALTER TABLE posts
  ADD PRIMARY KEY (id);

--
-- Índices para tabela users
--
ALTER TABLE users
  ADD PRIMARY KEY (id);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela admin
--
ALTER TABLE admin
  MODIFY id int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela posts
--
ALTER TABLE posts
  MODIFY id int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela users
--
ALTER TABLE users
  MODIFY id int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
