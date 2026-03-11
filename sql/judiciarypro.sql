CREATE DATABASE IF NOT EXISTS judiciarypro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE judiciarypro;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    summary TEXT,
    description MEDIUMTEXT,
    category VARCHAR(100),
    price DECIMAL(10,2) DEFAULT 0.00,
    students_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    lectures INT DEFAULT 0,
    quizzes INT DEFAULT 0,
    duration VARCHAR(120),
    skill_level VARCHAR(120),
    language VARCHAR(120),
    assessments VARCHAR(120),
    author_name VARCHAR(120),
    author_title VARCHAR(120),
    author_image VARCHAR(255),
    image VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS course_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    slug VARCHAR(180) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student_rankings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(150) NOT NULL,
    rank_title VARCHAR(150),
    score VARCHAR(50),
    exam VARCHAR(100),
    year VARCHAR(20),
    photo VARCHAR(255),
    testimonial TEXT,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student_signups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(30) NOT NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    course VARCHAR(150),
    city VARCHAR(120),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    short_description TEXT,
    description MEDIUMTEXT,
    event_date DATE,
    start_time VARCHAR(50),
    end_time VARCHAR(50),
    location VARCHAR(150),
    image VARCHAR(255),
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    excerpt TEXT,
    content MEDIUMTEXT,
    image VARCHAR(255),
    author VARCHAR(120),
    published_at DATETIME,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS material_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    slug VARCHAR(180) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    summary TEXT,
    overview_html MEDIUMTEXT,
    detail_html MEDIUMTEXT,
    category VARCHAR(150),
    meta_title VARCHAR(200),
    meta_description TEXT,
    cover_image VARCHAR(255),
    images_json MEDIUMTEXT,
    pdfs_json MEDIUMTEXT,
    youtube_json MEDIUMTEXT,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS material_leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    material_id INT DEFAULT NULL,
    material_title VARCHAR(200),
    pdf_name VARCHAR(200),
    pdf_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS seo_meta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL UNIQUE,
    meta_title VARCHAR(200),
    meta_description TEXT,
    meta_keywords TEXT,
    og_title VARCHAR(200),
    og_description TEXT,
    og_image VARCHAR(255),
    twitter_title VARCHAR(200),
    twitter_description TEXT,
    twitter_image VARCHAR(255),
    canonical_url VARCHAR(255),
    robots VARCHAR(50)
) ENGINE=InnoDB;

INSERT INTO admins (name, email, password_hash)
SELECT 'Admin', 'admin@judiciarypro.local', '$2y$10$TSsQhQVgqzc6hrK3VGjYTOR3ODsXHhBD511g30W7w.6hTmJvVXHsq'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE email = 'admin@judiciarypro.local');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'home', 'JudiciaryPRO', 'JudiciaryPRO offers judiciary and prosecutor exam preparation with clear concepts, disciplined practice, and personal mentorship.', 'judiciary, prosecutor, exam prep, law, coaching'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'home');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'about', 'About JudiciaryPRO', 'Learn more about JudiciaryPRO and our founder-led mentorship.', 'about judiciary, coaching, mentor'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'about');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'courses', 'JudiciaryPRO Courses', 'Explore JudiciaryPRO courses for judiciary and prosecutor exams.', 'courses, judiciary, prosecutor'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'courses');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'events', 'JudiciaryPRO Events', 'Upcoming JudiciaryPRO events and sessions.', 'events, judiciary, coaching'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'events');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'news', 'JudiciaryPRO News', 'Latest JudiciaryPRO news and announcements.', 'news, judiciary, updates'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'news');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'materials', 'JudiciaryPRO Study Materials', 'Download JudiciaryPRO study materials, PDFs, and video resources for judiciary preparation.', 'judiciary materials, study pdf, notes'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'materials');

INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords)
SELECT 'contact', 'Contact JudiciaryPRO', 'Contact JudiciaryPRO for admissions and guidance.', 'contact, judiciary, coaching'
WHERE NOT EXISTS (SELECT 1 FROM seo_meta WHERE page_key = 'contact');
