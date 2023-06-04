CREATE TABLE IF NOT EXISTS notes(
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        author_id INT UNSIGNED NOT NULL,
        folder_id BIGINT UNSIGNED NOT NULL,
        content TEXT,
        pinned BOOL DEFAULT false,
        completed BOOL DEFAULT false,
        created_at DATETIME DEFAULT NOW(),
        updated_at DATETIME DEFAULT NOW()
    );
