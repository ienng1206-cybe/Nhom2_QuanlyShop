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
}
