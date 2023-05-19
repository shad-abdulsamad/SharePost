<?php
class User
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  // Regsiter user
  public function registerUser($name, $email, $hashed_password)
  {
    $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
    // Bind values
    $this->db->bind(':name', $name);
    $this->db->bind(':email', $email);
    $this->db->bind(':password', $hashed_password);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Login User
  public function loginUser($email, $password)
  {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    $hashed_password = $row->password;
    if (password_verify($password, $hashed_password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Find user by email
  public function findUserByEmail($email)
  {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    // Bind value
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    // Check row
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  // Get User by ID
  public function getUserById($id)
  {
    $this->db->query('SELECT * FROM users WHERE id = :id');
    // Bind value
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }

  public function updateUser($id, $name, $email, $password)
  {
    $this->db->query('UPDATE users SET name = :name, email = :email, password = :password WHERE id =:id');

    //bind the values
    $this->db->bind(':id', $id);
    $this->db->bind(':name', $name);
    $this->db->bind(':email', $email);
    $this->db->bind(':password', $password);

    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }





  //admin stuff

  public function retrieveUserInfoForAdmin()
  {
    //$this->db->query('SELECT name, email, created_at FROM users');
    $this->db->query('SELECT u.name, u.email, u.created_at, f.body
    FROM users u
    LEFT JOIN feedbacks f ON u.id = f.user_id;
    ');

    return $this->db->resultSet();
  }

  public function retrieveFeedback()
  {
    $this->db->query('SELECT body FROM feedbacks');
  }
}
