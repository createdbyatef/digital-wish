<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PUNB Inventory System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-teal: #0099A7;
            --brand-teal-dark: #007A85;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            height: 100vh; display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(circle at 0% 0%, rgba(0, 153, 167, 0.03) 0%, transparent 40%),
                              radial-gradient(circle at 100% 100%, rgba(0, 153, 167, 0.03) 0%, transparent 40%);
        }

        .login-card {
            width: 100%; max-width: 440px; background: #ffffff;
            padding: 50px; border-radius: 40px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.02);
            text-align: center; position: relative; overflow: hidden;
        }

        .login-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, var(--brand-teal), var(--brand-teal-dark));
        }

        .logo-area { margin-bottom: 40px; }
        .logo-area img { max-width: 220px; height: auto; }

        .headline h2 { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -1.2px; margin-bottom: 8px; }
        .headline p { color: #64748b; font-size: 15px; margin-bottom: 35px; font-weight: 600; }

        .input-box { text-align: left; margin-bottom: 25px; }
        .input-box label { display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .input-box input {
            width: 100%; padding: 18px 22px; background: #f8fafc; border: 2px solid #f1f5f9;
            border-radius: 18px; font-size: 15px; color: #1e293b; font-family: inherit; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-box input:focus {
            background: #fff; border-color: var(--brand-teal); outline: none;
            box-shadow: 0 0 0 6px rgba(0, 153, 167, 0.1); transform: translateY(-2px);
        }

        .btn-submit {
            width: 100%; padding: 20px; background: var(--brand-teal); color: #fff;
            border: none; border-radius: 18px; font-size: 16px; font-weight: 800;
            cursor: pointer; transition: all 0.3s; margin-top: 10px;
            box-shadow: 0 15px 30px -5px rgba(0, 153, 167, 0.3);
        }
        .btn-submit:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 25px 45px -10px rgba(0, 153, 167, 0.4); background: var(--brand-teal-dark); }

        .auth-footer { margin-top: 45px; font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; }

        .error-msg { background: #fef2f2; color: #dc2626; padding: 15px; border-radius: 14px; font-size: 13px; font-weight: 700; margin-bottom: 30px; text-align: left; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-area">
            <img src="/logo.png" alt="PUNB Logo">
        </div>

        <div class="headline">
            <h2>Sign In</h2>
            <p>Authorized access for IMS v2.0</p>
        </div>

        @if($errors->any())
            <div class="error-msg">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="input-box">
                <label>Institutional Account</label>
                <input type="email" name="email" placeholder="example@punb.com.my" required autofocus>
            </div>
            <div class="input-box">
                <label>Credentials Key</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-submit">Authenticate Session</button>
        </form>

        <div class="auth-footer">
            PUNB DIGITAL INVENTORY
        </div>
    </div>
</body>
</html>
