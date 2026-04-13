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

  // Bestehenden HTML-Inhalt aus data-Attribut laden
  const existing = container.dataset['content'] ?? '';
  if (existing) quill.root.innerHTML = existing;

  // Vor Submit: HTML-Inhalt des Editors in hidden input schreiben
  input.closest('form')?.addEventListener('submit', () => {
    input.value = quill.root.innerHTML;
  });
}

initEditor('editor-datenschutz', 'content-datenschutz');
initEditor('editor-impressum',   'content-impressum');

// ── Tab-Switching ────────────────────────────────────────────────────────────
const tabs   = document.querySelectorAll<HTMLButtonElement>('.legal-tabs__tab');
const panels = document.querySelectorAll<HTMLElement>('.legal-tabs__panel');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset['tab'];

    tabs.forEach(t => {
      t.classList.remove('is-active');
      t.setAttribute('aria-selected', 'false');
    });
    panels.forEach(p => p.classList.remove('is-active'));

    tab.classList.add('is-active');
    tab.setAttribute('aria-selected', 'true');
    if (target) document.getElementById(target)?.classList.add('is-active');
  });
});
