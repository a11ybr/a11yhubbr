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

  function initRadioProxyField(groupName, targetId) {
    var target = document.getElementById(targetId);
    var radios = document.querySelectorAll('input[type="radio"][name="' + groupName + '"]');
    if (!target || !radios.length) {
      return;
    }

    function syncRadiosFromTarget() {
      var current = String(target.value || '').trim();
      radios.forEach(function (radio) {
        radio.checked = radio.value === current;
      });
    }

    radios.forEach(function (radio) {
      radio.addEventListener('change', function () {
        if (!radio.checked) {
          return;
        }
        target.value = radio.value;
        target.dispatchEvent(new Event('input', { bubbles: true }));
        target.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });

    target.addEventListener('change', syncRadiosFromTarget);
    syncRadiosFromTarget();
  }

  initRadioProxyField('modality_choice', 'event-modality');
})();

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
    var count = slots.querySelectorAll('.a11yhubbr-slot').length + 1;
    var startId = 'slot-start-' + count;
    var endId = 'slot-end-' + count;
    var row = document.createElement('div');
    row.className = 'a11yhubbr-slot';
    row.innerHTML =
      '<label class="a11yhubbr-slot-field" for="' + startId + '">Inicio <span aria-hidden="true">*</span><input id="' + startId + '" type="datetime-local" name="slot_start[]" required aria-required="true"></label>' +
      '<label class="a11yhubbr-slot-field" for="' + endId + '">Fim <span aria-hidden="true">*</span><input id="' + endId + '" type="datetime-local" name="slot_end[]" required aria-required="true"></label>' +
      '<button type="button" class="a11yhubbr-slot-remove" aria-label="Remover esta data" title="Remover esta data">&#128465;</button>';
    slots.appendChild(row);
    if (window.a11yhubbrNormalizeLabelAssociations) {
      window.a11yhubbrNormalizeLabelAssociations(row);
    }
    bindRemove(row.querySelector('.a11yhubbr-slot-remove'));
    refreshRemoveButtons();
  });

  slots.querySelectorAll('.a11yhubbr-slot-remove').forEach(bindRemove);
  refreshRemoveButtons();
})();

(function () {
  'use strict';

  var form = document.getElementById('event-form');
  var modality = document.getElementById('event-modality');
  if (!form || !modality) {
    return;
  }

  var blocks = form.querySelectorAll('[data-event-modality]');
  if (!blocks.length) {
    return;
  }

  function refreshEventLocationBlocks() {
    var selected = (modality.value || '').trim();
    blocks.forEach(function (block) {
      var allowed = (block.getAttribute('data-event-modality') || '').split(',').map(function (item) { return item.trim(); }).filter(Boolean);
      var visible = allowed.indexOf(selected) >= 0;
      block.hidden = !visible;
      block.querySelectorAll('input, select, textarea').forEach(function (field) {
        field.disabled = !visible;
        if (field.required) {
          field.dataset.required = '1';
        }
        if (!visible) {
          field.required = false;
          if (field.tagName.toLowerCase() === 'select') {
            field.selectedIndex = 0;
          } else {
            field.value = '';
          }
        } else if (field.dataset.required === '1') {
          field.required = true;
        }
      });
    });

    if (typeof form.a11yhubbrRefreshAccordionState === 'function') {
      form.a11yhubbrRefreshAccordionState();
    }
  }

  modality.addEventListener('change', refreshEventLocationBlocks);
  refreshEventLocationBlocks();
})();

