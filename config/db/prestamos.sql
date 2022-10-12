/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.21-MariaDB : Database - prestamos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`prestamos` /*!40100 DEFAULT CHARACTER SET utf32 */;

USE `prestamos`;

/*Table structure for table `cliente` */

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `cliente_id` int(10) NOT NULL AUTO_INCREMENT,
  `cliente_dni` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cliente_nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cliente_apellido` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cliente_telefono` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `cliente_direccion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `cliente` */

insert  into `cliente`(`cliente_id`,`cliente_dni`,`cliente_nombre`,`cliente_apellido`,`cliente_telefono`,`cliente_direccion`) values (3,'1003652438','Juan Sebastian','Hernandez Cardenas','3227405024','Carrera 75L bis 62 H #66 Sur'),(5,'3242344234','Juan','Hernandez','3227405024','Carrera 75L bis 62 H #66 Sur');

/*Table structure for table `detalle` */

DROP TABLE IF EXISTS `detalle`;

CREATE TABLE `detalle` (
  `detalle_id` int(100) NOT NULL AUTO_INCREMENT,
  `detalle_cantidad` int(10) NOT NULL,
  `detalle_formato` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `detalle_tiempo` int(7) NOT NULL,
  `detalle_costo_tiempo` decimal(30,2) NOT NULL,
  `detalle_descripcion` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `prestamo_codigo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `item_id` int(10) NOT NULL,
  PRIMARY KEY (`detalle_id`),
  KEY `item_id` (`item_id`),
  KEY `prestamo_codigo` (`prestamo_codigo`),
  CONSTRAINT `detalle_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`),
  CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`prestamo_codigo`) REFERENCES `prestamo` (`prestamo_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `detalle` */

insert  into `detalle`(`detalle_id`,`detalle_cantidad`,`detalle_formato`,`detalle_tiempo`,`detalle_costo_tiempo`,`detalle_descripcion`,`prestamo_codigo`,`item_id`) values (6,12,'Horas',12,1500.00,'001 Computadores','CP5353729-1',2),(7,2,'Horas',12,1600.00,'001 Computadores','CP7345390-2',2);

/*Table structure for table `empresa` */

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `empresa_id` int(2) NOT NULL AUTO_INCREMENT,
  `empresa_nombre` varchar(90) COLLATE utf8_spanish2_ci NOT NULL,
  `empresa_email` varchar(70) COLLATE utf8_spanish2_ci NOT NULL,
  `empresa_telefono` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `empresa_direccion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `empresa` */

insert  into `empresa`(`empresa_id`,`empresa_nombre`,`empresa_email`,`empresa_telefono`,`empresa_direccion`) values (1,'HernandezDesigner','rrejuancho1999@gmail.com','3227405024','Carrera 75L bis 62 H #66 Sur');

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_codigo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `item_nombre` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `item_stock` int(10) NOT NULL,
  `item_estado` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `item_detalle` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `item` */

insert  into `item`(`item_id`,`item_codigo`,`item_nombre`,`item_stock`,`item_estado`,`item_detalle`) values (2,'001','Computadores',12,'Habilitado','Es un computador, dah'),(3,'002','Papas',15,'Habilitado','Papas');

/*Table structure for table `pago` */

DROP TABLE IF EXISTS `pago`;

CREATE TABLE `pago` (
  `pago_id` int(20) NOT NULL AUTO_INCREMENT,
  `pago_total` decimal(30,2) NOT NULL,
  `pago_fecha` date NOT NULL,
  `prestamo_codigo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`pago_id`),
  KEY `prestamo_codigo` (`prestamo_codigo`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`prestamo_codigo`) REFERENCES `prestamo` (`prestamo_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `pago` */

insert  into `pago`(`pago_id`,`pago_total`,`pago_fecha`,`prestamo_codigo`) values (17,500.00,'2022-06-13','CP5353729-1'),(18,500.00,'2022-06-13','CP7345390-2'),(19,215500.00,'2022-06-14','CP5353729-1');

/*Table structure for table `prestamo` */

DROP TABLE IF EXISTS `prestamo`;

CREATE TABLE `prestamo` (
  `prestamo_id` int(50) NOT NULL AUTO_INCREMENT,
  `prestamo_codigo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `prestamo_fecha_inicio` date NOT NULL,
  `prestamo_hora_inicio` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `prestamo_fecha_final` date NOT NULL,
  `prestamo_hora_final` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `prestamo_cantidad` int(10) NOT NULL,
  `prestamo_total` decimal(30,2) NOT NULL,
  `prestamo_pagado` decimal(30,2) NOT NULL,
  `prestamo_estado` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `prestamo_observacion` varchar(535) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_id` int(10) NOT NULL,
  `cliente_id` int(10) NOT NULL,
  PRIMARY KEY (`prestamo_id`),
  UNIQUE KEY `prestamo_codigo` (`prestamo_codigo`),
  KEY `usuario_id` (`usuario_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`),
  CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `prestamo` */

insert  into `prestamo`(`prestamo_id`,`prestamo_codigo`,`prestamo_fecha_inicio`,`prestamo_hora_inicio`,`prestamo_fecha_final`,`prestamo_hora_final`,`prestamo_cantidad`,`prestamo_total`,`prestamo_pagado`,`prestamo_estado`,`prestamo_observacion`,`usuario_id`,`cliente_id`) values (6,'CP5353729-1','2022-06-13','11:04 pm','2022-06-26','01:09 am',12,216000.00,216000.00,'Reservacion','Ninguna',1,5),(7,'CP7345390-2','2022-06-13','11:05 pm','2022-06-19','02:10 am',2,38400.00,500.00,'Finalizado','Nell',1,3);

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL AUTO_INCREMENT,
  `usuario_dni` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_apellido` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_telefono` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_direccion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_email` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_usuario` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_clave` varchar(535) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_estado` varchar(17) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario_privilegio` int(2) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `usuario` */

insert  into `usuario`(`usuario_id`,`usuario_dni`,`usuario_nombre`,`usuario_apellido`,`usuario_telefono`,`usuario_direccion`,`usuario_email`,`usuario_usuario`,`usuario_clave`,`usuario_estado`,`usuario_privilegio`) values (1,'0000000000','Administrador',' Principal','3227405024','Administrador','Administrador@gmail.com','Administrador','dWFBeENEeEZXdm9qeVZxSW9uU3RGZz09','Activo',1),(2,'1003652437','Juan Sebastian','Cardenas Hernandez','3227405024','Carrera 75L bis 62 H #66 Sur','rrejuancho1999@gmail.com','rrejuancho1999','S24xOWF5VGx6cXhOenB4NXZ2ODA0Zz09','Activo',2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
