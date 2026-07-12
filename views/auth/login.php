<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill display-1 text-primary"></i>
                        <h3 class="mt-3 fw-bold">School MS</h3>
                        <p class="text-muted">Sign in to your account</p>
                    </div>
                    <div id="alert-box"></div>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted mt-3">&copy; <?= date('Y') ?> School Management System</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#loginForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '<?= url('login') ?>',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Welcome!', timer: 1500, showConfirmButton: false });
                setTimeout(() => { window.location.href = '<?= url('') ?>' + res.data.role + '/dashboard'; }, 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Login Failed', text: res.message });
            }
        },
        error: function(xhr) {
            var res = xhr.responseJSON;
            Swal.fire({ icon: 'error', title: 'Error', text: res ? res.message : 'Something went wrong' });
        }
    });
});
</script>
</body>
</html>