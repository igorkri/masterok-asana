<?php

/* @var $this yii\web\View */
/* @var $model common\models\report\Invoice */
?>


<div class="container">
    <div class="row">
        <h4 class="text-center">РАХУНОК-ФАКТУРА № <?= $model->invoice_no ?> від
            <?= !empty($model->date_invoice) ? Yii::$app->formatter->asDate($model->date_invoice, 'php:d.m.Y') : "__________________" ?>
            р.</h4>
        <p>
            <b>Постачальник:</b> ФОП Кривошей І. О.<br>
            <b>Ідентифікаційний номер (ЄДРПОУ):</b> 3137600777<br>
            <b>Юридична адреса:</b> Чернігівська область, Варвинський район, с. Кухарка вул. Перемоги, буд.34<br>
            <b>Фізична адреса:</b> м. Полтава вул. Пушкіна 22/14 оф. 206<br>
            <b>Тел.:</b> 096 521 2323<br>
            <b>р/р №:</b> UA543071230000026000010537441<br>
            <b>Банк:</b> ПАТ “БАНК ВОСТОК”<br>
            <b>МФО:</b> 307123<br>
        </p>
        <p>
            <b>Платник:</b> <?= $model->payer->name; ?><br>
        </p>
        <table width="100%" cellspacing="-1" cellpadding="6" border="1">
            <thead>
            <tr style="background: #bcbbbb">
                <th scope="col" align="center">№ п/п</th>
                <th scope="col" align="center">Послуга</th>
                <th scope="col" align="center">Од.</th>
                <th scope="col" align="center">К-сть</th>
                <th scope="col" align="center">Ціна, грн</th>
                <th scope="col" align="center">Сума, грн</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td align="center">1</td>
                <td><?= $model->title_invoice ?></td>
                <td align="center">Послуга</td>
                <td align="center"><?= $model->qty ?></td>
                <td align="right"><?= Yii::$app->formatter->asDecimal($model->amount, 2) ?></td>
                <td align="right"><?= Yii::$app->formatter->asDecimal($model->amount * $model->qty, 2) ?></td>
            </tr>
            </tbody>
        </table>
        <?php $total_sum = $model->amount * $model->qty; ?>
        <br>
        <div class="text-right" style="font-size: 12px"><b>Всього: <?= Yii::$app->formatter->asDecimal($total_sum, 2) ?> грн</b></div>
        <div class="text-right" style="font-size: 12px"><b>Податок на додану вартість (ПДВ): 0,00 грн</b></div>
        <div class="text-right" style="font-size: 12px"><b>Загальна вартість з ПДВ: <?= Yii::$app->formatter->asDecimal($total_sum, 2) ?> грн</b></div>
        <br>
        <p style="font-size: 12px"><b>Загальна вартість робіт (послуг) склала:</b>
            <i><?= \common\models\report\NumberFormatterUA::numberToString($total_sum) ?></i></p>
        <br><br>
        <p class="text-right" style="margin-right: 45px">Постачальник: Кривошей І. О. _________________ </p>
    </div>
</div>
