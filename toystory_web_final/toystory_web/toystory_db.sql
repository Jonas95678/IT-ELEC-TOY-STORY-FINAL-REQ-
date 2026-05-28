-- ============================================================
-- TOY STORY FAN SITE - MySQL Database
-- Database: toystory_db
-- ============================================================

CREATE DATABASE IF NOT EXISTS toystory_db;
USE toystory_db;

-- ============================================================
-- TABLE: tblusers (Admin users)
-- ============================================================
CREATE TABLE IF NOT EXISTS tblusers (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fname       VARCHAR(100) NOT NULL,
    mname       VARCHAR(100) DEFAULT '',
    lname       VARCHAR(100) NOT NULL,
    username    VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: tbl_movies
-- ============================================================
CREATE TABLE IF NOT EXISTS tbl_movies (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(150) NOT NULL,
    release_year INT(4) NOT NULL,
    tagline      TEXT NOT NULL,
    runtime      INT NOT NULL COMMENT 'Runtime in minutes',
    rating       DECIMAL(3,1) DEFAULT 0.0,
    poster_url   VARCHAR(255) NOT NULL DEFAULT 'img/toystory1.webp',
    is_displayed TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=shown on website, 0=hidden',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: tbl_characters
-- ============================================================
CREATE TABLE IF NOT EXISTS tbl_characters (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    role         VARCHAR(150) NOT NULL,
    quote        VARCHAR(255) NOT NULL,
    description  TEXT NOT NULL,
    avatar_url   VARCHAR(255) NOT NULL DEFAULT 'img/woody.jpg',
    css_class    VARCHAR(50) DEFAULT 'woody' COMMENT 'Card CSS class for styling',
    is_displayed TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=shown on website, 0=hidden',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA: Movies (from HTML hardcoded content)
-- ============================================================
INSERT INTO tbl_movies (title, release_year, tagline, runtime, rating, poster_url, is_displayed) VALUES
('Toy Story',   1995, 'Hang on for the comedy that goes to infinity and beyond!', 81,  8.3, 'img/toystory1.webp',              1),
('Toy Story 2', 1999, 'The toys are back!',                                        92,  7.9, 'img/Toy_Story_2_-_Poster.webp',   1),
('Toy Story 3', 2010, 'No toy gets left behind.',                                  103, 8.3, 'img/toystory3.jpg',               1),
('Toy Story 4', 2019, 'Get Ready to Hit the Road',                                 100, 7.7, 'img/toystory4.jpg',               1);

-- ============================================================
-- SEED DATA: Characters (from HTML hardcoded content)
-- ============================================================
INSERT INTO tbl_characters (name, role, quote, description, avatar_url, css_class, is_displayed) VALUES
('Woody',       'The Cowboy Leader',     '"You\'ve got a friend in me!"',         'A brave and loyal pull-string cowboy doll who serves as the leader of Andy\'s toys, always putting his friends first.',                                   'img/woody.jpg',  'woody',  1),
('Buzz Lightyear','Space Ranger',        '"To infinity and beyond!"',             'A confident space ranger action figure who learns the true meaning of friendship and bravery alongside Woody.',                                            'img/buzz.jpg',   'buzz',   1),
('Jessie',      'The Yodeling Cowgirl',  '"I\'m going!", "I\'m going!"',          'An energetic and fun-loving yodeling cowgirl who brings excitement and enthusiasm to every adventure.',                                                    'img/jessie.jpg', 'jessie', 1),
('Rex',         'The Anxious Dinosaur',  '"I don\'t think I can do this!"',       'A caring but nervous plastic tyrannosaurus who worries about fitting in, yet always shows up for his friends.',                                             'img/rex.jpg',    'rexy',   1),
('Hamm',        'The Wise Piggy Bank',   '"It\'s déjà vu all over again!"',       'A smart and witty piggy bank who offers clever advice and comic relief with his sarcastic humor.',                                                          'img/hamm.jpg',   'ham',    1),
('Slinky Dog',  'The Loyal Friend',      '"I gotta get me one of these!"',        'A loyal and friendly dachshund toy with a stretchy spring body, always ready to help his friends.',                                                         'img/dog.jpg',    'slinky', 1);
