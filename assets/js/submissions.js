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
      '<label for="' + startId + '">Inicio *</label>' +
      '<input id="' + startId + '" type="datetime-local" name="slot_start[]" required>' +
      '<label for="' + endId + '">Fim *</label>' +
      '<input id="' + endId + '" type="datetime-local" name="slot_end[]" required>' +
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
