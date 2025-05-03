<div>
    <h3>Your Files:</h3>
    <ul>
        @foreach ($files as $file)
            <li>{{ $file->filename }}</li>
        @endforeach
    </ul>
</div>
