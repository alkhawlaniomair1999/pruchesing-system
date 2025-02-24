<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة إدارة النظام</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #6a5acd;
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            flex-shrink: 0;

        }

        header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 200px;
            background: #ffffff;
            padding: 0;
            box-shadow: -2px 0 3px rgba(0, 0, 0, 0.1);
            transition: width 0.3s, opacity 0.3s, transform 0.3s;
            order: 2;
            height: 100%;
        }

        .sidebar.collapsed {
            width: 0;
            opacity: 0;
            overflow: hidden;
        }

        .sidebar h3 {
            color: #333;
            text-align: center;
            display: none;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar ul li a {
            display: block;
            color: #333;
            text-decoration: none;
            padding: 15px;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            width: 100%;
        }

        .sidebar ul li a:hover {
            background-color: #e0e0e0;
            color: #333;
        }

        .content {
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s, width 0.3s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 10px;
            background-color: #f5f5f5;
            color: #333;
            order: 1;
            overflow-y: auto;
        }

        .content.expanded {
            width: 100%;
        }

        footer {
            background-color: #6a5acd;
            color: white;
            text-align: center;
            padding: 10px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            flex-shrink: 0;
        }

        .toggle-btn {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
            transition: right 0.3s;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
                transform: translateX(0);
            }

            .sidebar.collapsed {
                display: block;
                width: 100%;
                position: absolute;
                top: 60px;
                right: 0;
                height: 100%;
                z-index: 1000;
                transform: translateX(0);
                border-radius: 0;
                opacity: 1;
            }

            .content {
                margin: 0;
                border-radius: 0;
            }

            .content.expanded {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header>
        <button class="toggle-btn" id="toggleBtn">☰</button>
        <a href="#">الصفحة الرئيسية</a>
    </header>

    <main>
        <div class="content" id="content">
            <h1>مرحباً بك في نظام الإدارة</h1>
            <p>هذه مساحة المحتوى الرئيسية.</p>
        </div>
        <div class="sidebar" id="sidebar">
            <h3>القائمة الجانبية</h3>
            <ul>
                <li><a href="#">رابط 1</a></li>
                <li><a href="#">رابط 2</a></li>
                <li><a href="#">رابط 3</a></li>
            </ul>
        </div>
    </main>

    <footer>
        &copy; 2023 جميع الحقوق محفوظة
    </footer>

    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth > 768) {
                content.classList.toggle('expanded');
            }
        });
    </script>

</body>

</html>
