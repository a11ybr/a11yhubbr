(function () {
  'use strict';

  function normalizeLabelAssociations(scope) {
    var root = scope || document;
    var fields = root.querySelectorAll('input, select, textarea');
    var seq = 1;
    var maxSeq = 0;

    document.querySelectorAll('[id^="a11y-field-"]').forEach(function (node) {
      var parts = (node.id || '').split('-');
      var value = parseInt(parts[parts.length - 1], 10);
      if (!Number.isNaN(value) && value > maxSeq) {
        maxSeq = value;
      }
    });
    seq = maxSeq + 1;

    fields.forEach(function (field) {
      if (!field.id) {
        field.id = 'a11y-field-' + seq;
        seq += 1;
      }

      var label = field.closest('label');
      if (label && !label.getAttribute('for')) {
        label.setAttribute('for', field.id);
      }
    });
  }

  window.a11yhubbrNormalizeLabelAssociations = normalizeLabelAssociations;
  normalizeLabelAssociations(document);
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

  window.requestAnimationFrame(function () {
    window.requestAnimationFrame(revealVisibleMarks);
  });
})();

(function () {
  'use strict';

  function handleClearClick(event) {
    var target = event.target;
    if (!target) {
      return;
    }

    var control = target.closest('.a11yhubbr-content-search-clear, .a11yhubbr-header-search-clear, [data-clear-url]');
    if (!control) {
      return;
    }

    var clearUrl = control.getAttribute('data-clear-url');
    if (!clearUrl) {
      return;
    }

    event.preventDefault();
    window.location.assign(clearUrl);
  }

  document.addEventListener('click', handleClearClick);
})();

(function () {
  'use strict';

  var toggle = document.querySelector('.a11yhubbr-menu-toggle');
  var panel = document.getElementById('a11yhubbr-header-panel');
  if (!toggle || !panel) {
    return;
  }

  function setOpen(isOpen) {
    panel.classList.toggle('is-open', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    toggle.setAttribute('aria-label', isOpen ? 'Fechar menu principal' : 'Abrir menu principal');
  }

  toggle.addEventListener('click', function () {
    setOpen(!panel.classList.contains('is-open'));
  });

  panel.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      if (window.matchMedia('(max-width: 1060px)').matches) {
        setOpen(false);
      }
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      setOpen(false);
    }
  });

  window.addEventListener('resize', function () {
    if (!window.matchMedia('(max-width: 1060px)').matches) {
      setOpen(false);
    }
  });
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
      if (sameWrapper && row && row.parentNode) {
        while (row.firstChild) {
          form.insertBefore(row.firstChild, row);
        }
        row.remove();
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
        emailField.setAttribute('aria-label', 'Endereco de e-mail');
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
        node.textContent = 'Inscricao confirmada com sucesso. Obrigado por fazer parte da comunidade A11YBR.';
      } else if (isError) {
        node.textContent = 'Nao foi possivel concluir a inscricao agora. Verifique o e-mail e tente novamente.';
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
