<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'gid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'assignee_gid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'assignee_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'assignee_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'section_project_gid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'section_project_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'completed')->textInput() ?>

    <?= $form->field($model, 'completed_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'due_on')->textInput() ?>

    <?= $form->field($model, 'start_on')->textInput() ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'permalink_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_gid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'workspace_gid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modified_at')->textInput() ?>

    <?= $form->field($model, 'resource_subtype')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_hearts')->textInput() ?>

    <?= $form->field($model, 'num_likes')->textInput() ?>

  
        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
