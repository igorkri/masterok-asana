<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="ltr" data-scompiler-id="0">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?php $this->registerCsrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <title><?= Html::encode($this->title) ?></title>
    <!-- icon -->
    <link rel="icon" type="image/png" href="/images/favicon.png" />
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- sa-app -->
<div class="sa-app sa-app--desktop-sidebar-shown sa-app--mobile-sidebar-hidden sa-app--toolbar-fixed">
    <!-- sa-app__sidebar -->
    <?=$this->render('_sidebar')?>
    <!-- sa-app__sidebar / end -->
    <!-- sa-app__content -->

    <div class="sa-app__content">
        <!-- sa-app__toolbar -->
        <?=$this->render('_top')?>
        <!-- sa-app__toolbar / end -->
        <!-- sa-app__body -->
        <?=$content?>
        <!-- sa-app__body / end -->
        <!-- sa-app__footer -->
        <div class="sa-app__footer d-block d-md-flex">
            <!-- copyright -->
            MASTEROK ASANA
            <div class="m-auto"></div>
            <div>
                Powered by HTML — Design by
                <a href="https://themeforest.net/user/kos9/portfolio">Kos</a>
            </div>
            <!-- copyright / end -->
        </div>
        <!-- sa-app__footer / end -->
    </div>

    <!-- sa-app__content / end -->
    <!-- sa-app__toasts -->
    <div class="sa-app__toasts toast-container bottom-0 end-0"></div>
    <!-- sa-app__toasts / end -->
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();