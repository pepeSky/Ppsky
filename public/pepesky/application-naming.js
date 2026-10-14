(() => {
    const input = document.getElementById('name');
    const preview = document.getElementById('application-name-preview');
    const description = document.getElementById('description');
    if (!input || !preview || !description) return;
    const refresh = () => {
        const base = input.value.trim().replace(/(?:[.-](?:Application|MeWo))+$/gi, '').trim();
        const name = base ? `${base}.MeWo` : '';
        preview.textContent = name || '—';
        description.value = !name ? '' : name.toLowerCase() === 'pepesky.mewo'
            ? 'Solución informática al problema de comunicación del usuario PepeSky, creada bajo las normas del mecanismo de trabajo MeWo en el sistema JFRV.'
            : `Solución informática al problema de comunicación del usuario ${description.dataset.systemName}, creada bajo las normas del mecanismo de trabajo MeWo en el sistema PepeSky –Execution Quantum–.`;
    };
    input.addEventListener('input', refresh);
    refresh();
})();
