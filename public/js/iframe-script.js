window.addEventListener('message', (event) => {
    if (event.data.type === 'navigate') {
        window.location.href = event.data.url;
    }
});

window.addEventListener('load', () => {
    parent.postMessage({ type: 'loaded', url: window.location.href }, '*');
});