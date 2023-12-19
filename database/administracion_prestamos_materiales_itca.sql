CREATE DATABASE administracion_prestamos_materiales_itca;
USE administracion_prestamos_materiales_itca;

CREATE TABLE depto (
  id_depto INT(11) NOT NULL AUTO_INCREMENT,
  depto VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_depto)
) AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;


CREATE TABLE usuario (
  id_usuario INT(11) NOT NULL AUTO_INCREMENT,
  carnet VARCHAR(20) NOT NULL,
  nom_usuario VARCHAR(30) NOT NULL DEFAULT '',
  ape_usuario VARCHAR(30) NOT NULL DEFAULT '',
  tipo VARCHAR(30) DEFAULT NULL,
  telcasa VARCHAR(9) DEFAULT NULL,
  celular VARCHAR(9) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  estado VARCHAR(20) DEFAULT NULL,
  clave VARCHAR(50) DEFAULT NULL,
  imagen VARCHAR(200) DEFAULT NULL,
  id_depto INT(2) DEFAULT NULL,
  accesosistemas INT(4) DEFAULT '1' COMMENT '1=si 0=no',
  esadministrador INT(1) DEFAULT '0' COMMENT '1=si 0=no 3=soporte',
  created_by INT(11),
  PRIMARY KEY (id_usuario),
  UNIQUE KEY id_usuario (id_usuario),
  FOREIGN KEY (id_depto)
  REFERENCES administracion_prestamos_materiales_itca.depto (id_depto)
) AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

CREATE TABLE marca (
  id_marca INT(11) NOT NULL AUTO_INCREMENT,
  marca VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_marca)
) AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

CREATE TABLE unidad (
  id_unidad INT(11) NOT NULL AUTO_INCREMENT,
  unidad VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_unidad)
) AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

CREATE TABLE material (
  id_material INT(11) NOT NULL AUTO_INCREMENT,
  material VARCHAR(50) DEFAULT NULL,
  n_serie VARCHAR(50) DEFAULT NULL,
  fecha_ingreso DATE DEFAULT NULL,
  estado VARCHAR(25) DEFAULT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  tipo VARCHAR(100) DEFAULT NULL,
  cantidad INT(9) DEFAULT NULL,
  precio DECIMAL(13,2) DEFAULT NULL,
  created_by INT(11) DEFAULT NULL,
  id_marca INT(11) DEFAULT NULL,
  id_depto INT(11) DEFAULT NULL,
  id_unidad INT(11) DEFAULT NULL,
  PRIMARY KEY (id_material),
  FOREIGN KEY (id_marca)
  REFERENCES administracion_prestamos_materiales_itca.marca (id_marca),
  FOREIGN KEY (id_depto)
  REFERENCES administracion_prestamos_materiales_itca.depto (id_depto),
  FOREIGN KEY (id_unidad)
  REFERENCES administracion_prestamos_materiales_itca.unidad (id_unidad)
) AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

CREATE TABLE material_archivado (
  id_archivado INT(11) NOT NULL AUTO_INCREMENT,
  cantidad_ingresada INT(9) DEFAULT NULL,
  fecha_ingreso DATE DEFAULT NULL,
  id_material INT(11) DEFAULT NULL,
  PRIMARY KEY (id_archivado),
  FOREIGN KEY (id_material)
  REFERENCES administracion_prestamos_materiales_itca.material (id_material)
) AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

CREATE TABLE aula (
  id_aula INT(11) NOT NULL AUTO_INCREMENT,
  aula VARCHAR(50) DEFAULT NULL,
  ubicacion VARCHAR(100) DEFAULT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  tipo VARCHAR(50) DEFAULT NULL COMMENT 'cambiar a int', 
  PRIMARY KEY (id_aula)
) AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

CREATE TABLE prestamo (
  id_prestamo INT(11) NOT NULL AUTO_INCREMENT,
  id_usuario INT(11) DEFAULT NULL,
  id_aula INT(11) DEFAULT NULL,
  estado VARCHAR(50) DEFAULT NULL,
  fecha_hecha DATE DEFAULT NULL,
  fecha_destino DATE DEFAULT NULL,
  PRIMARY KEY (id_prestamo),
  FOREIGN KEY (id_usuario)
  REFERENCES administracion_prestamos_materiales_itca.usuario(id_usuario),
  FOREIGN KEY (id_aula)
  REFERENCES administracion_prestamos_materiales_itca.aula (id_aula)
) AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;

CREATE TABLE det_prestamo (
  id_det_prestamo INT(11) NOT NULL AUTO_INCREMENT,
  id_prestamo INT(11) DEFAULT NULL,
  id_material INT(11) DEFAULT NULL,
  estado VARCHAR(20) DEFAULT NULL,
  cantidad INT(9) DEFAULT NULL,
  inicio TIME DEFAULT NULL,
  fin TIME DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  PRIMARY KEY (id_det_prestamo),
  FOREIGN KEY (id_prestamo)
  REFERENCES administracion_prestamos_materiales_itca.prestamo (id_prestamo),
  FOREIGN KEY (id_material)
  REFERENCES administracion_prestamos_materiales_itca.material (id_material)
) AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;