<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why You Need IUB Smart Parking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #363636 100%);
            padding: 2rem;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
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

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .necessity-section {
            padding: 4rem 2rem;
            background: #f8f9fa;
        }

        .necessity-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .necessity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .necessity-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .necessity-card:hover {
            transform: translateY(-10px);
        }

        .necessity-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #4CAF50;
        }

        .necessity-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .necessity-text {
            color: #666;
            line-height: 1.6;
        }

        .benefits-section {
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #1a1a1a 0%, #363636 100%);
            color: white;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .benefit-icon {
            font-size: 2rem;
            margin-right: 1.5rem;
            color: #4CAF50;
        }

        .benefit-content h3 {
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 2rem;
            background: #f8f9fa;
        }

        .nav-button {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-button {
            background: linear-gradient(45deg, #333, #666);
        }

        .home-button {
            background: linear-gradient(45deg, #4CAF50, #45a049);
        }

        .nav-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2.5rem;
            }
            
            .necessity-grid {
                grid-template-columns: 1fr;
            }
            
            .navigation-buttons {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1 class="header-title">Why You Need Smart Parking</h1>
            <p>Discover how our smart parking solution transforms your daily experience</p>
        </div>
    </header>

    <section class="necessity-section">
        <div class="necessity-container">
            <div class="necessity-grid">
                <div class="necessity-card">
                    <i class="fas fa-clock necessity-icon"></i>
                    <h3 class="necessity-title">Time Efficiency</h3>
                    <p class="necessity-text">Save valuable time with real-time parking space availability and quick entry/exit processing. No more circling around looking for spots or dealing with manual paperwork.</p>
                </div>

                <div class="necessity-card">
                    <i class="fas fa-shield-alt necessity-icon"></i>
                    <h3 class="necessity-title">Enhanced Security</h3>
                    <p class="necessity-text">Protect your vehicle with our advanced security features, including digital tracking, instant alerts for unauthorized access, and comprehensive incident reporting system.</p>
                </div>

                <div class="necessity-card">
                    <i class="fas fa-chart-line necessity-icon"></i>
                    <h3 class="necessity-title">Data-Driven Insights</h3>
                    <p class="necessity-text">Access detailed parking analytics, usage patterns, and occupancy rates to make informed decisions about your parking habits and timing.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits-section">
        <div class="necessity-container">
            <div class="benefit-item">
                <i class="fas fa-money-bill-wave benefit-icon"></i>
                <div class="benefit-content">
                    <h3>Cost-Effective Solution</h3>
                    <p>Reduce operational costs through automated management and efficient space utilization. Minimize the need for manual security personnel and paper-based systems.</p>
                </div>
            </div>

            <div class="benefit-item">
                <i class="fas fa-leaf benefit-icon"></i>
                <div class="benefit-content">
                    <h3>Environmental Impact</h3>
                    <p>Reduce carbon emissions by minimizing the time spent searching for parking spaces. Our system helps create a more sustainable campus environment.</p>
                </div>
            </div>

            <div class="benefit-item">
                <i class="fas fa-mobile-alt benefit-icon"></i>
                <div class="benefit-content">
                    <h3>User-Friendly Experience</h3>
                    <p>Access all parking features through an intuitive interface. Receive real-time notifications and updates directly on your device.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="navigation-buttons">
        <a href="about.php" class="nav-button back-button">
            <i class="fas fa-arrow-left"></i> Back to About
        </a>
        <a href="index.php" class="nav-button home-button">
            <i class="fas fa-home"></i> Go to Home
        </a>
    </div>
</body>
</html>