CREATE DATABASE IF NOT EXISTS dar_al_hiraf;
USE dar_al_hiraf;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS messages;


-- ─── Admins ───────────────────────────────────────────────────────────────────
CREATE TABLE admins (
    admin_id    INT PRIMARY KEY AUTO_INCREMENT,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    name        VARCHAR(100)
);

INSERT INTO admins (email, password, name)
VALUES ('admin@daralhiraf.sa', 'password123', 'Dar Al Hiraf Admin');

-- ─── Products & Workshops ─────────────────────────────────────────────────────
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

-- ─── Physical Products ────────────────────────────────────────────────────────
INSERT INTO products (name, category, description, material, price, artisan, image, quantity, admin_id) VALUES

('Woven Baskets & Embroidered Textiles',
 'Textiles',
 'A beautiful collection of hand-woven baskets and embroidered textiles crafted in the traditional Asiri style, featuring bold geometric patterns in vivid colours. Each piece reflects the rich cultural heritage of southern Saudi Arabia.',
 'Natural wool, silk thread',
 320.00, 'Fatima Al-Ghamdi', 'images/p1_baskets.jpg', 10, 1),

('Brass Mortar & Pestle',
 'Metalwork',
 'A heavy, hand-cast brass mortar and pestle used for centuries to grind spices, coffee beans, and cardamom. Engraved with classic Najdi geometric motifs by a master metalsmith.',
 'Solid hand-cast brass',
 495.00, 'Hassan Al-Otaibi', 'images/p2_mortar.jpg', 8, 1),

('Palm Leaf Sun Hat',
 'Textiles',
 'A light, breathable sun hat woven entirely by hand from dried palm leaves. A perfect blend of traditional craft and everyday practicality, ideal for Saudi Arabia''s sunny climate.',
 'Natural palm leaf fibre',
 145.00, 'Aisha Al-Mutairi', 'images/p3_hat.jpg', 15, 1),

('Traditional Saudi Khanjar',
 'Metalwork',
 'A traditional ceremonial khanjar dagger featuring a curved blade, an ornately decorated silver sheath, and a carved wooden grip. A striking piece of Saudi cultural heritage crafted by a master of the old guild.',
 'Silver, brass, hardwood grip',
 980.00, 'Ibrahim Al-Dosari', 'images/p4_khanjar.jpg', 5, 1),

('Hand-Woven Palm Basket',
 'Textiles',
 'A sturdy, beautifully patterned basket woven entirely by hand from palm fibre. Perfect as a storage piece or home accent, each basket carries the marks of a long tradition of Saudi women''s weaving.',
 'Natural palm fibre',
 155.00, 'Aisha Al-Mutairi', 'images/p5_weaving.jpg', 20, 1),

('Traditional Silver Jewellery',
 'Metalwork',
 'Hand-wrought silver jewellery featuring the distinctive filigree work and amber accents of the southern mountains. Worn historically for celebrations and passed down across generations.',
 'Sterling silver, natural stones',
 640.00, 'Maha Al-Shehri', 'images/p6_jewelry.jpg', 6, 1),

('Woven Palm Mat',
 'Textiles',
 'A large, durable floor mat woven in the traditional majlis style. Ideal for seating areas and prayer, each mat is hand-crafted from sun-dried palm leaves by a skilled artisan.',
 'Natural palm leaf fibre',
 195.00, 'Aisha Al-Mutairi', 'images/p7_palmweave.jpg', 12, 1),

('Taif Rose Water',
 'Fragrance',
 'Authentic Taif rose water, distilled in small batches from the prized damask roses grown in the highlands of Taif. Used in cooking, skincare, and as a timeless fragrance with deep cultural significance.',
 'Pure damask rose distillate',
 220.00, 'Reem Al-Harbi', 'images/p8_roses.jpg', 18, 1),

