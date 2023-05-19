<?php require APPROOT . '/views/layout/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['post']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
  Written by <?php echo $data['user']->name; ?> on <?php echo $data['post']->created_at; ?>
</div>
<p><?php echo $data['post']->body; ?></p>

<?php if ($data['post']->user_id == $_SESSION['user_id']) : ?>
  <hr>
  <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>

  <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" method="post">
    <input type="submit" value="Delete" class="btn btn-danger">
  </form>
<?php endif; ?>
<br><br>
<a href="<?php echo URLROOT; ?>/posts/comment/<?php echo $data['post']->id; ?>" class="btn btn-primary">Write Comment</a>

<br> <br>

<?php foreach ($data['comments'] as $comment) : ?>
  <h2><?php echo $comment->name; ?></h2>
  <p>Comment: <?php echo $comment->comment; ?></p>

  <hr>
<?php endforeach; ?>



<?php require APPROOT . '/views/layout/footer.php'; ?>