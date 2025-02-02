window.addEventListener('DOMContentLoaded', () => {
  function addHoverAndClickEffects() {
    const elements = document.querySelectorAll('p, a, h1, h2, h3, h4, h5, h6, img, table, th, td');

    elements.forEach(element => {
      element.addEventListener('mouseenter', () => {
        element.style.border = '1px solid #29b729';
      });

      element.addEventListener('mouseleave', () => {
        element.style.border = 'none';
      });

      element.addEventListener('click', (event) => {
        event.preventDefault();  // Prevent default action for all elements
        let info = element.innerText || element.alt || element.src || element.href || 'Element clicked';

        if (element.tagName.toLowerCase() === 'a') {
          info = element.href;  // Get href for anchor tags
        }

        window.electron.ipcRenderer.send('data-clicked', info);
      });
    });
  }

  addHoverAndClickEffects();
});
