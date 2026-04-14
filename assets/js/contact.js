(function () {
  'use strict';

  var form     = document.getElementById('hub-contact-form');
  var feedback = document.getElementById('contact-feedback');
  var submit   = document.getElementById('contact-submit');

  if (!form || !feedback || !submit) {
    return;
  }

  var btnLabel   = submit.querySelector('.contact-btn-label');
  var btnSending = submit.querySelector('.contact-btn-sending');

  /**
   * Exibe mensagem de feedback acessível na região aria-live.
   * @param {string} message  Texto da mensagem.
   * @param {'success'|'error'} type  Tipo do feedback.
   */
  function showFeedback(message, type) {
    feedback.textContent = message;
    feedback.className = 'a11yhubbr-contact-feedback a11yhubbr-toast a11yhubbr-toast-' + type;
    feedback.removeAttribute('hidden');
    // Foco na região para leitores de tela que não anunciam aria-live em hidden→visible
    feedback.setAttribute('tabindex', '-1');
    feedback.focus();
  }

  function hideFeedback() {
    feedback.hidden = true;
    feedback.textContent = '';
    feedback.className = 'a11yhubbr-contact-feedback';
    feedback.removeAttribute('tabindex');
  }

  function setLoading(isLoading) {
    submit.disabled = isLoading;
    submit.setAttribute('aria-disabled', isLoading ? 'true' : 'false');
    if (btnLabel)   { btnLabel.setAttribute('aria-hidden',   isLoading ? 'true' : 'false'); }
    if (btnSending) {
      btnSending.setAttribute('aria-hidden', isLoading ? 'false' : 'true');
      btnSending.classList.toggle('a11yhubbr-sr-only', !isLoading);
    }
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    hideFeedback();

    // Validação nativa do browser antes de enviar
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    setLoading(true);

    var data = new FormData(form);
    data.append('action', 'hub_contact_submit');

    fetch(hubContactData.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data,
    })
      .then(function (res) { return res.json(); })
      .then(function (json) {
        if (json.success) {
          showFeedback(json.data.message, 'success');
          form.reset();
        } else {
          var msg = (json.data && json.data.message)
            ? json.data.message
            : 'Ocorreu um erro. Tente novamente ou envie para a11yhubbr@gmail.com.';
          showFeedback(msg, 'error');
        }
      })
      .catch(function () {
        showFeedback(
          'Falha na conexão. Verifique sua internet e tente novamente.',
          'error'
        );
      })
      .finally(function () {
        setLoading(false);
      });
  });
})();
