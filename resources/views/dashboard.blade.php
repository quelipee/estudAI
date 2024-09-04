<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        .card p {
            margin-bottom: 10px;
            color: #666;
        }

        .card ul {
            list-style: none;
            padding: 0;
        }

        .card ul li {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            color: #333;
        }

        .card ul li:last-child {
            border-bottom: none;
        }

        .card ul li .activity-title {
            font-weight: 500;
        }

        .card ul li .activity-date {
            font-size: 14px;
            color: #888;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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
    <h1>Dashboard</h1>

    <div class="card">
        <h3>Course Summary</h3>
        <p>Total Courses: {{ count($courses) }}</p>
        <p>Active Courses: {{ $status['active'] }}</p>
        <p>Inactive Courses: {{ $status['inactive'] }}</p>
    </div>

    <div class="card">
        <h3>Recent Activities</h3>
        <ul>
            @foreach ($courses->sortByDesc('created_at') as $activity)
                <li>
                    <span class="activity-title">{{ $activity->title }}</span>
                    <span class="activity-date">{{ $activity->created_at->format('d M Y H:i') }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card">
        <h3>Recent Updates</h3>
        <ul>
            @foreach ($courses as $update)
                <li>
                    <span class="activity-title">{{ $update->title }}</span>
                    <span class="activity-date">{{ $update->updated_at->format('d M Y H:i') }}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card">
        <h3>Quick Links</h3>
        <a href="{{ route('course.create') }}" class="btn btn-primary">Add New Course</a>
        <a href="{{ route('index') }}" class="btn btn-primary">View All Courses</a>
    </div>
</div>
</body>
</html>
