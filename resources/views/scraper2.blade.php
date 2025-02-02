<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
         body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
}

#browser {
    width: 80%;
    height: 90%;
    border: 1px solid #ccc;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#controls {
    display: flex;
    padding: 10px;
    background-color: #f8f8f8;
    border-bottom: 1px solid #ccc;
}

#controls button, #controls input {
    margin-right: 10px;
}

#controls input {
    flex-grow: 1;
    padding: 5px;
}

#webview {
    width: 100%;
    height: calc(100% - 50px);
    border: none;
}

#preloader {
    position: absolute;
    top: 50px; /* Adjust according to your controls height */
    left: 0;
    width: 100%;
    height: calc(100% - 50px); /* Adjust according to your controls height */
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.hidden {
    display: none !important;
}

.spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-top: 4px solid #000;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Web Browser</title>
    <style type="text/css">
   
    </style>
</head>
<body>
    <div id="browser">
        <div id="controls">
            <button id="back" onclick="goBack()">Back</button>
            <button id="forward" onclick="goForward()">Forward</button>
            <button id="refresh" onclick="refreshPage()">Refresh</button>
            <input type="text" id="url" placeholder="Enter URL or search term">
            <button id="go" onclick="navigate()">Go</button>
        </div>
        <div id="preloader" class="hidden">
            <div class="spinner"></div>
        </div>
        <iframe id="webview" src=""></iframe>
    </div>
<script type="text/javascript">
    let webview = document.getElementById('webview');

function goBack() {
    webview.contentWindow.history.back();
            showPreloader();
}

function goForward() {
    webview.contentWindow.history.forward();
            showPreloader();
}

function refreshPage() {
    webview.contentWindow.location.reload();
            showPreloader();
}

function navigate() {
    let urlInput = document.getElementById('url').value;
    if (!urlInput.startsWith('http://') && !urlInput.startsWith('https://')) {
        urlInput = 'https://' + encodeURIComponent(urlInput);
    }
    webview.src = urlInput;
        showPreloader();
    webview.contentWindow.postMessage({ type: 'navigate', url: webview.src }, '*');
}

function showPreloader() {
    preloader.classList.remove('hidden');
}

function hidePreloader() {
    preloader.classList.add('hidden');
}

// Listen for messages from the iframe
window.addEventListener('message', (event) => {
    if (event.data.type === 'loaded') {
        hidePreloader();
        document.getElementById('url').value = event.data.url;
    }
});

document.getElementById('url').addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        navigate();
    }
});

// Update URL in the input field when navigating
webview.addEventListener('load', () => {
    alert('load')
    console.log(webview.contentWindow)
    // alert(webview.contentWindow.location.href)
    // document.getElementById('url').value = webview.contentWindow.location.href;
        hidePreloader();
});

</script>
</body>
</html>