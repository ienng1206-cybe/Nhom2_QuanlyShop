<?php

class UserModel extends BaseModel
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($name, $email, $password, $role = 'client')
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users(name, email, password, role, created_at) VALUES (:name, :email, :password, :role, NOW())'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
        ]);
        return $this->find($this->pdo->lastInsertId());
    }

    public function updateRole($userId, $role)
    {
        if (!in_array($role, ['client', 'admin'], true)) {
            return false;
        }
        $stmt = $this->pdo->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $userId]);
    }

    public function toggleLock($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        $newStatus = ($user['is_locked'] ?? 0) ? 0 : 1;
        $stmt = $this->pdo->prepare('UPDATE users SET is_locked = :is_locked WHERE id = :id');
        return $stmt->execute(['is_locked' => $newStatus, 'id' => $userId]);
    }

    public function setLockStatus($userId, $locked)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET is_locked = :is_locked WHERE id = :id');
        return $stmt->execute(['is_locked' => $locked ? 1 : 0, 'id' => $userId]);
    }
}

