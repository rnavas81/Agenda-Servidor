SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS AutobusesRivilla
    CHARACTER SET utf8
    COLLATE utf8_spanish_ci;

USE AutobusesRivilla;
-- ----------------------------
-- Table structure for Coches
-- ----------------------------
DROP TABLE IF EXISTS `Coches`;
CREATE TABLE `Coches`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `matricula` varchar(8) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nombreUnico`(`matricula`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for Conductores
-- ----------------------------
DROP TABLE IF EXISTS `Conductores`;
CREATE TABLE `Conductores`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nombreUnico`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for Clientes
-- ----------------------------
DROP TABLE IF EXISTS `Clientes`;
CREATE TABLE `Clientes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `nombre` varchar(64) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `telefono` varchar(13) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nombreUnico`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for Usuarios
-- ----------------------------
DROP TABLE IF EXISTS `Usuarios`;
CREATE TABLE `Usuarios`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `username` varchar(16) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `apellidos` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `foto` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `detalle` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `usuarioUnico`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;



-- ----------------------------
-- Table structure for Agenda
-- ----------------------------
DROP TABLE IF EXISTS `Agenda`;
CREATE TABLE `Agenda`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `creacionFecha` timestamp NOT NULL DEFAULT current_timestamp,
  `creacionUsuario` int NOT NULL,
  `salidaFecha` date NULL DEFAULT NULL,
  `salidaHora` time NULL DEFAULT NULL,
  `salidaLugar` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `llegadaFecha` date NULL DEFAULT NULL,
  `llegadaHora` time NULL DEFAULT NULL,
  `llegadaLugar` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `itinerario` varchar(1000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `cliente` int NULL DEFAULT NULL,
  `clienteDetalle` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `presupuesto` double(12, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `creacionUsuario`(`creacionUsuario`) USING BTREE,
  INDEX `cliente`(`cliente`) USING BTREE,
  CONSTRAINT `Agenda_ibfk_1` FOREIGN KEY (`creacionUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Agenda_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `Clientes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for AgendaCoches
-- ----------------------------
DROP TABLE IF EXISTS `AgendaCoches`;
CREATE TABLE `AgendaCoches`  (
  `idAgenda` int NOT NULL DEFAULT 0,
  `idCoche` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAgenda`,`idCoche`) USING BTREE,
  CONSTRAINT `AgendaCoches_ibfk_1` FOREIGN KEY (`idAgenda`) REFERENCES `Agenda` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `AgendaCoches_ibfk_2` FOREIGN KEY (`idCoche`) REFERENCES `Coches` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);


-- ----------------------------
-- Table structure for AgendaConductores
-- ----------------------------
DROP TABLE IF EXISTS `AgendaConductores`;
CREATE TABLE `AgendaConductores`  (
  `idAgenda` int NOT NULL DEFAULT 0,
  `idConductor` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAgenda`,`idConductor`) USING BTREE,
  CONSTRAINT `AgendaConductores_ibfk_1` FOREIGN KEY (`idAgenda`) REFERENCES `Agenda` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `AgendaConductores_ibfk_2` FOREIGN KEY (`idConductor`) REFERENCES `Conductores` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- ----------------------------
-- Table structure for Libro
-- ----------------------------
DROP TABLE IF EXISTS `Libro`;
CREATE TABLE `Libro`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT 1,
  `idAgenda` int NULL DEFAULT NULL,
  `creacionFecha` timestamp NOT NULL DEFAULT current_timestamp,
  `creacionUsuario` int NOT NULL,
  `salidaFecha` date NULL DEFAULT NULL,
  `salidaHora` time NULL DEFAULT NULL,
  `salidaLugar` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `llegadaFecha` date NULL DEFAULT NULL,
  `llegadaHora` time NULL DEFAULT NULL,
  `llegadaLugar` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `itinerario` varchar(1000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `kms` double(9, 2) NULL DEFAULT NULL,
  `cliente` int NULL DEFAULT NULL,
  `clienteDetalle` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `contacto` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `contactoTlf` varchar(64) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `facturarA` int NULL DEFAULT NULL,
  `importe` double(12, 2) NULL DEFAULT NULL,
  `cobrado` tinyint NULL DEFAULT 0,
  `cobradoFecha` timestamp NULL DEFAULT current_timestamp,
  `cobradoForma` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `cobradoDetalle` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `gastos` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `facturaNombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `facturaNumero` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idAgenda`(`idAgenda`) USING BTREE,
  INDEX `creacionUsuario`(`creacionUsuario`) USING BTREE,
  INDEX `cliente`(`cliente`) USING BTREE,
  INDEX `facturarA`(`facturarA`) USING BTREE,
  CONSTRAINT `Libro_ibfk_1` FOREIGN KEY (`idAgenda`) REFERENCES `Agenda` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Libro_ibfk_2` FOREIGN KEY (`creacionUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Libro_ibfk_3` FOREIGN KEY (`cliente`) REFERENCES `Clientes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Libro_ibfk_4` FOREIGN KEY (`facturarA`) REFERENCES `Clientes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for LibroCoches
-- ----------------------------
DROP TABLE IF EXISTS `LibroCoches`;
CREATE TABLE `LibroCoches`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `idLibro` int NOT NULL DEFAULT 0,
  `idCoche` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idLibro`(`idLibro`) USING BTREE,
  INDEX `idCoche`(`idCoche`) USING BTREE,
  CONSTRAINT `LibroCoches_ibfk_1` FOREIGN KEY (`idLibro`) REFERENCES `Libro` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `LibroCoches_ibfk_2` FOREIGN KEY (`idCoche`) REFERENCES `Coches` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for LibroConductores
-- ----------------------------
DROP TABLE IF EXISTS `LibroConductores`;
CREATE TABLE `LibroConductores`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `idLibro` int NOT NULL DEFAULT 0,
  `idConductor` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idLibro`(`idLibro`) USING BTREE,
  INDEX `idConductor`(`idConductor`) USING BTREE,
  CONSTRAINT `LibroConductores_ibfk_1` FOREIGN KEY (`idLibro`) REFERENCES `Libro` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `LibroConductores_ibfk_2` FOREIGN KEY (`idConductor`) REFERENCES `Conductores` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;


SET FOREIGN_KEY_CHECKS = 1;
