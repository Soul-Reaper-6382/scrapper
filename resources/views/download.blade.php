@extends('layouts.app')

@section('content')
<style>
     body {
        background: url('{{ asset("images/abstract.jpg") }}');
        height: 100vh; /* Ensures full viewport height */
        margin: 0;
    }
    .download-container {
        text-align: center;
        margin-top: 100px;
        background: rgb(243 238 238 / 79%);
        padding: 50px;
        border-radius: 10px;
    }
    
    .download-btn {
    background-color: black;
    color: #ffc107 !important;
    font-size: 17px;
    padding: 7px 50px;
    border-radius: 5px;
    text-decoration: none;
    transition: 0.3s;
    display: inline-block;
    margin-top: 15px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 download-container">
            <h2>Download UPI Tool</h2>
            <p>Click the button below to download the latest version of UPI Tool.</p>
            <a href="https://scrapper.webxcube.com/download/UPI%20tool%20Setup%201.0.0.exe" class="download-btn">
                Download EXE
            </a>
        </div>
    </div>
</div>
@endsection
