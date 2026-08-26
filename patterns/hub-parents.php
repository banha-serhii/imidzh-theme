<?php
/**
 * Title: Хаб: Вступникам та батькам
 * Slug: imidzh/hub-parents
 * Categories: imidzh-hubs
 * Keywords: хаб, батьки, вступ, прийом, харчування
 * Description: Вступний текст для хаба «Вступникам та батькам».
 * Viewport Width: 920
 */
$admission = esc_url( home_url( '/parents/admission/' ) );
$visit     = esc_url( home_url( '/parents/visiting-hours/' ) );
$meals     = esc_url( home_url( '/parents/meals/' ) );
$code      = esc_url( home_url( '/parents/code-of-conduct/' ) );
$support   = esc_url( home_url( '/parents/support-services/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">Розділ для вступників і батьків: правила прийому, територія обслуговування, графік особистого прийому громадян, харчування, правила поведінки учнів та психолого-логопедична підтримка. Якщо плануєте вступ до ліцею, почніть зі сторінки правил прийому — там порядок зарахування та перелік документів.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Що є в цьому розділі</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"hub-intro__toc"} -->
<ul class="wp-block-list hub-intro__toc"><!-- wp:list-item -->
<li><a href="<?php echo $admission; ?>">Правила прийому та територія обслуговування</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $visit; ?>">Графік особистого прийому громадян</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $meals; ?>">Організація харчування</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $code; ?>">Правила поведінки учнів</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $support; ?>">Психологічна служба та логопед</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"hub-intro__cta"} -->
<div class="wp-block-buttons hub-intro__cta"><!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $admission; ?>">Правила прийому</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta"} -->
<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $visit; ?>">Графік прийому громадян</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta is-style-outline"} -->
<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $meals; ?>">Харчування</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
