ALTER TABLE `publishers` ADD `image` VARCHAR( 20 ) NULL AFTER `media`;

CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
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
  `description` varchar(300) NOT NULL,
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

INSERT INTO areas (name) VALUES ('Administración y Gerencia');
INSERT INTO areas (name) VALUES ('Agronomía');
INSERT INTO areas (name) VALUES ('Albañiles y obreros');
INSERT INTO areas (name) VALUES ('Arquitectura y Construcción');
INSERT INTO areas (name) VALUES ('Arte y Cultura');
INSERT INTO areas (name) VALUES ('Asistentes y Secretarias');
INSERT INTO areas (name) VALUES ('Belleza y Estética');
INSERT INTO areas (name) VALUES ('Bienestar social');
INSERT INTO areas (name) VALUES ('Cajeros y Oficinistas');
INSERT INTO areas (name) VALUES ('Carpinteros y Tapiceros');
INSERT INTO areas (name) VALUES ('Cerrajeros');
INSERT INTO areas (name) VALUES ('Comercio exterior y Relaciones internacionales');
INSERT INTO areas (name) VALUES ('Compras');
INSERT INTO areas (name) VALUES ('Comunicación social');
INSERT INTO areas (name) VALUES ('Conductores y choferes');
INSERT INTO areas (name) VALUES ('Conserjes y trabajo del hogar');
INSERT INTO areas (name) VALUES ('Consultoría');
INSERT INTO areas (name) VALUES ('Contabilidad y Auditoría');
INSERT INTO areas (name) VALUES ('Costureras y sastres');
INSERT INTO areas (name) VALUES ('Cuidado de adultos y niños');
INSERT INTO areas (name) VALUES ('Deportes');
INSERT INTO areas (name) VALUES ('Diseño');
INSERT INTO areas (name) VALUES ('Economía, Banca, Finanzas y Seguros');
INSERT INTO areas (name) VALUES ('Educación');
INSERT INTO areas (name) VALUES ('Electricidad');
INSERT INTO areas (name) VALUES ('Electrónica y Telecomunicaciones');
INSERT INTO areas (name) VALUES ('Fotografía');
INSERT INTO areas (name) VALUES ('Gastronomía, restaurantes y cocina');
INSERT INTO areas (name) VALUES ('Hotelería y Turismo');
INSERT INTO areas (name) VALUES ('Idiomas');
INSERT INTO areas (name) VALUES ('Informática y sistemas');
INSERT INTO areas (name) VALUES ('Internet y comercio electrónico');
INSERT INTO areas (name) VALUES ('Investigación y desarrollo');
INSERT INTO areas (name) VALUES ('Jardinería y Paisajismo');
INSERT INTO areas (name) VALUES ('Legal');
INSERT INTO areas (name) VALUES ('Logística, distribución y almacenamiento');
INSERT INTO areas (name) VALUES ('Mecánicos y latoneros');
INSERT INTO areas (name) VALUES ('Medicina y Salud');
INSERT INTO areas (name) VALUES ('Mensajeros y motorizados');
INSERT INTO areas (name) VALUES ('Mercadeo y publicidad');
INSERT INTO areas (name) VALUES ('Organización y métodos');
INSERT INTO areas (name) VALUES ('Pasantes y Tesistas');
INSERT INTO areas (name) VALUES ('Petróleo, Minería y Gas');
INSERT INTO areas (name) VALUES ('Pintores');
INSERT INTO areas (name) VALUES ('Plomeros y Fontaneros');
INSERT INTO areas (name) VALUES ('Producción, Operaciones y Mantenimiento');
INSERT INTO areas (name) VALUES ('Promotores y Modelos');
INSERT INTO areas (name) VALUES ('Recursos Humanos');
INSERT INTO areas (name) VALUES ('Relaciones Públicas');
INSERT INTO areas (name) VALUES ('Seguridad industrial y Medio ambiente');
INSERT INTO areas (name) VALUES ('Soldadores, herreros y torneros');
INSERT INTO areas (name) VALUES ('Ventas');
INSERT INTO areas (name) VALUES ('Veterinaria');
INSERT INTO areas (name) VALUES ('Vigilancia, Seguridad, Escoltas');
INSERT INTO areas (name) VALUES ('Otro sector');

