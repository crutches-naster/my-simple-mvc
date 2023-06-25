CREATE TABLE IF NOT EXISTS shared_notes (
        note_id BIGINT UNSIGNED,
        user_id INT UNSIGNED,
        created_at DATETIME DEFAULT NOW(),
        updated_at DATETIME DEFAULT NOW()

        PRIMARY KEY pk_shared_note (note_id, user_id)
    );
