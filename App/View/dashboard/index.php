<?= extend('/frontView/layout/head.php') ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Creacion</th>
            <th scope="col">Ult Actualizacion</th>
            <th scope="col">Editar</th>
            <th scope="col">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <th scope="row"><?= $user->id ?></th>
                <td><?= $user->name ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->created_at ?></td>
                <td><?= $user->updated_at ?></td>
                <td><a href="<?= base_url('/dashboard/edit?id=' . $user->id) ?>" class="btn btn-outline-warning btn-sm">Editar</a></td>
                <td><a href="<?= base_url('/dashboard/delete?id=' . $user->id) ?>" class="btn btn-outline-danger btn-sm">Eliminar</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= extend('/frontView/layout/footer.php') ?>