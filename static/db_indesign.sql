-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2018 at 02:58 PM
-- Server version: 5.7.19
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_indesign`
--

-- --------------------------------------------------------

--
-- Table structure for table `bankdeposits`
--

DROP TABLE IF EXISTS `bankdeposits`;
CREATE TABLE IF NOT EXISTS `bankdeposits` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `bankTo` varchar(45) NOT NULL,
  `form` varchar(45) NOT NULL,
  `amount` float UNSIGNED NOT NULL,
  `from` varchar(45) DEFAULT NULL,
  `chequeNo` int(10) UNSIGNED NOT NULL,
  `bankFrom` varchar(45) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `chequeNo_UNIQUE` (`chequeNo`),
  KEY `bankTo_idx` (`bankTo`),
  KEY `bankFrom_idx` (`bankFrom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bankdeposits`
--

INSERT INTO `bankdeposits` (`id`, `date`, `bankTo`, `form`, `amount`, `from`, `chequeNo`, `bankFrom`, `purpose`) VALUES
(1, '2017-01-07', 'chase bank', 'CASH', 300000, 'Francis Kiragu', 3, 'EQUITY BANK', 'debt'),
(2, '2017-12-17', 'KCB BANK', 'Cheque', 7000, 'James Macharia', 2, 'EQUITY BANK', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`name`) VALUES
('CHASE BANK'),
('CORPORATIVE BANK'),
('EQUITY BANK'),
('KCB BANK');

-- --------------------------------------------------------

--
-- Table structure for table `bankstatements`
--

DROP TABLE IF EXISTS `bankstatements`;
CREATE TABLE IF NOT EXISTS `bankstatements` (
  `chequeNo` int(20) NOT NULL,
  `date` date NOT NULL,
  `bank1` varchar(45) NOT NULL,
  `amount` double NOT NULL,
  `form` varchar(45) NOT NULL,
  `bank2` varchar(45) NOT NULL,
  `from_to` varchar(45) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`chequeNo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bankstatements`
--

INSERT INTO `bankstatements` (`chequeNo`, `date`, `bank1`, `amount`, `form`, `bank2`, `from_to`, `purpose`, `type`) VALUES
(90, '2017-11-08', 'Equity Bank', 60000, 'cheque', 'Chase Bank', 'Milimani', 'Billboards', 'withdrawals'),
(567, '2017-01-03', 'Equity', 60000, 'cheque', 'Chase', 'Washaa', 'Bills', 'Deposits');

-- --------------------------------------------------------

--
-- Table structure for table `bankwithdrawals`
--

DROP TABLE IF EXISTS `bankwithdrawals`;
CREATE TABLE IF NOT EXISTS `bankwithdrawals` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `bankFrom` varchar(45) NOT NULL,
  `amount` float NOT NULL,
  `form` varchar(45) NOT NULL,
  `chequeNo` int(11) DEFAULT NULL,
  `to` varchar(45) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `chequeNo_UNIQUE` (`chequeNo`),
  KEY `bankFrom_idx` (`bankFrom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bankwithdrawals`
--

INSERT INTO `bankwithdrawals` (`id`, `date`, `bankFrom`, `amount`, `form`, `chequeNo`, `to`, `purpose`) VALUES
(1, '2017-04-07', 'EQUITY BANK', 34000, 'CASH', 2, 'Francis Kiragu', 'PAYMENT'),
(2, '2017-04-07', 'EQUITY BANK', 354000, 'CASH', 3, 'Francis Kiragu', 'PAYMENT');

-- --------------------------------------------------------

--
-- Table structure for table `cbcredit`
--

DROP TABLE IF EXISTS `cbcredit`;
CREATE TABLE IF NOT EXISTS `cbcredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `particulars` varchar(45) NOT NULL,
  `amount` float UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cbcredit`
--

INSERT INTO `cbcredit` (`id`, `date`, `particulars`, `amount`) VALUES
(9, '2017-05-08', 'gkj', 70000);

-- --------------------------------------------------------

--
-- Table structure for table `cbdebit`
--

DROP TABLE IF EXISTS `cbdebit`;
CREATE TABLE IF NOT EXISTS `cbdebit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `particulars` varchar(45) NOT NULL,
  `amount` float UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cbdebit`
--

INSERT INTO `cbdebit` (`id`, `date`, `particulars`, `amount`) VALUES
(8, '2017-05-12', 'dfgh', 60000);

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

DROP TABLE IF EXISTS `contractors`;
CREATE TABLE IF NOT EXISTS `contractors` (
  `personId` int(11) NOT NULL,
  `cName` varchar(45) NOT NULL,
  `moneyAgreed` float NOT NULL,
  `moneyPaid` float NOT NULL,
  `balance` float NOT NULL,
  `siteName` varchar(45) NOT NULL,
  PRIMARY KEY (`personId`),
  UNIQUE KEY `personId_UNIQUE` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`personId`, `cName`, `moneyAgreed`, `moneyPaid`, `balance`, `siteName`) VALUES
(33505268, 'Kimani Joseph', 60000, 20000, 40000, 'Westlands Houses');

-- --------------------------------------------------------

--
-- Table structure for table `extworkers`
--

DROP TABLE IF EXISTS `extworkers`;
CREATE TABLE IF NOT EXISTS `extworkers` (
  `clientId` int(20) NOT NULL AUTO_INCREMENT,
  `clientName` varchar(30) NOT NULL,
  `mAgreed` float NOT NULL,
  `mPaid` float NOT NULL,
  `balance` float NOT NULL,
  PRIMARY KEY (`clientId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `extworkers`
