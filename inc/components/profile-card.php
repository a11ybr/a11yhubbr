<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'post_id' => 0,
    'badge_label' => '',
    'details_url' => '',
    'show_details_link' => true,
    'details_label' => 'Ver detalhes',
    'show_external_link' => true,
    'external_label' => 'Site',
    'show_social' => true,
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
$post_id = (int) $args['post_id'];
if ($post_id <= 0) {
    $post_id = get_the_ID();
}

if ($post_id <= 0) {
    return;
}

$title = get_the_title($post_id);
if (!is_string($title) || $title === '') {
    $title = 'Perfil';
}

$role = (string) get_post_meta($post_id, '_a11yhubbr_profile_role', true);
$location = (string) get_post_meta($post_id, '_a11yhubbr_profile_location', true);
$website = (string) get_post_meta($post_id, '_a11yhubbr_profile_website', true);
$social_raw = (string) get_post_meta($post_id, '_a11yhubbr_profile_social_links', true);

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
    } else {
        foreach (explode(',', $social_raw) as $part) {
            $url = esc_url_raw(trim($part));
            if ($url !== '') {
                $social_links[] = $url;
            }
        }
    }
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

$profile_url = $website !== '' ? esc_url_raw($website) : (!empty($social_links) ? $social_links[0] : '');
$details_url = is_string($args['details_url']) && $args['details_url'] !== '' ? $args['details_url'] : get_permalink($post_id);

$initials = '';
foreach (preg_split('/\s+/', trim($title)) as $word) {
    if ($word !== '') {
        $initials .= function_exists('mb_substr') ? mb_substr($word, 0, 1, 'UTF-8') : substr($word, 0, 1);
    }
    $initials_length = function_exists('mb_strlen') ? mb_strlen($initials, 'UTF-8') : strlen($initials);
    if ($initials_length >= 2) {
        break;
    }
}
if ($initials === '') {
    $initials = 'P';
}

$excerpt = get_the_excerpt($post_id);
if (!is_string($excerpt) || trim($excerpt) === '') {
    $excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $post_id)), 26);
}
?>
<article class="a11yhubbr-community-profile-card a11yhubbr-card-base">
  <header class="a11yhubbr-community-profile-head">
    <?php if (has_post_thumbnail($post_id)): ?>
      <?php echo get_the_post_thumbnail($post_id, 'thumbnail', array('class' => 'a11yhubbr-community-avatar-image', 'loading' => 'lazy', 'decoding' => 'async')); ?>
    <?php else: ?>
      <span class="a11yhubbr-community-avatar" aria-hidden="true"><?php echo esc_html(function_exists('mb_strtoupper') ? mb_strtoupper($initials, 'UTF-8') : strtoupper($initials)); ?></span>
    <?php endif; ?>

    <div class="a11yhubbr-community-profile-head-copy">
      <?php if ($args['badge_label'] !== ''): ?>
        <span class="a11yhubbr-content-item-badge"><?php echo esc_html((string) $args['badge_label']); ?></span>
      <?php endif; ?>
      <h3>
        <a href="<?php echo esc_url($details_url); ?>"><?php echo esc_html($title); ?></a>
        
      </h3>
    </div>
  </header>

  <?php if ($role !== ''): ?><p class="a11yhubbr-community-role"><?php echo esc_html($role); ?></p><?php endif; ?>
  <?php if ($location !== ''): ?><p class="a11yhubbr-community-location"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?php echo esc_html($location); ?></p><?php endif; ?>

  <p class="a11yhubbr-community-desc"><?php echo esc_html($excerpt); ?></p>
  <?php if (!empty($args['show_social']) && !empty($social_links)): ?>
    <div class="a11yhubbr-community-social">
      <?php foreach ($social_links as $social_url): ?>
        <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Abrir rede social">
          <i class="<?php echo esc_attr($social_icon_from_url($social_url)); ?>" aria-hidden="true"></i>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</article>
