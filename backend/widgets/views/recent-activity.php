<?php


?>

<div class="col-12 col-lg-6 d-flex">
    <div class="card flex-grow-1">
        <div class="card-body">
            <div class="sa-widget-header mb-4">
                <h2 class="sa-widget-header__title">Остання діяльність</h2>
                <div class="sa-widget-header__actions">
                    <div class="dropdown">
                        <button
                            type="button"
                            class="btn btn-sm btn-sa-muted d-block"
                            id="widget-context-menu-8"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="More"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                <path
                                    d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"
                                ></path>
                            </svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-8">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Move</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sa-timeline mb-n2 pt-2">
                <ul class="sa-timeline__list">
                    <?php foreach ($taskStories as $story): ?>
                    <li class="sa-timeline__item">
                        <div class="sa-timeline__item-title"><?= $story['created_by_name'] ?>
                            <?=date('d.m.Y H:i:s', strtotime($story['created_at'])) ?>
                        </div>
                        <div class="sa-timeline__item-content">
                            <a href="<?=\yii\helpers\Url::to(['/task/update', 'gid' => $story['task_gid']])?>">
                                <?= $story['text'] ?>
                            </a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
