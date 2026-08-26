<?php
/**
 * Title: Хаб: Педагогам
 * Slug: imidzh/hub-teachers
 * Categories: imidzh-hubs
 * Keywords: педагоги, атестація, кваліфікація, підручники, хаб
 * Description: Вступний контент розділу для педагогів з акцентом на атестацію та підвищення кваліфікації.
 * Viewport Width: 1240
 */
$pd     = esc_url( home_url( '/teachers/professional-development/' ) );
$attest = esc_url( home_url( '/teachers/attestation/' ) );
$books  = esc_url( home_url( '/teachers/textbooks/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">Розділ для педагогічних працівників: підвищення кваліфікації, атестація педагогів і замовлення підручників. Атестація та професійний розвиток відбуваються згідно з чинним законодавством і локальними документами закладу.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Що є в цьому розділі</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"hub-intro__toc"} -->
<ul class="wp-block-list hub-intro__toc"><!-- wp:list-item -->
<li><a href="<?php echo $pd; ?>">Підвищення кваліфікації</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $attest; ?>">Атестація педагогів</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $books; ?>">Замовлення та вибір підручників</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"hub-intro__cta"} -->
<div class="wp-block-buttons hub-intro__cta"><!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $attest; ?>">Атестація педагогів</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta"} -->
<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $pd; ?>">Підвищення кваліфікації</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta is-style-outline"} -->
<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $books; ?>">Підручники</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
