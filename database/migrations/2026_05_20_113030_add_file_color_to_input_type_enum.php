<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // SQLite ne supporte pas ALTER COLUMN — on recrée la table avec la nouvelle contrainte CHECK

    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement("
            CREATE TABLE questions_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                questionnaire_id INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                explanation TEXT,
                input_type VARCHAR(255) NOT NULL CHECK (input_type IN (
                    'text','number','email','phone','date',
                    'textarea','select','radio','checkbox','file','color'
                )),
                options TEXT,
                required INTEGER NOT NULL DEFAULT 1,
                \"order\" INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (questionnaire_id) REFERENCES questionnaires(id) ON DELETE CASCADE
            )
        ");

        DB::statement('INSERT INTO questions_new SELECT * FROM questions');
        DB::statement('DROP TABLE questions');
        DB::statement('ALTER TABLE questions_new RENAME TO questions');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement("
            CREATE TABLE questions_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                questionnaire_id INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                explanation TEXT,
                input_type VARCHAR(255) NOT NULL CHECK (input_type IN (
                    'text','number','email','phone','date',
                    'textarea','select','radio','checkbox'
                )),
                options TEXT,
                required INTEGER NOT NULL DEFAULT 1,
                \"order\" INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (questionnaire_id) REFERENCES questionnaires(id) ON DELETE CASCADE
            )
        ");

        DB::statement("INSERT INTO questions_new SELECT * FROM questions WHERE input_type NOT IN ('file','color')");
        DB::statement('DROP TABLE questions');
        DB::statement('ALTER TABLE questions_new RENAME TO questions');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
