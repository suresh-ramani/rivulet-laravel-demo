@extends('layouts.auth')

@section('content')

    <section>
        <div class="row mb-2">
            <div class="col-12 text-center">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
            </div>
        </div>
        <div class="row justify-content-center" id="posts__div">
            
        </div>
    </section>

    <script>
        axios.get('/api/posts',{
            headers:{
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        }).then(({data})=>{

            $.each(data.posts,function(key, value){

                var post = `<div class="col-12 col-sm-12 col-md-6">
                    <div class="card">
                        <img src="/storage/posts/${value.image}" class="card-img-top" height="100px">
                            <div class="card-body">
                                <p class="card-text">${value.title}</p>
                                <span>Comments (${value.comments_count})</span><br/>
                                ${value.canEdit ? `<a href="/posts/${value.id}/edit">Edit</a>` : ``}
                                <br/><a href="/posts/${value.id}">View Post</a>
                            </div>
                        </div>
                </div>`;
                
                $(`#posts__div`).append(post)
            })

        })
    </script>

@endsection