(function () {
  'use strict';

  var form = document.getElementById('content-form');
  var typeSelect = document.getElementById('content-type-select');
  if (!form || !typeSelect) {
    return;
  }

  function parseTypes(node) {
    var raw = (node.getAttribute('data-content-types') || '').trim();
    if (!raw) {
      return [];
    }
    return raw.split(',').map(function (item) { return item.trim(); }).filter(Boolean);
  }

  function toggleGroup(node, currentType) {
    var allowed = parseTypes(node);
    var show = allowed.indexOf(currentType) >= 0;
    node.hidden = !show;

    if (node.classList.contains('a11yhubbr-form-section')) {
      if (!show) {
        node.classList.add('is-collapsed');
      }
      var toggle = node.querySelector('.a11yhubbr-section-toggle');
      if (toggle) {
        toggle.setAttribute('aria-expanded', show && !node.classList.contains('is-collapsed') ? 'true' : 'false');
      }
    }

    node.querySelectorAll('input, select, textarea').forEach(function (field) {
      field.disabled = !show;
      if (!show && field.tagName.toLowerCase() !== 'select') {
        field.value = '';
      }
      if (!show && field.tagName.toLowerCase() === 'select') {
        field.selectedIndex = 0;
      }
    });
  }

  function refreshConditionalFields() {
    var selectedType = (typeSelect.value || '').trim();
    form.querySelectorAll('.a11yhubbr-content-conditional[data-content-types]').forEach(function (group) {
      toggleGroup(group, selectedType);
    });

    if (typeof form.a11yhubbrRefreshAccordionState === 'function') {
      form.a11yhubbrRefreshAccordionState();
    }
  }

  typeSelect.addEventListener('change', refreshConditionalFields);
  typeSelect.addEventListener('input', refreshConditionalFields);
  refreshConditionalFields();
  window.requestAnimationFrame(refreshConditionalFields);
})();