INSERT INTO careers (name) VALUES ('Administración');
INSERT INTO careers (name) VALUES ('Administración Bancaria y Financiera');
INSERT INTO careers (name) VALUES ('Administración Comercial');
INSERT INTO careers (name) VALUES ('Administración de Aduanas');
INSERT INTO careers (name) VALUES ('Administración de Personal / de Recursos Humanos');
INSERT INTO careers (name) VALUES ('Administración Pública');
INSERT INTO careers (name) VALUES ('Administración Tributaria');
INSERT INTO careers (name) VALUES ('Agronomía');
INSERT INTO careers (name) VALUES ('Arquitectura / Urbanismo');
INSERT INTO careers (name) VALUES ('Artes');
INSERT INTO careers (name) VALUES ('Banca y Finanzas');
INSERT INTO careers (name) VALUES ('Bibliotecología y Archivología');
INSERT INTO careers (name) VALUES ('Biología / Biología Marina');
INSERT INTO careers (name) VALUES ('Ciencias Ambientales');
INSERT INTO careers (name) VALUES ('Ciencias Audiovisuales y Fotografía');
INSERT INTO careers (name) VALUES ('Ciencias Estadísticas');
INSERT INTO careers (name) VALUES ('Ciencias Políticas');
INSERT INTO careers (name) VALUES ('Comercio Exterior / Internacional');
INSERT INTO careers (name) VALUES ('Computación');
INSERT INTO careers (name) VALUES ('Comunicación Social');
INSERT INTO careers (name) VALUES ('Contabilidad');
INSERT INTO careers (name) VALUES ('Contaduría Pública');
INSERT INTO careers (name) VALUES ('Derecho');
INSERT INTO careers (name) VALUES ('Diseño de Modas');
INSERT INTO careers (name) VALUES ('Diseño Gráfico');
INSERT INTO careers (name) VALUES ('Economía');
INSERT INTO careers (name) VALUES ('Educación Especial / Integral / Preescolar');
INSERT INTO careers (name) VALUES ('Electricidad ');
INSERT INTO careers (name) VALUES ('Electrónica');
INSERT INTO careers (name) VALUES ('Enfermería');
INSERT INTO careers (name) VALUES ('Estadística');
INSERT INTO careers (name) VALUES ('Estudios Internacionales');
INSERT INTO careers (name) VALUES ('Estudios Liberales');
INSERT INTO careers (name) VALUES ('Estudios Políticos');
INSERT INTO careers (name) VALUES ('Farmacia');
INSERT INTO careers (name) VALUES ('Filosofía');
INSERT INTO careers (name) VALUES ('Física');
INSERT INTO careers (name) VALUES ('Fisioterapia');
INSERT INTO careers (name) VALUES ('Geografía');
INSERT INTO careers (name) VALUES ('Geología');
INSERT INTO careers (name) VALUES ('Higiene y Seguridad Industrial');
INSERT INTO careers (name) VALUES ('Historia / Museología');
INSERT INTO careers (name) VALUES ('Hotelería');
INSERT INTO careers (name) VALUES ('Idiomas Modernos/Traducción/Interprete');
INSERT INTO careers (name) VALUES ('Informática');
INSERT INTO careers (name) VALUES ('Ingeniería Agrícola');
INSERT INTO careers (name) VALUES ('Ingeniería Agroindustrial');
INSERT INTO careers (name) VALUES ('Ingeniería Ambiental');
INSERT INTO careers (name) VALUES ('Ingeniería Civil');
INSERT INTO careers (name) VALUES ('Ingeniería de Materiales');
INSERT INTO careers (name) VALUES ('Ingeniería de Minas');
INSERT INTO careers (name) VALUES ('Ingeniería de Petróleo');
INSERT INTO careers (name) VALUES ('Ingeniería de Producción');
INSERT INTO careers (name) VALUES ('Ingeniería de Sistemas');
INSERT INTO careers (name) VALUES ('Ingeniería de Telecomunicaciones');
INSERT INTO careers (name) VALUES ('Ingeniería Eléctrica');
INSERT INTO careers (name) VALUES ('Ingeniería Electrónica');
INSERT INTO careers (name) VALUES ('Ingeniería en Computación');
INSERT INTO careers (name) VALUES ('Ingeniería en Informática');
INSERT INTO careers (name) VALUES ('Ingeniería en Telecomunicaciones');
INSERT INTO careers (name) VALUES ('Ingeniería Industrial');
INSERT INTO careers (name) VALUES ('Ingeniería Petroquímica');
INSERT INTO careers (name) VALUES ('Ingeniería Química');
INSERT INTO careers (name) VALUES ('Letras');
INSERT INTO careers (name) VALUES ('Matemática');
INSERT INTO careers (name) VALUES ('Mecánica');
INSERT INTO careers (name) VALUES ('Medicina / Radiología');
INSERT INTO careers (name) VALUES ('Medicina Veterinaria');
INSERT INTO careers (name) VALUES ('Mercadeo');
INSERT INTO careers (name) VALUES ('Mercadotecnia');
INSERT INTO careers (name) VALUES ('Nutrición y Dietética');
INSERT INTO careers (name) VALUES ('Odontología');
INSERT INTO careers (name) VALUES ('Organización Empresarial');
INSERT INTO careers (name) VALUES ('Otra carrera…');
INSERT INTO careers (name) VALUES ('Psicología');
INSERT INTO careers (name) VALUES ('Psicopedagogía');
INSERT INTO careers (name) VALUES ('Publicidad y Mercadeo');
INSERT INTO careers (name) VALUES ('Química');
INSERT INTO careers (name) VALUES ('Relaciones Industriales');
INSERT INTO careers (name) VALUES ('Relaciones Públicas');
INSERT INTO careers (name) VALUES ('Secretariado Administrativo');
INSERT INTO careers (name) VALUES ('Seguridad Industrial');
INSERT INTO careers (name) VALUES ('Sociología');
INSERT INTO careers (name) VALUES ('Teología');
INSERT INTO careers (name) VALUES ('Topografía');
INSERT INTO careers (name) VALUES ('Trabajo Social');
INSERT INTO careers (name) VALUES ('Turismo y Hotelería');
INSERT INTO careers (name) VALUES ('Ventas');

CREATE TABLE IF NOT EXISTS `password_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;