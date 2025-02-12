@extends('layouts.app')

@section('content')
<style>
    .download-container {
        text-align: center;
        margin-top: 100px;
    }
    .download-btn {
        background-color: #007bff;
        color: #fff;
        font-size: 18px;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        transition: 0.3s;
    }
    .download-btn:hover {
        background-color: #0056b3;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 download-container">
            <h2>Download Scraper</h2>
            <p>Click the button below to download the latest version of Scraper.</p>
            <a href="{{ asset('exe/scrapper.exe') }}" class="download-btn" download>
                Download EXE
            </a>
        </div>
    </div>
</div>
@endsection
