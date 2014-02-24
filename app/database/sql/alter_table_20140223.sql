--
-- Estructura de tabla para la tabla `cron_jobs`
--

CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_code` varchar(30) NOT NULL,
  `cron_desc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL COMMENT 'Last execution',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Cron executions control' AUTO_INCREMENT=1 ;

-- Insertar cron de cache de publicaciones

INSERT INTO `mercatino`.`cron_jobs` (`id`, `cron_code`, `cron_desc`, `created_at`, `updated_at`) VALUES (NULL, 'publication_cache', 'Check and regenarte publications cache', '2014-02-01 00:00:00','2014-02-01 00:00:00');