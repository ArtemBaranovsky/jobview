<?php

/* @var $this \yii\web\View */

/**
 * Массив выполненных целей
 */
$goals = [];

/**
 * Регистрация
 */
if (Yii::$app->session->getFlash('goal_registration')){
    $goals[] = 'yaCounter44070169.reachGoal("registration");';
}

/**
 * Активация аккаунта
 */
if (Yii::$app->session->getFlash('goal_activate_account')){
    $goals[] = 'yaCounter44070169.reachGoal("activate_account");';
}

/**
 * Пополнение баланса
 */
if (Yii::$app->session->getFlash('goal_pay_balance')){
    $goals[] = 'yaCounter44070169.reachGoal("pay_balance");';
}

/**
 * Если есть выполненные цели, отправляем их в метрику
 */
if (count($goals)){
    $goals = implode(PHP_EOL, $goals);
    $js =
        "
            /* Отправляем цель в Яндекс.Метрика */
            jQuery(document).on('yacounter44070169inited', function () {
                $goals
            });
        ";
    $this->registerJs($js);
}
?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter44070169 = new Ya.Metrika({
                    id:44070169,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    triggerEvent:true,
                    webvisor:<?= Yii::$app->user->isGuest ? 'true' : 'false' ?>,
                    ut:"noindex"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/44070169?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->