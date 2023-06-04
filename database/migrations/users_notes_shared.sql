CREATE TABLE IF NOT EXISTS shared_notes (
        note_id BIGINT UNSIGNED,
        user_id INT UNSIGNED,

        PRIMARY KEY pk_shared_note (note_id, user_id)
    );
