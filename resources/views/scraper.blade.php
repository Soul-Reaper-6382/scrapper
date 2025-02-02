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

             <ul class="list-unstyled px-2">
                <li class=""><div class="p-1 bg-light rounded rounded-pill shadow-sm mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <button id="button-addon2" type="submit" class="btn btn-link text-warning"><i class="fa fa-search"></i></button>
              </div>
              <input type="search" placeholder="What're you searching for?" aria-describedby="button-addon2" class="form-control border-0 bg-light">
            </div>
          </div></li>
                
            </ul>
            <hr class="h-color mx-2">

        </div>
        <div class="content">

            <div class="dashboard-content">
                <div id="browser">
        <div id="controls">
            <button id="back-btn" class="gogg_icon_left"><i class="fas fa-long-arrow-alt-left"></i></button>
            <button id="forward-btn" class="gogg_icon_left"><i class="fas fa-long-arrow-alt-right"></i></button>
            <button id="refresh-btn" class="gogg_icon_left"><i class="fa fa-refresh"></i></button>
            <input type="text" class="goog_searchbox" id="url" onclick="selectAllText()" onfocus="selectAllText()" placeholder="Enter URL or search term">
            <button id="go-btn" class="gogg_icon_left"><i class="fa fa-search"></i></button>
        </div>
        <div id="preloader" class="">
            <div class="spinner"></div>
        </div>
        <webview id="webview" src="https://google.com"></webview>
    </div>
                    <div class="scrapper_json mt-3">
                  <nav>
      <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
        <button class="nav-link" id="nav-csv-tab" data-bs-toggle="tab" data-bs-target="#nav-csv" type="button" role="tab" aria-controls="nav-csv" aria-selected="true">CSV/Excel</button>
        <button class="nav-link active" id="nav-json-tab" data-bs-toggle="tab" data-bs-target="#nav-json" type="button" role="tab" aria-controls="nav-json" aria-selected="false">JSON</button>
      </div>
    </nav>

      <div class="tab-content p-3 border bg-light" id="nav-tabContent">
      <div class="tab-pane fade" id="nav-csv" role="tabpanel" aria-labelledby="nav-csv-tab">
        <p><strong>This is some placeholder content the Home tab's associated content.</strong>
          Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps
          classes to control the content visibility and styling. You can use it with tabs, pills, and any
          other <code>.nav</code>-powered navigation.</p>
      </div>
      <div class="tab-pane fade active show" id="nav-json" role="tabpanel" aria-labelledby="nav-json-tab">
        <p>
          {
            name:"demo"
          }
        </p>
      </div>
    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
const back_btn = document.getElementById("back-btn")
const forward_btn = document.getElementById("forward-btn")
const refresh_btn = document.getElementById("refresh-btn")
const go_btn = document.getElementById("go-btn")
const go_url = document.getElementById("url")
const webview = document.getElementById("webview")
const preloader = document.getElementById("preloader")

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

webview.addEventListener('did-navigate', (event) => {
finalurl = event.url;
go_url.value = finalurl
})

webview.addEventListener('did-start-loading', () => {
        // loader.remove();
        alert('loading')
});

webview.addEventListener('did-stop-loading', () => {
        // loader.remove();
        alert('fdd')
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
</script>
@endsection

