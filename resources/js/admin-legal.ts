import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

const editorConfig: EasyMDE.Options = {
  spellChecker: false,
  autosave: { enabled: false, uniqueId: '' },
  minHeight: '420px',
  toolbar: [
    'heading-2', 'heading-3', '|',
    'bold', 'italic', '|',
    'unordered-list', 'ordered-list', '|',
    'link', 'horizontal-rule', '|',
    'preview', 'guide',
  ],
  status: false,
};

const datenschutzEl = document.getElementById('editor-datenschutz') as HTMLTextAreaElement | null;
const impressumEl   = document.getElementById('editor-impressum')   as HTMLTextAreaElement | null;

if (datenschutzEl) new EasyMDE({ element: datenschutzEl, ...editorConfig });
if (impressumEl)   new EasyMDE({ element: impressumEl,   ...editorConfig });

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