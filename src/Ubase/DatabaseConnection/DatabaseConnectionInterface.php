<?php

declare(strict_types=1);

namespace Ubase\DatabaseConnection;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Connects to the database
     * @return PDO
     */
    public function open(): PDO;

    /**
     * Closes database connexion
     * @return void
     */
    public function close(): void;
}