<?php
/**
 * Title: Хаб: Безпека та захист
 * Slug: imidzh/hub-safety
 * Categories: imidzh-hubs
 * Keywords: безпека, тривога, булінг, цивільний захист, хаб
 * Description: Вступний контент розділу безпеки з основною дією — алгоритм під час повітряної тривоги.
 * Viewport Width: 1240
 */
$air     = esc_url( home_url( '/safety/air-raid/' ) );
$bully   = esc_url( home_url( '/safety/anti-bullying/' ) );
$domv    = esc_url( home_url( '/safety/domestic-violence/' ) );
$net     = esc_url( home_url( '/safety/safer-internet/' ) );
$civil   = esc_url( home_url( '/safety/civil-protection/' ) );
?>
<!-- wp:group {"className":"hub-intro","layout":{"type":"constrained"}} -->
<div class="wp-block-group hub-intro">
	<!-- wp:paragraph {"className":"hub-intro__lead"} -->
	<p class="hub-intro__lead">Безпека учнів і працівників — пріоритет ліцею. У розділі зібрано алгоритм дій під час повітряної тривоги, протидію булінгу, запобігання домашньому насильству, правила безпечного інтернету та цивільний захист. Ознайомте дитину з інструкцією заздалегідь і дійте спокійно, за порядком, затвердженим у закладі.</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"hub-alert hub-callout hub-callout--alert","layout":{"type":"constrained"}} -->
	<div class="wp-block-group hub-alert hub-callout hub-callout--alert">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading">Повітряна тривога</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Якщо лунає сигнал «Повітряна тривога», перейдіть до затвердженого алгоритму дій ліцею. Збережіть сторінку і повторіть її з дитиною до початку навчального року.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"hub-intro__cta"} -->
		<div class="wp-block-buttons hub-intro__cta">
			<!-- wp:button {"className":"hub-cta hub-cta--primary"} -->
			<div class="wp-block-button hub-cta hub-cta--primary"><a class="wp-block-button__link wp-element-button" href="<?php echo $air; ?>">Алгоритм дій під час повітряної тривоги</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading">Що є в цьому розділі</h2>
	<!-- /wp:heading -->

	<!-- wp:list {"className":"hub-intro__toc"} -->
	<ul class="wp-block-list hub-intro__toc">
		<!-- wp:list-item -->
		<li><a href="<?php echo $air; ?>">Алгоритм дій під час повітряної тривоги</a></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li><a href="<?php echo $bully; ?>">Протидія булінгу та омбудсман</a></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li><a href="<?php echo $domv; ?>">Запобігання домашньому насильству</a></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li><a href="<?php echo $net; ?>">Безпечний інтернет</a></li>
		<!-- /wp:list-item -->
		<!-- wp:list-item -->
		<li><a href="<?php echo $civil; ?>">Охорона праці та цивільний захист</a></li>
		<!-- /wp:list-item -->
	</ul>
	<!-- /wp:list -->

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:buttons {"className":"hub-intro__cta"} -->
	<div class="wp-block-buttons hub-intro__cta">
		<!-- wp:button {"className":"hub-cta"} -->
		<div class="wp-block-button hub-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $bully; ?>">Протидія булінгу</a></div>
		<!-- /wp:button -->
		<!-- wp:button {"className":"hub-cta is-style-outline"} -->
		<div class="wp-block-button hub-cta is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $civil; ?>">Цивільний захист</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
