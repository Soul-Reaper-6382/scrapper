
const back_btn = document.getElementById("back-btn")
const forward_btn = document.getElementById("forward-btn")
const refresh_btn = document.getElementById("refresh-btn")
const go_btn = document.getElementById("go-btn")
const go_url = document.getElementById("url")
const webview = document.getElementById("webview")
const preloader = document.getElementById("preloader")
const gotogoogle = document.getElementById("gotogoogle")
const select_mode = document.getElementById("select_mode")
let finalurl = '';

const hidescrapper_json = document.getElementById("hidescrapper_json");
const showscrapper_json = document.getElementById("showscrapper_json");

hidescrapper_json.addEventListener('click',(event) => {
    event.preventDefault();
    hidescrapper_json.style.display = 'none';
    showscrapper_json.style.display = 'block';
    document.querySelector('.scrapper_json').style.height = '5vh';
    document.getElementById('browser').style.height = '89vh';
    document.getElementById('nav-tabContent').style.height = '0';
    document.getElementById('nav-tabContent').classList.remove('p-3');
})
showscrapper_json.addEventListener('click',(event) => {
    event.preventDefault();
    showscrapper_json.style.display = 'none';
    hidescrapper_json.style.display = 'block';
    document.querySelector('.scrapper_json').style.height = '30vh';
    document.getElementById('browser').style.height = '65vh';
    document.getElementById('nav-tabContent').style.height = '165px';
    document.getElementById('nav-tabContent').classList.add('p-3');
})

function showPreloader() {
    preloader.classList.remove('hidden');
}

function hidePreloader() {
    preloader.classList.add('hidden');
}


function selectAllText() {
    go_url.select();
}


go_url.addEventListener('keydown',(event) => {
if(event.key == "Enter"){
    event.preventDefault();
    handleUrl();
}
})

go_btn.addEventListener('click',(event) => {
    event.preventDefault();
    handleUrl();
})

back_btn.addEventListener('click',(event) => {
    webview.goBack();
})
forward_btn.addEventListener('click',(event) => {
    webview.goForward();
})
refresh_btn.addEventListener('click',(event) => {
webview.reload();
})

gotogoogle.addEventListener('click',(event) => {
webview.src = "https://google.com";
})

webview.addEventListener('did-navigate', (event) => {
finalurl = event.url;
go_url.value = finalurl
})

webview.addEventListener('did-start-loading', () => {
        showPreloader();
});

webview.addEventListener('did-stop-loading', () => {
        hidePreloader();
});

function handleUrl() {
    let inputUrl = go_url.value.trim(); // Trim whitespace from input

    // Check if input is a valid URL
    if (isValidUrl(inputUrl)) {
        finalurl = inputUrl;
        if(finalurl.startsWith("http://") || finalurl.startsWith("https://")){
        webview.src = finalurl;
        }
        else{
        webview.src = 'https://' + finalurl; // Load the URL in the webview
        }
    } 
    else if(inputUrl.startsWith("http://") || inputUrl.startsWith("https://")){
        finalurl = inputUrl;
        webview.src = finalurl;
    }
     else {
        // If input is not a valid URL, search Google with the input as query
        webview.src = `https://www.google.com/search?q=${encodeURIComponent(inputUrl)}`;
    }
}


function isValidUrl(url) {
    // Regular expression to validate URL format
    const urlRegex = /^(?:https?:\/\/)?(?:www\.)?[\w.-]+(?:\.[a-zA-Z]{2,})+(?:\/[\w.-]*)*\/?$/;
    return urlRegex.test(url);
}

window.addEventListener('beforeunload', function(e) {
    console.log(go_url.value);
    localStorage.setItem('url', go_url.value);
        });

 function handleCheckboxChange(event) {
            if (event.target.checked) {
                localStorage.setItem('checkbox', 'yes');
            } else {
                localStorage.setItem('checkbox', 'not');
            }
            location.reload(true);
        }

document.addEventListener('DOMContentLoaded', function() {
          const checkbox = document.getElementById('personalize');
            checkbox.addEventListener('change', handleCheckboxChange);
            
            let store_checkbox_status = localStorage.getItem('checkbox');
            if (store_checkbox_status === 'yes') {
                window.browser_toggle = 'yes';
                checkbox.checked = true;
                select_mode.style.display = 'block';
                document.getElementById('browser').classList.add('shadow');
            } else {
                window.browser_toggle = 'not';
                checkbox.checked = false;
                select_mode.style.display = 'none';
                document.getElementById('browser').classList.remove('shadow');
            }
            
            // Get the stored URL value from localStorage
            let storedUrl = localStorage.getItem('url');
            // alert(storedUrl)
            if (storedUrl) {
                // Set the value of the input field
                setTimeout(function(){
                document.getElementById('url').value = storedUrl;
                webview.src = storedUrl;
                // Remove the stored URL from localStorage
                localStorage.removeItem('url');
                },1000)
            }
            else{
                webview.src = "https://google.com";
            }
        })

