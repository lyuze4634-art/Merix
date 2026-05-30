/*
 * 本文件负责后台 BOM 和表格交互。
 * 包含复制表格、点击行跳转、多选清空和 BOM 预览编辑逻辑。
 */

document.addEventListener('click', async (event) => {
  const button = event.target.closest('[data-copy-table]');
  if (!button) return;

  const table = document.querySelector(button.dataset.copyTable || '#copyTable');
  const status = document.querySelector('[data-copy-status]');
  if (!table) return;

  const text = [...table.querySelectorAll('tr')]
    .map((tr) => [...tr.children].map((cell) => cell.innerText.trim()).join('\t'))
    .join('\n');

  try {
    await navigator.clipboard.writeText(text);
    if (status) status.textContent = 'BOM copied. Paste into Excel to split columns.';
  } catch (error) {
    if (status) status.textContent = 'Copy was blocked. Please select the table manually.';
  }
});

document.addEventListener('change', (event) => {
  if (!event.target.matches('[data-check-all]')) return;
  document.querySelectorAll('[data-row-check]').forEach((box) => {
    box.checked = event.target.checked;
  });
});

document.addEventListener('change', (event) => {
  const select = event.target.closest('select[multiple]');
  if (!select) return;
  const emptyOption = [...select.options].find((option) => option.dataset.emptyOption !== undefined);
  if (!emptyOption || !emptyOption.selected) return;
  [...select.options].forEach((option) => {
    option.selected = option === emptyOption;
  });
});

document.addEventListener('change', (event) => {
  const select = event.target.closest('[data-fill-input]');
  if (!select || !select.value) return;
  const field = select.closest('.combo-field');
  const input = field?.querySelector('input');
  if (input) {
    input.value = select.value;
    input.dispatchEvent(new Event('input', { bubbles: true }));
  }
  select.value = '';
});

document.addEventListener('click', (event) => {
  const row = event.target.closest('[data-href]');
  if (!row) return;
  if (event.target.closest('a, button, input, select, textarea, label, form')) return;
  window.location.href = row.dataset.href;
});

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Enter') return;
  const row = event.target.closest('[data-href]');
  if (!row) return;
  window.location.href = row.dataset.href;
});

(() => {
  const form = document.querySelector('[data-bom-editor-form]');
  const previewBody = document.querySelector('[data-bom-preview-body]');
  if (!form || !previewBody || !window.bomEditorKey) return;

  const storageKey = window.bomEditorKey;
  const materials = window.bomEditorMaterials || {};
  let preview = {};

  const saved = sessionStorage.getItem(storageKey);
  if (saved) {
    try {
      preview = JSON.parse(saved) || {};
    } catch (error) {
      preview = {};
    }
  } else {
    Object.entries(window.bomEditorInitial || {}).forEach(([id, values]) => {
      preview[id] = { ...(materials[id] || { id }), ...values };
    });
  }

  const savePreview = () => {
    preview = collectPreview();
    sessionStorage.setItem(storageKey, JSON.stringify(preview));
  };

  const collectPreview = () => {
    const next = {};
    previewBody.querySelectorAll('[data-preview-row]').forEach((row) => {
      const id = row.dataset.id;
      next[id] = {
        ...(preview[id] || {}),
        ...(materials[id] || {}),
        id,
        qty: row.querySelector(`[name="qty[${id}]"]`)?.value || '',
        machine_qty: row.querySelector(`[name="machine_qty[${id}]"]`)?.value || '',
        spare_qty: row.querySelector(`[name="spare_qty[${id}]"]`)?.value || '',
        position_ref: row.querySelector(`[name="position_ref[${id}]"]`)?.value || '',
        remark: row.querySelector(`[name="remark[${id}]"]`)?.value || '',
      };
    });
    return next;
  };

  const cell = (text) => {
    const td = document.createElement('td');
    td.textContent = text || '';
    return td;
  };

  const inputCell = (name, id, value, type = 'text') => {
    const td = document.createElement('td');
    const input = document.createElement('input');
    input.name = `${name}[${id}]`;
    input.value = value || '';
    if (type === 'number') {
      input.type = 'number';
      input.step = '0.01';
    }
    input.addEventListener('input', savePreview);
    td.append(input);
    return td;
  };

  const renderPreview = () => {
    previewBody.innerHTML = '';
    const rows = Object.values(preview);

    if (!rows.length) {
      const empty = document.createElement('tr');
      empty.dataset.emptyRow = 'true';
      const td = document.createElement('td');
      td.className = 'empty';
      td.colSpan = 13;
      td.textContent = '尚未添加材料。';
      empty.append(td);
      previewBody.append(empty);
      return;
    }

    rows.forEach((item) => {
      const id = String(item.id);
      const row = document.createElement('tr');
      row.dataset.previewRow = 'true';
      row.dataset.id = id;

      const action = document.createElement('td');
      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'mini-btn danger';
      remove.textContent = '移除';
      remove.addEventListener('click', () => {
        preview = collectPreview();
        delete preview[id];
        sessionStorage.setItem(storageKey, JSON.stringify(preview));
        renderPreview();
      });
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'material_ids[]';
      hidden.value = id;
      action.append(remove, hidden);

      row.append(
        action,
        cell(item.name_cn),
        cell(item.item_name),
        cell(item.model),
        cell(item.material),
        cell(item.unit),
        cell(item.brand),
        cell(item.supplier),
        inputCell('qty', id, item.qty, 'number'),
        inputCell('machine_qty', id, item.machine_qty, 'number'),
        inputCell('spare_qty', id, item.spare_qty, 'number'),
        inputCell('position_ref', id, item.position_ref),
        inputCell('remark', id, item.remark),
      );
      previewBody.append(row);
    });
  };

  document.querySelector('[data-add-to-bom-preview]')?.addEventListener('click', () => {
    preview = collectPreview();
    document.querySelectorAll('[data-candidate-check]:checked').forEach((box) => {
      const id = String(box.value);
      preview[id] = {
        ...(materials[id] || { id }),
        ...(preview[id] || {}),
        id,
      };
      box.checked = false;
    });
    sessionStorage.setItem(storageKey, JSON.stringify(preview));
    renderPreview();
  });

  document.querySelector('[data-clear-candidate-checks]')?.addEventListener('click', () => {
    document.querySelectorAll('[data-candidate-check]').forEach((box) => {
      box.checked = false;
    });
  });

  document.querySelector('[data-clear-bom-preview]')?.addEventListener('click', () => {
    if (!confirm('确定清空预览 BOM 吗？')) return;
    preview = {};
    sessionStorage.setItem(storageKey, JSON.stringify(preview));
    renderPreview();
  });

  document.querySelectorAll('form.filter-grid').forEach((filterForm) => {
    filterForm.addEventListener('submit', savePreview);
  });

  form.addEventListener('submit', () => {
    savePreview();
    sessionStorage.removeItem(storageKey);
  });

  renderPreview();
})();