--

INSERT INTO `extworkers` (`clientId`, `clientName`, `mAgreed`, `mPaid`, `balance`) VALUES
(3, 'rty', 67000, 34000, 12000);

-- --------------------------------------------------------

--
-- Table structure for table `intworkers`
--

DROP TABLE IF EXISTS `intworkers`;
CREATE TABLE IF NOT EXISTS `intworkers` (
  `workerId` int(20) NOT NULL AUTO_INCREMENT,
  `workerName` varchar(80) NOT NULL,
  `workerRole` varchar(80) NOT NULL,
  PRIMARY KEY (`workerId`)
) ENGINE=MyISAM AUTO_INCREMENT=78935 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intworkers`
--

INSERT INTO `intworkers` (`workerId`, `workerName`, `workerRole`) VALUES
(1, 'Dan', 'Electrician'),
(12, 'hyj', 'xdf');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
CREATE TABLE IF NOT EXISTS `inventories` (
  `assetId` int(10) NOT NULL AUTO_INCREMENT,
  `assetName` varchar(30) NOT NULL,
  `category` varchar(45) NOT NULL,
  `Location` varchar(45) NOT NULL,
  `worth` float NOT NULL,
  `quantity` int(10) NOT NULL,
  `purchaseDate` date NOT NULL,
  PRIMARY KEY (`assetId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL,
  `detail` varchar(45) NOT NULL,
  `qtypurchased` double NOT NULL,
  `pricePer` float NOT NULL,
  `qtyStock` float NOT NULL,
  `stockWorth` float NOT NULL,
  `reorderL` int(11) NOT NULL,
  `reorderQty` float NOT NULL,
  `qtySold` float NOT NULL,
  `discoPrdct` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventorys`
--

DROP TABLE IF EXISTS `inventorys`;
CREATE TABLE IF NOT EXISTS `inventorys` (
  `id` int(11) NOT NULL,
  `detail` varchar(45) NOT NULL,
  `qtypurchased` double NOT NULL,
  `pricePer` float NOT NULL,
  `qtyStock` float NOT NULL,
  `stockWorth` float NOT NULL,
  `reorderL` int(11) NOT NULL,
  `reorderQty` float NOT NULL,
  `qtySold` float NOT NULL,
  `discoPrdct` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventorys`
--

