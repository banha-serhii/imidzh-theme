<?php
/**
 * Title: Хаб: Прозорість та звітність
 * Slug: imidzh/hub-transparency
 * Categories: imidzh-hubs
 * Keywords: хаб, прозорість, стаття 30, звітність
 * Description: Вступний текст для хаба публічної інформації за ст. 30 ЗУ «Про освіту».
 * Viewport Width: 920
 */
$statute  = esc_url( home_url( '/transparency/statute/' ) );
$license  = esc_url( home_url( '/transparency/license/' ) );
$report   = esc_url( home_url( '/transparency/annual-report/' ) );
$quality  = esc_url( home_url( '/transparency/quality-assurance/' ) );
$network  = esc_url( home_url( '/transparency/class-network/' ) );
$lang     = esc_url( home_url( '/transparency/language/' ) );
$rules    = esc_url( home_url( '/transparency/internal-regulations/' ) );
$finance  = esc_url( home_url( '/transparency/finance/' ) );
$staffing = esc_url( home_url( '/transparency/staffing-table/' ) );
$proc     = esc_url( home_url( '/transparency/procurement/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">Заклад освіти оприлюднює відкриту інформацію відповідно до статті 30 Закону України «Про освіту». У цьому розділі зібрано статут, ліцензію, річний звіт керівника, відомості про фінансування, штат, публічні закупівлі та організацію освітнього процесу.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Що є в цьому розділі</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"hub-intro__toc"} -->
<ul class="wp-block-list hub-intro__toc"><!-- wp:list-item -->
<li><a href="<?php echo $statute; ?>">Статут закладу</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $license; ?>">Ліцензія на провадження освітньої діяльності</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $report; ?>">Річний звіт керівника</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $quality; ?>">Забезпечення якості освіти</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $network; ?>">Мережа та наповнюваність класів</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $lang; ?>">Мова освітнього процесу</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $rules; ?>">Правила внутрішнього розпорядку</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $finance; ?>">Фінансовий звіт та кошторис</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $staffing; ?>">Штатний розпис</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $proc; ?>">Договори та публічні закупівлі</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"hub-intro__cta"} -->
<div class="wp-block-buttons hub-intro__cta"><!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $statute; ?>">Статут закладу</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta"} -->
<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $license; ?>">Ліцензія</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta is-style-outline"} -->
<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $finance; ?>">Фінансовий звіт</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
