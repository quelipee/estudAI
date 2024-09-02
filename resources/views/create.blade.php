<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
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
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow-y: auto;
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
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-top: 20px;
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
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 15px;
        }

        .topic-item h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .topic-item p {
            margin-bottom: 10px;
            color: #666;
        }

        .topic-item .btn {
            margin-top: 10px;
        }

        .form-group button {
            display: inline-block;
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
        <li><a href="{{ route('index') }}">Settings</a></li>
        <li><a href="{{ route('admin.logout') }}">Logout</a></li>
    </ul>
</div>
<div class="content">
    <h1>Create Course</h1>

    <form action="{{ route('course.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Course Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Course Description</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->value }}" {{ old('category') === $category->value ? 'selected' : '' }}>
                        {{ $category->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <h2 style="margin-bottom: 10px">Add Topics (Optional)</h2>
        <div class="form-group">
            <label for="topic_title_0">Topic Title</label>
            <input type="text" id="topic_title_0" name="topics[0][title]">
        </div>

        <div class="form-group">
            <label for="topic_description_0">Topic Description</label>
            <textarea id="topic_description_0" name="topics[0][topic]"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Course</button>
        </div>
    </form>
</div>
</body>
</html>
