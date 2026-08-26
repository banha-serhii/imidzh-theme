<?php
/**
 * Title: Хаб: Освітній процес
 * Slug: imidzh/hub-education
 * Categories: imidzh-hubs
 * Keywords: хаб, освіта, розклад, програми, НМТ
 * Description: Вступний текст для хаба «Освітній процес».
 * Viewport Width: 920
 */
$year   = esc_url( home_url( '/education/academic-year/' ) );
$curr   = esc_url( home_url( '/education/curriculum/' ) );
$times  = esc_url( home_url( '/education/timetables/' ) );
$dist   = esc_url( home_url( '/education/distance-learning/' ) );
$books  = esc_url( home_url( '/education/e-textbooks/' ) );
$olymp  = esc_url( home_url( '/education/olympiads/' ) );
$assess = esc_url( home_url( '/education/assessment/' ) );
$gov    = esc_url( home_url( '/education/student-government/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">У розділі «Освітній процес» зібрано структуру навчального року, освітні програми, розклади та графіки занять, а також порядок дистанційного навчання. Тут також є електронні підручники, олімпіади, підсумкова атестація (НМТ / ДПА) та учнівське самоврядування.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Що є в цьому розділі</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"hub-intro__toc"} -->
<ul class="wp-block-list hub-intro__toc"><!-- wp:list-item -->
<li><a href="<?php echo $year; ?>">Структура навчального року</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $curr; ?>">Освітні програми та навчальні плани</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $times; ?>">Розклади та графіки занять</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $dist; ?>">Дистанційне навчання</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $books; ?>">Електронні підручники</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $olymp; ?>">Олімпіади та конкурси</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $assess; ?>">Підсумкова атестація (НМТ / ДПА)</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $gov; ?>">Учнівське самоврядування</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"hub-intro__cta"} -->
<div class="wp-block-buttons hub-intro__cta"><!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $times; ?>">Розклади занять</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta"} -->
<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $year; ?>">Навчальний рік</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"hub-cta is-style-outline"} -->
<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $dist; ?>">Дистанційне навчання</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
