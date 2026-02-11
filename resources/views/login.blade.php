<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — JWT API</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f0f0f;
            --surface: #1a1a1a;
            --border: #2e2e2e;
            --accent: #00e5a0;
            --accent-dim: rgba(0, 229, 160, 0.1);
            --text: #e8e8e8;
            --muted: #666;
            --error: #ff5f5f;
            --mono: 'IBM Plex Mono', monospace;
            --sans: 'IBM Plex Sans', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            width: 100%;
            max-width: 400px;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2.5rem;
            animation: fadeUp 0.4s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .badge {
            display: inline-block;
            font-family: var(--mono);
            font-size: 0.65rem;
            color: var(--accent);
            border: 1px solid var(--accent);
            padding: 2px 8px;
            margin-bottom: 1.5rem;
            letter-spacing: 0.1em;
        }

        h1 {
            font-family: var(--mono);
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .subtitle {
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        .field {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            font-family: var(--mono);
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--mono);
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: var(--accent);
        }

        input::placeholder { color: var(--muted); }

        button {
            width: 100%;
            margin-top: 0.5rem;
            background: var(--accent);
            color: #000;
            border: none;
            font-family: var(--mono);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 0.85rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
        }

        button:hover { opacity: 0.88; }
        button:active { transform: scale(0.99); }
        button:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Mensajes */
        .msg {
            margin-top: 1.2rem;
            padding: 0.75rem 1rem;
            font-family: var(--mono);
            font-size: 0.78rem;
            border-left: 3px solid;
            display: none;
            word-break: break-all;
        }

        .msg.error  { border-color: var(--error); color: var(--error); background: rgba(255,95,95,0.06); }
        .msg.success{ border-color: var(--accent); color: var(--accent); background: var(--accent-dim); }
        .msg.show   { display: block; }

        /* Token display */
        .token-box {
            margin-top: 1.5rem;
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 1rem;
            display: none;
        }

        .token-box.show { display: block; }

        .token-label {
            font-family: var(--mono);
            font-size: 0.65rem;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .token-value {
            font-family: var(--mono);
            font-size: 0.7rem;
            color: var(--accent);
            word-break: break-all;
            line-height: 1.5;
        }

        .user-info {
            margin-top: 0.8rem;
            font-family: var(--mono);
            font-size: 0.72rem;
            color: var(--muted);
        }

        .user-info span { color: var(--text); }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 2rem 0 1.5rem;
        }

        .footer {
            font-family: var(--mono);
            font-size: 0.65rem;
            color: var(--muted);
            text-align: center;
        }
    </style>
</head>
<body>

<div class="card">
    <span class="badge">JWT · AUTH</span>
    <h1>Iniciar sesión</h1>
    <p class="subtitle">Autenticación stateless mediante token</p>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="usuario@ejemplo.com" autocomplete="off">
    </div>

    <div class="field">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="••••••••">
    </div>

    <button id="btn" onclick="doLogin()">Autenticar →</button>

    <div class="msg error"  id="msgError"></div>
    <div class="msg success" id="msgSuccess"></div>

    <div class="token-box" id="tokenBox">
        <div class="token-label">access_token (Bearer)</div>
        <div class="token-value" id="tokenValue"></div>
        <div class="user-info" id="userInfo"></div>
    </div>

    <hr class="divider">
    <div class="footer">hardening de apis · jwt stateless</div>
</div>

<script>
    async function doLogin() {
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const btn      = document.getElementById('btn');
        const msgError   = document.getElementById('msgError');
        const msgSuccess = document.getElementById('msgSuccess');
        const tokenBox   = document.getElementById('tokenBox');

        // Reset
        msgError.classList.remove('show');
        msgSuccess.classList.remove('show');
        tokenBox.classList.remove('show');

        if (!email || !password) {
            showError('Por favor, rellena todos los campos.');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Verificando...';

        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (!res.ok) {
                showError(data.error || 'Credenciales incorrectas.');
            } else {
                msgSuccess.textContent = '✓ Autenticación correcta';
                msgSuccess.classList.add('show');

                document.getElementById('tokenValue').textContent = data.access_token;
                document.getElementById('userInfo').innerHTML =
                    `usuario: <span>${data.user?.email ?? '—'}</span> &nbsp;|&nbsp; ` +
                    `expira en: <span>${data.expires_in}s</span>`;
                tokenBox.classList.add('show');
            }
        } catch (e) {
            showError('Error de red. Verifica la conexión con la API.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Autenticar →';
        }
    }

    function showError(msg) {
        const el = document.getElementById('msgError');
        el.textContent = '✗ ' + msg;
        el.classList.add('show');
    }

    // Enter key
    document.addEventListener('keydown', e => {
        if (e.key === 'Enter') doLogin();
    });
</script>

</body>
</html>