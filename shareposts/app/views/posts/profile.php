<?php require APPROOT . '/views/layout/header.php'; ?>

<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light mb-4"><i class="fa fa-backward"></i> Back</a>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <form id="update-form" method="POST">
                <h2 class="mb-4" style="text-align:center;">Profile Setting</h2>

                <div class="mb-4">
                    <input type="text" class="form-control" id="name" name="name" placeholder="New Name">
                </div>

                <div class="mb-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="New Email">
                </div>

                <div class="mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="New  Password">
                </div>



                <div class="d-grid">
                    <button type="submit" class="btn btn-primary mb-3">Update</button>
                </div>




            </form>


            <div id="message"></div>
        </div>
    </div>
</div>

<script>
    const form = document.querySelector('#update-form');
    const message = document.querySelector('#message');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('<?php echo URLROOT; ?>/posts/profile', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    message.innerHTML = '<p style="color:green; text-align: center;">' + data.message + '</p>';
                    setTimeout(() => {
                        message.innerHTML = '';
                    }, 5000);
                }
                if (data.status === 'error') {
                    message.innerHTML = '<p style="color:red; text-align:center;">' + data.message + '</p>';
                    setTimeout(() => {
                        message.innerHTML = '';
                    }, 5000);
                }
            });
    });
</script>
<?php require APPROOT . '/views/layout/footer.php'; ?>