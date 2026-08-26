<?php
/**
 * Title: Хаб: Контакти
 * Slug: imidzh/hub-contacts
 * Categories: imidzh-hubs
 * Keywords: хаб, контакти, адреса, телефон, мапа
 * Description: Вступ і реквізити для сторінки контактів. Актуальні дані беріть із Налаштувача.
 * Viewport Width: 920
 */
$phone   = get_theme_mod( 'imidzh_phone', '+380 (50) 777 90 36' );
$email   = get_theme_mod( 'imidzh_email', 'uzhschool19@ukr.net' );
$address = get_theme_mod( 'imidzh_address', 'м. Ужгород, Закарпатська обл.' );
$hours   = get_theme_mod( 'imidzh_hours', __( 'Пн–Пт: 8:00–17:00', 'imidzh' ) );
$tel     = preg_replace( '/[^\d+]/', '', (string) $phone );
$visit   = esc_url( home_url( '/parents/visiting-hours/' ) );
$about   = esc_url( home_url( '/about/' ) );
?>
<!-- wp:paragraph {"className":"hub-intro__lead"} -->
<p class="hub-intro__lead">Як зв’язатися з Ужгородським ліцеєм «Імідж» і як нас знайти. Адресу, телефон, email і графік роботи можна оновити в <strong>Зовнішній вигляд → Налаштувати</strong>.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><strong>Адреса:</strong> <?php echo esc_html( $address ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Телефон:</strong> <a href="<?php echo esc_url( 'tel:' . $tel ); ?>"><?php echo esc_html( $phone ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Email:</strong> <a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Графік роботи:</strong> <?php echo esc_html( $hours ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Щоб додати мапу, вставте блок «Власний HTML» з кодом iframe з Google Maps (Поділитися → Вбудовування карти) або вкажіть URL мапи в Налаштувачі — шаблон хаба покаже її автоматично, якщо поле заповнене.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo $visit; ?>">Графік прийому громадян</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $about; ?>">Про ліцей</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
