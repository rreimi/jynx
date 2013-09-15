ALTER TABLE `publishers` ADD `image` VARCHAR( 20 ) NULL AFTER `media`;

CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` int(11) NOT NULL,
  `company_name` varchar(80) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city` varchar(80),
  `job_title` varchar(100) NOT NULL,
  `vacancy` tinyint(2),
  `job_type` enum('Contracted','Temporary','Internship','Independent'),
  `temporary_months` tinyint(2),
  `descripton` varchar(300) NOT NULL,
  `requirements` varchar(300),
  `academic_level` enum('Secondary','Senior Technician','Master / Specialization','PhD'),
  `experience_years` tinyint(2),
  `age` tinyint(2),
  `sex` enum('Male','Female','Indistinct'),
  `languages` varchar(50),
  `salary` varchar(50),
  `benefits` varchar(300),
  `contact_email` varchar(50) NOT NULL,
  `close_date` datetime,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jobs_publisher_idx` (`publisher_id`),
  KEY `fk_jobs_state_idx` (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jobs_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jobs_areas_job_idx` (`job_id`),
  KEY `fk_jobs_areas_area_idx` (`area_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jobs_careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `career_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jobs_careers_job_idx` (`job_id`),
  KEY `fk_jobs_careers_career_idx` (`career_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

