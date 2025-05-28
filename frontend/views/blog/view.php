<?php

$this->registerCss(<<<CSS
    body {
            font-family: sans-serif;
            line-height: 1.6;
            background: #f9f9f9;
            padding: 2em;
            max-width: 900px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #007acc;
        }
        code {
            background: #eee;
            padding: 2px 6px;
            border-radius: 4px;
        }
        pre {
            background: #eee;
            padding: 1em;
            border-radius: 5px;
            overflow-x: auto;
        }
        .note {
            background: #dff0d8;
            padding: 1em;
            border-left: 4px solid #3c763d;
            margin: 1em 0;
        }
CSS);




?>

<h1>🔀 Переключение версии PHP (Ubuntu / Pop!_OS + Apache)</h1>

<h2>1. Проверка активной версии PHP в Apache</h2>
<pre><code>ls /etc/apache2/mods-enabled/ | grep php</code></pre>
<p>Ожидаемый вывод:</p>
<pre>
    <code>
php8.3.conf
php8.3.load
    </code>
</pre>

<h2>2. Отключение старой и активация нужной версии</h2>
<pre>
    <code>
sudo a2dismod php8.2
sudo a2enmod php8.3
sudo systemctl restart apache2
    </code>
</pre>

<h2>3. Проверка версии PHP в CLI</h2>
<pre><code>php -v</code></pre>
<p>Если версия неправильная, переходим к следующему шагу:</p>

<h2>4. Переключение PHP в CLI (update-alternatives)</h2>
<pre><code>sudo update-alternatives --config php</code></pre>
<p>Вывод:</p>
<pre><code>
There are 3 choices for the alternative php (providing /usr/bin/php).

  Selection    Path             Priority   Status
------------------------------------------------------------
* 0            /usr/bin/php8.1   81        auto mode
  1            /usr/bin/php7.4   74        manual mode
  2            /usr/bin/php8.2   82        manual mode
  3            /usr/bin/php8.3   83        manual mode
</code></pre>
<p>Введите номер нужной версии и нажмите Enter.</p>

<h2>5. Проверка версии PHP в Apache (через браузер)</h2>
<p>Создаём файл <code>info.php</code>:</p>
<pre><code>sudo nano /var/www/html/info.php</code></pre>
<p>Содержимое:</p>
<pre><code>&lt;?php phpinfo();</code></pre>

<p>Открываем в браузере:</p>
<pre><code>http://localhost/info.php</code></pre>
<p>Ищем строку:</p>
<pre><code>PHP Version => 8.3.19</code></pre>

<p>После проверки удаляем файл:</p>
<pre><code>sudo rm /var/www/html/info.php</code></pre>

<div class="note">
    ✅ Готово! Теперь и CLI, и Apache используют одну и ту же версию PHP.
</div>
