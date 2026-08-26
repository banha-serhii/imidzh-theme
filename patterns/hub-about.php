<?php
/**
 * Title: Хаб: Про ліцей
 * Slug: imidzh/hub-about
 * Categories: imidzh-hubs
 * Keywords: хаб, про ліцей, about, вступ
 * Description: Вступний текст для хаба «Про ліцей». Сітка дочірніх сторінок додається шаблоном.
 * Viewport Width: 920
 */
$admin    = esc_url( home_url( '/about/administration/' ) );
$staff    = esc_url( home_url( '/about/staff/' ) );
$council  = esc_url( home_url( '/about/council/' ) );
$fac      = esc_url( home_url( '/about/facilities/' ) );
$acc      = esc_url( home_url( '/about/accessibility/' ) );
$vac      = esc_url( home_url( '/about/vacancies/' ) );
$contacts = esc_url( home_url( '/contacts/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">Ужгородський ліцей «Імідж» — комунальний заклад загальної середньої освіти. У цьому розділі зібрано офіційні відомості про керівництво, педагогічний колектив, раду ліцею, умови навчання та доступності. Контакти й розташування винесено на окрему сторінку.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Що є в цьому розділі</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"hub-intro__toc"} -->
<ul class="wp-block-list hub-intro__toc"><!-- wp:list-item -->
<li><a href="<?php echo $admin; ?>">Адміністрація</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $staff; ?>">Педагогічний колектив</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $council; ?>">Рада ліцею</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $fac; ?>">Матеріально-технічна база</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $acc; ?>">Інклюзія та умови доступності</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $vac; ?>">Вакансії</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $contacts; ?>">Контакти та розташування</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"hub-intro__cta"} -->
<div class="wp-block-buttons hub-intro__cta"><!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $admin; ?>">Адміністрація</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta"} -->
<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $staff; ?>">Педагогічний колектив</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta is-style-outline"} -->
<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $contacts; ?>">Контакти</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
