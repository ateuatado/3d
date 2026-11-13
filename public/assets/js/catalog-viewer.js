const viewerScript = document.currentScript;

function createViewer(container) {
    const source = container.dataset.source;
    if (!source) {
        container.classList.add('viewer-empty');
        container.textContent = 'Pré-visualização 3D ainda não disponível.';
        return;
    }

    const viewer = document.createElement('model-viewer');
    viewer.setAttribute('src', source);
    viewer.setAttribute('camera-controls', '');
    viewer.setAttribute('auto-rotate', '');
    viewer.setAttribute('ar', '');
    viewer.setAttribute('loading', 'lazy');
    viewer.setAttribute('alt', container.dataset.alt || 'Pré-visualização do modelo');
    container.replaceChildren(viewer);
}

document.querySelectorAll('[data-model-viewer]').forEach(createViewer);
