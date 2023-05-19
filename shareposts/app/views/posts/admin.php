<?php require APPROOT . '/views/layout/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light mb-4"><i class="fa fa-backward"></i> Back</a>

<?php foreach ($data['userInfos'] as $userInfo) : ?>
    <h2><?php echo $userInfo->name; ?></h2>
    <p>Email: <?php echo $userInfo->email; ?></p>
    <p>Created At: <?php echo $userInfo->created_at; ?></p>
    <p>Feedback: <?php echo $userInfo->body; ?></p>
    <hr>
<?php endforeach; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>