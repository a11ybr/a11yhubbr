(function () {
  'use strict';

  var addButton = document.getElementById('add-slot');
  var slots = document.getElementById('event-slots');

  if (!addButton || !slots) {
    return;
  }

  function bindRemove(button) {
    if (!button) {
      return;
    }

    button.addEventListener('click', function () {
      var allRows = slots.querySelectorAll('.a11yhubbr-slot');
      if (allRows.length <= 1) {
        return;
      }

      var row = button.closest('.a11yhubbr-slot');
      if (row) {
        row.remove();
        refreshRemoveButtons();
      }
    });
  }

  function refreshRemoveButtons() {
    var allRows = slots.querySelectorAll('.a11yhubbr-slot');
    allRows.forEach(function (row, index) {
      var removeButton = row.querySelector('.a11yhubbr-slot-remove');
      if (!removeButton) {
        return;
      }

      removeButton.hidden = allRows.length === 1;
      removeButton.disabled = allRows.length === 1;
      removeButton.setAttribute('aria-label', 'Remover data ' + (index + 1));
      removeButton.setAttribute('title', 'Remover data ' + (index + 1));
    });
  }

  addButton.addEventListener('click', function () {
    var row = document.createElement('div');
    row.className = 'a11yhubbr-slot';
    row.innerHTML =
      '<label>Início *<input type="datetime-local" name="slot_start[]" required></label>' +
      '<label>Fim *<input type="datetime-local" name="slot_end[]" required></label>' +
      '<button type="button" class="a11yhubbr-slot-remove" aria-label="Remover esta data" title="Remover esta data">&#128465;</button>';

    slots.appendChild(row);
    bindRemove(row.querySelector('.a11yhubbr-slot-remove'));
    refreshRemoveButtons();
  });

  var initialButtons = slots.querySelectorAll('.a11yhubbr-slot-remove');
  initialButtons.forEach(bindRemove);
  refreshRemoveButtons();
})();

(function () {
  'use strict';

  var marks = document.querySelectorAll('.a11yhubbr-hero-mark');
  if (!marks.length) {
    return;
  }

  function isInViewport(node) {
    var rect = node.getBoundingClientRect();
    var vh = window.innerHeight || document.documentElement.clientHeight;
    return rect.top < vh * 0.92 && rect.bottom > vh * 0.12;
  }

  function revealVisibleMarks() {
    marks.forEach(function (node) {
      if (isInViewport(node)) {
        node.classList.add('is-visible');
      }
    });
  }

  if (!('IntersectionObserver' in window)) {
    revealVisibleMarks();
    return;
  }

  marks.forEach(function (node) {
    node.classList.remove('is-visible');
  });

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      } else {
        entry.target.classList.remove('is-visible');
      }
    });
  }, {
    threshold: 0.12,
    rootMargin: '0px 0px -8% 0px'
  });

  marks.forEach(function (node) {
    observer.observe(node);
  });

  // Garante animação no primeiro paint quando já está visível.
  window.requestAnimationFrame(function () {
    window.requestAnimationFrame(revealVisibleMarks);
  });
})();

(function () {
  'use strict';

  var addButton = document.getElementById('add-social-link');
  var rows = document.getElementById('profile-social-links');

  if (!addButton || !rows) {
    return;
  }

  function bindRemove(button) {
    if (!button) {
      return;
    }

    button.addEventListener('click', function () {
      var allRows = rows.querySelectorAll('.a11yhubbr-social-slot');
      if (allRows.length <= 1) {
        return;
      }

      var row = button.closest('.a11yhubbr-social-slot');
      if (row) {
        row.remove();
        refreshRemoveButtons();
      }
    });
  }

  function refreshRemoveButtons() {
    var allRows = rows.querySelectorAll('.a11yhubbr-social-slot');
    allRows.forEach(function (row, index) {
      var removeButton = row.querySelector('.a11yhubbr-social-slot-remove');
      if (!removeButton) {
        return;
      }

      removeButton.hidden = allRows.length === 1;
      removeButton.disabled = allRows.length === 1;
      removeButton.setAttribute('aria-label', 'Remover link social ' + (index + 1));
      removeButton.setAttribute('title', 'Remover link social ' + (index + 1));
    });
  }

  addButton.addEventListener('click', function () {
    var row = document.createElement('div');
    row.className = 'a11yhubbr-slot a11yhubbr-social-slot';
    row.innerHTML =
      '<label>Rede social' +
      '<select name="social_network[]">' +
      '<option value="">Selecione</option>' +
      '<option value="linkedin">LinkedIn</option>' +
      '<option value="github">GitHub</option>' +
      '<option value="instagram">Instagram</option>' +
      '<option value="x">X/Twitter</option>' +
      '<option value="facebook">Facebook</option>' +
      '<option value="website">Outro website</option>' +
      '</select>' +
      '</label>' +
      '<label>URL' +
      '<input type="url" name="social_url[]" placeholder="https://...">' +
      '</label>' +
      '<button type="button" class="a11yhubbr-slot-remove a11yhubbr-social-slot-remove" aria-label="Remover link social" title="Remover link social">&#128465;</button>';

    rows.appendChild(row);
    bindRemove(row.querySelector('.a11yhubbr-social-slot-remove'));
    refreshRemoveButtons();
  });

  var initialButtons = rows.querySelectorAll('.a11yhubbr-social-slot-remove');
  initialButtons.forEach(bindRemove);
  refreshRemoveButtons();
})();

