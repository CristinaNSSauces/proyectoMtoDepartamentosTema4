-- CREACION BASE DE DATOS
-- Creacion de la base de datos DAW215DBDepartamentos
CREATE DATABASE if NOT EXISTS DAW215DBDepartamentos;
-- Creacion de tablas de la base de datos
CREATE TABLE if NOT EXISTS DAW215DBDepartamentos.Departamento (
    CodDepartamento VARCHAR(3),
    DescDepartamento VARCHAR(255) NOT NULL,
    FechaBaja DATE NULL,
    VolumenNegocio FLOAT NULL,
    PRIMARY KEY(CodDepartamento)
)ENGINE=INNODB;

-- CREACION USUARIO ADMINISTRADOR
-- Creacion de usuario administrador de la base de datos: usuarioDAW215DBDepartamentos / P@ssw0rd
CREATE USER 'usuarioDAW215DBDepartamentos'@'%' IDENTIFIED BY 'P@ssw0rd';
-- Permisos para la base de datos
GRANT ALL PRIVILEGES ON DAW215DBDepartamentos.* TO 'usuarioDAW215DBDepartamentos'@'%' WITH GRANT OPTION;