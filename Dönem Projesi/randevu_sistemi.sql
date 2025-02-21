-- Veritabanı oluşturma
CREATE DATABASE IF NOT EXISTS randevu_sistemi;

-- Veritabanını kullanma
USE randevu_sistemi;

-- Hastalar tablosu
CREATE TABLE IF NOT EXISTS hastalar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(255) NOT NULL,
    soyad VARCHAR(255) NOT NULL,
    tc VARCHAR(11) NOT NULL UNIQUE,
    randevu_tarih DATE NOT NULL,
    randevu_saat TIME NOT NULL,
    muayene_sonuc TEXT DEFAULT NULL,
    ilaclar TEXT DEFAULT NULL
);

-- Meşgul saatler için tablo
CREATE TABLE IF NOT EXISTS mesgul_saatler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doktor_id INT NOT NULL,
    tarih DATE NOT NULL,
    saat TIME NOT NULL,
    UNIQUE(doktor_id, tarih, saat) -- Aynı doktora aynı saat iki kez atanamaz
);

-- Oturum tablosu (örneğin loglar veya oturum yönetimi için opsiyonel)
CREATE TABLE IF NOT EXISTS oturum_loglari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    role ENUM('doktor', 'sekreter') NOT NULL,
    baslangic_zaman DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    bitis_zaman DATETIME NULL
);
