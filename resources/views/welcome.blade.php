<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Laravel SPA Jquery</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-end">
            <button class="btn btn-primary"
                    onclick="create(event)">Create</button>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td style="width: 200px">
                                <img src="{{ Storage::url($post->image, 'public') }}"
                                     alt="{{ $post->title }}"
                                     class="img-fluid">
                            </td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->created_at->toFormattedDateString() }}</td>
                            <td>
                                <div class="row">
                                    <button class="btn btn-success"
                                            onclick="edit(event, {{ json_encode($post) }}, '{{ Storage::url($post->image, 'public') }}')">Edit</button>
                                    <form action="{{ route('post.destroy', $post->id) }}"
                                          method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger ml-2"
                                                onclick="return confirm('Are your sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No posts.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $posts->links() }}
    </div>

    <!-- Post Modal -->
    <div class="modal fade"
         id="post-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="post-form"
                      onsubmit="createOrUpdate(event)">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text"
                                   name="title"
                                   class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file"
                                   name="image"
                                   class="form-control"
                                   onchange="preview(event)">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div>
                            <img id="preview-image"
                                 src=""
                                 alt=""
                                 class="img-fluid">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                        <button type="submit"
                                class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */
        let isEditMode = false;
        let postId = null;
        const title = $('#post-form [name="title"]');
        const image = $('#post-form [name="image"]');
        const previewImage = $('#preview-image');

        /*
        |--------------------------------------------------------------------------
        | Methods
        |--------------------------------------------------------------------------
        */
        function reset() {
            title.val('');
            title.removeClass('is-invalid');

            image.val('');
            image.removeClass('is-invalid');
            previewImage.removeAttr('src');
        }

        function preview(e) {
            const file = e.target.files[0];
            if (file) {
                previewImage.attr('src', URL.createObjectURL(file));
            }
        }

        function create(e) {
            e.preventDefault();

            reset();
            isEditMode = false;

            $('#post-modal').modal('show');
        }

        function store(formData) {
            axios.post('/post', formData)
            .then(response => {
                $('#post-modal').modal('hide');
                location.reload();
            })
            .catch(error => {
                if (error.response) {
                    let errors = error.response.data.errors;
                    
                    if(errors.hasOwnProperty('title')) {
                        title.addClass('is-invalid')
                        title.siblings('.invalid-feedback').text(errors.title[0])
                    }

                    if(errors.hasOwnProperty('image')) {
                        image.addClass('is-invalid')
                        image.siblings('.invalid-feedback').text(errors.image[0])
                    }
                }
            })
        }

        function edit(e, post, image) {
            e.preventDefault();

            reset();
            
            isEditMode = true;
            postId = post.id;
            title.val(post.title);

            previewImage.attr('src', image)

            $('#post-modal').modal('show');
        }

        function update(formData) {
            formData.append('_method', 'PUT');

            axios.post(`/post/${postId}`, formData)
            .then(response => {
                $('#post-modal').modal('hide');
                location.reload();
            })
            .catch(error => {
                if (error.response) {
                    let errors = error.response.data.errors;
                    
                    if(errors.hasOwnProperty('title')) {
                        title.addClass('is-invalid')
                        title.siblings('.invalid-feedback').text(errors.title[0])
                    }

                    if(errors.hasOwnProperty('image')) {
                        image.addClass('is-invalid')
                        image.siblings('.invalid-feedback').text(errors.image[0])
                    }
                }
            })
        }
        
        function createOrUpdate(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('title', title.val() || '');
            formData.append('image', image[0].files[0] || '');
            
            isEditMode ? update(formData) : store(formData);
        }
    </script>
</body>

</html>