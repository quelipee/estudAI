<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Course Management</title>
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
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

        .course-list {
            list-style: none;
            padding: 0;
        }

        .course-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .course-item:hover {
            background-color: #f1f1f1;
        }

        .course-item-content {
            display: flex;
            justify-content: space-between;
            padding: 15px;
        }

        .course-item-content h3 {
            margin: 0;
            color: #333;
        }

        .course-item-content p {
            margin: 0;
            color: #666;
        }

        .course-item-actions {
            display: flex;
            align-items: center;
            padding: 15px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .actions {
            display: flex;
            align-items: center;
        }

        .course-item-actions .btn {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="{{ route('index') }}">Dashboard</a></li>
        <li><a href="{{ route('index') }}">Courses</a></li>
        <li><a href="{{ route('index') }}">Settings</a></li>
        <li><a href="{{ route('admin.logout') }}">Logout</a></li>
    </ul>
</div>
<div class="content">
    <h1>Course Management</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">Add New Course</a>
    <ul class="course-list">
        @foreach ($courses as $course)
{{--            @dd($course->topics)--}}
            <li class="course-item" onclick="window.location.href='{{ route('edit', $course->id) }}'">
                <div class="course-item-content">
                    <div>
                        <h3>{{ $course->title }}</h3>
                        <p>{{ $course->description }}</p>
                    </div>
                    <div class="actions">
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
</body>
</html>
