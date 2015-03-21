<?

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
	
require "db.php";

$GLOBALS['db']->exec("CREATE TABLE `lessons` (
  `_id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL ,
  `id_groups` bigint(20) NOT NULL ,
  `id_weeks` bigint(20) NOT NULL ,
  `discipline` varchar(255) NOT NULL DEFAULT '-' ,
  `teacher` varchar(255) NOT NULL DEFAULT '-' ,
  `place` varchar(255) NOT NULL DEFAULT '-' ,
  `status` tinyint(1) NOT NULL DEFAULT '0' ,
  `lesson_number` int(1) NOT NULL,
  `day_number` tinyint(1) NOT NULL,
  `on_odd` int(1) NOT NULL DEFAULT '0',
  `on_even` int(1) NOT NULL DEFAULT '0',
  `id_subgroup` int(1) NOT NULL DEFAULT '-1',
  `for_subgroups` int(1) NOT NULL,
  `institute_id` int(3),
  `campus_id` int(3)
) ;


CREATE TABLE `weeks` (
  `_id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL ,
  `number` int(11) NOT NULL ,
  `id_seasons` bigint(20) NOT NULL ,
  `week_start_day` date NOT NULL,
  `week_finish_day` date NOT NULL,
  `is_even` int(1) DEFAULT NULL
);

CREATE TABLE `groups` (
  `_id` INTEGER PRIMARY KEY NOT NULL ,
  `name` varchar(255) NOT NULL ,
  `status` tinyint(1) NOT NULL DEFAULT '0' ,
  `has_subgroups` tinyint(1) NOT NULL DEFAULT '0',
  `campus_id` int(3) NOT NULL,
  `institute_id` INT(3) NOT NULL,
  `course` int(1) NOT NULL
) ;

CREATE TABLE `campuses` (
  `_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `institutes` (
	_id INTEGER PRIMARY KEY AUTOINCREMENT, 
	name VARCHAR(255)
);

CREATE TABLE `schedule_schemas` (
    _id INTEGER PRIMARY KEY   AUTOINCREMENT,
    campus_id INTEGER NOT NULL,
    lesson_number INTEGER NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL
);

CREATE TABLE `lessons_time` (
	_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	campus_id INTEGER(2) NOT NULL,
	lesson_number INTEGER(1) NOT NULL,
	start_time TIME NOT NULL,
	end_time TIME NOT NULL
);

INSERT INTO `institutes` (_id, name) VALUES (1, 'Политехнический институт');
INSERT INTO `institutes` (_id, name) VALUES (2, 'Институт информационных технологий и управления в технических системах');
INSERT INTO `institutes` (_id, name) VALUES (3, 'Институт кораблестроения и морского транспорта');
INSERT INTO `institutes` (_id, name) VALUES (4, 'Институт финансов, экономики и управления');
INSERT INTO `institutes` (_id, name) VALUES (5, 'Гуманитарно-педагогический институт');
INSERT INTO `institutes` (_id, name) VALUES (6, 'Институт ядерной энергии и промышленности');
INSERT INTO `institutes` (_id, name) VALUES (7, 'Морской колледж');


INSERT INTO `lessons_time` VALUES(1,2,1,'8:15','9:45');
INSERT INTO `lessons_time` VALUES(2,2,2,'9:45','11:15');
INSERT INTO `lessons_time` VALUES(5,2,3,'11:15','13:00');
INSERT INTO `lessons_time` VALUES(6,2,4,'13:00','14:30');
INSERT INTO `lessons_time` VALUES(7,2,5,'14:30','16:00');
INSERT INTO `lessons_time` VALUES(8,2,6,'16:00','17:30');
INSERT INTO `lessons_time` VALUES(9,2,7,'17:30','19:00');


");

?>
