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
    <input type="text" name="story-message" placeholder="" class="form-control" />
    <button class="btn btn-primary ms-3" id="send-message">Відправити</button>
</div>


<?php
$js = <<<JS
    $('#send-message').on('click', function(e) {
        e.preventDefault(); // Остановка стандартного поведения кнопки
        var message = $('[name="story-message"]').val();
        
        // Проверка на пустое сообщение
        if (!message.trim()) {
            alert('Повідомлення не може бути пустим!');
            return;
        }

        $.ajax({
            url: 'send-message',
            type: 'POST',
            data: {
                message: message,
                task_gid: '{$model->gid}'
            },
            success: function(data) {
                console.log(data);
                $('[name="story-message"]').val(''); // Очистка поля ввода после успешной отправки
                $('#live-toast').removeClass('toast-sa-dark').addClass(data.toast.class);
                $('.toast #toast-name').text(data.toast.name);
                $('.toast .toast-body').html(data.toast.message);
                $('.toast').toast('show');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Помилка відправлення повідомлення.');
            }
        });
    });
JS;

$this->registerJs($js);