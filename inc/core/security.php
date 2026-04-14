<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_security_is_public_web_request() {
    if (is_admin()) {
        return false;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return false;
    }

    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return false;
    }

    return true;
}

/**
 * Headers HTTP de segurança
 * Protege contra XSS, clickjacking e MIME sniffing (WCAG + hardening)
 */
add_action('send_headers', function () {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
});


/**
 * Bloquear enumeração de usuários via ?author=N
 * Impede vazamento de usernames via redirects do WordPress
 */
add_action('init', function () {
    if (!a11yhubbr_security_is_public_web_request()) {
        return;
    }

    if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'GET') {
        return;
    }

    if (isset($_GET['author'])) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
});


/**
 * Mensagem de erro de login genérica
 * Não revela se o problema é o usuário ou a senha
 */
add_filter('login_errors', function () {
    return 'Usuário ou senha incorretos.';
});


/**
 * Limite de tentativas de login (brute-force básico via transients)
 * Bloqueia IP após 5 falhas em 15 minutos
 */
add_action('wp_login_failed', function ($username) {
    if (empty($_SERVER['REMOTE_ADDR'])) {
        return;
    }
    $ip  = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    $key = 'a11yhubbr_login_fail_' . md5($ip);
    $tentativas = (int) get_transient($key);
    set_transient($key, $tentativas + 1, 15 * MINUTE_IN_SECONDS);
});

add_filter('authenticate', function ($user, $username, $password) {
    if (empty($_SERVER['REMOTE_ADDR'])) {
        return $user;
    }
    $ip  = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    $key = 'a11yhubbr_login_fail_' . md5($ip);
    if ((int) get_transient($key) >= 5) {
        return new WP_Error(
            'too_many_retries',
            'Muitas tentativas de login. Aguarde 15 minutos antes de tentar novamente.'
        );
    }
    return $user;
}, 30, 3);


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
