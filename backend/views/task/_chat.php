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
<!--                <button class="sa-chat__message-actions" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More">-->
<!--                    <svg-->
<!--                        xmlns="http://www.w3.org/2000/svg"-->
<!--                        width="1em"-->
<!--                        height="1em"-->
<!--                        viewBox="0 0 24 24"-->
<!--                        fill="none"-->
<!--                        stroke="currentColor"-->
<!--                        stroke-width="2"-->
<!--                        stroke-linecap="round"-->
<!--                        stroke-linejoin="round"-->
<!--                        class="feather feather-more-vertical"-->
<!--                    >-->
<!--                        <circle cx="12" cy="12" r="1"></circle>-->
<!--                        <circle cx="12" cy="5" r="1"></circle>-->
<!--                        <circle cx="12" cy="19" r="1"></circle>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <ul class="dropdown-menu" aria-label="Chat message context menu">-->
<!--                    <li><a class="dropdown-item" href="#">Reply</a></li>-->
<!--                    <li><a class="dropdown-item" href="#">Copy Text</a></li>-->
<!--                    <li><a class="dropdown-item" href="#">Forward Message</a></li>-->
<!--                    <li><hr class="dropdown-divider" /></li>-->
<!--                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>-->
<!--                </ul>-->
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
<form class="sa-chat__form d-flex">
    <input type="text" placeholder="" class="form-control" />
    <button class="btn btn-primary ms-3">Відправити</button>
</form>
