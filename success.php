<?php
$ref = isset($_GET['ref']) ? $_GET['ref'] : "N/A";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Application Success</title>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .card {
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
        }

        h1 {
            color: #00ff99;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #00ff99;
            border: none;
            color: black;
            font-weight: bold;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background: #00cc77;
        }
    </style>
</head>

<body>

<div class="card">
    <h1>🎓 GNITS Application Submitted</h1>
    <p>Your application has been successfully submitted.</p>

    <h3>Reference Number</h3>
    <h2 style="color:yellow;"><?php echo $ref; ?></h2>

    <a class="btn" href="index.php">Submit Another Application</a>
</div>

<!-- SweetAlert Popup -->
<script>
Swal.fire({
    title: "Success!",
    text: "Your application has been submitted successfully.",
    icon: "success",
    confirmButtonColor: "#00ff99",
    confirmButtonText: "OK"
});
</script>

</body>
</html>