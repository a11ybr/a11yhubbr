<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-profile-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $profile_type = (string) get_post_meta($post_id, '_a11yhubbr_profile_type', true);
    $role = (string) get_post_meta($post_id, '_a11yhubbr_profile_role', true);
    $location = (string) get_post_meta($post_id, '_a11yhubbr_profile_location', true);
    $website = (string) get_post_meta($post_id, '_a11yhubbr_profile_website', true);
    $social_raw = (string) get_post_meta($post_id, '_a11yhubbr_profile_social_links', true);
    $tags = get_the_terms($post_id, 'post_tag');
    $permalink = get_permalink($post_id);
    $tag_ids = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'ids'));

    $raw_content = (string) get_post_field('post_content', $post_id);
    $legacy_markers = array(
        'Tipo de perfil:',
        'Nome/Organizacao:',
        'Localizacao:',
        'Cargo/Especialidade:',
        'Website:',
        'Redes sociais:',
        'Arquivo de foto:',
        'Tags:',
    );

    $filtered_lines = array();
    foreach (preg_split('/\R/u', $raw_content) as $line) {
        $trimmed = ltrim((string) $line);
        $is_legacy = false;
        foreach ($legacy_markers as $marker) {
            if (stripos($trimmed, $marker) === 0 || strpos($trimmed, '- ') === 0) {
                $is_legacy = true;
                break;
            }
        }
        if ($is_legacy) {
            break;
        }
        $filtered_lines[] = $line;
    }
    $content_to_render = trim(implode("\n", $filtered_lines));
    if ($content_to_render === '') {
        $content_to_render = $raw_content;
    }
    $content_to_render = apply_filters('the_content', $content_to_render);

    $social_links = array();
    if ($social_raw !== '') {
        $decoded = json_decode($social_raw, true);
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                $network = '';
                if (is_array($item)) {
                    $url = esc_url_raw($item['url'] ?? '');
                    $network = sanitize_key((string) ($item['network'] ?? ''));
                } else {
                    $url = esc_url_raw((string) $item);
                }
                if ($url !== '') {
                    $social_links[] = array(
                        'url' => $url,
                        'network' => $network,
                    );
                }
            }
        }
    }

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Página inicial', 'url' => home_url('/')),
            array('label' => 'Rede', 'url' => home_url('/rede')),
            array('label' => get_the_title()),
        ),
        'icon' => 'fa-regular fa-id-card',
        'title' => get_the_title(),
        'summary' => get_the_excerpt() !== '' ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, $post_id)), 28),
        'use_page_context' => false,
        'context' => 'rede',
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-profile-head">
            <?php if (has_post_thumbnail()): ?>
              <?php echo get_the_post_thumbnail($post_id, 'medium', array('class' => 'a11yhubbr-single-profile-image', 'loading' => 'lazy', 'decoding' => 'async')); ?>
            <?php endif; ?>
            <div>
              <?php if ($profile_type !== ''): ?><span class="a11yhubbr-content-item-badge a11yhubbr-content-item-badge--rede"><?php echo esc_html($profile_type); ?></span><?php endif; ?>
              <?php if ($role !== ''): ?><p class="a11yhubbr-community-role"><?php echo esc_html($role); ?></p><?php endif; ?>
              <?php if ($location !== ''): ?><p class="a11yhubbr-community-location"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?php echo esc_html($location); ?></p><?php endif; ?>
            </div>
          </div>

          <div class="a11yhubbr-single-content-body"><?php echo wp_kses_post($content_to_render); ?></div>

          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <a href="<?php echo esc_url(add_query_arg(array('busca' => $tag->name, 'tipo' => 'rede'), home_url('/busca/'))); ?>"><?php echo esc_html($tag->name); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($social_links)): ?>
            <div class="a11yhubbr-community-social">
              <?php foreach ($social_links as $social_item): ?>
                <?php
                $social_url = (string) ($social_item['url'] ?? '');
                $social_network = (string) ($social_item['network'] ?? '');
                ?>
                <a class="a11yhubbr-social-link is-<?php echo esc_attr(function_exists('a11yhubbr_get_social_network_key') ? a11yhubbr_get_social_network_key($social_url, $social_network) : 'website'); ?>" href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Abrir rede social">
                  <i class="<?php echo esc_attr(function_exists('a11yhubbr_get_social_icon_class') ? a11yhubbr_get_social_icon_class($social_url, $social_network) : 'fa-solid fa-globe'); ?>" aria-hidden="true"></i>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <div class="a11yhubbr-side-card a11yhubbr-single-side">
            <h2>Informações do perfil</h2>
            <dl>
              <?php if ($profile_type !== ''): ?><div><dt>Tipo</dt><dd><?php echo esc_html($profile_type); ?></dd></div><?php endif; ?>
              <?php if ($role !== ''): ?><div><dt>Cargo / especialidade</dt><dd><?php echo esc_html($role); ?></dd></div><?php endif; ?>
              <?php if ($location !== ''): ?><div><dt>Localização</dt><dd><?php echo esc_html($location); ?></dd></div><?php endif; ?>
              <?php if ($website !== ''): ?><div><dt>Website</dt><dd><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">Abrir website <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a></dd></div><?php endif; ?>
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'contact_url' => home_url('/contato'),
            'suggest_label' => 'Sugerir alteração',
          )); ?>
        </aside>
      </div>
    </section>

    <?php
    $related_profile_query = null;
    if (!empty($tag_ids)) {
        $related_profile_query = new WP_Query(array(
            'post_type' => 'a11y_perfil',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'tag__in' => array_map('intval', $tag_ids),
        ));
    }

    if (!($related_profile_query instanceof WP_Query) || !$related_profile_query->have_posts()) {
        $related_profile_query = new WP_Query(array(
            'post_type' => 'a11y_perfil',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }
    ?>
    <?php if ($related_profile_query instanceof WP_Query && $related_profile_query->have_posts()): ?>
      <section class="a11yhubbr-section a11yhubbr-single-related">
        <div class="a11yhubbr-container">
          <h2 class="a11yhubbr-content-heading">Perfis relacionados</h2>
          <div class="a11yhubbr-content-results-grid">
            <?php while ($related_profile_query->have_posts()): $related_profile_query->the_post(); ?>
              <?php get_template_part('inc/components/profile-card', null, array(
                'post_id' => get_the_ID(),
                'badge_label' => (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_type', true),
                'details_url' => get_permalink(),
                'show_details_link' => true,
                'details_label' => 'Ver detalhes',
                'show_external_link' => true,
                'external_label' => 'Site',
                'show_social' => true,
              )); ?>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
