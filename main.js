const { app, BrowserWindow, ipcMain  } = require('electron');
const path = require('path');

function createWindow() {
    let mainWindow = new BrowserWindow({
        width: 1100,
        height: 700,
        icon: path.join(__dirname, 'icon.ico'), // Set the path to your local icon file
        webPreferences: {
            contextIsolation: true,
            enableRemoteModule: false,
            preload: path.join(__dirname, './preloader.js'),
            webviewTag:true
        }
    });

    // const startUrl = isDev
    //     ? 'http://localhost:3000'  // Assuming Vite dev server runs on port 3000
    //     : `file://${path.join(__dirname, 'dist/index.html')}`;

    // mainWindow.loadURL('http://52.14.188.169/');
    mainWindow.loadURL('http://localhost/webscrapper/');

    mainWindow.once('ready-to-show', () => {
        mainWindow.show(); // Show the main window
    });

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

app.on('ready', createWindow);

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
        createWindow();
    }
});
