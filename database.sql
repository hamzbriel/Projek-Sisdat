SHOW DATABASES;

CREATE DATABASE project_uas;
USE project_uas;

CREATE TABLE developers(
    developer_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(250) NOT NULL, 
    password VARCHAR(250) NOT NULL,
    nama_developer VARCHAR(250) NOT NULL,
    CONSTRAINT developers_pk PRIMARY KEY(developer_id)
);

CREATE TABLE users(
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(250) NOT NULL, 
    password_hash VARCHAR(250) NOT NULL,
    nama_pengguna VARCHAR(250) NOT NULL,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT users_pk PRIMARY KEY(user_id)
);

CREATE TABLE games(
    game_id INT NOT NULL AUTO_INCREMENT,
    nama_game VARCHAR(250) NOT NULL,
    harga INT NOT NULL,
    deskripsi VARCHAR(250),
    developer_id INT not null,
    gambar VARCHAR(250),
    foreign key(developer_id) REFERENCES developers(developer_id),
    CONSTRAINT games_pk PRIMARY KEY(game_id)
);

CREATE TABLE genres(
    genre_id INT NOT NULL AUTO_INCREMENT,
    genre VARCHAR(250) NOT NULL,
    game_id INT NOT NULL,
    foreign key(game_id) REFERENCES games(game_id),
    CONSTRAINT genres_pk PRIMARY KEY(genre_id)
);

CREATE TABLE pembelian(
    pembelian_id INT NOT NULL AUTO_INCREMENT,
    game_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_pembelian DATETIME DEFAULT current_timestamp,
    foreign key(game_id) REFERENCES games(game_id),
    foreign key(user_id) REFERENCES users(user_id),
    CONSTRAINT pembelian_pk PRIMARY KEY(pembelian_id)
);

SHOW TABLES;