<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Laravel Ajax Pagination</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <!-- Search -->
        <div class="row justify-content-end">
            <div class="col-6">
                <form onsubmit="searchPosts(event)">
                    <div class="input-group mb-3">
                        <input type="search"
                               name="search"
                               placeholder="Search..."
                               class="form-control">

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary"
                                    type="submit">Seach</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Post List -->
        <div class="row">
            <div class="col-12"
                 id="posts">
                @include('posts')
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();

                getPosts(e.target.href);
            });
        });

        function getPosts(url) {
            axios.defaults.headers.post['Content-Type'] = 'application/json';
            axios.get(url)
            .then(function (response) {
                document.querySelector('#posts').innerHTML = response.data;
            })
            .catch(function (error) {
                alert('Something was wrong. Post could not be loaded.');
            });
        }

        function searchPosts(e) {
            e.preventDefault();

            let search = e.target.elements['search'].value;

            getPosts(`${location.origin}?search=${search}`);
        }

    </script>
</body>

</html>