function addWebviewEventListener_a() {
    webview.addEventListener('dom-ready', () => {
        webview.executeJavaScript(`
            document.body.addEventListener('click', event => {
                let target = event.target;
                let tagName = target.tagName.toLowerCase();
                if (tagName === 'a') {
                    event.preventDefault();
                    event.stopPropagation();
                    let href = target.getAttribute('href');
                }
            });
        `);
    });
}


function addWebviewEventListener() {
webview.addEventListener('dom-ready', () => {
       webview.executeJavaScript(`
        const style = document.createElement('style');
        style.innerHTML = \`
          .highlight {
            border: 2px solid #29b729 !important;
            overflow:visible;
          }
          .parent_highlight{
              overflow:visible !important;
          }
          .all_highlight{
            border: 2px solid #29b729 !important;
          }
          .tag-name {
              z-index:9999;
            background: #29b729;
            color:black;
            font-size: 12px;
            padding: 2px;
            min-width:30px;
            height:20px;
            line-height:1;
          }
        \`;
        document.head.appendChild(style);
        
         document.body.addEventListener('mouseover', event => {
          let target = event.target;
          const allowedTags = ['p', 'img', 'h1', 'h2', 'h3', 'h4' , 'h5', 'h6', 'td', 'th', 'span', 'a', 'div', 'code', 'b'];

          if(window.click_tag == 'yes'){
            return;
          }
            // Only highlight if the target is one of the allowed tags
            if (!allowedTags.includes(target.tagName.toLowerCase())) {
                return;
            }
            
              if (target.tagName.toLowerCase() !== 'img' && target.innerText.trim() === '') {
            return;
            }
            if (target.tagName.toLowerCase() !== 'img') {
        const hasDirectText = Array.from(target.childNodes).some(node => node.nodeType === Node.TEXT_NODE && node.nodeValue.trim() !== '');
        if (!hasDirectText) {
            return;
        }
        }

         
    if (target.tagName.toLowerCase() === 'img') {
        // Create a new parent div and wrap the img element
        const wrapper = document.createElement('div');
        wrapper.classList.add('highlight');
        target.parentNode.insertBefore(wrapper, target);
        wrapper.appendChild(target);

        // Add span with tag name to the new parent div
        const tagNameSpan = document.createElement('span');
        tagNameSpan.classList.add('tag-name');
        tagNameSpan.innerText = target.tagName.toLowerCase();
        wrapper.appendChild(tagNameSpan);
    } else {
        target.classList.add('highlight');
        const tagNameSpan = document.createElement('span');
        tagNameSpan.classList.add('tag-name');
        tagNameSpan.innerText = target.tagName.toLowerCase();
        target.appendChild(tagNameSpan);
    }
    target.parentElement.classList.add('parent_highlight')
         });

        document.body.addEventListener('mouseout', event => {
          let target = event.target;
            if(window.click_tag == 'yes'){
            return;
          }
    if (target.tagName.toLowerCase() === 'img') {
        // Remove the highlight class and unwrap the img element
        const wrapper = target.parentElement;
        if (wrapper && wrapper.classList.contains('highlight')) {
            wrapper.classList.remove('highlight');
            const tagNameSpan = wrapper.querySelector('.tag-name');
            if (tagNameSpan) {
                tagNameSpan.remove();
            }

            // Unwrap the img element
            wrapper.parentNode.insertBefore(target, wrapper);
            wrapper.remove();
        }
    }else {
        target.classList.remove('highlight');
        const tagNameSpan = target.querySelector('.tag-name');
        if (tagNameSpan) {
            tagNameSpan.remove();
        }
    }
     target.parentElement.classList.remove('parent_highlight')
        });

        document.body.addEventListener('click', event => {
          event.preventDefault();
          event.stopPropagation();
          if(window.click_tag == 'yes'){
            return;
          }
          let target = event.target;
          let tagName = target.tagName.toLowerCase();

          const allowedTags = ['p', 'img', 'h1', 'h2', 'h3', 'h4' , 'h5', 'h6', 'td', 'th', 'span', 'a', 'div', 'code', 'b'];

            // Only highlight if the target is one of the allowed tags
            if (!allowedTags.includes(target.tagName.toLowerCase())) {
                return;
            }
              if (target.tagName.toLowerCase() !== 'img' && target.innerText.trim() === '') {
            return;
            }
            if (target.tagName.toLowerCase() === 'div') {
        const hasDirectText = Array.from(target.childNodes).some(node => node.nodeType === Node.TEXT_NODE && node.nodeValue.trim() !== '');
        if (!hasDirectText) {
            return;
        }
        }

        if (target.tagName.toLowerCase() === 'img') {
        target = target.parentElement;
        }
        else{
         target = target;   
        }
           // Create Yes and No buttons if they don't already exist
          window.click_tag = 'yes';
            if (!target.querySelector('.yes-btn') && !target.querySelector('.no-btn')) {
                const yesbtn = document.createElement('span');
                yesbtn.classList.add('yes-btn');
                yesbtn.innerText = '✔️';
                yesbtn.style.marginLeft = '10px';
                yesbtn.style.cursor = 'pointer';
                target.appendChild(yesbtn);

                const nobtn = document.createElement('span');
                nobtn.classList.add('no-btn');
                nobtn.innerText = '❎';
                nobtn.style.marginLeft = '10px';
                nobtn.style.cursor = 'pointer';
                target.appendChild(nobtn);
                
                const allbtn = document.createElement('span');
                allbtn.classList.add('all-btn');
                allbtn.innerText = '🔄';
                allbtn.style.marginLeft = '10px';
                allbtn.style.cursor = 'pointer';
                target.appendChild(allbtn);
                
                // Handle Yes button click
                yesbtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    window.click_tag = 'no'
                    removeButtonsAndBorder(target);

                });

                // Handle No button click
                nobtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    window.click_tag = 'no';
                    removeButtonsAndBorder(target);

                });
                
                 // Handle All button click
                allbtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    applyBorderToAllSimilarElements(target,tagName);
                });
        
            }
        })

    function removeButtonsAndBorder(target) {
    // Remove the Yes and No buttons if they exist
    target.classList.remove('highlight');
    const tagNameSpan = target.querySelector('.tag-name');
        if (tagNameSpan) {
            tagNameSpan.remove();
        }
    const yesbtn = target.querySelector('.yes-btn');
    const nobtn = target.querySelector('.no-btn');
    const allbtn = target.querySelector('.all-btn');
    if (allbtn) allbtn.remove();
    if (yesbtn) yesbtn.remove();
    if (nobtn) nobtn.remove();
    }
    
    
    function removeButtonsAndBorder(target) {
    // Remove the Yes and No buttons if they exist
    target.classList.remove('highlight');
    const tagNameSpan = target.querySelector('.tag-name');
        if (tagNameSpan) {
            tagNameSpan.remove();
        }
    const yesbtn = target.querySelector('.yes-btn');
    const nobtn = target.querySelector('.no-btn');
    const allbtn = target.querySelector('.all-btn');
    if (allbtn) allbtn.remove();
    if (yesbtn) yesbtn.remove();
    if (nobtn) nobtn.remove();
    }
    
    function applyBorderToAllSimilarElements(target,tagName_get) {
    const tagNameSpan = target.querySelector('.tag-name');
        if (tagNameSpan) {
            tagNameSpan.remove();
        }
    const yesbtn = target.querySelector('.yes-btn');
    const nobtn = target.querySelector('.no-btn');
    const allbtn = target.querySelector('.all-btn');
    if (allbtn) allbtn.remove();
    if (yesbtn) yesbtn.remove();
    if (nobtn) nobtn.remove();
    const tagName = target.tagName.toLowerCase();
    target.classList.remove('highlight');
    let className = target.className.split(' ').filter(Boolean).map(cls => \`.\${cls}\`\).join(' ');
    className = target.className.split(' ');
    className = className.filter(cls => cls !== 'highlight');
    let classSelector = '';
    if (tagName_get === 'img') {
      classSelector = className.length > 0 ? '.' + className.join('.') : '';
    }
    else{
      classSelector = className.length > 1 ? '.' + className.join('.') : '';  
    }   
      let selector = tagName + classSelector;
      let checkimgselect = 'no';
      
      if (selector === 'div.parent_highlight') {
        const imgTarget = target.querySelector('img');
        let imgClassName = imgTarget.className.split(' ').filter(Boolean).map(cls => \`.\${cls}\`\).join(' ');
        imgClassName = imgTarget.className.split(' ');
        
        const classSelectorimg = imgClassName.length > 0 ? '.' + imgClassName.join('.') : '';
        selector = imgTarget.tagName.toLowerCase();
        checkimgselect = 'yes';
        target.classList.remove('highlight');
            }
    if (target.closest('table')) {
        
        const tableTarget = target.closest('table');
        tableTarget.classList.add('all_highlight');
        tableTarget.querySelectorAll('th, td').forEach(element => {
            element.classList.add('all_highlight');
        });
        setTimeout(function(){
            tableTarget.classList.remove('all_highlight');
            target.classList.remove('highlight');
        tableTarget.querySelectorAll('th, td').forEach(element => {
        element.classList.remove('all_highlight');
      });
      window.click_tag = 'no';
      },5000)
        }
        else{
     const similarElements = document.querySelectorAll(selector);
      similarElements.forEach(element => {
        if(checkimgselect == 'no'){
        if (element.innerText.trim() === '') {
            return;
            }
            const hasDirectText = Array.from(element.childNodes).some(node => node.nodeType === Node.TEXT_NODE && node.nodeValue.trim() !== '');
        if (!hasDirectText) {
            return;
        }
        }
        element.classList.add('all_highlight');
      });
      setTimeout(function(){
            target.classList.remove('highlight');
        similarElements.forEach(element => {
        element.classList.remove('all_highlight');
      });
      window.click_tag = 'no';
      },5000)
        }
}
      `);
    });
}

document.addEventListener('DOMContentLoaded', function() {
if(window.browser_toggle == 'yes'){
    addWebviewEventListener();
}
else{
    addWebviewEventListener_a();
}
})

    