('Sadu Woven Textile Strip',
 'Textiles',
 'A hand-woven Sadu textile strip in the classic red, black, and cream palette. Sadu weaving is a UNESCO-recognised Bedouin craft and every strip is created on a ground loom by a master weaver.',
 'Hand-spun wool',
 360.00, 'Fatima Al-Ghamdi', 'images/p9_textile.jpg', 9, 1),

('Handmade Clay Pottery Jug',
 'Pottery',
 'A rustic pottery jug hand-shaped on a potter''s wheel from local red clay and finished with a hand-painted rim. Cool to the touch and perfect for traditional service of water or dates.',
 'Local red clay',
 280.00, 'Noura Al-Rashidi', 'images/p10_clay.jpg', 11, 1),

('Camel Leather Shoulder Bag',
 'Leather',
 'A supple shoulder bag hand-stitched from tanned camel leather and finished with traditional tooled patterns. Durable, aged naturally, and a modern companion to a timeless Saudi craft.',
 'Tanned camel leather',
 850.00, 'Ibrahim Al-Dosari', 'images/p11_leather.jpg', 4, 1),

('Carved Wooden Heritage Panel',
 'Wood',
 'A large hand-carved decorative panel in the old Hijazi door style, featuring deep geometric relief and hand-pegged joinery. A statement piece that brings centuries of architectural tradition into the home.',
 'Solid acacia hardwood',
 1400.00, 'Khalid Al-Zahrani', 'images/p12_wooddoor.jpg', 3, 1);

-- ─── Workshops ────────────────────────────────────────────────────────────────
INSERT INTO products (name, category, description, price, artisan, image, location, duration, sessions, seats, admin_id) VALUES

('Traditional Palm Weaving',
 'Workshop',
 'Learn the traditional Khousse palm-leaf weaving technique with a master artisan from Al-Ahsa. All materials are provided. Suitable for beginners.',
 280.00, 'Fatima Al-Ghamdi', 'images/p5_weaving.jpg',
 'Al-Ahsa Heritage Center, Al-Ahsa, Eastern Province',
 '3 Hours', 'May 14 2026 10:00AM-1:00PM|May 14 2026 2:00PM-5:00PM|May 21 2026 10:00AM-1:00PM', 12, 1),

('Sadu Weaving Workshop',
 'Workshop',
 'Master the iconic Sadu weaving — a UNESCO-recognized Bedouin craft. Learn to use a traditional ground loom and create your own Sadu-patterned piece with Fatima Al-Ghamdi.',
 340.00, 'Fatima Al-Ghamdi', 'images/p9_textile.jpg',
 'Asir Cultural Center, Abha, Asir Region',
 '4 Hours', 'May 21 2026 9:00AM-1:00PM|Jun 4 2026 9:00AM-1:00PM|Jun 4 2026 2:00PM-6:00PM', 8, 1),

('Brass & Copper Crafting',
 'Workshop',
 'Shape, engrave and polish traditional brass pieces in this immersive metalwork session with master craftsman Hassan Al-Otaibi. Take home your own handmade piece.',
 420.00, 'Hassan Al-Otaibi', 'images/p2_mortar.jpg',
 'Riyadh Craft District, Al-Murabba, Riyadh',
 '5 Hours', 'Jun 5 2026 10:00AM-3:00PM|Jun 19 2026 10:00AM-3:00PM|Jul 3 2026 10:00AM-3:00PM', 6, 1),

('Traditional Pottery Workshop',
 'Workshop',
 'Shape and glaze your own clay piece under the guidance of Noura Al-Rashidi from the Eastern Province. Using locally sourced red clay, you''ll create a unique handmade piece to keep.',
 260.00, 'Noura Al-Rashidi', 'images/p10_clay.jpg',
 'Al-Qatif Heritage Village, Al-Qatif, Eastern Province',
 '3 Hours', 'Jun 18 2026 10:00AM-1:00PM|Jun 18 2026 2:00PM-5:00PM|Jul 2 2026 10:00AM-1:00PM', 10, 1);

-- ─── Verify ───────────────────────────────────────────────────────────────────
SELECT * FROM products;
SELECT * FROM admins;
