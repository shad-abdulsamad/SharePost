<?php
class Posts extends Controller
{
  protected $postModel;
  protected $userModel;

  public function __construct()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    $this->postModel = $this->model('Post');
    $this->userModel = $this->model('User');
  }



  public function index()
  {
    // Get posts
    $posts = $this->postModel->getPosts();

    $data = [
      'posts' => $posts
    ];

    $this->view('posts/index', $data);
  }



  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $title = $_POST['title'];
      $body = $_POST['body'];
      $user_id = $_SESSION['user_id'];

      //if both title and body are empty 
      if (empty($title) && empty($body)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Please Fill in The Fields'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate title
      if (empty($title)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Post title is required'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      //validate body
      if (empty($body)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Post body is required'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }


      if ($this->postModel->addPost($title, $body, $user_id)) {
        $response = ['status' => 'success', 'message' => 'Post created Successfully'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      } else {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Something Went Wrong'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
    }
    $this->view('posts/add');
  }



  public function edit($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $data = [
        'id' => $id,
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate data
      if (empty($data['title']) && empty($data['body'])) {
        $response = ['status' => 'error', 'message' => 'please fill in the fields'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
      if (empty($data['title'])) {
        $response = ['status' => 'error', 'message' => 'Title cannot be empty'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
      if (empty($data['body'])) {
        $response = ['status' => 'error', 'message' => 'Body cannot be empty'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      // Make sure no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validated
        if ($this->postModel->updatePost($data)) {
          $response = ['status' => 'error', 'message' => 'Post updated successfully'];
          header('Content-Type: application/json');
          echo json_encode($response);
          return;
        } else {
          die('Something went wrong');
        }
      } else {

        //$this->view('posts/edit', $data);
      }
    } else {
      // Get existing post from model
      $post = $this->postModel->getPostById($id);

      // Check for owner
      if ($post->user_id != $_SESSION['user_id']) {
        //redirect('posts');
      }

      $data = [
        'id' => $id,
        'title' => $post->title,
        'body' => $post->body
      ];

      $this->view('posts/edit', $data);
    }
  }



  public function show($id)
  {
    $post = $this->postModel->getPostById($id);
    $user = $this->userModel->getUserById($post->user_id);
    $comment = $this->postModel->retrieveComment($id);

    $data = [
      'post' => $post,
      'user' => $user,
      'comments' => $comment
    ];

    $this->view('posts/show', $data);
  }




  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing post from model
      $post = $this->postModel->getPostById($id);

      // Check for owner
      if ($post->user_id != $_SESSION['user_id']) {
        redirect('posts');
      }

      if ($this->postModel->deletePost($id)) {
        redirect('posts');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('posts');
    }
  }

  public function profile()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $id = $_SESSION['user_id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];


      if (empty($name) && empty($email) && empty($password)) {
        $response = ['status' => 'error', 'message' => 'please fill all the fields'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if (empty($name)) {
        $response = ['status' => 'error', 'message' => 'please enter the new name'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if (empty($email)) {
        $response = ['status' => 'error', 'message' => 'please enter the new email'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if ($this->userModel->findUserByEmail($email)) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => 'Email is already taken'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if (empty($password)) {
        $response = ['status' => 'error', 'message' => 'please enter the new password'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }



      if (strlen($password) < 6) {
        $response = ['status' => 'error', 'message' => 'password length must be at least 6 characters'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      if ($this->userModel->updateUser($id, $name, $email, $hashed_password)) {
        $response = ['status' => 'success', 'message' => 'data updated successfully'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      } else {
        $response = ['status' => 'error', 'message' => 'something went wrong'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
    }

    $this->view('posts/profile');
  }



  public function admin()
  {
    $userInfos = $this->userModel->retrieveUserInfoForAdmin();


    $data = [
      'userInfos' => $userInfos,

    ];

    $this->view('posts/admin', $data);
  }

  public function feedback()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $id = $_SESSION['user_id'];
      $name = $_SESSION['user_name'];
      $email = $_POST['email'];
      $feedback = $_POST['body'];

      if (empty($email) && empty($feedback)) {
        $response = ['status' => 'error', 'message' => 'All the Fields are Required'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if (empty($email)) {
        $response = ['status' => 'error', 'message' => 'Please Enter the Email'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if (empty($feedback)) {
        $response = ['status' => 'error', 'message' => 'Please Write Your Feedback'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if ($email != $_SESSION['user_email']) {
        $response = ['status' => 'error', 'message' => 'Please Use Your Email'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if ($this->postModel->insertFeedback($id, $name, $email, $feedback)) {
        $response = ['status' => 'success', 'message' => 'Feedback Sent Successfully'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      } else {
        $response = ['status' => 'error', 'message' => 'Something Went Wrong'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
    }
    $this->view('posts/feedback');
  }

  //comment stuff
  public function comment()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $comment = $_POST['comment'];
      $user_id = $_SESSION['user_id'];

      $post_id = $_POST['postId'];

      if (empty($comment)) {
        $response = ['status' => 'error', 'message' => 'Plese Write Your Comment'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }

      if ($this->postModel->insertComment($user_id, $post_id, $comment)) {
        $response = ['status' => 'success', 'message' => 'Comment Added'];
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
      }
    }

    $this->view('posts/comment');
  }
}
