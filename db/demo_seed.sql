SET NAMES utf8mb4;

-- translators
INSERT INTO translator (name, email, is_active, created_at, updated_at) VALUES
('Иван Петров', 'ivan@example.com', 1, NOW(), NOW()),
('Анна Смирнова', 'anna@example.com', 1, NOW(), NOW()),
('Олег Кузнецов', 'oleg@example.com', 1, NOW(), NOW());

-- availability:
-- day_of_week: 1=Sunday, 2=Monday ... 7=Saturday (MySQL DAYOFWEEK format)

-- Иван: будни (2=Mon, 3=Tue, 4=Wed, 5=Thu, 6=Fri)
INSERT INTO translator_availability (translator_id, day_of_week, is_available, created_at)
SELECT t.id, d.day_of_week, 1, NOW()
FROM translator t
JOIN (SELECT 2 day_of_week UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6) d
WHERE t.email='ivan@example.com';

-- Анна: выходные (1=Sun, 7=Sat)
INSERT INTO translator_availability (translator_id, day_of_week, is_available, created_at)
SELECT t.id, d.day_of_week, 1, NOW()
FROM translator t
JOIN (SELECT 1 day_of_week UNION SELECT 7) d
WHERE t.email='anna@example.com';

-- Олег: будни, но среда недоступна (4 = Wed)
INSERT INTO translator_availability (translator_id, day_of_week, is_available, created_at)
SELECT t.id, d.day_of_week,
       CASE WHEN d.day_of_week = 4 THEN 0 ELSE 1 END,
       NOW()
FROM translator t
JOIN (SELECT 2 day_of_week UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6) d
WHERE t.email='oleg@example.com';
