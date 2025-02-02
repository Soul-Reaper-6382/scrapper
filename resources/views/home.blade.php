@extends('layouts.scrapper')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/scrapper.css')}}">
  <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-2 d-flex justify-content-between">
                <h1 class="fs-4"><img src="{{ url('/images/logo.png') }}" alt="logo" style="width: 70px;"></h1>
                <button class="btn d-md-none d-block close-btn px-1 py-0 text-white"><i
                        class="fal fa-stream"></i></button>
            </div>

            <ul class="list-unstyled">
                <li class="li-item-footer">
                  <a class="" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                </li>
            </ul>
            <!-- <hr class="h-color mx-2"> -->

        </div>
        <div class="content">

            <div class="dashboard-content">
                <div id="browser">
        <div id="controls">
            <button class="btn btn-primary text-white" id="gotogoogle">Goto Google</button>
                <div class="personalize-switch">
                <input type="checkbox" id="personalize" name="personalize" />
                <label for="personalize" class="switch">
              </span>
              </div>
            <button id="back-btn" class="gogg_icon_left"><i class="fas fa-long-arrow-alt-left"></i></button>
            <button id="forward-btn" class="gogg_icon_left"><i class="fas fa-long-arrow-alt-right"></i></button>
            <button id="refresh-btn" class="gogg_icon_left"><i class="fa fa-refresh"></i></button>
            <input type="text" class="goog_searchbox" id="url"  placeholder="Enter URL or search term">
            <button id="go-btn" class="gogg_icon_left_go" ><i class="fa fa-search"></i></button>
        </div>
        <div id="preloader" class="hidden">
            <div class="spinner"></div>
        </div>
        <div id="select_mode">
            <span>Select mode</spam>
        </div>
        <webview id="webview"></webview>
    </div>
                    <div class="scrapper_json mt-3">
                  <nav style="position:relative;">
      <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
        <!-- <button class="nav-link" id="nav-csv-tab" data-bs-toggle="tab" data-bs-target="#nav-csv" type="button" role="tab" aria-controls="nav-csv" aria-selected="true">CSV/Excel</button> -->
        <button class="nav-link active" id="nav-json-tab" data-bs-toggle="tab" data-bs-target="#nav-json" type="button" role="tab" aria-controls="nav-json" aria-selected="false">JSON</button>
        <a class="empty_json" id="empty_json_id" href="javascript:void(0);">Empty Data</a>
        <a class="hideshowbtn" id="hidescrapper_json" href="javascript:void(0);">Hide</a>
        <a class="hideshowbtn" id="showscrapper_json" href="javascript:void(0);">show</a>
      </div>
    </nav>

      <div class="tab-content p-3 border bg-light" id="nav-tabContent">
      <div class="tab-pane fade" id="nav-csv" role="tabpanel" aria-labelledby="nav-csv-tab">
        <p></p>
      </div>
      <div class="tab-pane fade active show" id="nav-json" role="tabpanel" aria-labelledby="nav-json-tab">
        <div id="preloader2" class="hidden">
            <div class="spinner"></div>
        </div>
        <p id="json_view_parent">
          
        </p>
      </div>
    </div>
                </div>
            </div>
        </div>
    </div>

<script type="module">
window.userid = '123abca';


const back_btn = document.getElementById("back-btn")
const forward_btn = document.getElementById("forward-btn")
const refresh_btn = document.getElementById("refresh-btn")
const go_btn = document.getElementById("go-btn")
const go_url = document.getElementById("url")
const webview = document.getElementById("webview")
const preloader = document.getElementById("preloader")
const preloader2 = document.getElementById("preloader2")
const gotogoogle = document.getElementById("gotogoogle")
const select_mode = document.getElementById("select_mode")
let json_view_parent = document.getElementById("json_view_parent")
let empty_json_id = document.getElementById("empty_json_id")
let finalurl = '';

const hidescrapper_json = document.getElementById("hidescrapper_json");
const showscrapper_json = document.getElementById("showscrapper_json");

