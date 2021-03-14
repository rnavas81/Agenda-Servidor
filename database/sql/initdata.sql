
USE AutobusesRivilla;

-- ----------------------------
-- Records of Usuarios
-- ----------------------------
INSERT INTO `Usuarios` VALUES (1, 1, 'R', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'Rodrigo', 'Navas', 'platea.jpg', NULL);
INSERT INTO `Usuarios` VALUES (2, 1, 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08	', 'Test', '', NULL, NULL);
INSERT INTO `Usuarios` VALUES (3, 1, 'lstjohn1', 'c7be62b08fb9d5e5136fd5215e98991fd22d074e3ccfcd063e7995f8d2343e27', 'Lyssa', 'St. John', 'blanditNam.jpeg', 'id nisl venenatis lacinia aenean sit amet justo morbi ut odio');
INSERT INTO `Usuarios` VALUES (4, 1, 'jscholes2', 'dced5ed8a4b57d25588c5a57dc830f040332ad5863de8978d433495867c66952', 'Jillane', 'Scholes', 'id_ornare_imperdiet.jpeg', 'donec ut mauris eget massa tempor convallis nulla neque libero convallis eget eleifend luctus ultricies');
INSERT INTO `Usuarios` VALUES (5, 1, 'bdumper3', '978350a8e0d7018bb069035f0b8a676ef3f9a572e720245a557c79d84844d97a', 'Brigida', 'Dumper', 'mauris.jpg', 'rutrum neque aenean auctor gravida sem praesent id massa id nisl venenatis lacinia aenean sit');
INSERT INTO `Usuarios` VALUES (6, 1, 'esavine4', 'effacd06a765e87f780c8e91e9b1e8ee1933fda4e993c79d570bc09c4951a9ca', 'Elsinore', 'Savine', 'vestibulumAnte.jpeg', 'curabitur convallis duis consequat dui nec nisi volutpat eleifend donec ut dolor morbi vel');
-- ----------------------------
-- Records of Clientes
-- ----------------------------
INSERT INTO `Clientes` VALUES (1, 1, 'AYTO PUERTOLLANO', NULL);
INSERT INTO `Clientes` VALUES (2, 1, 'AYTO ARGAMASILLA', NULL);
INSERT INTO `Clientes` VALUES (3, 1, 'AYTO ALMODOVAR', NULL);
INSERT INTO `Clientes` VALUES (4, 1, 'ASOCIACION EL POBLADO', NULL);

-- ----------------------------
-- Records of Coches
-- ----------------------------
INSERT INTO `Coches` VALUES (1, 1, '1234BCD');
INSERT INTO `Coches` VALUES (2, 1, '1234FGH');
INSERT INTO `Coches` VALUES (3, 1, '1234JKL');
INSERT INTO `Coches` VALUES (4, 1, '1234MNP');

-- ----------------------------
-- Records of Conductores
-- ----------------------------
INSERT INTO `Conductores` VALUES (1, 1, 'JUÁN');
INSERT INTO `Conductores` VALUES (2, 1, 'PEDRO');
INSERT INTO `Conductores` VALUES (3, 1, 'ANTONIO');
INSERT INTO `Conductores` VALUES (4, 1, 'MANUEL');

-- ----------------------------
-- Records of Agenda
-- ----------------------------
INSERT INTO `Agenda` VALUES (1, 1, NOW(), 1, '2020-03-15', '10:00:00', 'Edificio Tauro (Puertollano)', '2020-03-16', '15:00:00', 'Pase San Gregorio Pares', '', 1, 'Esto es una prueba', 1000.00);
INSERT INTO `Agenda` VALUES (2, 1, NOW(), 2, '2020-03-16', '08:00:00', 'Carrefour', '2020-03-16', '15:00:00', 'Carrefour', 'Puertollano-Argamasilla-Ciudad Real', 2, 'Autobus deporte escolar', 0.00);

-- ----------------------------
-- Records of AgendaCoches
-- ----------------------------
INSERT INTO `AgendaCoches` VALUES (1, 1);
INSERT INTO `AgendaCoches` VALUES (1, 2);
INSERT INTO `AgendaCoches` VALUES (2, 3);
INSERT INTO `AgendaCoches` VALUES (2, 4);

-- ----------------------------
-- Records of AgendaConductores
-- ----------------------------
INSERT INTO `AgendaConductores` VALUES (1, 1);
INSERT INTO `AgendaConductores` VALUES (1, 2);
INSERT INTO `AgendaConductores` VALUES (2, 3);
INSERT INTO `AgendaConductores` VALUES (2, 4);

-- ----------------------------
-- Records of Libro
-- ----------------------------
INSERT INTO `Libro` VALUES (1, 1, 1, NOW(), 2, '2020-03-15', '10:00:00', 'Edificio Tauro (Puertollano)', '2020-03-16', '15:00:00', 'Pase San Gregorio Pares', '', 0.00, 1, '', 'John Doe', '55566789', NULL, 1000.00, 0, NOW(), NULL, NULL, NULL, NULL, NULL);
INSERT INTO `Libro` VALUES (2, 1, 2, NOW(), 3, '2020-03-16', '08:00:00', 'Carrefour', '2020-03-16', '15:00:00', 'Carrefour', 'Puertollano-Argamasilla-Ciudad Real', 0.00, 1, '', 'John Doe', '55566789', NULL, 1000.00, 0, NOW(), NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Records of LibroCoches
-- ----------------------------
INSERT INTO `LibroCoches` VALUES (1, 1, 1);
INSERT INTO `LibroCoches` VALUES (2, 1, 2);
INSERT INTO `LibroCoches` VALUES (3, 2, 3);
INSERT INTO `LibroCoches` VALUES (4, 2, 4);

-- ----------------------------
-- Records of LibroConductores
-- ----------------------------
INSERT INTO `LibroConductores` VALUES (1, 1, 1);
INSERT INTO `LibroConductores` VALUES (2, 1, 2);
INSERT INTO `LibroConductores` VALUES (3, 2, 3);
INSERT INTO `LibroConductores` VALUES (4, 2, 4);