INSERT INTO `inventorys` (`id`, `detail`, `qtypurchased`, `pricePer`, `qtyStock`, `stockWorth`, `reorderL`, `reorderQty`, `qtySold`, `discoPrdct`) VALUES
(1, 'dfgvv', 34, 300, 456, 20000, 100, 300, 200, 'rgsh'),
(2, 'sdwdwq', 345, 500, 450, 30000, 200, 400, 200, 'ether');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `invoiceNo` int(11) NOT NULL,
  `gross` float UNSIGNED NOT NULL,
  `tax` float NOT NULL,
  `net` float NOT NULL,
  `date` date NOT NULL,
  `siteName` int(11) NOT NULL,
  PRIMARY KEY (`invoiceNo`),
  UNIQUE KEY `invoiceNo_UNIQUE` (`invoiceNo`),
  KEY `siteName_idx` (`siteName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoiceNo`, `gross`, `tax`, `net`, `date`, `siteName`) VALUES
(19084, 5900, 200, 5700, '2017-12-08', 1),
(289774, 6700, 100, 6600, '2017-12-30', 1),
(345789, 350000, 10000, 340000, '2017-12-22', 3);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
CREATE TABLE IF NOT EXISTS `leaves` (
  `leaveNo` int(20) NOT NULL AUTO_INCREMENT,
  `leaved` datetime NOT NULL,
  `returnd` datetime NOT NULL,
  `personId` int(11) NOT NULL,
  `reason` varchar(45) NOT NULL,
  PRIMARY KEY (`leaveNo`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leaveNo`, `leaved`, `returnd`, `personId`, `reason`) VALUES
(2, '2017-12-01 14:53:00', '2017-12-04 14:53:00', 6, 'Sickness'),
(5, '2017-12-03 00:01:00', '2017-12-07 11:13:00', 2, 'Sickness'),
(10, '2018-03-15 05:48:00', '2018-03-16 05:48:00', 1, 'Sick leave');

-- --------------------------------------------------------

--
-- Table structure for table `leavetack`
--

DROP TABLE IF EXISTS `leavetack`;
CREATE TABLE IF NOT EXISTS `leavetack` (
  `workerId` int(10) NOT NULL AUTO_INCREMENT,
  `workertype` varchar(30) NOT NULL,
  `leaveDate` date NOT NULL,
  `returnDate` date NOT NULL,
  `reason` varchar(45) NOT NULL,
  `leaveId` int(10) NOT NULL,
  PRIMARY KEY (`workerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL,
  `site` int(11) NOT NULL,
  `item` varchar(45) NOT NULL,
  `qty` float NOT NULL,
  `perCost` float NOT NULL,
  `total` float NOT NULL,
  `date` datetime NOT NULL,
  `supplier` int(11) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `site`, `item`, `qty`, `perCost`, `total`, `date`, `supplier`, `purpose`) VALUES
(1, 2, 'Brush', 5, 150, 750, '2017-12-16 18:56:00', 2, 'N/A'),
(2, 1, 'Sockets', 4, 150, 600, '2018-01-06 18:56:00', 1, 'Building material'),
(14, 3, 'WHEELBARROW', 2, 2000, 4000, '2018-03-08 16:43:00', 19095, 'FOR WORK');

-- --------------------------------------------------------

--
-- Table structure for table `peoples`
--

DROP TABLE IF EXISTS `peoples`;
CREATE TABLE IF NOT EXISTS `peoples` (
  `personId` int(11) NOT NULL,
  `name` varchar(65) NOT NULL,
  `email` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `classification` varchar(45) DEFAULT NULL,
  `phone` varchar(45) NOT NULL,
  PRIMARY KEY (`personId`),
  UNIQUE KEY `personId_UNIQUE` (`personId`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `peoples`
--

INSERT INTO `peoples` (`personId`, `name`, `email`, `gender`, `classification`, `phone`) VALUES
(29456789, 'James Macharia', 'jaska@gmail.com', 'M', 'contractor', '0703993629'),
(32505268, 'Cisco', 'cisco1576@gmail.com', 'M', 'worker', '0703993629'),
(33505268, 'Kimani Joseph', 'misskamau1234@gmail.com', 'M', 'supplier', '0733293667');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
CREATE TABLE IF NOT EXISTS `receipts` (
  `receiptNo` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `supplier` varchar(45) NOT NULL,
  `item` varchar(45) NOT NULL,
  `gross` float UNSIGNED NOT NULL,
  `tax` float UNSIGNED DEFAULT NULL,
  `net` float UNSIGNED NOT NULL,
  `invoiceNo` int(11) DEFAULT NULL,
  PRIMARY KEY (`receiptNo`),
  UNIQUE KEY `receiptNo_UNIQUE` (`receiptNo`),
  UNIQUE KEY `invoiceNo_UNIQUE` (`invoiceNo`),
  KEY `supplier_idx` (`supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sitelogs`
--

DROP TABLE IF EXISTS `sitelogs`;
CREATE TABLE IF NOT EXISTS `sitelogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `siteName` varchar(80) NOT NULL,
  `invoiceNo` int(11) NOT NULL,
  `amt` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sitelogs`
--

INSERT INTO `sitelogs` (`id`, `start`, `end`, `siteName`, `invoiceNo`, `amt`) VALUES
(1, '2017-12-16', '2018-01-31', 'Westlands Houses', 289774, 350000),
(2, '2017-12-02', '2017-12-30', 'Karen Paints', 345789, 5900),
(3, '2017-12-18', '2018-02-28', 'Embakasi Building Plaza', 345789, 6700);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personId` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `company` varchar(45) NOT NULL,
  `vatNo` varchar(45) NOT NULL,
  `pinNo` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`),
  UNIQUE KEY `id` (`id`),
  KEY `personIdS` (`personId`),
  KEY `company_2` (`company`)
) ENGINE=InnoDB AUTO_INCREMENT=19096 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `personId`, `name`, `company`, `vatNo`, `pinNo`) VALUES
(19095, 33505268, 'Kimani Joseph', 'METALS SUPPLIERS', '23445AQ', '324ERTY');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `upload_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `upload_name`) VALUES
(50, 'code.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `level` varchar(45) DEFAULT NULL,
  `userName` varchar(45) NOT NULL,
  `people_personId` int(11) NOT NULL,
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_people1_idx` (`people_personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `password`, `level`, `userName`, `people_personId`) VALUES
('cisco1576@gmail.com', '22dea792b27e9bf934f7f4074be18c5a', '1', 'Cisco', 32505268),
('jaska@gmail.com', '73175b284f20e4f943c61d1267a8d296', '2', 'James Macharia', 29456789);

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

DROP TABLE IF EXISTS `workers`;
CREATE TABLE IF NOT EXISTS `workers` (
  `name` varchar(45) NOT NULL,
  `role` varchar(45) NOT NULL,
  `people_personId` int(11) NOT NULL,
  PRIMARY KEY (`people_personId`),
  KEY `fk_workers_people1_idx` (`people_personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`name`, `role`, `people_personId`) VALUES
('Cisco', 'Data Manager', 32505268);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bankdeposits`
--
ALTER TABLE `bankdeposits`
  ADD CONSTRAINT `bankFrom` FOREIGN KEY (`bankFrom`) REFERENCES `banks` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bankTo` FOREIGN KEY (`bankTo`) REFERENCES `banks` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bankwithdrawals`
--
ALTER TABLE `bankwithdrawals`
  ADD CONSTRAINT `bankFromW` FOREIGN KEY (`bankFrom`) REFERENCES `banks` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contractors`
--
ALTER TABLE `contractors`
  ADD CONSTRAINT `personId` FOREIGN KEY (`personId`) REFERENCES `peoples` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `invoiceNoR` FOREIGN KEY (`invoiceNo`) REFERENCES `invoices` (`invoiceNo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supplierR` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`company`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `personIdS` FOREIGN KEY (`personId`) REFERENCES `peoples` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_people1` FOREIGN KEY (`people_personId`) REFERENCES `peoples` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `fk_workers_people1` FOREIGN KEY (`people_personId`) REFERENCES `peoples` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
