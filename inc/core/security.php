<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_security_settings_register() {
    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_enabled', array(
        'type' => 'string',
        'sanitize_callback' => static function ($value) {
            return $value === '1' ? '1' : '0';
        },
        'default' => '0',
    ));

    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_site_key', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ));

    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_secret_key', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ));
}
add_action('admin_init', 'a11yhubbr_security_settings_register');


function a11yhubbr_security_menu() {
    add_options_page(
        'A11YBR Seguranca',
        'A11YBR Seguranca',
        'manage_options',
        'a11yhubbr-security',
        'a11yhubbr_render_security_page'
    );
}
add_action('admin_menu', 'a11yhubbr_security_menu');


function a11yhubbr_get_turnstile_site_key() {
    if (defined('A11YHUBBR_TURNSTILE_SITE_KEY') && A11YHUBBR_TURNSTILE_SITE_KEY) {
        return (string) A11YHUBBR_TURNSTILE_SITE_KEY;
    }
    return (string) get_option('a11yhubbr_turnstile_site_key', '');
}


function a11yhubbr_get_turnstile_secret_key() {
    if (defined('A11YHUBBR_TURNSTILE_SECRET_KEY') && A11YHUBBR_TURNSTILE_SECRET_KEY) {
        return (string) A11YHUBBR_TURNSTILE_SECRET_KEY;
    }
    return (string) get_option('a11yhubbr_turnstile_secret_key', '');
}


function a11yhubbr_is_turnstile_enabled() {
    $enabled = (string) get_option('a11yhubbr_turnstile_enabled', '0') === '1';
    return $enabled && a11yhubbr_get_turnstile_site_key() !== '' && a11yhubbr_get_turnstile_secret_key() !== '';
}


function a11yhubbr_render_security_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
      <h1>A11YBR Seguranca</h1>
      <p>Ative o Cloudflare Turnstile para reduzir spam automatizado nos formularios de submissao.</p>
      <form method="post" action="options.php">
        <?php settings_fields('a11yhubbr_security'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row">Ativar Turnstile</th>
            <td>
              <label>
                <input type="checkbox" name="a11yhubbr_turnstile_enabled" value="1" <?php checked(get_option('a11yhubbr_turnstile_enabled', '0'), '1'); ?>>
                Exigir verificacao humana nos formularios de submissao
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="a11yhubbr_turnstile_site_key">Site key</label></th>
            <td><input type="text" class="regular-text" id="a11yhubbr_turnstile_site_key" name="a11yhubbr_turnstile_site_key" value="<?php echo esc_attr((string) get_option('a11yhubbr_turnstile_site_key', '')); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="a11yhubbr_turnstile_secret_key">Secret key</label></th>
            <td><input type="password" class="regular-text" id="a11yhubbr_turnstile_secret_key" name="a11yhubbr_turnstile_secret_key" value="<?php echo esc_attr((string) get_option('a11yhubbr_turnstile_secret_key', '')); ?>"></td>
          </tr>
        </table>
        <?php submit_button('Salvar configuracoes'); ?>
      </form>
    </div>
    <?php
}


function a11yhubbr_render_human_check_field() {
    if (!a11yhubbr_is_turnstile_enabled()) {
        return;
    }

    $site_key = a11yhubbr_get_turnstile_site_key();
    if ($site_key === '') {
        return;
    }
    ?>
    <div class="a11yhubbr-human-check">
      <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      <div class="cf-turnstile" data-sitekey="<?php echo esc_attr($site_key); ?>"></div>
      <p class="a11yhubbr-help">Confirmacao de seguranca para evitar envios automatizados.</p>
    </div>
    <?php
}


function a11yhubbr_turnstile_is_valid() {
    if (!a11yhubbr_is_turnstile_enabled()) {
        return true;
    }

    $token = isset($_POST['cf-turnstile-response']) ? sanitize_text_field(wp_unslash($_POST['cf-turnstile-response'])) : '';
    if ($token === '') {
        return false;
    }

    $secret = a11yhubbr_get_turnstile_secret_key();
    if ($secret === '') {
        return false;
    }

    $body = array(
        'secret' => $secret,
        'response' => $token,
    );
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $body['remoteip'] = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'timeout' => 8,
        'body' => $body,
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $payload = json_decode((string) wp_remote_retrieve_body($response), true);
    return is_array($payload) && !empty($payload['success']);
}

