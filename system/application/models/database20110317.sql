-- MySQL dump 10.11
--
-- Host: localhost    Database: shipito
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny5

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `_config`
--

DROP TABLE IF EXISTS `_config`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `_config` (
  `config_name` varchar(64) NOT NULL,
  `config_value` varchar(64) NOT NULL,
  PRIMARY KEY  (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `_config`
--

LOCK TABLES `_config` WRITE;
/*!40000 ALTER TABLE `_config` DISABLE KEYS */;
INSERT INTO `_config` VALUES ('price_for_trasmission','400'),('price_for_marge','100'),('price_for_declaration','60'),('price_for_help','10');
/*!40000 ALTER TABLE `_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL auto_increment,
  `address_desc` varchar(255) character set cp1251 NOT NULL,
  `address_user` int(11) NOT NULL,
  `address_town` varchar(255) character set cp1251 NOT NULL,
  `address_zip` int(11) NOT NULL,
  `address_address` varchar(255) character set cp1251 NOT NULL,
  `address_phone` varchar(24) character set cp1251 NOT NULL,
  `address_is_default` smallint(6) NOT NULL,
  PRIMARY KEY  (`address_id`),
  KEY `address_client` (`address_user`),
  KEY `address_is_default` (`address_is_default`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,'na derevnu dedushke',2,'Nagano',354341,'Tverskaya 5, 24','72366489352',1),(4,'еще один адрес',2,'Юфа',323255,'адрес 1','+78999545643213',0),(6,'еще один адрес',2,'Юфа',323255,'адрес 1','+78999545643213',0),(8,'тест на сохранение',2,'Юфа',323255,'адрес 1','+78999545643213',0);
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `c2m`
--

DROP TABLE IF EXISTS `c2m`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `c2m` (
  `client_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  PRIMARY KEY  (`client_id`,`manager_id`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `c2m_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `c2m_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`manager_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `c2m`
--

LOCK TABLES `c2m` WRITE;
/*!40000 ALTER TABLE `c2m` DISABLE KEYS */;
INSERT INTO `c2m` VALUES (2,3),(94,3),(114,3),(123,3),(128,3),(163,3),(184,3),(2,103),(114,103),(123,103),(128,103),(2,107),(94,107),(114,107),(123,107),(128,107),(2,149),(94,149),(114,149),(123,149),(128,149),(163,149),(184,149),(2,152),(94,152),(114,152),(123,152),(128,152),(163,152),(184,152),(2,158),(94,158),(114,158),(123,158),(128,158),(163,158),(184,158),(163,159),(184,159),(94,161),(163,161),(184,161);
/*!40000 ALTER TABLE `c2m` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `clients` (
  `client_name` varchar(32) NOT NULL,
  `client_user` int(11) NOT NULL,
  `client_surname` varchar(128) NOT NULL,
  `client_otc` varchar(32) NOT NULL,
  `client_country` int(11) NOT NULL,
  `client_town` varchar(64) NOT NULL,
  `client_index` varchar(12) NOT NULL,
  `client_address` varchar(512) NOT NULL,
  `client_phone` varchar(13) NOT NULL,
  PRIMARY KEY  (`client_user`),
  KEY `client_country` (`client_country`),
  CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`client_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`client_country`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES ('Marsel',1,'Valiullin','Ramisovich',3,'Saint-Petersburg','198922','Bucharest street, 138','89643774723'),('Kona',2,'Tamiko','Konavna',3,'Nagano','354341','Tverskaya 5, 24','72366489352'),('Feriartos',50,'Sella','',4,'Bubno','11122','Tverskaya 5, 24','1221222'),('ass',51,'assd','',3,'dddd','232322','Tverskaya 5, 24','23232'),('Sara',52,'Conner','',4,'Nagano','11122','Tverskaya 5, 24','111222333'),('qwdwqd',53,'wqdwqd','',3,'qwdwqd','11111','wdqw wqd w w','268888888'),('wdw',54,'wdw','',3,'wdwd','222','sdsd  s s s s','2222222'),('Craftsman',55,'Craftsman','',3,'Moscow','156456','Tverskaya 5, 24','745567505'),('q',93,'q','',8,'q','11','q','11111'),('Kona',94,'Tamiko','aaa',3,'Nagano','22222','Tverskaya 5, 24','12255454'),('m',95,'m','',3,'m','11','m','11'),('n',96,'n','',8,'1','1','1','1'),('o',97,'o','o',8,'o','111111','o','11111111'),('p',98,'p','p',7,'p','11111','p','1111'),('c',102,'c','c',4,'c','111','c','1'),('c',104,'c','c',8,'c','11','c','1'),('c',105,'c','c',4,'c','11','c','111'),('c',108,'c','c',8,'c','1','1','1'),('c5',109,'c','c',8,'c','1','c','1'),('1',110,'1','1',8,'1','1','1','1'),('1',112,'1','1',8,'1','1','1','1'),('c',113,'c','c',4,'c','1','c','1'),('c',114,'c','c',4,'c','1','c','1'),('q',115,'q','q',4,'b','1','1','1'),('q',116,'1','1',8,'q','1','1','1'),('q',117,'3','3',8,'q','3','3','3'),('v',118,'v','v',8,'5','5','5','5'),('a',119,'a','a',8,'a','1','1','1'),('a',120,'a','a',8,'a','1','1','1'),('1',121,'1','1',4,'1','1','1','1'),('1',122,'1','1',4,'1','1','1','1'),('1',123,'1','1',9,'1','1','1','1'),('1',124,'1','1',4,'1','1','1','1'),('1',125,'1','1',4,'1','1','1','1'),('a',128,'s','s',8,'a','1','1','1'),('1',129,'1','1',4,'1','1','1','1'),('1',130,'1','1',4,'1','1','1','1'),('1',131,'1','1',4,'1','1','1','1'),('1',132,'1','1',8,'1','1','1','1'),('1',133,'1','1',4,'1','1','1','1'),('1',134,'1','1',4,'1','1','1','1'),('1',135,'1','1',4,'1','1','1','1'),('1',136,'1','1',4,'1','1','1','1'),('1',137,'1','1',4,'1','1','1','1'),('1',138,'1','1',4,'1','1','1','1'),('1',139,'1','1',4,'1','1','1','1'),('1',140,'1','1',4,'1','1','1','1'),('1',141,'1','2',4,'1','1','1','1'),('1',142,'2','1',4,'1','1','1','1'),('1',143,'1','1',4,'1','1','1','1'),('1',144,'1','1',4,'1','1','1','1'),('1',145,'1','1',4,'1','1','1','1'),('1',147,'1','1',8,'1','1','1','1'),('q',151,'q','q',8,'q','1','q','1'),('1',160,'1','1',4,'1','1','1','1'),('Yury',162,'Ivanov','Vladimirivich',3,'Moscow','115000','Lenina 15, 24','4548498'),('Vovan',163,'Yaseslav','Fedorovich',9,'beshkek','115000','Kekelevo 5, 56','345525'),('1',164,'1','1',4,'1','1','1','1'),('Rekki',184,'Rakka','Kakka',18,'Gothem','345354','Tverskaya 5, 24','79061857380');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL auto_increment,
  `country_name` varchar(64) NOT NULL,
  PRIMARY KEY  (`country_id`),
  KEY `country_name` (`country_name`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (12,'Абхазия'),(13,'Австралия'),(14,'Австрия'),(15,'Азербайджан'),(16,'Азорские острова'),(17,'Аландские острова'),(18,'Албания'),(19,'Алжир'),(52,'Американские Виргинские острова'),(20,'Американское Самоа'),(21,'Ангилья'),(22,'Ангола'),(23,'Андорра'),(24,'Антигуа и Барбуда'),(26,'Аргентина'),(27,'Армения'),(28,'Аруба'),(29,'Афганистан'),(30,'Багамы'),(31,'Бангладеш'),(32,'Барбадос'),(33,'Бахрейн'),(8,'Беларусь'),(34,'Белиз'),(35,'Бельгия'),(36,'Бенин'),(37,'Бермуды'),(38,'Болгария'),(39,'Боливия'),(40,'Босния и Герцеговина'),(41,'Ботсвана'),(42,'Бразилия'),(51,'Британские Виргинские острова'),(43,'Бруней'),(44,'Буркина Фасо'),(45,'Бурунди'),(46,'Бутан'),(47,'Вануату'),(48,'Ватикан'),(49,'Великобритания'),(4,'Венгрия'),(50,'Венесуэла'),(53,'Восточный Тимор'),(54,'Вьетнам'),(55,'Габон'),(56,'Гавайи'),(57,'Гаити'),(58,'Гайана'),(59,'Гамбия'),(60,'Гана'),(61,'Гваделупа'),(62,'Гватемала'),(63,'Гвинея'),(64,'Гвинея-Бисау'),(65,'Германия'),(66,'Гернси'),(67,'Гибралтар'),(68,'Гондурас'),(69,'Гонконг'),(70,'Гренада'),(71,'Гренландия'),(72,'Греция'),(73,'Грузия'),(74,'Гуам'),(75,'Дания'),(76,'Джерси'),(77,'Джибути'),(78,'Доминика'),(79,'Доминиканская Республика'),(80,'Египет'),(81,'Замбия'),(82,'Зимбабве'),(83,'Израиль'),(84,'Индия'),(85,'Индонезия'),(86,'Иордания'),(87,'Ирак'),(88,'Иран'),(89,'Ирландия'),(90,'Исландия'),(91,'Испания'),(92,'Италия'),(93,'Йемен'),(94,'Кабо-Верде'),(9,'Казахстан'),(95,'Каймановы острова'),(96,'Камбоджа'),(97,'Камерун'),(98,'Канада'),(99,'Катар'),(100,'Кения'),(101,'Кипр'),(102,'Киргизия'),(103,'Кирибати'),(5,'Китай'),(108,'КНДР'),(104,'Кокосовые острова'),(105,'Колумбия'),(106,'Коморы'),(107,'Конго'),(6,'Корея'),(111,'Коста-Рика'),(112,'Кот-д’Ивуар'),(113,'Куба'),(114,'Кувейт'),(116,'Кюрасао'),(117,'Лаос'),(118,'Латвия'),(119,'Лесото'),(120,'Либерия'),(121,'Ливан'),(122,'Ливия'),(123,'Литва'),(124,'Лихтенштейн'),(125,'Люксембург'),(126,'Маврикий'),(127,'Мавритания'),(128,'Мадагаскар'),(129,'Мадейра'),(130,'Майотта'),(25,'Макао'),(132,'Малави'),(133,'Малайзия'),(134,'Мали'),(135,'Мальдивы'),(136,'Мальта'),(137,'Марокко'),(138,'Мартиника'),(139,'Маршалловы Острова'),(140,'Мексика'),(142,'Мозамбик'),(143,'Молдавия'),(144,'Монако'),(145,'Монголия'),(146,'Монтсеррат'),(147,'Мьянма'),(149,'Нагорно-Карабахская Республика'),(150,'Намибия'),(151,'Науру'),(152,'Непал'),(153,'Нигер'),(154,'Нигерия'),(155,'Нидерланды'),(156,'Никарагуа'),(157,'Ниуэ'),(158,'Новая Зеландия'),(159,'Новая Каледония'),(160,'Норвегия'),(162,'ОАЭ'),(163,'Оман'),(148,'Остров Мэн'),(161,'Остров Норфолк'),(176,'Остров Рождества'),(186,'Остров Святой Елены'),(115,'Острова Кука'),(171,'Острова Питкэрн'),(164,'Пакистан'),(165,'Палау'),(166,'Палестина'),(167,'Панама'),(168,'Папуа'),(169,'Парагвай'),(170,'Перу'),(10,'Польша'),(172,'Португалия'),(173,'Приднестровская Молдавская Республика'),(174,'Пуэрто-Рико'),(109,'Республика Корея'),(110,'Республика Косово'),(131,'Республика Македония'),(175,'Реюньон'),(3,'Россия'),(177,'Руанда'),(178,'Румыния'),(179,'Сальвадор'),(180,'Самоа'),(181,'Сан-Марино'),(182,'Сан-Томе и Принсипи'),(184,'Саудовская Аравия'),(183,'Сахарская Арабская Демократическая Республика'),(185,'Свазиленд'),(187,'Северные Марианские острова'),(189,'Сейшельские Острова'),(191,'Сен-Пьер и Микелон'),(190,'Сенегал'),(192,'Сент-Винсент и Гренадины'),(193,'Сент-Китс и Невис'),(194,'Сент-Люсия'),(195,'Сербия'),(196,'Силенд'),(11,'Сингапур'),(197,'Синт-Маартен'),(198,'Сирия'),(199,'Словакия'),(200,'Словения'),(202,'Соломоновы Острова'),(203,'Сомали'),(204,'Сомалиленд'),(205,'Судан'),(206,'Суринам'),(201,'США'),(207,'Сьерра-Леоне'),(208,'Таджикистан'),(209,'Таиланд'),(210,'Танзания'),(211,'Тёркс и Кайкос'),(212,'Того'),(213,'Токелау'),(214,'Тонга'),(215,'Тринидад и Тобаго'),(216,'Тувалу'),(217,'Тунис'),(188,'Турецкая Республика Северного Кипра'),(218,'Туркмения'),(219,'Турция'),(220,'Уганда'),(221,'Узбекистан'),(7,'Украина'),(222,'Уоллис и Футуна'),(223,'Уругвай'),(224,'Фарерские острова'),(141,'Федеративные Штаты Микронезии'),(225,'Фиджи'),(226,'Филиппины'),(227,'Финляндия'),(228,'Фолклендские острова'),(229,'Франция'),(230,'Французская Гвиана'),(231,'Французская Полинезия'),(232,'Французские Южные и Антарктические Территории'),(233,'Хорватия'),(234,'ЦАР'),(235,'Чад'),(236,'Черногория'),(237,'Чехия'),(238,'Чили'),(239,'Швейцария'),(240,'Швеция'),(241,'Шпицберген'),(242,'Шри-Ланка'),(243,'Эквадор'),(244,'Экваториальная Гвинея'),(245,'Эритрея'),(246,'Эстония'),(247,'Эфиопия'),(250,'ЮАР'),(248,'Южная Георгия и Южные Сандвичевы острова'),(249,'Южная Осетия'),(251,'Ямайка'),(252,'Япония');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `declarations`
--

DROP TABLE IF EXISTS `declarations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `declarations` (
  `declaration_id` int(11) NOT NULL auto_increment,
  `declaration_package` int(11) unsigned zerofill NOT NULL,
  `declaration_item` text NOT NULL,
  `declaration_amount` int(11) unsigned NOT NULL,
  `declaration_cost` float NOT NULL,
  PRIMARY KEY  (`declaration_id`),
  KEY `declaration_package` (`declaration_package`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `declarations`
--

LOCK TABLES `declarations` WRITE;
/*!40000 ALTER TABLE `declarations` DISABLE KEYS */;
INSERT INTO `declarations` VALUES (4,00000000002,'2',3,4),(8,00000000005,'55',55,5),(9,00000000005,'5',5,5),(10,00000000005,'5',5,5),(11,00000000005,'6',6456,6),(13,00000000005,'66666666666',5,66),(14,00000000004,'11',1,233),(15,00000000004,'2',2,2),(16,00000000005,'7',7,8),(17,00000000008,'6',6,6),(18,00000000005,'8',8,8),(19,00000000009,'1',2,3),(20,00000000024,'66',66,66),(21,00000000009,'66',66,66),(22,00000000009,'1',1,1),(23,00000000029,'2345',234,34),(24,00000000036,'1',1,1),(25,00000000038,'asfdasd',1,2000),(26,00000000032,'dfasdf',1,1400),(27,00000000038,'q',2,22),(28,00000000038,'w',3,33),(29,00000000039,'1',333,1),(30,00000000039,'1',33,2),(31,00000000044,'боты',1,1000),(32,00000000045,'sdfsdf',1,1000),(34,00000000045,'123',1,2),(35,00000000048,'Говяжие ляшки',12,155),(36,00000000048,'Свинные рульки',24,169),(37,00000000058,'Вещь №1 (rfynhjgekzwbjyysqa ublhj,ekm,ekznjh)',1,1590),(38,00000000043,'1',13,45);
/*!40000 ALTER TABLE `declarations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deliveries`
--

DROP TABLE IF EXISTS `deliveries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL auto_increment,
  `delivery_name` varchar(32) NOT NULL,
  `delivery_time` varchar(32) NOT NULL,
  PRIMARY KEY  (`delivery_id`),
  KEY `order_client` (`delivery_name`,`delivery_time`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `deliveries`
--

LOCK TABLES `deliveries` WRITE;
/*!40000 ALTER TABLE `deliveries` DISABLE KEYS */;
INSERT INTO `deliveries` VALUES (2,'DHL','3 дня'),(28,'EMS','3 дня'),(29,'KPP','1'),(1,'UPS','1-2 недели'),(27,'Почта России','1 месяц');
/*!40000 ALTER TABLE `deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL auto_increment,
  `faq_question` text,
  `faq_answer` text NOT NULL,
  PRIMARY KEY  (`faq_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (14,'Kvanta kosta?','Kosa Nostra'),(15,'Новый вопрос','Старый ответ'),(19,'скажика дядя, ведь недаром Москва, сожжёная пожаром... Мяу?!','Коты, спасённые на пожарах:\nнемного фоток и большая куча ссылок на другие фото:\nhttp://community.livejournal.com/ru_cats/10162531.html');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `files` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `dir` varchar(32) default NULL,
  `fullpath` varchar(255) default NULL,
  `ext` varchar(6) default NULL,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (5,'agu4.jpg','screenshots','http://localhost/static/images/screenshots/agu4.jpg','.jpg',1024,683,67),(6,'Nigra1.jpg','screenshots','http://omni.kio.samaraauto.ru/static/images/screenshots/Nigra1.jpg','.jpg',551,467,36),(7,'Nigra1.jpg','screenshots','http://omni.kio.teralabs.ru/static/images/screenshots/Nigra1.jpg','.jpg',551,467,36),(8,'Nigra11.jpg','screenshots','http://omni.kio.teralabs.ru/static/images/screenshots/Nigra11.jpg','.jpg',551,467,36),(9,'Nigra12.jpg','screenshots','http://omni.kio.teralabs.ru/static/images/screenshots/Nigra12.jpg','.jpg',551,467,36),(10,'Nigra13.jpg','screenshots','http://omni.kio.teralabs.ru/static/images/screenshots/Nigra13.jpg','.jpg',551,467,36),(11,'Teaser.JPG','screenshots','http://omni.kio.teralabs.ru/static/images/screenshots/Teaser.JPG','.JPG',540,420,46);
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manager_delivery`
--

DROP TABLE IF EXISTS `manager_delivery`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `manager_delivery` (
  `manager_delivery_id` int(11) NOT NULL auto_increment,
  `manager_id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  PRIMARY KEY  (`manager_delivery_id`),
  KEY `declaration_package` (`manager_id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `manager_delivery`
--

LOCK TABLES `manager_delivery` WRITE;
/*!40000 ALTER TABLE `manager_delivery` DISABLE KEYS */;
INSERT INTO `manager_delivery` VALUES (12,90,28),(13,59,2),(14,59,27),(17,0,2),(18,0,28),(19,0,1),(20,101,2),(21,101,28),(22,101,1),(25,106,2),(26,106,28),(27,106,1),(31,126,2),(32,126,28),(33,126,1),(34,127,2),(35,127,28),(36,127,1),(37,146,1),(47,148,2),(48,148,1),(61,152,2),(62,152,28),(63,152,1),(64,157,2),(65,157,28),(66,157,1),(73,158,2),(74,158,28),(75,158,1),(91,107,2),(92,107,28),(93,107,1),(97,159,2),(98,159,28),(99,159,1),(105,161,2),(106,161,28),(107,161,1),(108,161,27),(110,103,2),(111,103,28),(112,103,1),(113,103,27),(114,149,2),(115,149,28),(116,149,1),(121,3,28),(122,3,27),(158,185,2),(159,185,28),(160,185,29),(161,185,1),(162,185,27);
/*!40000 ALTER TABLE `manager_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `managers`
--

DROP TABLE IF EXISTS `managers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `managers` (
  `manager_user` int(11) NOT NULL,
  `manager_country` int(11) NOT NULL,
  `manager_max_clients` int(11) NOT NULL,
  `manager_name` varchar(32) NOT NULL,
  `manager_surname` varchar(32) NOT NULL,
  `manager_otc` varchar(32) NOT NULL,
  `manager_addres` text NOT NULL,
  `manager_phone` varchar(32) NOT NULL,
  `manager_status` int(11) NOT NULL,
  `last_client_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`manager_user`),
  KEY `manager_country` (`manager_country`),
  CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`manager_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `managers_ibfk_2` FOREIGN KEY (`manager_country`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `managers`
--

LOCK TABLES `managers` WRITE;
/*!40000 ALTER TABLE `managers` DISABLE KEYS */;
INSERT INTO `managers` VALUES (3,5,10,'Manager','First','Tester','Test','2147483647',1,'2010-10-25 17:10:47'),(59,4,6,'Manager','Second','Test','Test','2147483647',2,'2010-10-07 01:07:49'),(90,6,50,'test','test','test','test','2147483647',2,'2010-10-07 01:07:49'),(92,3,50,'mmmm','mmmm','mmmmm','mmmmmm','2147483647',2,'2010-10-07 01:07:49'),(100,6,1,'q','q','q','q','111',2,'2010-10-07 01:07:49'),(101,6,1,'q','q','q','q','111',2,'2010-10-07 01:07:49'),(103,7,54,'u','u','u','u','11',1,'2010-11-10 14:11:22'),(106,6,28,'p','p','p','p','11',2,'2010-10-07 15:52:02'),(107,6,53,'p','p','p','p','111111',1,'2010-11-10 14:11:22'),(126,4,2,'1','1','1','1','1',2,'2010-10-07 13:23:54'),(127,6,9,'1','1','1','1','1',2,'2010-10-07 15:41:58'),(146,7,3,'1','1','1','1','1',2,'2010-10-07 15:10:43'),(148,5,50,'q','q','q','q','1',2,'2010-10-07 18:10:38'),(149,4,52,'1','1','1','1','1',1,'2010-11-10 15:11:37'),(152,10,54,'p','p','p','p','1',1,'2010-11-10 15:11:37'),(157,5,50,'d','l','l','l','1',2,'2010-11-10 15:11:37'),(158,9,54,'1','1','1','1','1',1,'2010-11-10 15:11:37'),(159,6,53,'1','1','1','1','1',1,'2010-11-10 15:11:37'),(161,7,50,'u','u','u','u','111111111',1,'2010-11-10 15:11:37'),(185,13,13,'we','sd','fd','qwer','121123232',1,'2010-11-18 13:09:56');
/*!40000 ALTER TABLE `managers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` text,
  `news_body` text NOT NULL,
  `news_addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`news_id`),
  KEY `news_addtime` (`news_addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Приемную мать Глеба Агеева приговорили к исправительным работам','Жительницу Подмосковья Ларису Агееву приговорили к одному году и восьми месяцам исправительных работ за причинение вреда здоровью приемного сына Глеба. Об этом 15 ноября сообщает РИА Новости.\n\nМужа Агеевой Антона суд города Видное оправдал, не найдя в его действиях состава преступления. По данным агентства &quot;Интерфакс&quot;, мотивом Ларисы Агеевой суд признал неприязненное отношение к ребенку.\n\nАгеевы усыновили Глеба 2005 года рождения и его сестру Полину 2006 года рождения в мае 2008 года. Семья стала жить в частном доме в поселке Коробово Ленинского района Московской области.\n\nВесной 2009 года Глеб Агеев поступил в больницу с ушибами и ожогами. По факту нанесения вреда здоровью ребенка было возбуждено уголовное дело. Следователи пришли к выводу, что Лариса Агеева вскоре после усыновления стала испытывать неприязнь к приемному сыну и начала систематически его избивать. Агеевы же утверждали, что Глеб получил травмы при падении с лестницы, а также уронил на себя чайник.\n\nВ июне 2009 года усыновление Глеба и Полины было отменено судом.','2010-11-15 12:17:55'),(10,'новая новость','новая новость','2010-07-27 11:27:08'),(8,'тест','русский текст','2010-07-26 16:18:18'),(17,'Кашин описал избивших его преступников','Корреспондент &quot;Коммерсанта&quot; Олег Кашин пришел в сознание и смог говорить. Утром в понедельник, 15 ноября, его отключили от системы искусственной вентиляции легких, а несколькими днями ранее он вышел из комы. Кашин смог рассказать, что ударивший его в челюсть человек был похож на футбольного фаната.','2010-11-15 12:07:29');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `o2comments`
--

DROP TABLE IF EXISTS `o2comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `o2comments` (
  `o2comment_id` int(11) NOT NULL auto_increment,
  `o2comment_user` int(11) NOT NULL,
  `o2comment_order2out` int(11) NOT NULL,
  `o2comment_comment` text NOT NULL,
  PRIMARY KEY  (`o2comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `o2comments`
--

LOCK TABLES `o2comments` WRITE;
/*!40000 ALTER TABLE `o2comments` DISABLE KEYS */;
INSERT INTO `o2comments` VALUES (20,1,2,'qqq'),(19,1,2,'qqq'),(18,1,2,'qqq'),(17,1,2,'qqq'),(16,1,2,'qqq'),(15,1,2,'qqq'),(14,1,2,'qqq'),(21,1,2,'qqq'),(22,1,2,'qqq'),(23,1,2,'qqq'),(24,1,8,'asfdfas'),(25,1,8,'asfdfas'),(26,2,8,'dfasf'),(27,2,8,'zxvzx'),(28,2,9,'sdfasd'),(29,2,9,'ZScZX'),(30,2,8,'asfdfa gsgdfgfsdfgsdf'),(31,1,8,'zvxzcvz'),(32,1,8,'fgsd'),(33,2,10,'hgjh'),(34,2,10,'zCXzX'),(35,2,4,'test comment'),(36,2,4,'оппа!'),(37,1,4,'kl;ml;');
/*!40000 ALTER TABLE `o2comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ocomments`
--

DROP TABLE IF EXISTS `ocomments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ocomments` (
  `ocomment_id` int(11) NOT NULL auto_increment,
  `ocomment_user` int(11) NOT NULL,
  `ocomment_order` int(11) unsigned zerofill NOT NULL,
  `ocomment_comment` text NOT NULL,
  PRIMARY KEY  (`ocomment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ocomments`
--

LOCK TABLES `ocomments` WRITE;
/*!40000 ALTER TABLE `ocomments` DISABLE KEYS */;
INSERT INTO `ocomments` VALUES (1,2,00000000001,'vcvncvbncvncv'),(2,2,00000000007,'ncvbncv'),(3,3,00000000001,'Трям!'),(4,2,00000000001,'eee'),(5,2,00000000001,'uu'),(6,2,00000000001,'фур-р-р. съем'),(7,2,00000000001,'фур-р-р. съем'),(8,2,00000000001,'no-no'),(9,2,00000000005,'sss'),(10,2,00000000005,'fff'),(11,3,00000000001,'o_O'),(12,3,00000000032,'wwwwwwwwwwwwwwwwwwww\nsss'),(13,2,00000000034,'asdasdwq'),(14,3,00000000034,'sdfsdfsdwee'),(15,1,00000000040,'первый нах'),(16,2,00000000040,'второй'),(17,3,00000000039,'safd'),(18,2,00000000039,'и йа!'),(19,1,00000000039,'Loading Time Base Classes  	0.0028\nController Execution Time ( Admin / ShowOrderDetails )  	0.0141\nTotal Execution Time  	0.0170'),(20,2,00000000043,'=)'),(21,2,00000000042,'sdfsdf'),(22,3,00000000042,'sdfsdfsdf'),(23,3,00000000042,'sdfsdf');
/*!40000 ALTER TABLE `ocomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `odetails`
--

DROP TABLE IF EXISTS `odetails`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `odetails` (
  `odetail_id` int(11) NOT NULL auto_increment,
  `odetail_client` int(11) NOT NULL,
  `odetail_manager` int(11) NOT NULL,
  `odetail_order` int(11) unsigned NOT NULL,
  `odetail_link` text NOT NULL,
  `odetail_shop_name` varchar(255) NOT NULL,
  `odetail_product_name` varchar(255) NOT NULL,
  `odetail_product_color` varchar(255) NOT NULL,
  `odetail_product_size` varchar(255) NOT NULL,
  `odetail_product_amount` smallint(6) NOT NULL,
  `odetail_status` enum('available','not_available','not_available_color','not_available_size','not_available_count','deleted') NOT NULL default 'not_available',
  PRIMARY KEY  (`odetail_id`),
  KEY `odetail_client` (`odetail_client`),
  KEY `odetail_manager` (`odetail_manager`),
  KEY `odetail_order` (`odetail_order`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `odetails`
--

LOCK TABLES `odetails` WRITE;
/*!40000 ALTER TABLE `odetails` DISABLE KEYS */;
INSERT INTO `odetails` VALUES (1,56,91,1,'http://books.ru/phpAds/click.php3?bannerID=802','ozon','Книга Идеальная Архитектура','белый','2',1,'available'),(4,56,91,1,'http://amazon.com','amazon','kindle','красный','5',1,'deleted'),(5,56,91,1,'http://www.books.ru/shop/books/791571?mpc=new','amazon.com','book','зеленый','8',1,'available'),(6,56,91,1,'http://www.books.ru/shop/books/791571?mpc=new','amazon.com','book','синий','12',1,'deleted'),(9,2,158,8,'http://xado.ru/gel-revitalizant-dlya-dizelnogo-dvigatelya','','1','1','1',1,'not_available'),(10,2,158,8,'http://www.amazon.com/','','1','1','1',1,'available'),(11,2,158,8,'http://xado.ru/gel-revitalizant-dlya-dizelnogo-dvigatelya','xado','1','1','1',1,'not_available'),(12,2,158,8,'http://www.003.ru/product-246579742.html','','1','1','1',1,'available'),(17,2,158,8,'http://teralabs.ru/','','w','w','2',2,'not_available'),(18,2,158,8,'http://damochka.ru','','w','w','2',2,'not_available'),(19,2,158,8,'http://teralabs.ru/','','w','w','2',2,'not_available'),(42,2,152,11,'http://www.003.ru/product-76504957.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available'),(43,2,152,11,'http://xado.ru/gel-revitalizant-dlya-dizelnogo-dvigatelya','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available'),(44,2,149,12,'http://amazon.com','','фотоапарат','yellow','30х40',57,'not_available'),(45,2,149,12,'http://q8.com/links.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available'),(46,2,149,12,'http://www.003.ru/catalog-423003.html','','Ноутбук Dell Inspiron Mini 1012','yellow','30х40',57,'not_available'),(48,2,3,13,'http://teralabs.ru/','','w','w','2',2,'not_available'),(49,2,3,13,'http://www.003.ru/product-82123926.html','','Смартфон Apple iPhone 4 16GB Black','чёрный','58.6 x 9.3 x 115.2 мм',23,'not_available'),(55,2,107,14,'http://www.003.ru/product-246579742.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',1,'not_available'),(56,2,107,15,'http://www.003.ru/product-76504957.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',1,'not_available'),(57,2,103,16,'http://www.bolero.ru/books/9785170708901.html','','Метро 2033. В интересах революции','yellow','30х40',57,'not_available'),(63,2,103,17,'http://www.003.ru/product-247276864.html','','Смартфон Apple iPhone 4 16GB Black','чёрный','58.6 x 9.3 x 115.2 мм',23,'not_available'),(65,2,149,18,'http://www.003.ru/product-76504957.html','','Метро 2033. В интересах революции','yellow','30х40',57,'not_available'),(66,2,149,19,'http://xado.ru/gel-revitalizant-dlya-dizelnogo-dvigatelya','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available'),(67,2,149,20,'http://www.bolero.ru/books/9785170708901.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available'),(88,2,149,21,'http://www.003.ru/product-247727086.html','003','Черный властелин','чёрный','2м',1,'not_available'),(89,2,149,21,'http://www.003.ru/product-247727086.html','003','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',1,'not_available'),(91,2,149,21,'http://www.003.ru/product-247727086.html','','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',1,'not_available'),(135,2,107,25,'http://www.003.ru/product-249162676.html','','Ноутбук Samsung N145-JP01','black','11&quot;',17,'not_available'),(136,2,152,26,'http://www.003.ru/product-249162676.html','','Ноутбук Samsung N145-JP01','black','11&quot;',1,'not_available'),(137,2,152,26,'http://teralabs.ru/','sfb','товар','w','111',2,'not_available'),(140,2,107,27,'https://cards.wmtransfer.com/ru/products/tariff/payspark','ozon','одежда','красный','23',1,'not_available'),(141,2,107,27,'http://item.gmarket.co.kr/detailview/Item.asp?goodscode=183228815','gmarket','одежда','234','32',3,'not_available'),(142,2,149,28,'http://teralabs.ru/','popka','товар','синий','111',2,'not_available'),(143,2,158,29,'http://teralabs.ru/','фывыфвв','товар','синий','111',1,'not_available'),(144,2,3,30,'http://english.gmarket.co.kr/challenge/neo_order/basket_order.asp','gmarket.co.kr','одежда','красный','12',1,'not_available'),(145,2,3,30,'http://item.gmarket.co.kr/detailview/Item.asp?goodscode=183228815','12343','одежда1','красны','12',123,'not_available'),(146,2,107,31,'http://item.gmarket.co.kr/detailview/Item.asp?goodscode=183228815','gmarket','одежда','красный','12',2,'not_available'),(147,2,107,31,'https://cards.wmtransfer.com/ru/products/tariff/payspark','12343','одежда','красны','3d2',4,'not_available'),(148,2,3,32,'http://www.003.ru/product-247727086.html','bolero.ru','Автомобильный компрессор SBM PCC-252-Lt','yellow','30х40',57,'not_available_color'),(149,2,107,33,'http://item.gmarket.co.kr/detailview/Item.asp?goodscode=183228815','gmarket','одежда','красный','123',1,'not_available'),(151,2,107,33,'https://cards.wmtransfer.com/ru/products/tariff/payspark','ozon','одежда','черный','12',1,'not_available'),(152,2,3,34,'http://teralabs.ru/','1w1w1','товар','w','111',1,'not_available'),(154,2,149,35,'http://www.003.ru/product-247305727.html','003','Ноутбук Samsung N145-JP01','black','11&quot;',1,'not_available'),(155,2,107,36,'http://www.003.ru/product-247305727.html','003','Ноутбук Samsung N145-JP01','black','11&quot;',1,'not_available'),(156,2,103,37,'http://www.003.ru/product-247305727.html','003','Ноутбук Samsung N145-JP01','black','11&quot;',3,'not_available'),(157,2,107,38,'http://www.003.ru/product-247727086.html','360','Метро 2033. В интересах революции','yellow','30х40',1,'not_available'),(158,2,3,39,'http://www.003.ru/product-247727086.html','rrr','фотоапарат','yellow','30х40',1,'not_available'),(161,2,149,40,'http://www.003.ru/product-247727086.html','ozon','Шевели ластами Sammy’S Avonturen: De Geheime Doorgang','red','30х40',1,'not_available'),(163,2,3,41,'http://www.003.ru/product-247727086.html','amazon','Черный властелин','чёрный','58.6 x 9.3 x 115.2 мм',1,'deleted'),(164,2,3,41,'http://www.003.ru/product-247727086.html','eer','Смартфон Apple iPhone 4 16GB Black','чёрный','30х40',1,'available'),(165,2,3,42,'http://teralabs.ru/','1w1w1','товар','синий','100х300',2,'available'),(167,2,107,43,'http://teralabs.ru/','фывыфвв','adsdsdsd','синий','22233',22233,'not_available');
/*!40000 ALTER TABLE `odetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders` (
  `order_id` int(11) unsigned NOT NULL auto_increment,
  `order_client` int(11) NOT NULL,
  `order_manager` int(11) NOT NULL,
  `order_weight` float unsigned NOT NULL,
  `order_cost` float unsigned NOT NULL default '0',
  `order_manager_cost` float unsigned NOT NULL default '0',
  `order_country` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` enum('proccessing','not_available','not_payed','payed','sended','deleted') NOT NULL,
  `comment_for_manager` tinyint(1) NOT NULL default '0',
  `comment_for_client` tinyint(1) NOT NULL default '0',
  `order_address` text character set cp1250 NOT NULL,
  `order_login` varchar(32) NOT NULL,
  `order_delivery_cost` float unsigned NOT NULL default '0',
  `order_comission` float unsigned NOT NULL default '0',
  `order_manager_comission` float unsigned NOT NULL default '0',
  `order_products_cost` float unsigned NOT NULL default '0',
  `package_delivery_cost` text NOT NULL,
  `order_country_from` int(11) NOT NULL,
  `order_country_to` int(11) NOT NULL,
  `order_payed_to_manager` tinyint(1) NOT NULL default '0',
  `order_shop_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`order_id`),
  KEY `order_client` (`order_client`,`order_manager`),
  KEY `order_manager` (`order_manager`),
  KEY `order_client_2` (`order_client`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,3,60,1320,1260,3,'2010-08-30 02:20:13','sended',0,0,'Second Tester / 1214, sdfsdf, sdd, Moscow, Russia\r\n<br />Mob. 2424452','client',200,10,5,1000,'DHL: 100р<br />UPS: 200р<br />',5,3,0,''),(5,2,3,20,12100,11550,3,'2010-09-08 15:20:55','sended',0,0,'Second Tester / 1214, sdfsdf, sdd, Moscow, Russia\r\n<br />Mob. 2424452','client',1000,10,5,10000,'DHL: 100р<br />UPS: 500р<br />',5,3,1,''),(6,2,3,10,33,0,4,'2010-09-08 23:12:32','deleted',0,0,'Second Tester / 1214, sdfsdf, sdd, Moscow, Russia\r\n<br />Mob. 2424452','client',350,0,0,0,'',5,3,0,''),(7,2,3,200,1650,0,4,'2010-09-08 23:49:50','sended',0,0,'Second Tester / 1214, sdfsdf, sdd, Moscow, Russia\r\n<br />Mob. 2424452','client',300,10,0,1200,'DHL: 1000р<br />UPS: 200р<br />',5,3,0,''),(8,2,158,0,0,0,9,'2010-11-01 19:01:33','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(9,0,0,0,0,0,0,'0000-00-00 00:00:00','not_payed',0,0,'','',0,0,0,0,'',0,0,0,''),(10,0,0,0,0,0,0,'0000-00-00 00:00:00','not_payed',0,0,'','',0,0,0,0,'',0,0,0,''),(11,2,152,0,0,0,10,'2010-11-29 17:29:40','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(12,2,149,0,0,0,4,'2010-11-29 17:45:26','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(13,2,3,2,1122,1071,5,'2010-12-02 14:52:36','proccessing',0,0,'','',20,10,5,1000,'',0,0,0,''),(14,2,107,0,0,0,6,'2010-12-09 16:05:58','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(15,2,107,0,0,0,6,'2010-12-09 16:48:24','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(16,2,103,0,0,0,7,'2010-12-09 16:50:49','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(17,2,103,0,0,0,7,'2010-12-09 17:23:48','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(18,2,149,0,0,0,4,'2010-12-09 18:17:30','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(19,2,149,0,0,0,4,'2010-12-09 18:20:25','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(20,2,149,0,0,0,4,'2010-12-09 18:26:50','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(21,2,149,0,0,0,4,'2010-12-17 13:11:35','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(25,2,107,0,0,0,6,'2011-01-03 02:17:05','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(26,2,152,0,0,0,10,'2011-01-03 11:42:15','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(27,2,107,0,0,0,6,'2011-01-03 16:40:51','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(28,2,149,0,0,0,4,'2011-01-03 16:47:46','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(29,2,158,0,0,0,9,'2011-01-03 17:17:11','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(30,2,3,0,0,0,5,'2011-01-03 17:30:12','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(31,2,107,0,0,0,6,'2011-01-03 17:32:27','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(32,2,3,0,1.1,1.05,5,'2011-01-12 17:22:11','',0,0,'','',0,10,5,1,'',0,0,0,''),(33,2,107,0,0,0,6,'2011-01-12 20:27:47','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(34,2,3,0,0,0,5,'2011-01-17 17:33:04','sended',0,0,'','',0,0,0,0,'',0,0,0,''),(35,2,149,0,0,0,4,'2011-02-03 17:24:30','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(36,2,107,0,0,0,6,'2011-02-03 18:01:46','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(37,2,103,0,0,0,7,'2011-02-03 18:04:26','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(38,2,107,0,0,0,6,'2011-02-04 17:01:17','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(39,2,3,0,0,0,5,'2011-02-04 17:08:25','proccessing',0,0,'','',0,0,0,0,'',0,0,0,''),(40,2,149,0,0,0,4,'2011-02-04 17:44:44','proccessing',1,0,'','',0,0,0,0,'',0,0,0,''),(41,2,3,27.3,2202.2,2102.1,5,'2011-02-04 17:53:25','not_payed',0,0,'','',110,10,5,1892,'',0,0,0,'amazon'),(42,2,3,1,1320,1260,5,'2011-02-08 18:11:22','not_payed',0,0,'','',200,10,5,1000,'',0,0,0,'1w1w1'),(43,2,107,0,0,0,6,'2011-02-08 19:25:47','proccessing',1,0,'','',0,0,0,0,'',0,0,0,'фывыфвв'),(44,0,0,0,0,0,0,'0000-00-00 00:00:00','not_payed',0,0,'','',0,0,0,0,'',0,0,0,'');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders2out`
--

DROP TABLE IF EXISTS `orders2out`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders2out` (
  `order2out_id` int(10) unsigned NOT NULL auto_increment,
  `order2out_user` int(11) NOT NULL,
  `order2out_ammount` int(11) NOT NULL,
  `order2out_tax` int(11) NOT NULL,
  `order2out_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `order2out_status` enum('processing','payed') NOT NULL default 'processing',
  `comment_for_admin` tinyint(1) NOT NULL default '0',
  `comment_for_client` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`order2out_id`),
  KEY `order2out_user` (`order2out_user`),
  KEY `order2out_time` (`order2out_time`),
  KEY `order2out_status` (`order2out_status`),
  CONSTRAINT `orders2out_ibfk_1` FOREIGN KEY (`order2out_user`) REFERENCES `users` (`user_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders2out`
--

LOCK TABLES `orders2out` WRITE;
/*!40000 ALTER TABLE `orders2out` DISABLE KEYS */;
INSERT INTO `orders2out` VALUES (4,2,500,0,'2010-11-22 15:27:44','processing',0,0),(7,2,228,0,'2010-11-22 15:48:59','processing',0,0),(8,2,12,0,'2010-12-01 13:27:06','processing',0,0);
/*!40000 ALTER TABLE `orders2out` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `packages` (
  `package_id` int(11) unsigned NOT NULL auto_increment,
  `package_client` int(11) NOT NULL,
  `package_manager` int(11) NOT NULL,
  `package_weight` float unsigned NOT NULL,
  `package_cost` float unsigned NOT NULL,
  `package_manager_cost` float unsigned NOT NULL,
  `package_delivery_cost` float unsigned NOT NULL,
  `package_declaration_cost` float unsigned NOT NULL,
  `package_join_cost` float unsigned NOT NULL,
  `package_comission` int(11) unsigned NOT NULL,
  `package_manager_comission` int(11) NOT NULL,
  `package_join_count` int(11) unsigned NOT NULL default '0',
  `package_join_ids` varchar(255) NOT NULL,
  `package_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `package_status` enum('not_payed','payed','sent','deleted') NOT NULL default 'not_payed',
  `declaration_status` enum('not_completed','completed','help') NOT NULL default 'not_completed',
  `comment_for_manager` tinyint(1) NOT NULL default '0',
  `comment_for_client` tinyint(1) NOT NULL default '0',
  `package_address` text NOT NULL,
  `package_trackingno` varchar(255) default NULL,
  `package_delivery` int(11) NOT NULL,
  `package_country_from` int(11) NOT NULL,
  `package_country_to` int(11) NOT NULL,
  `package_payed_to_manager` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`package_id`),
  KEY `order_client` (`package_client`,`package_manager`),
  KEY `package_status` (`package_status`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,2,3,2,600,400,100,100,0,400,200,0,'','2010-09-23 16:47:27','sent','help',0,1,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, 3<br />Тел. 12255454','1е33',2,5,3,1),(2,2,3,123,1400,1200,1000,0,0,400,200,0,'','2010-09-23 17:42:29','not_payed','completed',0,0,'Tamiko Kona / 22222, \nTverskaya 5, 24,\nг.Nagano,\nТел. 12255454','0002',2,5,3,1),(3,2,3,20,400,0,0,0,0,400,0,0,'','2010-09-23 18:49:42','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, 3<br />Тел. 12255454',NULL,0,5,3,0),(4,2,3,30,400,0,0,0,0,400,0,0,'','2010-09-23 18:49:56','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, 3<br />Тел. 12255454',NULL,0,5,3,0),(5,2,3,2345,400,0,0,0,0,400,0,0,'','2010-09-23 22:18:02','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(6,50,3,23452,400,0,0,0,0,400,0,0,'','2010-09-23 22:26:54','deleted','not_completed',0,0,'Sella Feriartos / 11122, Tverskaya 5, 24, г.Bubno, Венгрия<br />Тел. 1221222',NULL,0,5,4,0),(7,51,3,3452,400,0,0,0,0,400,0,0,'','2010-09-23 22:27:06','deleted','not_completed',0,0,'assd ass / 232322, Tverskaya 5, 24, г.dddd, Россия<br />Тел. 23232',NULL,0,5,3,0),(8,2,3,4123,1400,0,1000,0,0,400,0,0,'','2010-09-23 22:28:48','deleted','completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(9,2,3,21,400,0,0,0,0,400,0,0,'','2010-09-23 22:28:57','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(10,2,3,86,400,0,0,0,0,400,0,0,'','2010-09-24 13:37:19','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(11,2,3,154,400,0,0,0,0,400,0,0,'','2010-09-24 13:51:09','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(12,2,3,155,400,0,0,0,0,400,0,0,'','2010-09-24 13:53:39','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(13,2,3,1,400,0,0,0,0,400,0,0,'','2010-09-24 14:24:09','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(14,2,3,3,400,0,0,0,0,400,0,0,'','2010-09-24 14:24:16','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(15,2,3,6,400,0,0,0,0,400,0,0,'','2010-09-24 14:24:24','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(16,2,3,10,400,0,0,0,0,400,0,0,'','2010-09-24 14:24:35','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(17,2,3,165,1400,0,1000,0,0,400,0,0,'','2010-09-24 14:31:54','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(18,2,3,1,400,0,0,0,0,400,0,0,'','2010-09-24 14:32:23','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(19,2,3,2,400,0,0,0,0,400,0,0,'','2010-09-24 14:32:31','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(20,2,3,3,400,0,0,0,0,400,0,0,'','2010-09-24 14:32:41','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(21,2,3,4,400,0,0,0,0,400,0,0,'','2010-09-24 14:32:49','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(22,2,3,7,1400,0,1000,0,0,400,0,0,'','2010-09-24 14:33:46','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(23,2,3,9,1400,0,1000,0,0,400,0,0,'','2010-09-24 14:35:34','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(24,2,3,10,1400,0,1000,0,0,400,0,0,'','2010-09-24 15:15:14','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(25,2,3,452,1500,0,1000,100,0,400,0,0,'','2010-09-24 15:16:16','deleted','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(26,2,3,2425,1400,0,1000,0,0,400,0,0,'','2010-09-24 15:17:09','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(27,2,3,2435,1500,0,1000,100,0,400,0,0,'','2010-09-24 15:21:26','deleted','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(28,2,3,617,1600,0,1000,100,100,400,0,1,'','2010-09-24 15:29:40','deleted','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(29,2,3,3052,1700,0,1000,100,200,400,0,2,'','2010-09-24 15:30:08','deleted','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(30,2,3,452,1400,0,1000,0,0,400,0,0,'','2010-09-24 17:09:23','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(31,2,3,11,600,400,100,100,0,400,200,0,'','2010-09-24 17:09:32','sent','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','313131',2,5,3,1),(32,2,3,123,1500,1300,1000,100,0,400,200,0,'','2010-09-24 17:09:39','deleted','completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия\nТел. 12255454','1111111111111',2,5,3,0),(33,2,3,3504,1700,1500,1000,0,300,400,200,3,'','2010-09-24 17:11:44','sent','completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия\nТел. 12255454','333333333',2,5,3,1),(34,2,3,2,400,0,0,0,0,400,0,0,'','2010-09-25 13:49:18','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(35,2,3,23,400,0,0,0,0,400,0,0,'','2010-09-25 13:50:11','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(36,2,3,25,600,0,100,0,100,400,0,1,'','2010-09-25 13:52:19','deleted','completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,2,5,3,0),(37,2,3,2,400,0,0,0,0,400,0,0,'','2010-09-25 13:54:11','deleted','not_completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454',NULL,0,5,3,0),(38,2,3,27,700,0,100,0,200,400,0,2,'','2010-09-25 13:54:48','sent','completed',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','1112222',2,5,3,0),(39,2,3,500,1500,0,1000,100,0,400,0,0,'','2010-09-25 13:59:45','sent','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','asdsdf',2,5,3,0),(40,2,3,11,400,0,0,0,0,400,0,0,'','2010-09-25 14:21:45','sent','not_completed',0,1,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','40',0,5,3,0),(41,2,3,11,1000,800,500,100,0,400,200,0,'','2010-10-22 17:48:07','sent','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','41414141',1,5,3,1),(42,2,3,12,1000,800,500,100,0,400,200,0,'','2010-10-22 18:05:22','sent','help',0,0,'Tamiko Kona / 22222, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 12255454','424242',1,5,3,1),(43,2,3,12,894,694,444,50,0,400,200,0,'','2010-10-25 16:50:12','not_payed','help',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,8,0),(44,162,3,12.5,500,300,100,0,0,400,200,0,'','2010-10-25 17:50:42','deleted','completed',0,0,'Ivanov Yury / 115000, Lenina 15, 24, г.Moscow, Россия<br />Тел. 4548498',NULL,2,5,3,0),(45,162,3,12.5,900,700,500,0,0,400,200,0,'','2010-10-25 18:01:08','sent','completed',0,0,'Ivanov Yury / 115000, Lenina 15, 24, г.Moscow, РоссияТел. 4548498','asdasdasd',1,5,3,0),(46,114,3,1,400,200,0,0,0,400,200,0,'','2010-10-30 12:06:21','payed','not_completed',0,0,'c c / 1, c, г.c, Венгрия<br />Тел. 1','1112222',0,5,4,0),(47,55,3,12,400,200,0,0,0,400,200,0,'','2010-11-08 15:41:09','not_payed','not_completed',0,0,'Craftsman Craftsman / 156456, Tverskaya 5, 24, г.Moscow, Россия<br />Тел. 745567505',NULL,0,5,3,0),(48,2,3,2,950,750,500,50,0,400,200,0,'','2010-11-10 14:10:51','deleted','help',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия&lt;br /&gt;Тел. 72366489352',NULL,1,5,3,0),(49,2,3,1,500,300,100,0,0,400,200,0,'','2010-12-20 16:55:38','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0),(50,2,3,12.75,500,300,100,0,0,400,200,0,'','2010-12-28 22:55:18','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0),(51,2,3,12.75,500,300,100,0,0,400,200,0,'','2010-12-28 23:06:37','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0),(52,2,3,12.75,400,200,0,0,0,400,200,0,'','2010-12-28 23:06:51','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,0,5,3,0),(53,2,3,12.75,400,200,0,0,0,400,200,0,'','2010-12-28 23:07:47','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,0,5,3,0),(54,2,3,25.5,650,450,100,50,100,400,200,1,'','2010-12-31 12:59:43','payed','help',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0),(55,2,3,25.5,600,400,100,0,100,400,200,1,'','2011-01-02 23:36:08','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0),(56,2,3,26.5,800,600,200,0,200,400,200,2,'','2011-01-02 23:36:51','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия&lt;br /&gt;Тел. 72366489352',NULL,1,5,3,0),(58,2,3,12.75,900,700,500,0,0,400,200,0,'','2011-01-11 22:49:21','deleted','completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(59,2,3,12.75,900,700,500,0,0,400,200,0,'','2011-01-11 23:16:56','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(60,2,3,12.75,900,700,500,0,0,400,200,0,'','2011-01-11 23:17:03','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(61,2,3,12.75,900,700,500,0,0,400,200,0,'','2011-01-11 23:17:08','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(62,2,3,28.2,900,700,200,0,300,400,200,3,'','2011-01-11 23:25:20','not_payed','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(69,2,3,2,400,200,0,0,0,400,200,0,'','2011-01-13 10:52:53','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,0,5,3,0),(70,2,3,27.5,700,500,200,0,100,400,200,1,'','2011-01-13 10:53:45','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(71,2,3,1,900,700,500,0,0,400,200,0,'','2011-01-13 10:58:04','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(72,2,3,28.5,800,600,200,0,200,400,200,2,'','2011-01-13 10:59:47','sent','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(73,2,3,25.5,700,500,200,0,100,400,200,1,'','2011-01-13 13:46:49','sent','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия Тел. 72366489352',NULL,1,5,3,0),(74,2,3,2,900,700,500,0,0,400,200,0,'','2011-02-06 22:06:49','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,1,5,3,0),(75,2,3,3.5,900,700,500,0,0,400,200,0,'','2011-02-06 22:07:09','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,1,5,3,0),(76,2,3,2.7,900,700,500,0,0,400,200,0,'','2011-02-06 22:07:22','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,1,5,3,0),(77,2,3,8.2,1000,800,500,0,100,400,200,1,'76+75+74','2011-02-06 22:07:54','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,1,5,3,0),(78,2,3,14.4,1100,900,500,0,200,400,200,2,'77+76+75','2011-02-06 22:18:32','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,1,5,3,0),(79,2,3,23,400,200,0,0,0,400,200,0,'','2011-02-09 14:29:43','deleted','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,0,5,3,0),(80,2,3,16.4,800,600,100,0,300,400,200,3,'78+74','2011-02-12 14:07:35','not_payed','not_completed',0,0,'Tamiko Kona / 354341, Tverskaya 5, 24, г.Nagano, Россия<br />Тел. 72366489352',NULL,2,5,3,0);
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL auto_increment COMMENT 'ид записи',
  `payment_amount_rur` int(11) default NULL COMMENT 'необязательное поле, сумма в рублях(например при пополнении счета)',
  `payment_from` varchar(32) NOT NULL default '' COMMENT 'ид юзера который производил оплату или источник откуда происходило зачисление',
  `payment_to` varchar(32) NOT NULL default '' COMMENT 'ид юзера который получил оплату или куда были перечисленны деньги (например ВМ кошелек)',
  `payment_tax` varchar(32) NOT NULL COMMENT 'такса которую взяла система (10% или 400р)',
  `payment_amount_from` float NOT NULL COMMENT 'сумма которую уплатил юзер',
  `payment_amount_to` float NOT NULL COMMENT 'конечная сумма которую получил продавец (с вычетом таксы и тп)',
  `payment_amount_tax` float NOT NULL COMMENT 'сумма которую получилоа система как таксу',
  `payment_purpose` text NOT NULL COMMENT 'назначение платежа',
  `payment_time` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'точное время когда был произведен платеж',
  `payment_comment` text NOT NULL COMMENT 'коментарий к платежу',
  `payment_type` enum('in','out','inner') NOT NULL COMMENT 'тип транзакции',
  `payment_status` enum('complite','processing','abort','cancel','reserved') NOT NULL COMMENT 'статус транзакции',
  `payment_transfer_info` varchar(255) NOT NULL COMMENT 'Доп. информация по транзакции',
  `payment_transfer_order_id` int(11) NOT NULL COMMENT 'Номер заказа  для платежных систем (не путать с order.order_id)',
  `payment_transfer_sign` varchar(255) NOT NULL default '' COMMENT 'Цифровая подпись транзакции (может облегчить жизнь при поиске данных в логах)',
  PRIMARY KEY  (`payment_id`),
  UNIQUE KEY `payment_transfer_order_id` (`payment_transfer_order_id`),
  KEY `payment_from` (`payment_from`),
  KEY `payment_to` (`payment_to`),
  KEY `payment_time` (`payment_time`),
  KEY `payment_type` (`payment_type`),
  KEY `payment_status` (`payment_status`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=cp1251 COMMENT='таблица истории платежей';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (135,31,'LP[sender_phone]:+79061857380','2','3%',1.03939,1.00912,0.0302735,'зачисление на счет пользователя','2011-01-20 18:18:18','Пополнение счета пользователем №2 на сумму 1у.е.(В рублях Ваша сумма составит 31 рублей)','in','complite','LP Transfer ID:8624316',22109,'on0sCrM9stuusvd6OizY6OXeJAg='),(136,3016,'WM[LMI_PAYER_PURSE]:','2','0.8%',100.82,96.9423,3.87769,'зачисление на счет пользователя','2011-01-20 23:39:23','В рублях Ваша сумма составит 3016 рублей','in','complite','WM Transfer ID:491',22122,'945B09432E6A709758ADBD1602E37F49'),(137,3016,'WM[LMI_PAYER_PURSE]:R16535635902','2','0.8%',100.82,100.02,0.800159,'зачисление на счет пользователя','2011-01-20 23:41:49','LMI_PAYER_PURSE','in','complite','WM Transfer ID:207',22081,'E941ABF720F5144410D1B5FDCDF97271'),(138,3082,'RK payment','2','3%',103.026,100.026,3.00077,'зачисление на счет пользователя','2011-01-20 23:44:52','В рублях Ваша сумма составит 3082 рублей','in','complite','RK Transfer',22087,'8C60F6429501076F6B4E8958B3DD0E7F'),(139,1,'W1[WMI_TO_USER_ID]:','2','4%',0.0334284,0.0321427,0.00128571,'зачисление на счет пользователя','2011-01-21 12:17:16','В рублях Ваша сумма составит 1 рублей','in','complite','W1 Transfer ID:343205891709',22093,'hvgt9V+uPQgQCYH26tBir4zKV/4='),(140,1,'W1[WMI_TO_USER_ID]:115079248867','2','4%',0.0334284,0.0321427,0.00128571,'зачисление на счет пользователя','2011-01-21 12:23:10','В рублях Ваша сумма составит 32 рублей','in','complite','W1 Transfer ID:343187560935',22111,'6pdUrmF3tk0NwJJ2xrco/xPQUYU='),(141,29584,'WM[LMI_PAYER_PURSE]:R16535635902','2','0.8%',1008.01,1000.01,8.00008,'зачисление на счет пользователя','2011-02-04 15:04:38','ree','in','complite','WM Transfer ID:76',22030,'B3C353461B04FD326ADE7BFB44EAEF35'),(142,NULL,'2','1','',2202.2,2002,200.2,'оплата заказа','2011-02-04 15:06:14','№ 41','inner','complite','',0,'');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pcomments`
--

DROP TABLE IF EXISTS `pcomments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pcomments` (
  `pcomment_id` int(11) NOT NULL auto_increment,
  `pcomment_user` int(11) NOT NULL,
  `pcomment_package` int(11) NOT NULL,
  `pcomment_comment` text NOT NULL,
  PRIMARY KEY  (`pcomment_id`),
  KEY `ocomment_user` (`pcomment_user`,`pcomment_package`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pcomments`
--

LOCK TABLES `pcomments` WRITE;
/*!40000 ALTER TABLE `pcomments` DISABLE KEYS */;
INSERT INTO `pcomments` VALUES (1,2,9,'dfasd'),(2,3,10,'safasdf'),(3,3,10,'gsdfgsdfgs'),(4,3,24,'dadfadadfadfadf'),(5,2,24,'hdfghd'),(6,2,9,'dfghg'),(7,3,1,'test'),(8,2,1,'test 2'),(21,1,40,'edit test 2'),(10,3,2,'первый нах'),(11,3,2,'второй комент'),(12,3,2,'3й, замыкающий'),(13,3,2,'тестовый комент с версткой'),(14,2,1,'офигеть!'),(15,2,2,'тестовый комент с версткой'),(16,2,2,'тест от клиента'),(17,3,43,'Ну шо, будем посылочку оплачивать? А?'),(18,1,43,'кука-рукка'),(19,1,43,'кука-рукка'),(20,1,43,'кука-рукка'),(23,1,40,'edit test'),(25,1,78,'rrrrrrqq'),(27,1,78,'FFFFqq'),(28,3,1,'ewf'),(29,3,40,'DA-DA-DA'),(30,3,1,'manager comment'),(31,3,78,'manager comment [edit by admin]');
/*!40000 ALTER TABLE `pcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricelist`
--

DROP TABLE IF EXISTS `pricelist`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pricelist` (
  `pricelist_id` int(11) NOT NULL auto_increment,
  `pricelist_weight` float unsigned NOT NULL,
  `pricelist_price` float unsigned NOT NULL,
  `pricelist_delivery` int(11) NOT NULL,
  `pricelist_country_from` int(11) NOT NULL,
  `pricelist_country_to` int(11) NOT NULL,
  PRIMARY KEY  (`pricelist_id`),
  KEY `pricelist_delivery` (`pricelist_delivery`),
  KEY `pricelist_country_from` (`pricelist_country_from`),
  KEY `pricelist_country_to` (`pricelist_country_to`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pricelist`
--

LOCK TABLES `pricelist` WRITE;
/*!40000 ALTER TABLE `pricelist` DISABLE KEYS */;
INSERT INTO `pricelist` VALUES (1,500,200,1,5,3),(2,10,350,1,6,3),(3,20,500,1,5,3),(4,30,700,2,6,3),(5,10000,1000,2,5,3),(7,10,300,1,5,8),(8,30,444,1,5,8),(9,5,5.55,1,5,8),(10,1,1,0,0,0),(11,10,10,0,0,0),(12,100,100,0,0,0),(13,1,1,0,0,0),(14,1,1,0,0,0),(15,1,1,1,5,8),(16,1,1,0,0,0),(17,1,1,0,0,0),(18,2,2,0,0,0),(19,111,111,0,0,0),(20,1,1,0,0,0),(21,111111,111111,0,0,0),(22,2,2,0,0,0),(23,1,1,0,5,8),(24,1,1,2,5,8),(25,2,2,2,5,8),(26,3,3,2,5,8),(27,11,111,27,5,8),(28,100,100,2,5,3),(38,1,2,29,44,18),(39,500,14,2,15,27);
/*!40000 ALTER TABLE `pricelist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scategories`
--

DROP TABLE IF EXISTS `scategories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `scategories` (
  `scategory_id` int(11) NOT NULL auto_increment,
  `scategory_name` varchar(256) NOT NULL,
  PRIMARY KEY  (`scategory_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `scategories`
--

LOCK TABLES `scategories` WRITE;
/*!40000 ALTER TABLE `scategories` DISABLE KEYS */;
INSERT INTO `scategories` VALUES (1,'Одежда'),(2,'Электроника'),(3,'Бытовая техника'),(4,'Разное'),(5,'Непонятное');
/*!40000 ALTER TABLE `scategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scomments`
--

DROP TABLE IF EXISTS `scomments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `scomments` (
  `scomment_id` int(11) NOT NULL auto_increment,
  `scomment_user` int(11) NOT NULL,
  `scomment_shop` int(11) NOT NULL,
  `scomment_comment` text NOT NULL,
  PRIMARY KEY  (`scomment_id`),
  KEY `scomment_user` (`scomment_user`,`scomment_shop`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `scomments`
--

LOCK TABLES `scomments` WRITE;
/*!40000 ALTER TABLE `scomments` DISABLE KEYS */;
INSERT INTO `scomments` VALUES (1,56,1,'Тестовый коммент'),(2,56,1,'Еще один коммент'),(3,56,5,'О да это классный магазин'),(4,57,1,'Какой-то блин комент (с)омни'),(5,2,1,'Новые качебурьки!'),(6,2,8,'Сушай, все спЭлое и сладкое! Сам пробовал, мамай клянуся!'),(7,2,11,'Китайская товара, однако!'),(8,2,10,'Сушай, все спЭлое и сладкое! Сам пробовал, мамай клянуся!');
/*!40000 ALTER TABLE `scomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `shops` (
  `shop_id` int(11) NOT NULL auto_increment,
  `shop_name` varchar(128) character set latin1 NOT NULL,
  `shop_country` int(11) NOT NULL,
  `shop_scategory` int(11) NOT NULL,
  `shop_desc` text NOT NULL,
  `shop_user` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`shop_id`),
  KEY `shop_name` (`shop_name`,`shop_country`),
  KEY `shop_scategory` (`shop_scategory`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=cp1251;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
INSERT INTO `shops` VALUES (19,'http://qwqwq.ww',27,1,'qwqwqw',2),(9,'http://www.m18.com',252,1,'япона-мода!',2),(10,'http://www.amazon.com/',201,4,'Мандарыны, лымоны, фейхуя, хурма сладкий! Покупай дарагой!',2),(11,'http://www.360buy.com',252,3,'made in джапония',2),(12,'http://www.eachnet.com',252,1,'Китайская что ли одежда',2),(13,'http://www.paipai.com/',252,4,'Все виды ассенизаторских услуг!',2),(14,'http://www.alibaba.com/',69,4,'Тоже магазин',2),(15,'http://www.gmarket.co.kr/',6,1,'Свежая одежда прямиком со складов черкизона!',2),(16,'http://www.taobao.com',252,1,'Тоже одежда... на русского человека правда может не налезть...',2),(17,'http://www.ebay.com',201,4,'Ебай ком',2),(18,'http://www.dangdang.com',252,5,'Сипулька!',2);
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_login` varchar(32) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_group` enum('manager','client','admin','system') NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_coints` float NOT NULL default '0',
  `user_deleted` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `user_group` (`user_group`),
  KEY `user_password` (`user_password`),
  KEY `user_deleted` (`user_deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=cp1251 COMMENT='возможно таблица не понадобится';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (0,'mar-li8','3b55bba273b6b375c1d80034172f56e3','client','burdo.marsel@gmail.com',0,2),(1,'admin','21232f297a57a5a743894a0e4a801fc3','admin','omice@ya.ru',2217.91,0),(2,'client','62608e08adc29a8d6dbc9754e659f125','client','omice@yandex.ru',10777,0),(3,'manager','1d0258c2440a8d19e716292b231e3190','manager','man@gmail.com',6900,0),(44,'Kona','ff19afbff2ee93a2ba9c54d5d807a3f8','client','kona@ya.ru',0,0),(50,'Typo','f7c0e071db137f5ae65382041c7cef4b','client','typo@ya.ru',0,1),(51,'tox2710','887c8c2ee0e2e6a527bd35273962b7e2','client','tox-tox@yandex.ru',0,1),(52,'T-101','698d51a19d8a121ce581499d7b701668','client','kona@ya.rue',0,1),(53,'wqdwq','4eae35f1b35977a00ebd8086c259d4c9','client','f@ye.ee',0,1),(54,'wd','4eae35f1b35977a00ebd8086c259d4c9','client','omice@yawwwex.ru',0,1),(55,'Craftsman','2d618170af5ef5a8ce9d202e412ae8b2','client','at3@yandex.ru',10001,1),(56,'hippout','0a774796aa7ed97bb346c2ba3f5400f3','client','hippout@gmail.com',0,0),(57,'ctest','ff84e4a3351a218b3e2c4a50a3245453','client','typo@ya.rur',0,0),(58,'manager1','202cb962ac59075b964b07152d234b70','manager','man1@local.ru',0,0),(59,'manager2','8df5127cd164b5bc2d2b78410a7eea0c','manager','man2@local',0,1),(61,'tester1','e10adc3949ba59abbe56e057f20f883e','client','tester1@local.ru',0,0),(62,'tester2','202cb962ac59075b964b07152d234b70','client','tester2@local.ru',0,0),(63,'tester3','202cb962ac59075b964b07152d234b70','client','tester3@local.ru',0,0),(64,'tester4','202cb962ac59075b964b07152d234b70','client','tester4@local.ru',0,0),(65,'tester5','202cb962ac59075b964b07152d234b70','client','tester5@local.ru',0,0),(66,'tester6','202cb962ac59075b964b07152d234b70','client','tester6@local.ru',0,0),(67,'tester7','202cb962ac59075b964b07152d234b70','client','tester7@local.ru',0,0),(68,'tester8','202cb962ac59075b964b07152d234b70','client','tester8@local.ru',0,0),(69,'tester9','202cb962ac59075b964b07152d234b70','client','tester9@local.ru',0,0),(70,'tester10','202cb962ac59075b964b07152d234b70','client','tester10@local.ru',0,0),(71,'tester11','202cb962ac59075b964b07152d234b70','client','tester11@local.ru',0,0),(72,'tester12','202cb962ac59075b964b07152d234b70','client','tester12@local.ru',0,0),(73,'tester13','202cb962ac59075b964b07152d234b70','client','tester13@local.ru',0,0),(74,'tester14','202cb962ac59075b964b07152d234b70','client','tester14@local.ru',0,0),(75,'tester15','202cb962ac59075b964b07152d234b70','client','tester15@local.ru',0,0),(76,'tester16','202cb962ac59075b964b07152d234b70','client','tester16@local.ru',0,0),(88,'manager8','202cb962ac59075b964b07152d234b70','manager','manager8@local.ru',0,1),(89,'manager9','d41d8cd98f00b204e9800998ecf8427e','manager','manager9@local.ru',0,0),(90,'manager10','bcdbc41262aa96efc42630746de11d19','manager','manager10@local.ru',0,1),(91,'manager11','0a774796aa7ed97bb346c2ba3f5400f3','manager','manager11@local.ru',0,0),(92,'mmmm','9de37a0627c25684fdd519ca84073e34','manager','m@gmail.com',0,1),(93,'q','7694f4a66316e53c8cdd9d9954bd611d','client','q@q.com',0,1),(94,'p111123','21232f297a57a5a743894a0e4a801fc3','client','pomoika_dlya_facebooka@mail.ru',0,0),(95,'m','6f8f57715090da2632453988d9a1501b','client','m@m.mm',0,1),(96,'n','7b8b965ad4bca0e41ab51de7b31363a1','client','n@n.nn',0,1),(97,'o','d95679752134a2d9eb61dbd7b91c4bcc','client','o@o.oo',0,1),(98,'ppp','62608e08adc29a8d6dbc9754e659f125','client','p@p.com',0,1),(100,'qqq','b2ca678b4c936f905fb82f2733f5297f','manager','qomice@ya.ru',0,1),(101,'qp','8266bb21c655c9dc496209b9f8bac19a','manager','qpomice@ya.ru',0,1),(102,'c1','a9f7e97965d6cf799a529102a973b8b9','client','c1@c.com',0,1),(103,'u','aa36ec52a9e063e870aeb3a53abdb8ac','manager','u@u.com',0,0),(104,'c2','9ab62b5ef34a985438bfdf7ee0102229','client','c2@c.com',0,1),(105,'c3','0a3d72134fb3d6c024db4c510bc1605b','client','c3@c.com',0,1),(106,'p1','ec6ef230f1828039ee794566b9c58adc','manager','p1@p.com',0,1),(107,'p4','f22062dfa46fd089918fad53ada68558','manager','p4@1.com',0,0),(108,'c4','cb7524d792327e4c443d619de5c71a7a','client','c4@c.com',0,1),(109,'c5','25ea1682e16466c0667abdc095920f6c','client','c5@c.com',0,1),(110,'c6','5a34d1edaea4e32871b6f7503ad4727e','client','c6@c.com',0,1),(112,'c7','5a34d1edaea4e32871b6f7503ad4727e','client','c6@c.com1',0,1),(113,'c8','7cd1d2b54911b95b06b1c423bd551f2f','client','c1@c.com8',0,1),(114,'c','37693cfc748049e45d87b8c7d8b9aacd','client','c1@c.com9',0,0),(115,'q1','ff33f1b12213e021c2c4a888141953ba','client','c1@c.comq',0,1),(116,'q2','74d502a7131cdac90eecdfb0531c4e87','client','q@q.com2',0,1),(117,'q3','69855eda6d7282de712fee7eb5235ab1','client','q@q.com3',0,1),(118,';','9eecb7db59d16c80417c72d1e1f4fbf1','client','ww@55.u',0,1),(119,'a1','8a8bb7cd343aa2ad99b7d762030857a2','client','a1@q.com',0,1),(120,'ppp111','8a8bb7cd343aa2ad99b7d762030857a2','client','rrrrrrr@r.com',0,1),(121,'sss1','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1',0,1),(122,'sss2','c81e728d9d4c2f636f067f89cc14862c','client','1@gmail.com12',0,1),(123,'sss3','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com13',0,0),(124,'ss4','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com11',0,1),(125,'ww22','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com144',0,1),(126,'k1','c4ca4238a0b923820dcc509a6f75849b','manager','1@gmail.com1k',0,1),(127,'k3','8ce4b16b22b58894aa86c421e8759df3','manager','omice@ya.rukk',0,1),(128,'aaa','0cc175b9c0f1b6a831c399e269772661','client','a1@q.com2',0,0),(129,'cccc','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1qq',0,1),(130,'nwwwwww','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1ee',0,1),(131,'qqqqqqqqq','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com11111',0,1),(132,'zz','c4ca4238a0b923820dcc509a6f75849b','client','1111@gmail.comq',0,1),(133,'zzaa','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1qqq',0,1),(134,'zzss','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com11111ww',0,1),(135,'zzzxc','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1zxc',0,1),(136,'zzsdfas','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1bnnbc',0,1),(137,'zzrttrfd','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com12fgsd',0,1),(138,'zzvbncvbnc','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1gxcbxcv',0,1),(139,'zzfgsdfgs','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1fdfsfdgs',0,1),(140,'zz55634','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1sfdgs',0,1),(141,'zz7634563','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1gsdf',0,1),(142,'zzbcvbxc','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1dfsdf',0,1),(143,'zz3452','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com12fgsf',0,1),(144,'zz2123412','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com13434',0,1),(145,'zzzxcxcass','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1fgsdf',0,1),(146,'ukr','6eecdc762106c8f35f52e510dd061c29','manager','u@u.com1',0,1),(147,'zzsdfgsd','c4ca4238a0b923820dcc509a6f75849b','client','1111@gmail.comq3453',0,1),(148,'china','a34c7156a0c451dbfe5be786685da7a0','manager','omice@ya.ruchina',0,1),(149,'hun','fe1b3b54fde5b24bb40f22cdd621f5d0','manager','1@gmail.com1fgsdfcv',0,0),(151,'zzdfasdfas','c4ca4238a0b923820dcc509a6f75849b','client','q@q.com3xc',0,1),(152,'poland','83878c91171338902e0fe0fb97a8c47a','manager','1@gmail.com1sdsd',0,0),(157,'ewqrqw','1b014086a5cf92eb3238d0d45c8c61a4','manager','dfa@d.com',0,1),(158,'kaz','40f5888b67c748df7efba008e7c2f9d2','manager','1@gmail.com1111111',0,0),(159,'korea','68992503f51f269010db1f1b9e273f1d','manager','omice@ya.rukkk',0,0),(160,'zer','c4ca4238a0b923820dcc509a6f75849b','client','1@gmail.com1dfssdf',0,1),(161,'urktelecom','fa3c30b378c6fa8161cdb79a06b6c25c','manager','omice@ya.ruurktelecom',0,0),(162,'Craftsam','3bd4c0d244c45ce85b2e1e6923ce5be3','client','asd@sdfsdf.ru',999100,1),(163,'Craftsam1','3bd4c0d244c45ce85b2e1e6923ce5be3','client','sdfwef1@yandex.ru',0,0),(164,'1234','81dc9bdb52d04dc20036dbd8313ed055','client','gmail.com@gmail.com',0,1),(184,'omni','c8837b23ff8aaa8a2dde915473ce0991','client','omni.dev@ya.ru',0,0),(185,'manat','a9eb812238f753132652ae09963a05e9','manager','manat@man.ru',0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-03-17 11:01:35
