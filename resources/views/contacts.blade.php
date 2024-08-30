<!DOCTYPE html>
<html>
<head>
    <title>Import Contacts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="file"] {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Import Contacts</h1>
    
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
    
        @if (session('missing_rows'))
            <div class="alert alert-warning">
                <h4>Some rows were not imported due to missing required fields:</h4>
                <ul>
                    @foreach (session('missing_rows') as $row)
                        <li>{{ json_encode($row) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="contacts_file" required>
            <br>
            <button type="submit">Import Contacts</button>
        </form>
    
        <h1>Export Contacts</h1>
        <a href="{{ route('contacts.export') }}">Export Contacts</a>
    </div>
    
    
</body>
</html>
