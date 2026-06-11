<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>GNITS Admissions</title>
<link rel="stylesheet" href="css/login.css">

<style>

/* 🔔 ANNOUNCEMENT */
.announcement{
    background: orange;
    color: white;
    text-align: center;
    padding: 10px;
    font-weight: bold;
}

/* 🎯 INFO CARDS */
.info-cards{
    display:flex;
    gap:20px;
    margin-top:30px;
}

.info-card{
    flex:1;
    padding:25px;
    background:white;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
}

.info-card:hover{
    transform:translateY(-10px);
    background: orange;
    color:white;
}


/* 🏫 ABOUT */
.about{
    margin-top:40px;
    padding:20px;
    background:#f9f9f9;
    border-left:5px solid orange;
    border-radius:8px;
}

.about h3{
    color:#006400;
}

/* 📊 STATS */
.stats{
    display:flex;
    gap:20px;
    margin-top:20px;
}

.stats div{
    flex:1;
    padding:15px;
    background:white;
    text-align:center;
    border-radius:8px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

/* 📱 RESPONSIVE */
@media(max-width:768px){
    .main-container{
        flex-direction:column;
    }

    .left-frame, .right-frame{
        width:100%;
    }

    .info-cards{
        flex-direction:column;
    }

    .stats{
        flex-direction:column;
    }
}

</style>
</head>

<body>

<!-- 🔔 ANNOUNCEMENT -->
<div class="announcement">
    📢 Admissions Open for 2026 – Apply Now!
</div>

<!-- 🎓 HEADER -->
<header class="header">
    <img src="pics/logo.jpg" class="logo">

    <h1 class="college-name">
        G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE
    </h1>

    <a href="login.php" class="login-top-btn">Login</a>
</header>

<!-- 📦 MAIN -->
<div class="main-container">

    <!-- 📌 LEFT -->
    <div class="left-frame">
        <h3>Admissions</h3>

        <a href="jee.php" class="admission-link">📘 JEE-Main Admission</a>
        <a href="nri.php" class="admission-link">🌍 NRI/NRI Sponsored Admission</a>
        <a href="oci.php" class="admission-link">🌍 OCI Admission</a>
        <a href="pending_payment_jee.php" class="admission-link">Pay Again if payment is not done for JEE</a>
        <a href="pending_payment_nri.php" class="admission-link">Pay Again if payment is not done for NRI</a>
        <a href="pending_payment_oci.php" class="admission-link">Pay Again if payment is not done for OCI</a>
    </div>

    <!-- 📄 RIGHT -->
    <div class="right-frame">

        <h2>Welcome to GNITS</h2>

        <p>
            Explore admission opportunities and apply through the respective 
            admission categories. Begin your journey with excellence.
        </p>
         
        <!-- 🎯 INFO CARDS -->
        <div class="info-cards">
            <div class="info-card">
                <h4>🎓 Courses</h4>
                <p>CSE, ECE, IT, EEE, CSM, CSD, ETM</p>
            </div>

            <div class="info-card">
                <h4>📅 Dates</h4>
                <p>Open till July 30</p>
            </div>

            <div class="info-card">
                <h4>📞 Contact</h4>
                <p>info@gnits.ac.in</p>
            </div>
        </div>

        <!-- 🏫 ABOUT -->
        <div class="about">
            <h3>About GNITS</h3>
            <p>
                GNITS is a premier institution known for academic excellence, 
                innovation, and empowering women in engineering education.
            </p>
        </div>

        <!-- 📊 STATS -->
        <div class="stats">
            <div>🏫 25+ Years</div>
            <div>👩‍🎓 5000+ Students</div>
            <div>📚 10+ Courses</div>
        </div>

    </div>

</div>

<!-- 🦶 FOOTER -->
<footer class="footer">
    © G. Narayanamma Institute of Technology and Science
</footer>
<?php include 'chatbot.php'; ?>
</body>
</html>