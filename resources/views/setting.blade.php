<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
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

        .card ul li .setting-title {
            font-weight: 500;
        }

        .card ul li .setting-control {
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

        .form-control {
            padding: 8px 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
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
    <h1>Settings</h1>

    <div class="card">
        <h3>Account Settings</h3>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" class="form-control" value="">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" value="">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control">
        </div>
        <button class="btn btn-primary">Save Changes</button>
    </div>

    <div class="card">
        <h3>Notification Settings</h3>
        <ul>
            <li>
                <span class="setting-title">Email Notifications</span>
                <span class="setting-control">
                    <input type="checkbox" id="email_notifications" >
                </span>
            </li>
            <li>
                <span class="setting-title">SMS Notifications</span>
                <span class="setting-control">
                    <input type="checkbox" id="sms_notifications" >
                </span>
            </li>
            <li>
                <span class="setting-title">Push Notifications</span>
                <span class="setting-control">
                    <input type="checkbox" id="push_notifications" >
                </span>
            </li>
        </ul>
        <button class="btn btn-primary">Save Changes</button>
    </div>

    <div class="card">
        <h3>System Settings</h3>
        <div class="form-group">
            <label for="timezone">Timezone</label>
            <select id="timezone" class="form-control">
{{--                @foreach ($timezones as $timezone)--}}
{{--                    <option value="{{ $timezone }}" {{ $settings->timezone == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>--}}
{{--                @endforeach--}}
            </select>
        </div>
        <div class="form-group">
            <label for="language">Language</label>
            <select id="language" class="form-control">
                <option value="en">English</option>
                <option value="es">Spanish</option>
                <option value="pt">Portuguese</option>
            </select>
        </div>
        <button class="btn btn-primary">Save Changes</button>
    </div>
</div>
</body>
</html>
