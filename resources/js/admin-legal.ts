import Quill from 'quill';
import 'quill/dist/quill.snow.css';

function initEditor(containerId: string, inputId: string): void {
  const container = document.getElementById(containerId);
  const input     = document.getElementById(inputId) as HTMLInputElement | null;
  if (!container || !input) return;

  const quill = new Quill(container, {
    theme: 'snow',
    modules: {
      toolbar: [
        [{ header: [2, 3, false] }],
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link', 'clean'],
      ],
    },
  });

  const existing = container.dataset['content'] ?? '';
  if (existing) quill.root.innerHTML = existing;

  input.closest('form')?.addEventListener('submit', () => {
    input.value = quill.root.innerHTML;
  });
}

initEditor('editor-datenschutz-de', 'content-datenschutz-de');
initEditor('editor-datenschutz-en', 'content-datenschutz-en');
initEditor('editor-impressum-de',   'content-impressum-de');
initEditor('editor-impressum-en',   'content-impressum-en');

// ── Typ-Tabs (Datenschutz / Impressum) ──────────────────────────────────────
const typeTabs   = document.querySelectorAll<HTMLButtonElement>('.legal-tabs__tab');
const typePanels = document.querySelectorAll<HTMLElement>('.legal-tabs__panel');

typeTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset['tab'];
    typeTabs.forEach(t => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
    typePanels.forEach(p => p.classList.remove('is-active'));
    tab.classList.add('is-active');
    tab.setAttribute('aria-selected', 'true');
    if (target) document.getElementById(target)?.classList.add('is-active');
  });
});

// ── Sprach-Tabs (DE / EN) – pro Typ-Panel unabhängig ────────────────────────
document.querySelectorAll<HTMLElement>('.legal-lang-tabs').forEach(langTabGroup => {
  const tabs   = langTabGroup.querySelectorAll<HTMLButtonElement>('.legal-lang-tabs__tab');
  const panels = langTabGroup.querySelectorAll<HTMLElement>('.legal-lang-tabs__panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset['langTab'];
      tabs.forEach(t => t.classList.remove('is-active'));
      panels.forEach(p => p.classList.remove('is-active'));
      tab.classList.add('is-active');
      if (target) document.getElementById(target)?.classList.add('is-active');
    });
  });
});