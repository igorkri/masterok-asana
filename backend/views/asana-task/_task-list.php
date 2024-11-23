<?php


?>


<?php foreach ($dataProvider->getModels() as $model): ?>
    <tr>
        <td><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></td>
        <td>
            <div class="d-flex align-items-center">
                <div>
                    <a href="app-product.html" class="text-reset"><?=$model->name?></a>
                    <div class="sa-meta mt-0">
                        <ul class="sa-meta__list">
                            <li class="sa-meta__item">
                                <span title="Дата створення" class="st-copy"><?=Yii::$app->formatter->asDate($model->created_at, 'medium')?></span>
                            </li>
                            <li class="sa-meta__item">
                                <span title="Дата оновлення" class="st-copy"><?=Yii::$app->formatter->asDate($model->modified_at, 'medium')?></span>
                            </li>
                            <li class="sa-meta__item">
                                <span title="Виконавець" class="st-copy"><?=$model->assignee_name?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </td>
        <td><a href="app-category.html" class="text-reset">
                <div class="badge badge-sa-<?=$model->getStatusColor()?>"><?=$model->getStatus()?></div>
            </a></td>
        <td>
            <div class="dropdown">
                <button
                    class="btn btn-sa-muted btn-sm"
                    type="button"
                    id="product-context-menu-0"
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
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="product-context-menu-0">
                    <li><a class="dropdown-item" href="#">Edit</a></li>
                    <li><a class="dropdown-item" href="#">Duplicate</a></li>
                    <li><a class="dropdown-item" href="#">Add tag</a></li>
                    <li><a class="dropdown-item" href="#">Remove tag</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                </ul>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