empty_json_id.addEventListener('click',(event) => {
        showPreloader2();
        let formData_json = new FormData();
        
        // Append the key, value, and url to the FormData object
        formData_json.append('key', window.userid);
        formData_json.append('value', '[]');
        formData_json.append('url', go_url.value);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        // Make the AJAX call
        $.ajax({
            url: 'https://scrapper.webxcube.com/api/save-data',
            type: 'POST',
            data: formData_json,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function(data) {
                webview.src = go_url.value;
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
})
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

function showPreloader2() {
    preloader2.classList.remove('hidden');
}

function hidePreloader2() {
    preloader2.classList.add('hidden');
}


function selectAllText() {
    go_url.select();
}


go_url.addEventListener('click',(event) => {
selectAllText()
})

go_url.addEventListener('focus',(event) => {
selectAllText()
})


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
        setTimeout(function(){
            fetchDataToDatabse();
        },2000)
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
                    location.href = href;
                }
                if (tagName === 'img') {
                    event.preventDefault();
                    event.stopPropagation();
                    let parentA = target.closest('a');
                    if (parentA) {
                    let href = parentA.getAttribute('href');
                    if (href) {
                    location.href = href;
                    }
                    }
                }
            });
        `);
    });
}


function syntaxHighlight(json) {
    json = JSON.stringify(json, undefined, 2); // Pretty-print JSON with 2-space indentation
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:\s*)?|true|false|null|[0-9]+)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
function fetchDataToDatabse() {
        let formData2 = new FormData();
        
        // Append the key, value, and url to the FormData object
        formData2.append('userid', window.userid);
        formData2.append('url', document.getElementById('url').value);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        // Make the AJAX call
        $.ajax({
            url: 'https://scrapper.webxcube.com/api/retrieve-data',
            type: 'POST',
            data: formData2,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function(data) {
                if(data.message == 'No data found'){
                    json_view_parent.innerHTML = 'No Scrapping Data';
                    empty_json_id.style.display = 'none';
                }
                else if(data == ''){
                    json_view_parent.innerHTML = 'No Scrapping Data';
                    empty_json_id.style.display = 'none';
                }
                else{
                json_view_parent.innerHTML = syntaxHighlight(data);
                empty_json_id.style.display = 'block';
                }
                hidePreloader2();
                        },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
        }

setInterval(function(){
fetchDataToDatabse()
},5000)

function addWebviewEventListener() {
webview.addEventListener('dom-ready', () => {
       webview.executeJavaScript(`
        window.userid = '${window.userid}';
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
            #preloader_scraper_oms {
                position: fixed;
                top: 0; /* Adjust according to your controls height */
                left: 0;
                width: 100%;
                height:100%; /* Adjust according to your controls height */
                background-color: rgba(255, 255, 255, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10;
            }

            .hidden_scraper_oms {
                display: none !important;
            }

            .spinner_scraper_oms {
                border: 4px solid #ffc107;
                border-top: 4px solid #000;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                animation: spin_scraper_oms 1s linear infinite;
            }

            @keyframes spin_scraper_oms {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        \`;
        document.head.appendChild(style);
        const spiner_scraper_oms = document.createElement('div');
        spiner_scraper_oms.innerHTML = \`
            <div id="preloader_scraper_oms" class="hidden_scraper_oms">
            <span>Please wait </span>
            <div class="spinner_scraper_oms"></div>
            </div>
          \`;
        document.body.appendChild(spiner_scraper_oms);

        var script_1 = document.createElement('script');
        script_1.src = "https://code.jquery.com/jquery-3.6.0.min.js";
        document.head.appendChild(script_1);
        let preloader_scraper_oms = document.getElementById('preloader_scraper_oms');
        function showPreloader_scrapper_oms() {
            preloader_scraper_oms.classList.remove('hidden_scraper_oms');
        }

        function hidePreloader_scrapper_oms() {
            preloader_scraper_oms.classList.add('hidden_scraper_oms');
        }
        function saveDataToDatabse(key, value, url) {
        showPreloader_scrapper_oms();
        let formData = new FormData();
        
        // Append the key, value, and url to the FormData object
        formData.append('key', key);
        formData.append('value', JSON.stringify(value));
        formData.append('url', url);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        // Make the AJAX call
        $.ajax({
            url: 'https://scrapper.webxcube.com/api/save-data',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function(data) {
                setTimeout(function(){
                hidePreloader_scrapper_oms();
                },3000)
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
        }

        function fetchDataToDatabse2() {
                let formData3 = new FormData();
                
                // Append the key, value, and url to the FormData object
                formData3.append('userid', window.userid);
                formData3.append('url', location.href);
                $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });
                // Make the AJAX call
                $.ajax({
                    url: 'https://scrapper.webxcube.com/api/retrieve-data',
                    type: 'POST',
                    data: formData3,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    success: function(data) {
                        if(data.message == 'No data found'){
                        window.myData_scraper = [];
                        }
                        else{
                        window.myData_scraper = data;
                        }

                                },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
                }
        fetchDataToDatabse2();
        
        
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
                    window.click_tag = 'no';

                    removeButtonsAndBorder_yes(target,tagName);

                });

                // Handle No button click
                nobtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    window.click_tag = 'no';
                    removeButtonsAndBorder_no(target);

                });
                
                 // Handle All button click
                allbtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    applyBorderToAllSimilarElements(target,tagName);
                });
        
            }
        })

    function removeButtonsAndBorder_no(target) {
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

    function removeButtonsAndBorder_yes(target,tagName_get) {
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
    if(tagName_get == 'img'){
    window.myData_scraper.push({
        [tagName_get]: target.querySelector('img').getAttribute('src'),
    });
    }
    else{
    window.myData_scraper.push({
        [target.tagName.toLowerCase()]: target.innerText,
    });
    }
     saveDataToDatabse(window.userid,window.myData_scraper,location.href);
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
        const transformData = (table) => {
        const headers = Array.from(table.querySelectorAll('th')).map(th => th.innerText.trim());
        const rows = Array.from(table.querySelectorAll('tr')).slice(1).map(tr => {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => {
                const img = td.querySelector('img');
                return img ? img.src : td.innerText.trim();
            });
            const rowData = {};
            headers.forEach((header, index) => {
                rowData[header] = cells[index];
            });
            return rowData;
        });
        return rows;
        };
        const tableTarget = target.closest('table');
        tableTarget.classList.add('all_highlight');
        tableTarget.querySelectorAll('th, td').forEach(element => {
            element.classList.add('all_highlight');
        });
        window.myData_scraper = transformData(tableTarget);
        saveDataToDatabse(window.userid,window.myData_scraper,location.href);
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
        if(tagName_get == 'img'){
        window.myData_scraper.push({
            [tagName_get]: element.getAttribute('src'),
        });
        }
        else{
        window.myData_scraper.push({
            [element.tagName.toLowerCase()]: element.innerText,
        });
        }
      });
      saveDataToDatabse(window.userid,window.myData_scraper,location.href);
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

    
</script>
@endsection

