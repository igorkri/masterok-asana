<?php

function debug($data)
{
    \yii\helpers\VarDumper::dump($data, 10, true);
}

function debugC($data)
{
    \yii\helpers\VarDumper::dump($data, 10, false);
}

function debugDie($data)
{
    \yii\helpers\VarDumper::dump($data, 10, true); die;
}
