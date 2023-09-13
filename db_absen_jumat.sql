CREATE DATABASE IF NOT EXISTS `db_absen_jumat`;
USE `db_absen_jumat`;

CREATE TABLE IF NOT EXISTS `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level INTEGER(1),
    rayon VARCHAR(20),
    nama VARCHAR(50),
    password VARCHAR(20)
    );

CREATE TABLE IF NOT EXISTS `siswa` (
    nama VARCHAR(200),
    nis  INTEGER(8) PRIMARY KEY,
    rayon VARCHAR(50),
    rombel VARCHAR(50),
    status VARCHAR(20)
    );

INSERT INTO `siswa` (nama,nis, rayon,rombel) VALUES ('Indra', 12209833,'Wik-1', 'PPLG-X-6'),
                                                    ('Rendra', 12208381,'Wik-2', 'PPLG-X-5'),
                                                    ('Defa', 12102821,'Wik-3', 'PPLG-XI-3');

INSERT INTO `users` (level,rayon,nama,password) VALUES (2, 'Wik-3', 'Atjep', 'Wikrama-123'),
                                                       (1, 'Wik-1', 'Nunuk', 'Wikrama-123'),
                                                       (2, 'Wik-1', 'Yoga', 'Wikrama-123'),
                                                       (3, 'Kesis', 'Budiono', 'Wikrama-123');