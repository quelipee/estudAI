<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Topics - Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            margin-left: 250px; /* Espaço para o sidebar */
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            left: 0;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: #bdc3c7;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .content {
            padding: 20px;
            width: 1700px;
            max-width: 1700px; /* Largura máxima do conteúdo */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
            display: block;
        }

        .form-control {
            padding: 8px 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .btn-primary, .btn-danger {
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-align: center;
            color: #fff;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .topic-item {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .topic-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px; /* Espaçamento entre os botões */
        }

        .add-topic {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('index') }}">Courses</a></li>
        <li><a href="{{ route('settings') }}">Settings</a></li>
        <li><a href="{{ route('admin.logout') }}">Logout</a></li>
    </ul>
</div>
<div class="content">
    <h1>Edit Topics</h1>

    @if(count($course->topics) > 0)
        <h2 style="margin-bottom: 10px">Topics</h2>

            <div class="card topic-item">
                <form action="{{ route('topic.update', ['topic' => $topic->id,'roleUserId' => $roleUserId, 'roleModelId' => $roleModelId, 'course_id' => $course->id]) }}"
                      method="POST" style="margin-bottom: 10px;">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title_{{ $topic->id }}">Title</label>
                        <input type="text" id="title_{{ $topic->id }}" name="title" class="form-control" value="{{ $topic->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="topic_{{ $topic->id }}">Description</label>
                        <textarea id="topic_{{ $topic->id }}" name="topic" class="form-control" required>{{ $topic->topic }}</textarea>
                    </div>
                    <div class="topic-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

    @else
        <p>No topics available.</p>
    @endif

</div>
</body>
</html>
