<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Transfer API</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Inter, Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }
        .hero {
            text-align: center;
            padding: 100px 20px 60px;
        }
        .badge {
            display: inline-block;
            background: rgba(59, 130, 246, .15);
            border: 1px solid rgba(59, 130, 246, .4);
            color: #60a5fa;
            padding: 8px 18px;
            border-radius: 999px;
            margin-bottom: 25px;
        }
        h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        .subtitle {
            max-width: 850px;
            margin: auto;
            color: #94a3b8;
            font-size: 1.1rem;
        }
        .buttons {
            margin-top: 35px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 14px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }
        .primary {
            background: #2563eb;
            color: white;
        }
        .secondary {
            border: 1px solid #334155;
            color: white;
        }
        section {
            padding: 70px 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        .card {
            background: #111827;
            border: 1px solid #1e293b;
            border-radius: 14px;
            padding: 25px;
        }
        .card h3 {
            color: #60a5fa;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table th,
        table td {
            padding: 14px;
            border: 1px solid #1e293b;
        }
        table th {
            background: #111827;
        }
        code {
            color: #38bdf8;
        }
        .footer {
            text-align: center;
            padding: 40px;
            border-top: 1px solid #1e293b;
            color: #64748b;
            font-size: 9px;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 2.4rem;
            }
        }
    </style>
</head>
<body>
<div class="hero">
    <div class="container">
        <div class="badge">
            Symfony • PHP 8.3 • MySQL • Redis
        </div>
        <h1>Fund Transfer API</h1>
        <p class="subtitle">
            Secure, scalable and production-ready API for transferring funds between accounts.
            Designed to demonstrate transaction integrity, reliability under load,
            clean architecture, testing and modern Symfony development practices.
        </p>
        <div class="buttons">
            <a href="https://github.com/karmakarpatrick1991/fund-transfer-api/blob/main/README.md" class="btn primary">README / Documentation</a>
            <a href="https://github.com/karmakarpatrick1991/fund-transfer-api" class="btn secondary" target="_blank">
                GitHub Repository
            </a>
        </div>
    </div>
</div>
<section>
    <div class="container">
        <h2>Project Highlights</h2>
        <div class="grid" style="margin-top:25px">
            <div class="card">
                <h3>Transaction Integrity</h3>
                <p>
                    Atomic database transactions, balance validation,
                    concurrency protection and rollback handling.
                </p>
            </div>
            <div class="card">
                <h3>High Load Ready</h3>
                <p>
                    MySQL persistence, Redis caching,
                    optimized queries and scalable architecture.
                </p>
            </div>
            <div class="card">
                <h3>Production Standards</h3>
                <p>
                    Logging, validation, exception handling,
                    integration tests and Docker support.
                </p>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <h2>API Endpoints</h2>
        <table>
            <thead>
            <tr>
                <th>Method</th>
                <th>Endpoint</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>POST</td>
                <td><code>/app/create</code></td>
                <td>Create Account</td>
            </tr>
            <tr>
                <td>GET</td>
                <td><code>/app/account/{uuid}</code></td>
                <td>Get Account Details</td>
            </tr>
            <tr>
                <td>PUT</td>
                <td><code>/app/update/{uuid}</code></td>
                <td>Update Account</td>
            </tr>
            <tr>
                <td>POST</td>
                <td><code>/app/transfer</code></td>
                <td>Transfer Funds</td>
            </tr>
            </tbody>
        </table>
    </div>
</section>
<section>
    <div class="container">
        <h2>Assessment Objectives</h2>
        <div class="grid" style="margin-top:25px">
            <div class="card">
                <h3>Technical Excellence</h3>
                <p>
                    Clean architecture, SOLID principles,
                    modern Symfony and PHP 8.3 patterns.
                </p>
            </div>
            <div class="card">
                <h3>Reliability</h3>
                <p>
                    Secure fund transfers, transaction consistency,
                    validation and fault tolerance.
                </p>
            </div>
            <div class="card">
                <h3>Professional Readiness</h3>
                <p>
                    Documentation, test coverage,
                    containerization and maintainability.
                </p>
            </div>
        </div>
    </div>
</section>
<div class="footer">
    Fund Transfer API •
     Pratik Karmakar

</div>
</body>
</html>
