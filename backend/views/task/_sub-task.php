<?php


?>

<div class="card w-100 mt-5">
    <div class="card-body p-5">
        <div class="mb-5"><h2 class="mb-0 fs-exact-18">Під задачі</h2></div>
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Нзва</th>
                <th scope="col">Дата створення</th>
                <th scope="col">Дата завершення</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->subTasks as $key => $value): ?>
                <?php /* @var \common\models\Task $value */ ?>
                <tr>
                    <th scope="row">
                        <input type="checkbox" class="form-check form-check-input" data-sub_gid="<?=$value->gid?>" <?=$value->completed == 1 ? 'checked' : ''?> />
                    </th>
                    <td><?=$value->name?></td>
                    <td><?=$value->created_at?></td>
                    <td><?=$value->completed_at?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
