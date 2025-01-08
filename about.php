<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUB Parking System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            background: linear-gradient(135deg, #1a1a1a 0%, #363636 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(76, 175, 80, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(33, 150, 243, 0.1) 0%, transparent 50%);
            animation: gradientAnimation 15s ease infinite;
        }

        @keyframes gradientAnimation {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-title {
            font-size: 4rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleAnimation 1s ease-out;
        }

        @keyframes titleAnimation {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .hero-subtitle {
            font-size: 1.8rem;
            margin-bottom: 3rem;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.5s;
        }

        .login-button {
            display: inline-block;
            padding: 1.2rem 3rem;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.2rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 1s;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .features {
            padding: 6rem 2rem;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(33, 150, 243, 0.05) 100%);
            z-index: 0;
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 4rem;
            color: #333;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            border-radius: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-text {
            color: #666;
            line-height: 1.8;
        }

        .stats-section {
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #1a1a1a 0%, #363636 100%);
            color: white;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item {
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-subtitle {
                font-size: 1.4rem;
            }

            .features .login-button {
                margin: 0.5rem;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <h1 class="hero-title">IUB Parking System</h1>
            <p class="hero-subtitle">Next-Generation Smart Parking Solution</p>
            <a href="index.php" class="login-button">Access Your Account</a>
        </div>
    </div>

    <section class="features">
        <h2 class="section-title">Smart Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-users feature-icon"></i>
                <h3 class="feature-title">Multi-User Platform</h3>
                <p class="feature-text">Specialized access and features for students, faculty, staff, administrators, and security personnel.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-qrcode feature-icon"></i>
                <h3 class="feature-title">Digital Registration</h3>
                <p class="feature-text">Streamlined registration with digital plate recognition and chassis tracking system.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3 class="feature-title">Real-time Monitoring</h3>
                <p class="feature-text">Advanced entry/exit tracking with automated logging and instant status updates.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h3 class="feature-title">Enhanced Security</h3>
                <p class="feature-text">Integrated complaint management system with real-time tracking and instant notifications.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-tachometer-alt feature-icon"></i>
                <h3 class="feature-title">Smart Dashboard</h3>
                <p class="feature-text">Comprehensive management interface for vehicle tracking and detailed parking analytics.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-bell feature-icon"></i>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-text">Instant alerts for parking status, security updates, and system announcements.</p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 4rem;">
            <a href="necessity.php" class="login-button" style="background: linear-gradient(45deg, #2196F3, #1976D2); margin-bottom: 3rem;">Why You Need This</a>
            <div style="margin-top: 3rem;">
                <p style="color: #666; font-size: 1.2rem; margin-bottom: 1.5rem;">We also provide vehicle repair service, you can check it out here:</p>
                <a href="https://shahariar163.github.io/Automobile_Solution/" class="login-button" style="background: linear-gradient(45deg, #FF4081, #C51162);">Automobile Solution</a>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">System Availability</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Digital Coverage</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Real-time</div>
                <div class="stat-label">Status Updates</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Secure</div>
                <div class="stat-label">Data Protection</div>
            </div>
        </div>
    </section>
</body>
</html>