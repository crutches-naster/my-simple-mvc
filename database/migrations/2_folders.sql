CREATE TABLE IF NOT EXISTS folders (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        user_id INT UNSIGNED DEFAULT 0,
        title VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT NOW(),
        updated_at DATETIME DEFAULT NOW(),

        UNIQUE KEY (user_id, title)
    );
