<?php

use igorkri\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use igorkri\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
    <div class="container">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="<?= yii\helpers\Url::to(['index', 'project_gid' => $model->project_gid]) ?>">Таски</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Редагування таска</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Задача: <?= Html::encode($model->name) ?> </h1>
                </div>
                <div class="col-auto d-flex">
                    <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary', 'id' => 'send-form']) ?>
                </div>
            </div>
        </div>

        <div class="progress" style="--sa-progress--value: 0%; display: none">
            <div
                class="
            progress-bar
            progress-bar-sa-primary
            progress-bar-striped
            progress-bar-animated
        "
                role="progressbar"
                aria-valuenow="25"
                aria-valuemin="0"
                aria-valuemax="100"
            ></div>
        </div>

        <div class="sa-page-meta mb-5">
            <div class="sa-page-meta__body">
                <div class="sa-page-meta__list">
                    <div class="sa-page-meta__item">
                        Створено: <?= Yii::$app->formatter->asDatetime($model->created_at, 'medium') ?></div>
                    <div class="sa-page-meta__item">
                        Оновлено: <?= Yii::$app->formatter->asDatetime($model->modified_at, 'medium') ?></div>
                    <div class="sa-page-meta__item d-flex align-items-center fs-6">
                        <span class="badge badge-sa-<?= $model->getPriority2()['color'] ?> me-2"><?= Html::encode($model->getPriority2()['name']) ?></span>
                        <span class="badge badge-sa-<?= $model->getType2()['color'] ?> me-2"><?= Html::encode($model->getType2()['name']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="sa-entity-layout"
             data-sa-container-query='{"920":"sa-entity-layout--size--md","1100":"sa-entity-layout--size--lg"}'>
            <?php $form = ActiveForm::begin([
                'id' => 'task-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnBlur' => false,
                'validateOnChange' => false,
                'validateOnType' => false,
                'validateOnSubmit' => true,
            ]); ?>
            <div class="sa-entity-layout__body">
                <div class="sa-entity-layout__main">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="mb-5"><h2 class="mb-0 fs-exact-18">Основна інформація</h2></div>
                            <div class="mb-4">
                                <?= $form->field($model, 'name')->textarea(['rows' => 2, 'class' => 'form-control']) ?>
                            </div>

                            <div class="mb-4">
                                <?= $form->field($model, 'notes')->widget(CKEditor::class, [
                                    'id' => 'notes',
                                    'editorOptions' =>
                                        ElFinder::ckeditorOptions('elfinder', [
                                            'preset' => 'custom',
                                            'height' => 200,
                                            'language' => 'uk',
                                            'controller' => 'elfinder',
                                        ]),
                                ]) ?>
                            </div>
                            <div>
                                <?php echo $form->field($model, 'work_done')->widget(CKEditor::class, [
                                    'id' => 'work-done',
                                    'editorOptions' =>
                                        ElFinder::ckeditorOptions('elfinder', [
                                            'preset' => 'custom',
                                            'height' => 200,
                                            'language' => 'uk',
                                            'controller' => 'elfinder',
                                        ]),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($model->subTasks): ?>
                        <?= $this->render('sub-task', [
                            'model' => $model
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($model->attachments): ?>
                        <?= $this->render('_attachments', [
                            'model' => $model
                        ]) ?>
                    <?php endif; ?>
                    <div class="card w-100 mt-5">
                        <div class="card-body p-5">
                            <?= $this->render('_chat', [
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
                                <?= $form->field($model, 'section_project_name')->radioList(
                                    $model->getStatusList(),
                                    [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return '<label class="form-check">' .
                                                '<input type="radio" class="form-check-input" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . '>' .
                                                '<span class="form-check-label">' . $label . '</span>' .
                                                '</label>';
                                        }
                                    ]
                                )->label(false) ?>
                            </div>
                        </div>
                    </div>

                    <div class="card w-100 mt-5">
                        <div class="card-body p-5">
                            <div class="mb-5"><h2 class="mb-0 fs-exact-18">Виконавець задачі</h2></div>
                            <?= $form->field($model, 'assignee_gid')
                                ->dropDownList($model->getAssigneeList(), [
                                    'prompt' => 'Виберіть виконавця',
                                    'class' => 'form-select'
                                ])->label(false) ?>
                        </div>
                    </div>
                    <div class="card w-100 mt-5">
                        <div class="card-body p-5">
                            <div class="mb-5"><h2 class="mb-0 fs-exact-18">Приоритет</h2></div>
                            <?= $form->field($model, 'priority')
                                ->dropDownList($model->getPriorityList(), [
                                    'prompt' => 'Виберіть приоритет',
                                    'class' => 'form-select'
                                ])->label(false) ?>
                        </div>
                        <div class="card-body p-5">
                            <div class="mb-5"><h2 class="mb-0 fs-exact-18">Тип задачі</h2></div>
                            <?= $form->field($model, 'type')
                                ->dropDownList($model->getTypeList(), [
                                    'prompt' => 'Виберіть тип задачі',
                                    'class' => 'form-select'
                                ])->label(false) ?>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="sa-divider my-5"></div>
                            <div class="w-100">
                                <dl class="list-unstyled m-0 mt-4">
                                    <dt class="fs-exact-14 fw-medium">Час, план.</dt>
                                    <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimePlan() ?>хв.
                                    </dd>
                                </dl>
                                <dl class="list-unstyled m-0 mt-4">
                                    <dt class="fs-exact-14 fw-medium">Час, факт.</dt>
                                    <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimeFact() ?>хв.
                                    </dd>
                                </dl>
                                <dl class="list-unstyled m-0 mt-4">
                                    <dt class="fs-exact-14 fw-medium">Час, рахунок.</dt>
                                    <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimeBill() ?>хв.
                                    </dd>
                                </dl>
                                <div class="sa-divider my-5"></div>
                                <dl class="list-unstyled m-0 mt-4">
                                    <dt class="fs-exact-14 fw-medium">Оплата (замовник)</dt>
                                    <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getPaymentCustomer() ?></dd>
                                </dl>
                                <dl class="list-unstyled m-0 mt-4">
                                    <dt class="fs-exact-14 fw-medium">Оплата (фахівець)</dt>
                                    <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getPaymentSpecialist() ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>