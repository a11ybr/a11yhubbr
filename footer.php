<footer class="a11yhubbr-site-footer">
  <section class="a11yhubbr-footer-newsletter-wrap" aria-labelledby="footer-newsletter-title">
    <div class="a11yhubbr-container">
      <div class="a11yhubbr-footer-newsletter">
        <h2 id="footer-newsletter-title">Receba novos conteúdos sobre acessibilidade</h2>
        <p>Fique por dentro das últimas novidades, recursos e discussões sobre acessibilidade digital no Brasil.</p>
        <div class="a11yhubbr-footer-newsletter-form">
          <?php
          if (shortcode_exists('sibwp_form')) {
              $newsletter_form = do_shortcode('[sibwp_form id=1]');
              $newsletter_form = preg_replace('#<style\b[^>]*>.*?</style>#is', '', (string) $newsletter_form);
              echo $newsletter_form;
          } else {
              echo '<p class="a11yhubbr-help" style="color:#fff;">Inscrição temporariamente indisponível.</p>';
          }
          ?>
        </div>

        <section class="a11yhubbr-footer-newsletter-benefits" aria-labelledby="footer-newsletter-benefits-title">
          <h3 id="footer-newsletter-benefits-title">O que você vai receber</h3>
          <div class="a11yhubbr-footer-newsletter-benefits-grid">
            <div class="a11yhubbr-footer-newsletter-benefit-card">
              <span class="a11yhubbr-footer-newsletter-benefit-icon" aria-hidden="true"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('bookmark') : ''; ?></span>
              <h4>Conteúdos<br />selecionados</h4>
              <p>Artigos, ferramentas e recursos cuidadosamente escolhidos.</p>
            </div>
            <div class="a11yhubbr-footer-newsletter-benefit-card">
              <span class="a11yhubbr-footer-newsletter-benefit-icon" aria-hidden="true"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('calendar') : ''; ?></span>
              <h4>Calendário<br />de eventos</h4>
              <p>Fique por dentro de conferências, workshops, webinars, etc.</p>
            </div>
            <div class="a11yhubbr-footer-newsletter-benefit-card">
              <span class="a11yhubbr-footer-newsletter-benefit-icon" aria-hidden="true"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('circle-dot') : ''; ?></span>
              <h4>Dicas<br />práticas</h4>
              <p>Sugestões para melhorar a acessibilidade dos seus projetos.</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>

  <div class="a11yhubbr-container a11yhubbr-footer-grid">
    <section>
      <h2 class="a11yhubbr-footer-logo">
        <span class="a11yhubbr-logo" aria-hidden="true">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-a11ybr.svg'); ?>" alt="" loading="lazy" decoding="async">
        </span>
      </h2>
      <p class="a11yhubbr-footer-text">Hub colaborativo de acessibilidade digital em português. Feito pela comunidade, para a comunidade.</p>

      <ul class="a11yhubbr-social-links" aria-label="Redes sociais da A11YBR">
        <li><a class="social-gh" href="https://github.com/a11yhubbr" target="_blank" rel="noopener noreferrer" aria-label="GitHub @a11yhubbr"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('github') : ''; ?></a></li>
        <li><a class="social-bs" href="https://bsky.app/profile/a11yhubbr.bsky.social" target="_blank" rel="noopener noreferrer" aria-label="Bluesky @a11yhubbr"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('bluesky') : ''; ?></a></li>
        <li><a class="social-x" href="https://x.com/a11yhubbr" target="_blank" rel="noopener noreferrer" aria-label="X @a11yhubbr"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('x-twitter') : ''; ?></a></li>
        <li><a class="social-in" href="https://linkedin.com/company/a11yhubbr" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn @a11yhubbr"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('linkedin') : ''; ?></a></li>
        <li><a class="social-ig" href="https://instagram.com/a11yhubbr" target="_blank" rel="noopener noreferrer" aria-label="Instagram @a11yhubbr"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('instagram') : ''; ?></a></li>
      </ul>
    </section>

    <section>
      <h2 class="a11yhubbr-footer-title">PLATAFORMA</h2>
      <?php
      wp_nav_menu(array(
        'theme_location' => 'footer_platform',
        'container' => false,
        'fallback_cb' => false,
        'menu_class' => 'a11yhubbr-footer-list',
      ));
      ?>
      <?php if (!has_nav_menu('footer_platform')) : ?>
        <ul class="a11yhubbr-footer-list">
          <li><a href="<?php echo esc_url(home_url('/')); ?>">Página inicial</a></li>
          <li><a href="<?php echo esc_url(home_url('/conteudos')); ?>">Conteúdos</a></li>
          <li><a href="<?php echo esc_url(home_url('/eventos')); ?>">Eventos</a></li>
          <li><a href="<?php echo esc_url(home_url('/rede')); ?>">Rede</a></li>
        </ul>
      <?php endif; ?>
    </section>

    <section>
      <h2 class="a11yhubbr-footer-title">COMUNIDADE</h2>
      <?php
      wp_nav_menu(array(
        'theme_location' => 'footer_community',
        'container' => false,
        'fallback_cb' => false,
        'menu_class' => 'a11yhubbr-footer-list',
      ));
      ?>
      <?php if (!has_nav_menu('footer_community')) : ?>
        <ul class="a11yhubbr-footer-list">
          <li><a href="<?php echo esc_url(home_url('/submeter')); ?>">Submeter</a></li>
          <li><a href="<?php echo esc_url(home_url('/diretrizes-da-comunidade')); ?>">Diretrizes da comunidade</a></li>
          <li><a href="<?php echo esc_url(home_url('/contato')); ?>">Contato</a></li>
          <li><a href="<?php echo esc_url(home_url('/newsletter')); ?>">Newsletter</a></li>
        </ul>
      <?php endif; ?>
    </section>

    <section>
      <h2 class="a11yhubbr-footer-title">LEGAL</h2>
      <?php
      wp_nav_menu(array(
        'theme_location' => 'footer_legal',
        'container' => false,
        'fallback_cb' => false,
        'menu_class' => 'a11yhubbr-footer-list',
      ));
      ?>
      <?php if (!has_nav_menu('footer_legal')) : ?>
        <ul class="a11yhubbr-footer-list">
          <li><a href="<?php echo esc_url(function_exists('a11yhubbr_get_accessibility_page_url') ? a11yhubbr_get_accessibility_page_url() : home_url('/acessibilidade')); ?>">Acessibilidade</a></li>
          <li><a href="<?php echo esc_url(function_exists('a11yhubbr_get_terms_page_url') ? a11yhubbr_get_terms_page_url() : home_url('/termos-de-uso')); ?>">Termos de uso</a></li>
          <li><a href="<?php echo esc_url(function_exists('a11yhubbr_get_privacy_page_url') ? a11yhubbr_get_privacy_page_url() : home_url('/politica-de-privacidade')); ?>">Política de privacidade</a></li>
        </ul>
      <?php endif; ?>
    </section>
  </div>

  <div class="a11yhubbr-container a11yhubbr-footer-copy">
    <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <strong>A11YBR</strong>. Conteúdo disponível sob licença <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">Creative Commons CC BY 4.0</a></p>
    <p>Feito com <span class="a11yhubbr-heart" aria-hidden="true"><?php echo function_exists('a11yhubbr_render_icon') ? a11yhubbr_render_icon('heart') : ''; ?></span> pela comunidade brasileira</p>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
