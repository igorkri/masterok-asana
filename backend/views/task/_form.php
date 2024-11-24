<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

    <!-- sa-app__body -->
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?=\yii\helpers\Url::to(['index', 'project_gid' => $model->project_gid])?>">Таски</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Редагування таска</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0">Задача: <?=$model->name?> </h1>
                        </div>
                        <div class="col-auto d-flex">
<!--                            <a href="#" class="btn btn-secondary me-3">Дублювати</a>-->
                            <a href="#" class="btn btn-primary">Зберегти</a>
                        </div>
                    </div>
                </div>
                <div class="sa-page-meta mb-5">
                    <div class="sa-page-meta__body">
                        <div class="sa-page-meta__list">
                            <div class="sa-page-meta__item">Створено: <?=Yii::$app->formatter->asDatetime($model->created_at, 'medium')?></div>
                            <div class="sa-page-meta__item">Оновлено: <?=Yii::$app->formatter->asDatetime($model->modified_at, 'medium')?></div>
<!--                            <div class="sa-page-meta__item">Total $5,882.00</div>-->
                            <div class="sa-page-meta__item d-flex align-items-center fs-6">
                                <span class="badge badge-sa-<?=$model->getPriority2()['color']?> me-2"><?=$model->getPriority2()['name']?></span>
                                <span class="badge badge-sa-<?=$model->getType2()['color']?> me-2"><?=$model->getType2()['name']?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sa-entity-layout" data-sa-container-query='{"920":"sa-entity-layout--size--md","1100":"sa-entity-layout--size--lg"}'>
                    <div class="sa-entity-layout__body">
                        <div class="sa-entity-layout__main">
                            <div class="card">
                                <div class="card-body p-5">
                                    <div class="mb-5"><h2 class="mb-0 fs-exact-18">Основна інформація</h2></div>
                                    <div class="mb-4">
                                        <label for="form-product/name" class="form-label">Назва</label>
                                        <textarea id="form-product/name" class="form-control" rows="2"><?=$model->name?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="form-product/description" class="form-label">Опис</label>
                                        <textarea id="form-product/description" class="sa-quill-control form-control" rows="8"><?=$model->notes?></textarea>
                                    </div>
                                    <div>
                                        <label for="form-product/short-description" class="form-label">ВН Опис</label>
                                        <textarea id="form-product/short-description" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php if($model->subTasks):?>
                            <?=$this->render('sub-task', [
                                    'model' => $model
                                ])?>
                            <?php endif; ?>
                            <?php if($model->attachments):?>
                            <?= $this->render('_attachments', [
                                'model' => $model
                            ]) ?>
                            <?php endif; ?>
                            <div class="card w-100 mt-5">
                                <div class="card-body p-5">
                                    <?php echo $this->render('_chat',[
                                        'model' => $model
                                    ]) ?>
                                </div>
                            </div>

                        </div>
                        <div class="sa-entity-layout__sidebar">
                            <div class="card w-100">
                                <div class="card-body p-5">
                                    <div class="mb-5"><h2 class="mb-0 fs-exact-18">Статус</h2></div>
                                    <div class="mb-4">
                                        <?php foreach ($model->getStatusList() as $key => $value): ?>
                                        <label class="form-check">
                                            <input type="radio" value="<?=$key?>" class="form-check-input" name="status" <?=$model->section_project_name == $value ? 'checked=""' : ''?> />
                                            <span class="form-check-label"><?=$value?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card w-100 mt-5">
                                <div class="card-body p-5">
                                    <div class="mb-5"><h2 class="mb-0 fs-exact-18">Виконавець задачі</h2></div>
                                    <select class="form-select">
                                        <option value="">Виберіть виконавця</option>
                                        <?php foreach ($model->getAssigneeList() as $key => $value): ?>
                                        <option value="<?=$key?>" <?=$model->assignee_gid == $key ? 'selected=""' : ''?>><?=$value?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card w-100 mt-5">
                                <div class="card-body p-5">
                                    <div class="mb-5"><h2 class="mb-0 fs-exact-18">Приоритет</h2></div>
                                    <select class="form-select">
                                        <option value="">Виберіть приоритет</option>
                                        <?php foreach ($model->getPriorityList() as $key => $value): ?>
                                        <option value="<?=$key?>" <?=$model->getPriority2()['name'] == $value ? 'selected=""' : ''?>><?=$value?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="card-body p-5">
                                    <div class="mb-5"><h2 class="mb-0 fs-exact-18">Тип задачі</h2></div>
                                    <select class="form-select">
                                        <option value="">Виберіть тип задачі</option>
                                        <?php foreach ($model->getTypeList() as $key => $value): ?>
                                        <option value="<?=$key?>" <?=$model->getType2()['name'] == $value ? 'selected=""' : ''?>><?=$value?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                                <div class="card-body d-flex flex-column align-items-center">
                                <div class="sa-divider my-5"></div>
                                <div class="w-100">

                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, план.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?=$model->getTimePlan()?> хв.</dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, факт.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?=$model->getTimeFact()?> хв.</dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, рахунок.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?=$model->getTimeBill()?> хв.</dd>
                                    </dl>
                                    <div class="sa-divider my-5"></div>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Оплата (замовник)</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?=$model->getPaymentCustomer()?></dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Оплата (фахівець)</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?=$model->getPaymentSpecialist()?></dd>
                                    </dl>
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sa-app__body / end -->