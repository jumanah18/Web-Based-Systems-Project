

CREATE DATABASE IF NOT EXISTS dar_al_hiraf;
USE dar_al_hiraf;

-- ─── Drop existing tables (order matters due to foreign keys) ─
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS admins;

-- ─── Admins ───────────────────────────────────────────────────
CREATE TABLE admins (
    admin_id    INT PRIMARY KEY AUTO_INCREMENT,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    name        VARCHAR(100)
);

-- ─── Products & Workshops ─────────────────────────────────────
CREATE TABLE products (
    pid          INT PRIMARY KEY AUTO_INCREMENT,
    name         VARCHAR(255) NOT NULL,
    category     VARCHAR(100) NOT NULL,
    description  TEXT,
    material     VARCHAR(255),
    price        DECIMAL(10,2) NOT NULL,
    artisan      VARCHAR(255) NOT NULL,
    image        VARCHAR(500) NOT NULL,
    quantity     INT DEFAULT NULL,
    location     VARCHAR(255),
    duration     VARCHAR(50),
    sessions     TEXT,
    seats        INT DEFAULT NULL,
    admin_id     INT,
    FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE SET NULL
);

-- ─── Messages ─────────────────────────────────────────────────
CREATE TABLE messages (
    message_id  INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    subject     VARCHAR(255) DEFAULT NULL,
    message     TEXT NOT NULL,
    sent_at     DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
