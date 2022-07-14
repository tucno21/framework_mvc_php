<?= extend('/frontView/layout/head.php') ?>

<div class="container">

    <form method="POST" action="<?= base_url('/register') ?>" enctype="multipart/form-data">
        <div class="mb-3 form-group has-validation">
            <label class="form-label">Apellidos y Nombres</label>
            <input type="text" class="form-control <?= isset($err->name) ? 'is-invalid' : '' ?>" name="name" value="<?= isset($data->name) ? $data->name : '' ?>" />
            <?php if (isset($err->name)) : ?>
                <div class="invalid-feedback">
                    <?= $err->name ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3 form-group has-validation">
            <label class="form-label">Email</label>
            <input type="text" class="form-control <?= isset($err->email) ? 'is-invalid' : '' ?>" name="email" value="<?= isset($data->email) ? $data->email : '' ?>" />
            <?php if (isset($err->email)) : ?>
                <div class="invalid-feedback">
                    <?= $err->email ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3 form-group has-validation">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control <?= isset($err->password) ? 'is-invalid' : '' ?>" name="password" value="" />
            <?php if (isset($err->password)) : ?>
                <div class="invalid-feedback">
                    <?= $err->password ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3 form-group has-validation">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control <?= isset($err->password_confirm) ? 'is-invalid' : '' ?>" name="password_confirm" value="" />
            <?php if (isset($err->password_confirm)) : ?>
                <div class="invalid-feedback">
                    <?= $err->password_confirm ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Registrarme</button>
        </div>
    </form>
</div>

<?= extend('/frontView/layout/footer.php') ?>