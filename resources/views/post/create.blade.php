@extends('layouts.auth')

@section('content')

    <section>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-7">
                <div class="card card-body">
                    <form onsubmit="createPost()" action="javascript:void(0)" method="post">
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
                                    <small class="text-danger" id="image_ve"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        axios.get('/api/categories',{
            headers:{
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        }).then(({data})=>{
            $.each(data.categories,function(key, value){
                $(`#category`).append(`<option value='${value.id}'>${value.name}</option>`)
            })
        })

        async function createPost(){
            $(`small`).hide()

            var category_id = document.getElementById('category').value
            var title = document.getElementById('title').value

            var formData = new FormData()

            formData.append('category_id', category_id)
            formData.append('title', title)
            formData.append('image', document.getElementById('image').files[0])

            axios.post('/api/posts',formData,{
                headers:{
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                }
            }).then(({data})=>{

                alert("Post Created Successfully!!")
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