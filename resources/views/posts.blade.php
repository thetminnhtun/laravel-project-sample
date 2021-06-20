<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Date</th>
        </tr>
    </thead>
    @foreach ($posts as $post)
    <tbody>
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->title }}</td>
            <td>{{ $post->created_at->toFormattedDateString() }}</td>
        </tr>
    </tbody>
    @endforeach
</table>

<div class="mt-3">
    {{ $posts->appends(request()->query())->links() }}
</div>