<?php
if (!defined('ABSPATH')) {
    exit;
}

$social_icon_from_url = static function ($url) {
    $host = wp_parse_url($url, PHP_URL_HOST);
    $host = is_string($host) ? strtolower($host) : '';

    if (strpos($host, 'linkedin') !== false) {
        return 'fa-brands fa-linkedin-in';
    }
    if (strpos($host, 'github') !== false) {
        return 'fa-brands fa-github';
    }
    if (strpos($host, 'instagram') !== false) {
        return 'fa-brands fa-instagram';
    }
    if (strpos($host, 'twitter') !== false || strpos($host, 'x.com') !== false) {
        return 'fa-brands fa-x-twitter';
    }
    if (strpos($host, 'facebook') !== false) {
        return 'fa-brands fa-facebook-f';
    }

    return 'fa-solid fa-globe';
};

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-profile-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $profile_type = (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_type', true);
    $role = (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_role', true);
    $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_location', true);
    $website = (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_website', true);
    $social_raw = (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_social_links', true);
    $tags = get_the_terms(get_the_ID(), 'post_tag');
    $permalink = get_permalink();

    $social_links = array();
    if ($social_raw !== '') {
        $decoded = json_decode($social_raw, true);
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $url = esc_url_raw($item['url'] ?? '');
                if ($url !== '') {
                    $social_links[] = $url;
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
        'summary' => get_the_excerpt() !== '' ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 28),
        'use_page_context' => false,
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-profile-head">
            <?php if (has_post_thumbnail()): ?>
              <?php echo get_the_post_thumbnail(get_the_ID(), 'medium', array('class' => 'a11yhubbr-single-profile-image', 'loading' => 'lazy', 'decoding' => 'async')); ?>
            <?php endif; ?>
            <div>
              <?php if ($profile_type !== ''): ?><span class="a11yhubbr-content-item-badge"><?php echo esc_html($profile_type); ?></span><?php endif; ?>
              <?php if ($role !== ''): ?><p class="a11yhubbr-community-role"><?php echo esc_html($role); ?></p><?php endif; ?>
              <?php if ($location !== ''): ?><p class="a11yhubbr-community-location"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?php echo esc_html($location); ?></p><?php endif; ?>
            </div>
          </div>

          <?php the_content(); ?>

          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <span><?php echo esc_html($tag->name); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($social_links)): ?>
            <div class="a11yhubbr-community-social">
              <?php foreach ($social_links as $social_url): ?>
                <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Abrir rede social">
                  <i class="<?php echo esc_attr($social_icon_from_url($social_url)); ?>" aria-hidden="true"></i>
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
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
