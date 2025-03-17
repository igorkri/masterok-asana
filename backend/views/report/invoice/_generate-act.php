<?php

/* @var $this yii\web\View */
/* @var $model common\models
eport\Invoice */
/* @var $payer common\models
eport\Payer */

$payer = $model->payer;
?>

<div class="container" style="font-size: 16px">
    <div class="row">
        <table width="100%">
            <tr>
                <td colspan="3" align="center" style="font-size: 18px;">ЗАТВЕРДЖУЮ</td>
                <td colspan="1"></td>
                <td colspan="3" align="center" style="font-size: 18px;">ЗАТВЕРДЖУЮ</td>
            </tr>
            <tr>
                <td colspan="3" width="350px" style="font-size: 18px;">
                    <br>ФОП Кривошей І.О.
                    <br><br><br>
                    Кривошей Ігор Олексійович
                </td>
                <td colspan="1" width="300px"></td>
                <td colspan="3" width="350px" style="font-size: 18px;">
                    Директор<br><?= $model->payer->name; ?>
                    <br><br><br>
                    <?= $model->payer->director; ?>
                </td>
            </tr>
        </table>
        <div style="text-align: center; font-size: 12px">
            <br>
            <br>
            <h5>АКТ здачі-прийняття робіт (надання послуг)  № <?= $model->act_no; ?>  від
                <?php
                if (!empty($model->date_act)){
                    echo date("d.m.Y", strtotime($model->date_act));
                }else{
                    echo "__________________";
                }
                ?>
                р.</h5>
        </div>
        <p style="font-size: 12px;">
            Ми, що нижче підписалися, представник Замовника <?=$payer->name?> в особі директора <?= $payer->director_case; ?>, з одного боку, і  представник Виконавця  ФОП Кривошей І.О. в особі Кривошей Ігор Олексійовича, з іншого боку, склали дійсний акт про те,
            що <?= !empty($payer->contract) ? "на підставі наступних документів:" : "виконавцем були проведені наступні роботи (зроблені такі послуги):";?>
        </p>
        <?php if(!empty($payer->contract)){
            echo '<p style="font-size: 13px; text-align: center;">
                Договір: № ' .  $payer->contract . ' від ' . date("d.m.Y",strtotime($payer->created_at)) . 'р.
                </p>
                <p style="font-size: 12px;">
                    виконавцем були проведені наступні роботи (зроблені такі послуги):
                </p>';
        }
        ?>

        <table width="100%" cellspacing="-1" cellpadding="6" border="1">
            <thead>
            <tr style="background: #bcbbbb">
                <th scope="col" align="center">№ п/п</th>
                <th scope="col" align="center">Послуга</th>
                <th scope="col" align="center">Од.</th>
                <th scope="col" align="center">К-сть</th>
                <th scope="col" align="center">Ціна</th>
                <th scope="col" align="center">Сума</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td><?= $model->title_act ?></td>
                <td>Послуга</td>
                <td><?= $model->qty ?></td>
                <td><?=Yii::$app->formatter->asDecimal($model->amount, 2) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($model->amount * $model->qty, 2) ?></td>
            </tr>
            </tbody>
        </table>
        <?php
        $total_sum = $model->amount * $model->qty;
        ?>
        <br>
        <div class="text-right" style="font-size: 12px; text-align: right"><b>Всього : <?= Yii::$app->formatter->asDecimal($total_sum, 2) ?></b></div>
        <div class="text-right" style="font-size: 12px; text-align: right"><b>Податок на додану вартість (ПДВ): 0,00</b></div>
        <div class="text-right" style="font-size: 12px; text-align: right"><b>Загальна вартість з ПДВ: <?= Yii::$app->formatter->asDecimal($total_sum, 2) ?></b></div>
        <div style="font-size: 12px; text-align: right">Місце складання: Полтава</div>
        <p style="font-size: 14px"><b>Загальна вартість робіт (послуг) склала:</b> <?= \common\models\report\NumberFormatterUA::numberToString($total_sum) ?></p>
        <p style="font-size: 12px">Сторони претензій одна до іншої не мають.</p>
        <p style="font-size: 12px">
            Виконавець працює за спрощеною системою оподаткування. ПДВ не сплачується.
        </p>

        <table width="100%" style="font-size: 22px">
            <tr>
                <td colspan="3" align="center">Від Виконавця</td><br>
                <td colspan="1"></td>
                <td colspan="3" align="center">Від Замовника</td><br>
            </tr>
            <tr>
                <td colspan="3" width="350px" align="center" style="text-decoration: overline;">
                    <br><br>Кривошей Ігор Олексійович
                </td>
                <td colspan="1" width="300px"></td>
                <td colspan="3" width="350px" align="center" style="text-decoration: overline;">
                    <br><br>Директор: <?= $model->payer->director; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" width="500px">
                    <br>
                    <b>ФОП Кривошей І. О.</b><br>
                    <b>Ідентифікаційний номер:</b> 3137600777<br>
                    <b>Юридична адреса:</b> Чернігівська область,
                    Варвинський район, с. Кухарка вул. Перемоги, буд.34<br>
                    <b>Фізична адреса:</b> м. Полтава вул. Пушкіна 22/14 офіс 204<br>
                    <b>тел.:</b> 096 521 2323<br>
                    <b>р/р №:</b> UA543071230000026000010537441<br>
                    <b>Банк:</b> ПАТ “БАНК ВОСТОК”<br>
                    <b>МФО:</b> 307123<br>
                </td>
                <td colspan="1" width="200px"></td>
                <td colspan="3" width="550px">
                    <br><?= $model->payer->requisites; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
