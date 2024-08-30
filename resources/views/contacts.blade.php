<!DOCTYPE html>
<html>
<head>
    <title>Import Contacts</title>
</head>
<body>
    <h1>Import Contacts</h1>
    <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="contacts_file" required>
        <button type="submit">Import Contacts</button>
    </form>
    <h1>Export Contacts</h1>
    <a href="{{ route('contacts.export') }}">Export Contacts</a>
</body>
</html>
