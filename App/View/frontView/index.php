<?= extend('/frontView/layout/head.php') ?>

<h1>home</h1>
<?= !empty($var) ? $var : '' ?>
<?= d($_SESSION) ?>

<?= extend('/frontView/layout/footer.php') ?>