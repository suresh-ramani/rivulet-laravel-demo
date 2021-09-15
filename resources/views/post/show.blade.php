@extends('layouts.auth')

@section('content')

    <section>
        <div class="row justify-content-center" id="post__div">
            
        </div>
        <div class="row justify-content-center my-2" id="comments__div">
            
        </div>
        <div class="row justify-content-center my-2">
            <div class="col-12">
                <div class="card card-body">
                    <form action="javascript:void(0)" onsubmit="storeComment()">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment">Comment</label>
                                    <textarea id="text" cols="30" rows="3" class="form-control"></textarea>
                                    <small class="text-danger" id="text_ve"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        axios.get('/api/posts/{!! $post !!}',{
            headers:{
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        }).then(({data})=>{

            var html = `
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <img src="/storage/posts/${data.post.image}" class="card-img-top" height="150px">
                            <div class="card-body">
                                <h3 class="card-text">${data.post.title}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            `

            $(`#post__div`).append(html)

            comments();
        })

        async function comments(){
            await axios.get(`/api/posts/{!! $post !!}/comments`,{
                headers:{
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(({data})=>{
                $.each(data,function(key, value){
                    $(`#comments__div`).append(commentHtml(value))
                })
            })
        }

        function commentHtml(comment){
            return `
                <div class="col-12 col-sm-12 col-md-12 mb-1">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="mt-0">${comment.user.name} (${comment.user.email})</h5>
                                <p>${comment.text}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function storeComment(){
            $(`small`).hide()

            var text = document.getElementById('text').value

            await axios.post(`/api/posts/{!! $post !!}/comments`,{text,},{
                headers:{
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(({data})=>{

                $(`#comments__div`).append(commentHtml(data.comment))

            }).catch(({response})=>{
                if(response.status===422){
                    $.each(response.data.errors,function(key, value){
                        $(`#${key}_ve`).html(value[0]).fadeIn()
                    })
                }else{
                    alert(response.data.message)
                }
            })
        }
    </script>

@endsection