<?php

declare(strict_types=1);

namespace Ubase\DatabaseConnection;

use Ubase\DatabaseConnection\Exception\DatabaseConnectionException;
use PDO;
use PDOException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    /**
     * @var PDO
     */
    protected PDO $dbh;

    /**
     * @array
     */
    protected array $credentials;

    /**
     * Main constructor class
     *
     * @return void
     */

    public function __construct(array $credentials, PDO $dbh)
    {
        $this->credentials = $credentials;
        $this->dbh = $dbh;
    }

    /**
     * @inheritdoc
     */
    public function open(): PDO
    {
        try {
        $params = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
            $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password']
            );
        } catch (PDOException $exception) {
            throw new DatabaseConnectionException($exception->getMessage(), (int)$exception->getCode());
        }

    }

    /**
     * @inheritdoc
     */
    public function close(): void
    {
        $this->dbh = null;
    }
}