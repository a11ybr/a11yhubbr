<?php
/*
Template Name: Projeto
*/
if (!defined('ABSPATH')) {
    exit;
}

$principles = array(
    array(
        'icon' => 'fa-regular fa-compass',
        'title' => 'Missao',
        'text' => 'Organizar e tornar encontraveis iniciativas, pessoas, eventos e referencias sobre acessibilidade digital no Brasil.',
    ),
    array(
        'icon' => 'fa-regular fa-eye',
        'title' => 'Visao',
        'text' => 'Ser uma base publica de referencia para quem busca contexto, repertorio e conexoes sem transformar a pauta em vitrine.',
    ),
    array(
        'icon' => 'fa-regular fa-handshake',
        'title' => 'Como atuamos',
        'text' => 'Com curadoria, documentacao e colaboracao comunitaria. A plataforma nao certifica nem ranqueia iniciativas.',
    ),
);

$story_steps = array(
    array(
        'title' => 'O problema de visibilidade',
        'text' => 'Boa parte do trabalho em acessibilidade digital acontece de forma dispersa. Ha eventos, pesquisas, coletivos, profissionais e ferramentas relevantes, mas nem sempre eles sao faceis de encontrar.',
    ),
    array(
        'title' => 'A motivacao da comunidade',
        'text' => 'A A11YBR nasce da necessidade de registrar esse ecossistema, reduzir a fragmentacao da informacao e dar contexto para quem quer aprender, contribuir ou contratar com mais criterio.',
    ),
    array(
        'title' => 'O resultado que buscamos',
        'text' => 'Quando uma iniciativa ganha visibilidade, ela tambem ganha chance de conexao. Isso fortalece a pauta, amplia repertorio e ajuda o tema a circular para alem dos mesmos grupos de sempre.',
    ),
);

$submission_types = array(
    array(
        'icon' => 'fa-regular fa-file-lines',
        'title' => 'Conteudos',
        'text' => 'Artigos, ferramentas, livros e materiais, multimidia e sites ou sistemas com relacao direta com acessibilidade digital.',
        'url' => function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo'),
        'label' => 'Submeter conteudo',
    ),
    array(
        'icon' => 'fa-regular fa-calendar',
        'title' => 'Eventos',
        'text' => 'Workshops, meetups, conferencias, webinars e outras atividades que ajudem a difundir praticas, debates e aprendizado.',
        'url' => function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos'),
        'label' => 'Submeter evento',
    ),
    array(
        'icon' => 'fa-regular fa-id-card',
        'title' => 'Perfis',
        'text' => 'Profissionais, empresas, coletivos, ONGs e comunidades que atuam com acessibilidade e inclusao digital.',
        'url' => function_exists('a11yhubbr_get_submit_profile_url') ? a11yhubbr_get_submit_profile_url() : home_url('/submeter/submeter-perfil'),
        'label' => 'Submeter perfil',
    ),
);

$summary_points = array(
    array(
        'icon' => 'fa-regular fa-compass',
        'title' => 'Missao e visao',
        'text' => 'Explicar o papel da A11YBR sem promessas infladas.',
    ),
    array(
        'icon' => 'fa-regular fa-comments',
        'title' => 'Motivacao coletiva',
        'text' => 'Mostrar por que a comunidade precisa de memoria e contexto.',
    ),
    array(
        'icon' => 'fa-regular fa-folder-open',
        'title' => 'Escopo de submissao',
        'text' => 'Deixar claro o que entra na plataforma e como isso se organiza.',
    ),
    array(
        'icon' => 'fa-regular fa-circle-check',
        'title' => 'Criterio editorial',
        'text' => 'Informacao util, verificavel e alinhada ao tema.',
    ),
);

