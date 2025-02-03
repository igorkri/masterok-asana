<?php


?>

<div class="card w-100 mt-5">
<div class="card flex-grow-1 mx-sm-0 mx-n4">
    <div class="sa-divider"></div>
    <div class="p-md-5 p-4">
        <div class="mt-5">
            <div class="fs-6 fw-medium mb-3">Файли</div>
            <div class="sa-grid">
                <div class="sa-grid__body">
                    <?php foreach ($model->attachments as $attachment):?>
                    <?php /* @var \common\models\TaskAttachment $attachment */ ?>
                    <div class="sa-grid__item">
                        <a href="<?=$attachment->getPermanentUrl()?>" class="sa-file sa-file--type--jpg" target="_blank">
                            <div class="sa-file__thumbnail sa-box">
                                <div class="sa-box__body">
                                    <div class="sa-box__container sa-file__icon">
                                        <?=$attachment->getIcon()?>
                                    </div>
                                </div>
                                <div class="sa-file__badge badge badge-sa-dark text-uppercase"><?=$attachment->getExtension()?></div>
                            </div>
                            <div class="sa-file__info">
                                <div class="sa-file__name" style="font-size: 5px;" title="<?=$attachment->getName()?>">
                                    <?=$attachment->getName()?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>