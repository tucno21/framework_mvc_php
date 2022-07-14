<?= extend('/frontView/layout/head.php') ?>

<div class="container">
    <form action="<?= base_url('/login') ?>" method="POST">
        <div class="mb-3 form-group has-validation">
            <label class="form-label">Email address</label>
            <input name="email" type="email" email" class="form-control <?= isset($err->email) ? 'is-invalid' : '' ?>">
            <?php if (isset($err->email)) : ?>
                <div class="invalid-feedback">
                    <?= $err->email ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3 form-group has-validation">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control <?= isset($err->password) ? 'is-invalid' : '' ?>">
            <?php if (isset($err->password)) : ?>
                <div class="invalid-feedback">
                    <?= $err->password ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<a class="nav-link" href="/register">Registrarce</a>

<?= extend('/frontView/layout/footer.php') ?>