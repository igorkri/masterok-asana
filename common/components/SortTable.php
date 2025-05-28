<?php

namespace common\components;

use backend\assets\SortTableAsset;
use yii\helpers\Html;
use yii\web\View;

class SortTable extends \kartik\grid\DataColumn
{

    public function init()
    {
        SortTableAsset::register($this->grid->view);
        $this->grid->view->registerJs("
        initSortableWidgets();

        $(document).on('pjax:end', function() {
            initSortableWidgets(); // Повторная инициализация после PJAX-запроса
        });
    ", View::POS_READY, 'sortable');
    }


    protected function renderDataCellContent($model, $key, $index)
    {
        $offset = 0;

        if ($this->grid->dataProvider->pagination) {
            $offset = $this->grid->dataProvider->pagination->pageSize * $this->grid->dataProvider->pagination->page;
        }

        return Html::tag('button', '<i class="fa-solid fa-sort"></i>', [
            'class' => 'sortable-widget-handler btn btn-sm btn-light',
            'title' => 'Перетягніть для зміни порядку',
            'data-id' => $model->getPrimaryKey(),
            'data-offset' => $offset
        ]);
    }
}