(function () {
  'use strict';

  var root = document.querySelector('.a11yhubbr-footer-newsletter-form');
  if (!root) {
    return;
  }

  function normalizeBrevoUi() {
    var emailField = root.querySelector('input[type="email"]');
    var form = root.querySelector('form');
    var submitControl = root.querySelector('input[type="submit"], button[type="submit"], .sib-default-btn');

    if (form && emailField && submitControl) {
      form.classList.add('a11yhubbr-brevo-inline');

      var emailBlock = emailField.closest('.sib-form-block, .sib-input, .form-group, p, div');
      var submitBlock = submitControl.closest('.sib-form-block, .sib-form-block__button, .form-group, p, div');
      var row = form.querySelector('.a11yhubbr-brevo-inline-row');

      if (!emailBlock) {
        emailBlock = emailField;
      }

      if (!submitBlock) {
        submitBlock = submitControl;
      }

      var sameWrapper = emailBlock === submitBlock;

      if (sameWrapper) {
        if (row && row.parentNode) {
          while (row.firstChild) {
            form.insertBefore(row.firstChild, row);
          }
          row.remove();
        }
      }

      if (!sameWrapper && emailBlock && emailBlock !== form) {
        emailBlock.classList.add('a11yhubbr-brevo-email-block');
      }

      if (!sameWrapper && submitBlock && submitBlock !== form) {
        submitBlock.classList.add('a11yhubbr-brevo-submit-block');
      }

      if (!sameWrapper && !row) {
        row = document.createElement('div');
        row.className = 'a11yhubbr-brevo-inline-row';
        form.insertBefore(row, form.firstChild);
      }

      if (!sameWrapper && emailBlock && emailBlock.parentNode !== row) {
        row.appendChild(emailBlock);
      }

      if (!sameWrapper && submitBlock && submitBlock.parentNode !== row) {
        row.appendChild(submitBlock);
      }
    }

    if (emailField) {
      emailField.setAttribute('placeholder', 'seu@email.com');
      if (!emailField.getAttribute('aria-label')) {
        emailField.setAttribute('aria-label', 'Endereéo de e-mail');
      }
    }

    root.querySelectorAll('input[type="submit"], button[type="submit"], .sib-default-btn').forEach(function (btn) {
      if (btn.tagName.toLowerCase() === 'input') {
        btn.value = 'Inscrever na newsletter';
      } else {
        btn.textContent = 'Inscrever na newsletter';
      }
    });

    root.querySelectorAll('.sib-form-message-panel, .sib_msg_disp, [class*="success"], [class*="error"]').forEach(function (node) {
      var text = (node.textContent || '').toLowerCase();
      var className = (node.className || '').toLowerCase();

      var isSuccess = className.indexOf('success') >= 0 || /subscribed|thank|sucesso|inscrit/.test(text);
      var isError = className.indexOf('error') >= 0 || /invalid|error|failed|falha|already|exists|existe/.test(text);

      if (isSuccess) {
        node.textContent = 'Inscrição confirmada com sucesso. Obrigado por fazer parte da comunidade A11YBR.';
      } else if (isError) {
        node.textContent = 'Não foi possível concluir a inscrição agora. Verifique o e-mail e tente novamente.';
      }
    });
  }

  normalizeBrevoUi();
  var observer = new MutationObserver(normalizeBrevoUi);
  observer.observe(root, { childList: true, subtree: true });
})();

(function () {
  'use strict';

  function bindCopyButton(button) {
    if (!button) {
      return;
    }

    button.addEventListener('click', function () {
      var url = button.getAttribute('data-copy-url') || window.location.href;
      if (!navigator.clipboard || !navigator.clipboard.writeText) {
        window.prompt('Copie o link:', url);
        return;
      }

      navigator.clipboard.writeText(url).then(function () {
        var old = button.textContent;
        button.textContent = 'Link copiado';
        window.setTimeout(function () {
          button.textContent = old;
        }, 1600);
      }).catch(function () {
        window.prompt('Copie o link:', url);
      });
    });
  }

  document.querySelectorAll('.a11yhubbr-copy-link').forEach(bindCopyButton);
})();
