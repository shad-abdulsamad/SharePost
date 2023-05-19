<?php require APPROOT . '/views/layout/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light mb-4"><i class="fa fa-backward"></i> Back</a>


<div class="card">
    <div class="card-header bg-dark text-white">
        Feedback Form
    </div>
    <div class="card-body">
        <form id="feedback" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Feedback</label>
                <textarea class="form-control" id="body" name="body" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
        </form>
        <div id="message"></div>
    </div>
</div>

<script>
    const form = document.querySelector('#feedback');
    const message = document.querySelector('#message');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('<?php echo URLROOT; ?>/posts/feedback', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    message.innerHTML = '<p style="color:green; text-align:center;">' + data.message + '</p>';
                    setTimeout(() => {
                        message.innerHTML = '';
                    }, 5000);
                    // redirect to the desired page
                    //window.location.href = 'posts';
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