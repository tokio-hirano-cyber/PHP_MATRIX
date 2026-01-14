CREATE TABLE IF NOT EXISTS engineers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    skill VARCHAR(200) NOT NULL,
    experience_years INT DEFAULT 0,
    created_at DATETIME NOT NULL,
    INDEX idx_name (name),
    INDEX idx_email (email),
    INDEX idx_skill (skill)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO engineers (name, email, skill, experience_years, created_at) VALUES
('山田太郎', 'yamada@example.com', 'PHP, MySQL, Laravel', 5, NOW()),
('佐藤花子', 'sato@example.com', 'JavaScript, React, Node.js', 3, NOW()),
('鈴木一郎', 'suzuki@example.com', 'Python, Django, PostgreSQL', 7, NOW()),
('田中次郎', 'tanaka@example.com', 'Java, Spring Boot, Oracle', 4, NOW()),
('渡辺三郎', 'watanabe@example.com', 'C#, .NET, SQL Server', 6, NOW()),
('伊藤四郎', 'ito@example.com', 'Ruby, Rails, MySQL', 2, NOW()),
('中村五郎', 'nakamura@example.com', 'Go, Docker, Kubernetes', 3, NOW()),
('小林六郎', 'kobayashi@example.com', 'TypeScript, Vue.js, MongoDB', 4, NOW()),
('加藤七郎', 'kato@example.com', 'Swift, iOS, Objective-C', 5, NOW()),
('吉田八郎', 'yoshida@example.com', 'Kotlin, Android, Java', 3, NOW()),
('山本九郎', 'yamamoto@example.com', 'PHP, WordPress, MySQL', 2, NOW()),
('松本十郎', 'matsumoto@example.com', 'JavaScript, Angular, TypeScript', 6, NOW()),
('井上十一', 'inoue@example.com', 'Python, Flask, Redis', 4, NOW()),
('木村十二', 'kimura@example.com', 'Java, Hibernate, MySQL', 5, NOW()),
('林十三', 'hayashi@example.com', 'PHP, Symfony, PostgreSQL', 3, NOW());