(function () {
  'use strict';

  var forms = document.querySelectorAll('.a11yhubbr-submit-form');
  if (!forms.length) {
    return;
  }

  function initCollapsibleSections(form) {
    var sections = form.querySelectorAll('[data-collapsible-section]');
    if (!sections.length) {
      return;
    }

    function isFieldActive(field) {
      if (!field || field.disabled) {
        return false;
      }
      if (field.closest('[hidden]')) {
        return false;
      }
      return true;
    }

    function updateSectionRequiredMarks() {
      sections.forEach(function (section) {
        var button = section.querySelector('.a11yhubbr-section-toggle');
        if (!button) {
          return;
        }

        var labelNode = button.querySelector('.a11yhubbr-section-toggle-label');
        if (!labelNode) {
          return;
        }

        var hasRequired = false;
        section.querySelectorAll('input, select, textarea').forEach(function (field) {
          if (field.required && isFieldActive(field)) {
            hasRequired = true;
          }
        });

        var mark = labelNode.querySelector('.a11yhubbr-required-mark');
        if (hasRequired && !mark) {
          mark = document.createElement('span');
          mark.className = 'a11yhubbr-required-mark';
          mark.setAttribute('aria-hidden', 'true');
          mark.textContent = ' *';
          labelNode.appendChild(mark);
        } else if (!hasRequired && mark) {
          mark.remove();
        }
      });
    }

    function updateSidebarLinksVisibility() {
      var submitGrid = form.closest('.a11yhubbr-submit-grid');
      if (!submitGrid) {
        return;
      }

      var navCard = submitGrid.querySelector('.a11yhubbr-submit-outline');
      var links = submitGrid.querySelectorAll('.a11yhubbr-submit-outline a[href^="#"]');
      if (!links.length) {
        return;
      }

      var visibleCount = 0;
      links.forEach(function (link) {
        var id = (link.getAttribute('href') || '').replace('#', '');
        var target = id ? document.getElementById(id) : null;
        if (!target) {
          link.classList.add('is-hidden');
          link.setAttribute('aria-hidden', 'true');
          return;
        }
        var isVisible = !target.hidden;
        link.classList.toggle('is-hidden', !isVisible);
        link.setAttribute('aria-hidden', isVisible ? 'false' : 'true');
        if (isVisible) {
          visibleCount += 1;
        }
      });

      if (navCard) {
        navCard.classList.toggle('is-hidden', visibleCount === 0);
      }
    }

    form.a11yhubbrRefreshAccordionState = function () {
      updateSectionRequiredMarks();
      updateSidebarLinksVisibility();
    };

    sections.forEach(function (section, index) {
      var heading = section.querySelector('h2');
      if (!heading) {
        return;
      }

      var body = document.createElement('div');
      body.className = 'a11yhubbr-form-section-body';
      while (heading.nextSibling) {
        body.appendChild(heading.nextSibling);
      }
      section.appendChild(body);

      var button = document.createElement('button');
      button.type = 'button';
      button.className = 'a11yhubbr-section-toggle';
      button.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
      button.innerHTML = '<span class="a11yhubbr-section-toggle-label">' + heading.textContent + '</span><i class="fa-solid fa-chevron-down" aria-hidden="true"></i>';
      heading.replaceWith(button);

      if (index !== 0) {
        section.classList.add('is-collapsed');
      }

      button.addEventListener('click', function () {
        var collapsed = section.classList.toggle('is-collapsed');
        button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
      });
    });

    form.a11yhubbrRefreshAccordionState();
    window.requestAnimationFrame(form.a11yhubbrRefreshAccordionState);

    if ('MutationObserver' in window) {
      var observer = new MutationObserver(function () {
        form.a11yhubbrRefreshAccordionState();
      });

      sections.forEach(function (section) {
        observer.observe(section, {
          attributes: true,
          attributeFilter: ['hidden', 'class']
        });
      });
    }
  }

  function initAnchorTracking() {
    var navLinks = document.querySelectorAll('.a11yhubbr-submit-outline a[href^="#"]');
    if (!navLinks.length) {
      return;
    }

    var sections = [];
    navLinks.forEach(function (link) {
      var id = (link.getAttribute('href') || '').replace('#', '');
      var target = id ? document.getElementById(id) : null;
      if (!target) {
        return;
      }
      sections.push({ link: link, target: target });
      link.addEventListener('click', function () {
        if (target.hidden) {
          return;
        }
        if (target.classList.contains('is-collapsed')) {
          target.classList.remove('is-collapsed');
          var toggle = target.querySelector('.a11yhubbr-section-toggle');
          if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
          }
        }
      });
    });

    if (!sections.length || !('IntersectionObserver' in window)) {
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }
        sections.forEach(function (item) {
          item.link.classList.toggle('is-active', item.target === entry.target);
        });
      });
    }, {
      rootMargin: '-30% 0px -55% 0px',
      threshold: 0.01
    });

    sections.forEach(function (item) {
      observer.observe(item.target);
    });
  }

  forms.forEach(initCollapsibleSections);
  initAnchorTracking();
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
      var allRows = rows.querySelectorAll('.a11yhubbr-slot');
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
    var allRows = rows.querySelectorAll('.a11yhubbr-slot');
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
    var count = rows.querySelectorAll('.a11yhubbr-slot').length + 1;
    var networkId = 'social-network-' + count;
    var urlId = 'social-url-' + count;
    var row = document.createElement('div');
    row.className = 'a11yhubbr-slot';
    row.innerHTML =
      '<label for="' + networkId + '">Rede social' +
      '<select id="' + networkId + '" name="social_network[]">' +
      '<option value="">Selecione</option>' +
      '<option value="linkedin">LinkedIn</option>' +
      '<option value="github">GitHub</option>' +
      '<option value="instagram">Instagram</option>' +
      '<option value="x">X/Twitter</option>' +
      '<option value="medium">Medium</option>' +
      '<option value="youtube">YouTube</option>' +
      '<option value="threads">Threads</option>' +
      '<option value="bluesky">Bluesky</option>' +
      '<option value="telegram">Telegram</option>' +
      '<option value="facebook">Facebook</option>' +
      '<option value="website">Outro website</option>' +
      '</select>' +
      '</label>' +
      '<label for="' + urlId + '">URL' +
      '<input id="' + urlId + '" type="url" name="social_url[]" placeholder="https://...">' +
      '</label>' +
      '<button type="button" class="a11yhubbr-slot-remove a11yhubbr-social-slot-remove" aria-label="Remover link social" title="Remover link social">&#128465;</button>';

    rows.appendChild(row);
    if (window.a11yhubbrNormalizeLabelAssociations) {
      window.a11yhubbrNormalizeLabelAssociations(row);
    }
    bindRemove(row.querySelector('.a11yhubbr-social-slot-remove'));
    refreshRemoveButtons();
  });

  rows.querySelectorAll('.a11yhubbr-slot-remove').forEach(bindRemove);
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
        emailField.setAttribute('aria-label', 'EndereÃƒÂ§o de e-mail');
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
        node.textContent = 'InscriÃƒÂ§ÃƒÂ£o confirmada com sucesso. Obrigado por fazer parte da comunidade A11YBR.';
      } else if (isError) {
        node.textContent = 'NÃƒÂ£o foi possÃƒÂ­vel concluir a inscriÃƒÂ§ÃƒÂ£o agora. Verifique o e-mail e tente novamente.';
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
