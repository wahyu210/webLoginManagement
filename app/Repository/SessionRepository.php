<?php
namespace MochamadWahyu\Phpmvc\Repository;

use MochamadWahyu\Phpmvc\Domain\Session;

class SessionRepository {
    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    public function save(Session $session): Session {
        $statement = $this->connection->prepare("INSERT INTO sessions (id, user_id) VALUES (?, ?)");
        $statement->execute([$session->id, $session->userId]);
        return $session;
    }

    public function findByID(string $id): ?Session {
        $statement = $this->connection->prepare("SELECT id, user_id FROM sessions WHERE id = ?");
        $statement->execute([$id]);
        
        try {
            if ($row = $statement->fetch()) {
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['user_id'];
                return $session;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id) {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
        $statement->execute([$id]);
    }

    public function deleteAll() {
        $statement = $this->connection->prepare("DELETE FROM sessions");
        $statement->execute(); // Ensure you execute the statement
    }
}
