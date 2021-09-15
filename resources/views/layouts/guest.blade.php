@extends('layouts.app')

@section('layout_content')

    <script>
        if(localStorage.getItem('access_token')){
            window.location.href = "/dashboard"
        }
    </script>

    <div class="container">
        @yield('content')
    </div>

@endsection