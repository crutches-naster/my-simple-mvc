<?php

use Core\DB;

require_once dirname(__DIR__) . '/Config/constants.php';
require_once BASE_DIR . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();

class Migration
{
    const MIGRATIONS_DIR = __DIR__ . '/migrations/';
    const MIGRATIONS_TABLE = '0_migrations';

    protected PDO $db;

    public function __construct()
    {
        $this->db = DB::connect();

        $this->tryInvokeMigrations();;
    }

    protected function tryInvokeMigrations() : void
    {
        try
        {
            $this->db->beginTransaction();

            $this->createMigrationsTable();
            $this->tryRunMigrations();

            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
        }
        catch (PDOException $exception) {
            d($exception->getMessage(), $exception->getTrace());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
        }
    }


    protected function createMigrationsTable()
    {
        d('----- Prepare migrations table query -----');
        $sql = file_get_contents(static::MIGRATIONS_DIR . static::MIGRATIONS_TABLE . '.sql');
        $query = $this->db->prepare($sql);

        $result = match( $query->execute() ) {
            true =>  '- Migrations table was created (or already exists)',
            false => '- Failed to create migrations table'
        };

        d($result);
        d('----- Finished migrations table query -----');
    }

    protected function tryRunMigrations()
    {
        d('----- FETCHING MIGRATIONS -----');

        $migrations = scandir(static::MIGRATIONS_DIR);
        $migrations = array_values(array_diff(
            $migrations,
            ['.', '..', static::MIGRATIONS_TABLE . '.sql']
        ));

        foreach($migrations as $migration) {
            $table = preg_replace('/[\d]+_/i', '', $migration);

            if (! $this->checkIfMigrationWasRun($migration)) {
                d("- Run [{$table}] ...");
                $sql = file_get_contents(static::MIGRATIONS_DIR . $migration);
                $query = $this->db->prepare($sql);

                if ($query->execute()) {
                    $this->logMigration($migration);
                    d("- [{$table}] - DONE!");
                }
            } else {
                d("- [{$table}] - ALREADY EXISTS!");
            }
        }
        d('----- FETCHING MIGRATIONS - DONE! -----');
    }


    protected function logMigration(string $migration): void
    {
        $query = $this->db->prepare("INSERT INTO migrations (name) VALUES (:name)");
        $query->bindParam('name', $migration);
        $query->execute();
    }

    protected function checkIfMigrationWasRun(string $migration): bool
    {
        $query = $this->db->prepare("SELECT * FROM migrations WHERE name = :name");
        $query->bindParam('name', $migration);
        $query->execute();

        return (bool) $query->fetch();
    }
}


new Migration();
