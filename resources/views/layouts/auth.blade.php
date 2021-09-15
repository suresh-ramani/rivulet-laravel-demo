@extends('layouts.app')

@section('layout_content')

    <script>
        if(!localStorage.getItem('access_token')){
            window.location.href = "/"
        }
    </script>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Laravel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('dashboard') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                </li>
            </ul>

            <button onclick="logout()" class="btn btn-danger">Logout</button>
        </div>
    </nav>

    <div class="container mt-3">
        @yield('content')
    </div>

    <script>
        async function logout(){
            await axios.get('/api/logout',{
                headers:{
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(()=>{
                alert("Logout Successfully!!")
            }).catch(()=>{
                alert("Something goes wrong!!")
            }).finally(()=>{
                localStorage.removeItem('access_token')
                localStorage.removeItem('user')
                window.location.href = '/'
            })
        }
    </script>

@endsection