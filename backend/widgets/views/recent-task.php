<?php


?>

<div class="col-12 col-xxl-9 d-flex">
    <div class="card flex-grow-1 saw-table">
        <div class="sa-widget-header saw-table__header">
            <h2 class="sa-widget-header__title">Недавні задачі</h2>
            <div class="sa-widget-header__actions">
                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-sm btn-sa-muted d-block"
                        id="widget-context-menu-6"
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
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-6">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Move</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="saw-table__body sa-widget-table text-nowrap">
            <table>
                <thead>
                <tr>
<!--                    <th>No.</th>-->
                    <th>Status</th>
                    <th>Task</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tasks as $index => $task): ?>
                <tr>
<!--                    <td><a href="--><?php //=\yii\helpers\Url::to(['/task/update', 'gid' => $task['task_gid']])?><!--" class="text-reset">--><?php //=$task['task_gid']?><!--</a></td>-->
                    <td>
                        <div class="d-flex fs-6"><div class="badge badge-sa-<?=\common\models\Task::getClassColor($task['section_project_name'])?>"><?=$task['section_project_name']?></div></div>
                    </td>

                    <td>
                        <div class="d-flex align-items-center">
                            <div><a href="<?=\yii\helpers\Url::to(['/task/update', 'gid' => $task['task_gid']])?>" class="text-reset"><?=$task['task_name']?></a></div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div><a href="#" class="text-reset"><?=$task['assignee_name']?></a></div>
                        </div>
                    </td>
                    <td><?=$task['task_created_at']?></td>
                    <td><?=$task['total_time'] ? Yii::$app->formatter->asCurrency($task['total_time']) : 0?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
