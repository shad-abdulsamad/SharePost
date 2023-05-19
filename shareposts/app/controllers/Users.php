<?php
class users extends Controller
{
  protected $userModel;
  public function __construct()
  {
    $this->userModel = $this->model('User');
  }


  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // retrieve form data
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      //if name,email, and password are empty
      if (empty($name) && empty($email) && empty($password)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Please Fill in the Fields'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate email
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // email is not valid, return an error response
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Invalid email address'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      } else {
        if ($this->userModel->findUserByEmail($email)) {
          http_response_code(400);
          $response = ['status' => 'error', 'message' => 'Email is already taken'];
          header('Content-Type: application/json');
          echo json_encode($response);
          return;
        }
      }

      //validate name
      if (empty($name)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Please Enter Name'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate password
      if (strlen($password) < 6) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Password must be at least 6 characters'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }


      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      // save form data to the database
      if ($this->userModel->registerUser($name, $email, $hashed_password)) {
        $response = ['status' => 'success', 'message' => 'Registration Successful, You can now Login'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      } else {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Something went wrong'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      // return a success response
    } else {
      // render the register form
      $this->view('users/register');
    }
  }


  public function login()
  {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // retrieve form data
      $email = $_POST['email'];
      $password = $_POST['password'];

      //if email and password are empty
      if (empty($email) && empty($password)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Please Fill in the Fields'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate email
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Invalid email address'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate password
      if (strlen($password) < 6) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Invalid Password'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }



      // validate user credentials against the database
      if ($this->userModel->findUserByEmail($email)) {
        //user found
      } else {
        //user not found
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'User not found'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      $loggedInUser = $this->userModel->loginUser($email, $password);

      if ($loggedInUser) {
        //create session
        $this->createusersession($loggedInUser);
        $message = 'Login successful!';
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => $message]);
        return;
      } else {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Password is wrong'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
    }

    // render the login form
    $this->view('users/login');
  }


  public function createusersession($user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_role'] = $user->role;
  }

  public function logout()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('users/login');
  }
}
