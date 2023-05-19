<?php
class Pages extends Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    if (isLoggedIn()) {
      redirect('posts');
    }

    $data = [
      'title' => 'SharePosts',
      'description' => 'Simple Blog Platform built on a customized PHP framework'
    ];

    $this->view('pages/index', $data);
  }

  public function about()
  {
    $data = [
      'title' => 'About Us',
      'description' => 'Share your ideas with us'
    ];

    $this->view('pages/about', $data);
  }
}
