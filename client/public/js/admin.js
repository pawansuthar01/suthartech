document.addEventListener('DOMContentLoaded', () => {
    const shell = document.querySelector('.admin-shell');
    const toggle = document.querySelector('[data-admin-menu-toggle]');
    const overlay = document.querySelector('[data-admin-menu-overlay]');

    if (!shell || !toggle || !overlay) return;

    const close = () => {
        shell.classList.remove('admin-menu-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open navigation');
    };

    toggle.addEventListener('click', () => {
        const open = shell.classList.toggle('admin-menu-open');
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    });
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') close();
    });
});

document.addEventListener('click', event => {
    const button = event.target.closest('[data-markdown-wrap], [data-markdown-prefix]');
    const editor = button?.closest('[data-markdown-editor]');
    const textarea = editor?.querySelector('textarea[name="body"]');
    if (!button || !textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selected = textarea.value.slice(start, end) || 'text';
    const wrap = button.dataset.markdownWrap;
    let replacement;
    let cursor;

    if (wrap) {
        const [before, after = before] = wrap.split('|');
        replacement = `${before}${selected}${after}`;
        cursor = start + replacement.length;
    } else {
        replacement = selected.split('\n').map(line => `${button.dataset.markdownPrefix}${line}`).join('\n');
        cursor = start + replacement.length;
    }

    textarea.setRangeText(replacement, start, end, 'select');
    textarea.focus();
    textarea.setSelectionRange(cursor, cursor);
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
});
