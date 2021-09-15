@extends('layouts.auth')

@section('content')

    <section>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-7">
                <div class="card card-body">
                    <form onsubmit="updatePost()" action="javascript:void(0)" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select id="category" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                    <small class="text-danger" id="category_id_ve"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title">
                                    <small class="text-danger" id="title_ve"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="d-block" for="image">Image</label>
                                    <input type="file" id="image"><br/>
                                    <input type="hidden" name="old_image" id="old_image">
                                    <small class="text-danger" id="image_ve"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        var post = null
        axios.get('/api/posts/{!! $post !!}',{
            headers:{
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        }).then(({data})=>{
            post = data.post
            $('#category').val(post.category_id)
            $('#title').val(post.title)
            $('#old_image').val(post.image)
        }).finally(()=>{
            categories()
        })

        async function categories(){
            await axios.get('/api/categories',{
                headers:{
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(({data})=>{
                $.each(data.categories,function(key, value){
                    $(`#category`).append(`<option value='${value.id}' ${post.category_id==value.id ? `selected` : `` }>${value.name}</option>`)
                })
            })
        }

        async function updatePost(){
            $(`small`).hide()

            var category_id = document.getElementById('category').value
            var title = document.getElementById('title').value
            var old_image = document.getElementById('old_image').value

            var formData = new FormData()

            formData.append('_method', 'patch')
            formData.append('category_id', category_id)
            formData.append('title', title)
            formData.append('old_image', old_image)
            formData.append('image', document.getElementById('image').files[0] || "")

            await axios.post(`/api/posts/${post.id}`,formData,{
                headers:{
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(({data})=>{

                alert("Post Updated Successfully!!")
                window.location.href = "/posts"

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