$review_notes = array(
    array(
        'title' => 'Escopo claro',
        'text' => 'A pagina recebe somente iniciativas relacionadas a acessibilidade digital e com informacoes verificaveis.',
    ),
    array(
        'title' => 'Curadoria editorial',
        'text' => 'As submissoes entram como pendentes e passam por revisao antes de publicacao. O objetivo e consistencia, nao burocracia.',
    ),
    array(
        'title' => 'Copy informativa',
        'text' => 'Descricoes precisam explicar o que a iniciativa faz, para quem serve e por que ela pode ser util para a comunidade.',
    ),
    array(
        'title' => 'Sem excesso promocional',
        'text' => 'A plataforma existe para documentar e conectar. Nao e um espaco de publicidade, ranking ou certificacao.',
    ),
);

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-project-page">
    <?php
    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Pagina inicial', 'url' => home_url('/')),
            array('label' => 'Projeto'),
        ),
        'icon' => 'fa-solid fa-diagram-project',
    ));
    ?>

    <section class="a11yhubbr-section a11yhubbr-project-intro">
        <div class="a11yhubbr-container">
            <div class="a11yhubbr-project-hero a11yhubbr-card">
                <p class="a11yhubbr-project-eyebrow">Projeto comunitario</p>
                <div class="a11yhubbr-project-hero-copy">
                    <h2>Uma pagina para explicar por que a A11YBR existe</h2>
                    <p>A A11YBR organiza referencias sobre acessibilidade digital no Brasil. O objetivo e reunir informacoes uteis, dar visibilidade ao que ja existe e facilitar conexoes entre pessoas, grupos e organizacoes sem transformar a pauta em vitrine.</p>
                </div>
                <div class="a11yhubbr-project-summary-grid">
                    <?php foreach ($summary_points as $item) : ?>
                        <article class="a11yhubbr-project-summary-card">
                            <span class="a11yhubbr-project-card-icon is-summary" aria-hidden="true">
                                <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                            </span>
                            <div>
                                <strong><?php echo esc_html($item['title']); ?></strong>
                                <p><?php echo esc_html($item['text']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="a11yhubbr-section a11yhubbr-section-soft">
        <div class="a11yhubbr-container">
            <h2 class="a11yhubbr-section-title">Base conceitual do projeto</h2>
            <div class="a11yhubbr-cards-grid a11yhubbr-project-principles-grid">
                <?php foreach ($principles as $item) : ?>
                    <?php get_template_part('inc/components/feature-card', null, array(
                        'icon' => $item['icon'],
                        'title' => $item['title'],
                        'text' => $item['text'],
                    )); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="a11yhubbr-section">
        <div class="a11yhubbr-container">
            <div class="a11yhubbr-project-story-head">
                <p class="a11yhubbr-project-eyebrow">Storytelling</p>
                <h2>De onde vem a motivacao da comunidade</h2>
                <p>O desafio nem sempre e falta de iniciativa. Muitas vezes e falta de visibilidade, contexto e continuidade publica para o que ja esta sendo feito.</p>
            </div>

            <div class="a11yhubbr-home-grid-3 a11yhubbr-project-story-grid">
                <?php foreach ($story_steps as $step) : ?>
                    <article class="a11yhubbr-card a11yhubbr-project-story-card">
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="a11yhubbr-section a11yhubbr-section-soft">
        <div class="a11yhubbr-container">
            <div class="a11yhubbr-project-section-head">
                <h2 class="a11yhubbr-section-title">O que pode ser submetido</h2>
                <p>A plataforma foi desenhada para receber tres frentes principais de informacao. Cada uma delas ajuda a construir uma visao mais ampla do ecossistema.</p>
            </div>

            <div class="a11yhubbr-home-grid-3 a11yhubbr-project-submission-grid">
                <?php foreach ($submission_types as $item) : ?>
                    <article class="a11yhubbr-card a11yhubbr-project-submission-card">
                        <span class="a11yhubbr-project-card-icon" aria-hidden="true">
                            <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                        </span>
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['text']); ?></p>
                        <a class="a11yhubbr-content-item-details" href="<?php echo esc_url($item['url']); ?>">
                            <?php echo esc_html($item['label']); ?>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="a11yhubbr-section">
        <div class="a11yhubbr-container">
            <div class="a11yhubbr-project-section-head">
                <h2 class="a11yhubbr-section-title">Como tratamos as submissoes</h2>
                <p>O processo de envio existe para manter o diretorio util, compreensivel e confiavel. A ideia nao e filtrar por prestigio, mas por aderencia ao escopo e clareza da informacao.</p>
            </div>

            <div class="a11yhubbr-cards-grid a11yhubbr-project-review-grid">
                <?php foreach ($review_notes as $item) : ?>
                    <article class="a11yhubbr-card a11yhubbr-project-note-card">
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
