<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <form id="register-form" method="POST">
        <h2 class="mb-4" style="text-align:center;">Register</h2>

        <div class="mb-4">
          <input type="text" class="form-control" id="name" name="name" placeholder="Name">
        </div>

        <div class="mb-4">
          <input type="email" class="form-control" id="email" name="email" placeholder="Email">
        </div>

        <div class="mb-4">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>

        <!-- <button type="submit" class="btn btn-primary mb-3" style="width:22rem;">Register</button> -->
        <div class="d-grid">
          <button type="submit" class="btn btn-primary mb-3">Register</button>
        </div>
      </form>

      <div id="message"></div>
    </div>
  </div>
</div>

<script>
  const form = document.querySelector('#register-form');
  const message = document.querySelector('#message');
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    fetch('<?php echo URLROOT; ?>/users/register', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          message.innerHTML = '<p style="color:green; text-align: center;">' + data.message + '</p>';
        }
        if (data.status === 'error') {
          message.innerHTML = '<p style="color:red; text-align:center;">' + data.message + '</p>';
        }
      });
  });
</script>
<?php require APPROOT . '/views/layout/footer.php'; ?>