--
-- Estructura de tabla para la tabla `my_directory`
--

CREATE TABLE IF NOT EXISTS `my_directory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `my_directory` ADD UNIQUE (
  `publisher_id` ,
  `user_id`
);