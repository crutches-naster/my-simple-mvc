<?php

class Seeder
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = DB::connect();

        $this->tryInvokeSeeders();;
    }

    protected function tryInvokeSeeders() : void
    {
        try
        {
            $this->db->beginTransaction();

            //ToDo Seeder functionality


            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
        }
        catch (PDOException $exception) {
            d($exception->getMessage(), $exception->getTrace(), $exception->getFile(), $exception->getLine());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
        }
    }
}
