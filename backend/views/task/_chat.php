<?php

//debugDie($model->stories);
?>

<div class="sa-chat__messages">
    <div class="sa-chat__divider">Коментарі та історія</div>
    <?php foreach ($model->stories as $story): ?>
    <?php if ($story->created_by_resource_type === 'comment') : ?>
    <div class="sa-chat__message sa-chat__message--opposite">
        <div class="sa-chat__message-parts">
            <div class="sa-chat__message-part dropdown">
                <div class="sa-chat__message-text"><?=$story->text?></div>
            </div>
        </div>
        <div class="sa-chat__message-time"><?=$story->created_by_name . ' ' . $story->created_at?></div>
    </div>
    <?php else: ?>
    <div class="sa-chat__message">
        <div class="sa-chat__message-parts">
            <div class="sa-chat__message-part dropdown">
                <div class="sa-chat__message-text"><?=$story->text?></div>
            </div>
        </div>
        <div class="sa-chat__message-time"><?=$story->created_by_name . ' ' . $story->created_at?></div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

</div>
<br>
<br>
<br>
<div class="sa-chat__form d-flex">
    <input type="text" placeholder="" class="form-control" />
    <button class="btn btn-primary ms-3">Відправити</button>
</div>
