-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2022 at 04:32 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petshop_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `raca` varchar(255) NOT NULL,
  `teldono` char(11) NOT NULL,
  `datacadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`id`, `nome`, `raca`, `teldono`, `datacadastro`) VALUES
(7, 'mel', 'fox paulistinha', '168819242', '2022-11-23 21:12:28'),
(8, 'luna', 'viralata', '1699182812', '2022-11-24 05:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `atende`
--

CREATE TABLE `atende` (
  `id` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL,
  `idanimal` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `atende`
--

INSERT INTO `atende` (`id`, `idfuncionario`, `idanimal`, `data`) VALUES
(20, 17, 7, '2022-11-28 00:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `funcionario`
--

CREATE TABLE `funcionario` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `datacadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `login` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `funcionario`
--

INSERT INTO `funcionario` (`id`, `nome`, `email`, `tipo`, `datacadastro`, `login`) VALUES
(10, 'Admin', 'funcionario@petshop.com', 'admin', '2022-11-27 21:57:20', 1),
(11, 'Funcionario', 'eu@petshop.com', 'funcionario', '2022-11-27 22:16:25', 2),
(17, 'Beta', 'cobaia@petshop.com', 'funcionario', '2022-11-28 00:15:02', 8);

-- --------------------------------------------------------

--
-- Table structure for table `gera`
--

CREATE TABLE `gera` (
  `id` int(11) NOT NULL,
  `idatendimento` int(11) NOT NULL,
  `idprocedimento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gera`
--

INSERT INTO `gera` (`id`, `idatendimento`, `idprocedimento`) VALUES
(46, 20, 79),
(47, 20, 81);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `senha`) VALUES
(1, 'funcionario@petshop.com', '$2y$15$7XUoexsYk7lAavoHbJ2WdO9bP.nxjTzgqXWPg2baDy/IMwi3yBYW.'),
(2, 'eu@petshop.com', '$2y$15$4wfsl4ZUNT0pkQQ9z11e8.uESl7JK.Q6lrz8zYC3fMjgX4pf4Qnta'),
(8, 'cobaia@petshop.com', '$2y$15$r05wcXNX99pGxskMJ8VUruVWV9f9pyAZsq4./iCLHbQxBV/t2Wxem');

-- --------------------------------------------------------

--
-- Table structure for table `procedimentos`
--

CREATE TABLE `procedimentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `procedimentos`
--

INSERT INTO `procedimentos` (`id`, `nome`) VALUES
(78, 'Consulta pediátrica'),
(79, 'Atendimento odontológico'),
(80, 'Aplicação de medicação e vacina'),
(81, 'Corte de unha'),
(82, 'Fluidoterapia subcutânea'),
(83, 'Curativos – ataduras e imobilização'),
(84, 'Coleta de exames'),
(85, 'Administração de medicamento via oral');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atende`
--
ALTER TABLE `atende`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idfuncionario_fk` (`idfuncionario`),
  ADD KEY `idanimal_fk` (`idanimal`);

--
-- Indexes for table `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idLogin_FK` (`login`);

--
-- Indexes for table `gera`
--
ALTER TABLE `gera`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idatendimento_fk` (`idatendimento`),
  ADD KEY `idprocedimento_fk` (`idprocedimento`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `procedimentos`
--
ALTER TABLE `procedimentos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `atende`
--
ALTER TABLE `atende`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `gera`
--
ALTER TABLE `gera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `procedimentos`
--
ALTER TABLE `procedimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `atende`
--
ALTER TABLE `atende`
  ADD CONSTRAINT `idanimal_fk` FOREIGN KEY (`idanimal`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `idfuncionario_fk` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionario` (`id`);

--
-- Constraints for table `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `idLogin_FK` FOREIGN KEY (`login`) REFERENCES `login` (`id`);

--
-- Constraints for table `gera`
--
ALTER TABLE `gera`
  ADD CONSTRAINT `idatendimento_fk` FOREIGN KEY (`idatendimento`) REFERENCES `atende` (`id`),
  ADD CONSTRAINT `idprocedimento_fk` FOREIGN KEY (`idprocedimento`) REFERENCES `procedimentos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
