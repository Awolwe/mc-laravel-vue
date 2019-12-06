<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Загрузка...</title>
    <link rel="stylesheet" href="<?php echo e(asset("/css/main.css?".random_int(1000, 9999))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset("/css/app.css")); ?>">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="client-secret" content="imnAzQtA9TJlugtYFT4EL0s18B0bNCCIjudKcz82">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

</head>
<body>
<div id="app">
    <div v-bind:class="{ 'bg-faded': !user }">

        <sidebar ref="sidebar" v-if="user"></sidebar>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>

    </div>
</div>



<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
<script src="<?php echo e(asset("/js/app.js?v.1.7")); ?>" charset="utf-8"></script>


</body>
</html>