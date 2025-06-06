<?php

use yii\helpers\Url;
use common\models\Project;

?>

<div class="sa-app__sidebar">
    <div class="sa-sidebar">
        <div class="sa-sidebar__header">
            <a class="sa-sidebar__logo" href="<?= Url::to(['/site/index']) ?>">
                <!-- logo -->
                <div class="sa-sidebar-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120px" height="20px">
                        <path
                                d="M118.5,20h-1.1c-0.6,0-1.2-0.4-1.4-1l-1.5-4h-6.1l-1.5,4c-0.2,0.6-0.8,1-1.4,1h-1.1c-1,0-1.8-1-1.4-2l1.1-3l1.5-4l3.6-10c0.2-0.6,0.8-1,1.4-1h1.6c0.6,0,1.2,0.4,1.4,1l3.6,10l1.5,4l1.1,3C120.3,19,119.5,20,118.5,20z M111.5,6.6l-1.6,4.4h3.2L111.5,6.6z M99.5,20h-1.4c-0.4,0-0.7-0.2-0.9-0.5L94,14l-2,3.5v1c0,0.8-0.7,1.5-1.5,1.5h-1c-0.8,0-1.5-0.7-1.5-1.5v-17C88,0.7,88.7,0,89.5,0h1C91.3,0,92,0.7,92,1.5v8L94,6l3.2-5.5C97.4,0.2,97.7,0,98.1,0h1.4c1.2,0,1.9,1.3,1.3,2.3L96.3,10l4.5,7.8C101.4,18.8,100.7,20,99.5,20z M80.3,11.8L80,12.3v6.2c0,0.8-0.7,1.5-1.5,1.5h-1c-0.8,0-1.5-0.7-1.5-1.5v-6.2l-0.3-0.5l-5.5-9.5c-0.6-1,0.2-2.3,1.3-2.3h1.4c0.4,0,0.7,0.2,0.9,0.5L76,4.3l2,3.5l2-3.5l2.2-3.8C82.4,0.2,82.7,0,83.1,0h1.4c1.2,0,1.9,1.3,1.3,2.3L80.3,11.8z M60,20c-5.5,0-10-4.5-10-10S54.5,0,60,0s10,4.5,10,10S65.5,20,60,20z M60,4c-3.3,0-6,2.7-6,6s2.7,6,6,6s6-2.7,6-6S63.3,4,60,4z M47.8,17.8c0.6,1-0.2,2.3-1.3,2.3h-2L41,14h-4v4.5c0,0.8-0.7,1.5-1.5,1.5h-1c-0.8,0-1.5-0.7-1.5-1.5v-17C33,0.7,33.7,0,34.5,0H41c0.3,0,0.7,0,1,0.1c3.4,0.5,6,3.4,6,6.9c0,2.4-1.2,4.5-3.1,5.8L47.8,17.8z M42,4.2C41.7,4.1,41.3,4,41,4h-3c-0.6,0-1,0.4-1,1v4c0,0.6,0.4,1,1,1h3c0.3,0,0.7-0.1,1-0.2c0.3-0.1,0.6-0.3,0.9-0.5C43.6,8.8,44,7.9,44,7C44,5.7,43.2,4.6,42,4.2z M29.5,4H25v14.5c0,0.8-0.7,1.5-1.5,1.5h-1c-0.8,0-1.5-0.7-1.5-1.5V4h-4.5C15.7,4,15,3.3,15,2.5v-1C15,0.7,15.7,0,16.5,0h13C30.3,0,31,0.7,31,1.5v1C31,3.3,30.3,4,29.5,4z M6.5,20c-2.8,0-5.5-1.7-6.4-4c-0.4-1,0.3-2,1.3-2h1c0.5,0,0.9,0.3,1.2,0.7c0.2,0.3,0.4,0.6,0.8,0.8C4.9,15.8,5.8,16,6.5,16c1.5,0,2.8-0.9,2.8-2c0-0.7-0.5-1.3-1.2-1.6C7.4,12,7,11,7.4,10.3l0.4-0.9c0.4-0.7,1.2-1,1.8-0.6c0.6,0.3,1.2,0.7,1.6,1.2c1,1.1,1.7,2.5,1.7,4C13,17.3,10.1,20,6.5,20z M11.6,6h-1c-0.5,0-0.9-0.3-1.2-0.7C9.2,4.9,8.9,4.7,8.6,4.5C8.1,4.2,7.2,4,6.5,4C5,4,3.7,4.9,3.7,6c0,0.7,0.5,1.3,1.2,1.6C5.6,8,6,9,5.6,9.7l-0.4,0.9c-0.4,0.7-1.2,1-1.8,0.6c-0.6-0.3-1.2-0.7-1.6-1.2C0.6,8.9,0,7.5,0,6c0-3.3,2.9-6,6.5-6c2.8,0,5.5,1.7,6.4,4C13.3,4.9,12.6,6,11.6,6z"
                        ></path>
                    </svg>
                    <div class="sa-sidebar-logo__caption">Admin</div>
                </div>
                <!-- logo / end -->
            </a>
        </div>
        <div class="sa-sidebar__body" data-simplebar="">
            <ul class="sa-nav sa-nav--sidebar" data-sa-collapse="">
                <li class="sa-nav__section">
                    <div class="sa-nav__section-title"><span>Application</span></div>
                    <ul class="sa-nav__menu sa-nav__menu--root">
                        <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                            <a href="<?= Url::to(['/site/index']) ?>" class="sa-nav__link">
                                <span class="sa-nav__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                         viewBox="0 0 16 16" fill="currentColor">
                                        <path
                                                d="M8,13.1c-4.4,0-8,3.4-8-3C0,5.6,3.6,2,8,2s8,3.6,8,8.1C16,16.5,12.4,13.1,8,13.1zM8,4c-3.3,0-6,2.7-6,6c0,4,2.4,0.9,5,0.2C7,9.9,7.1,9.5,7.4,9.2l3-2.3c0.4-0.3,1-0.2,1.3,0.3c0.3,0.5,0.2,1.1-0.2,1.4l-2.2,1.7c2.5,0.9,4.8,3.6,4.8-0.2C14,6.7,11.3,4,8,4z"
                                        ></path>
                                    </svg>
                                </span>
                                <span class="sa-nav__title">Dashboard</span>
                            </a>
                        </li>
                        <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                            <a href="<?= Url::to(['/asana-project/index']) ?>" class="sa-nav__link">
                                <span class="sa-nav__icon">
                                    <i class="fa-solid fa-diagram-project"></i>
                                </span>
                                <span class="sa-nav__title">Проєкти</span>
                            </a>
                        </li>
                        <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                            <a href="<?= Url::to(['/asana-task/index']) ?>" class="sa-nav__link">
                                            <span class="sa-nav__icon">
                                                <i class="fa-solid fa-hourglass-half"></i>
                                            </span>
                                <span class="sa-nav__title">Задачі</span>
                            </a>
                        </li>
                        <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                            <a href="<?= Url::to(['/timer/index']) ?>" class="sa-nav__link">
                                            <span class="sa-nav__icon">
                                                <i class="fa-solid fa-stopwatch"></i>
                                            </span>
                                <span class="sa-nav__title">Timer</span>
                            </a>
                        </li>
                </li>
                <li class="sa-nav__section">
                    <div class="sa-nav__section-title"><span>Проєкти</span></div>
                    <ul class="sa-nav__menu sa-nav__menu--root">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?php foreach (\common\models\Project::find()->orderBy('name ASC')/*->cache(3600)*/ ->all() as $project): ?>
                                <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                                    <a href="<?= Url::to(['/task/index', 'project_gid' => $project->gid]) ?>"
                                       class="sa-nav__link">
                                        <span class="sa-nav__icon"><i class="fa-solid fa-diagram-project"></i></span>
                                        <span class="sa-nav__title"><?php echo $project->getName() ?></span>
                                        <?php if ($project->getTaskExecution() > 0): ?>
                                            <span class="sa-nav__menu-item-badge badge badge-sa-pill badge-sa-theme"><?= $project->getTaskExecution() ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Пожалуйста, войдите в систему, чтобы увидеть данные.</p>
                        <?php endif; ?>
                        <hr>
                        <li class="sa-nav__menu-item sa-nav__menu-item--has-icon"
                            data-sa-collapse-item="sa-nav__menu-item--open">
                            <a href="" class="sa-nav__link" data-sa-collapse-trigger="">
                                            <span class="sa-nav__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path
                                                            d="M64 64C28.7 64 0 92.7 0 128L0 384c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 64zm64 320l-64 0 0-64c35.3 0 64 28.7 64 64zM64 192l0-64 64 0c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64l0 64-64 0zm64-192c-35.3 0-64-28.7-64-64l64 0 0 64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                                            </span>
                                <span class="sa-nav__title">Звіти</span>
                                <span class="sa-nav__arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="6" height="9"
                                                     viewBox="0 0 6 9" fill="currentColor">
                                                    <path
                                                            d="M5.605,0.213 C6.007,0.613 6.107,1.212 5.706,1.612 L2.696,4.511 L5.706,7.409 C6.107,7.809 6.107,8.509 5.605,8.808 C5.204,9.108 4.702,9.108 4.301,8.709 L-0.013,4.511 L4.401,0.313 C4.702,-0.087 5.304,-0.087 5.605,0.213 Z"
                                                    ></path>
                                                </svg>
                                            </span>
                            </a>
                            <ul class="sa-nav__menu sa-nav__menu--sub"
                                data-sa-collapse-content="">
                                <li class="sa-nav__menu-item">
                                    <a href="<?=Url::to(['/site/file'])?>" class="sa-nav__link">
                                        <span class="sa-nav__menu-item-padding"></span>
                                        <span class="sa-nav__title">Файли</span>
                                    </a>
                                </li>
                                <li class="sa-nav__menu-item">
                                    <a href="<?=Url::to(['/report/invoice/index'])?>" class="sa-nav__link">
                                        <span class="sa-nav__menu-item-padding"></span>
                                        <span class="sa-nav__title">Рахунки</span>
                                    </a>
                                </li>
                                <li class="sa-nav__menu-item">
                                    <a href="<?=Url::to(['/report/payer/index'])?>" class="sa-nav__link">
                                        <span class="sa-nav__menu-item-padding"></span>
                                        <span class="sa-nav__title">Платники</span>
                                    </a>
                                </li>
                                <li class="sa-nav__menu-item">
                                    <a href="<?= Url::to(['/report/accounting-entries/index']) ?>" class="sa-nav__link">
                                        <span class="sa-nav__menu-item-padding"></span>
                                        <span class="sa-nav__title">Звірка</span>
                                    </a>
                                </li>
                                <li class="sa-nav__menu-item">
                                    <a href="<?= Url::to(['/act-of-work/index']) ?>" class="sa-nav__link">
                                        <span class="sa-nav__menu-item-padding"></span>
                                        <span class="sa-nav__title">Акти</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <hr>
                        <br>
                        <br>
                        <br>
                        <br>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="sa-app__sidebar-shadow"></div>
    <div class="sa-app__sidebar-backdrop" data-sa-close-sidebar=""></div>
</div>


