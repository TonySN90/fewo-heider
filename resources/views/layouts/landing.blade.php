<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'fewo-heider – Ihre Ferienwohnungs-Website')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --color-primary: #2c6e49;
            --color-primary-dark: #1e4d33;
            --color-bg: #f9fafb;
            --color-text: #1a1a2e;
            --color-muted: #6b7280;
            --color-border: #e5e7eb;
            --color-white: #ffffff;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .lp-header {
            background: var(--color-white);
            border-bottom: 1px solid var(--color-border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lp-logo {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-primary);
            text-decoration: none;
            letter-spacing: 0.02em;
        }

        .lp-header-link {
            font-size: 0.875rem;
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
            border: 2px solid var(--color-primary);
            padding: 0.4rem 1rem;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
        }

        .lp-header-link:hover {
            background: var(--color-primary);
            color: var(--color-white);
        }

        /* HERO */
        .lp-hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem 3rem;
        }

        .lp-eyebrow {
            display: inline-block;
            background: #dcfce7;
            color: var(--color-primary);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            margin-bottom: 1.5rem;
        }

        .lp-headline {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 700;
            line-height: 1.25;
            max-width: 700px;
            margin-bottom: 1.25rem;
            color: var(--color-text);
        }

        .lp-subline {
            font-size: 1.05rem;
            color: var(--color-muted);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .lp-cta {
            display: inline-block;
            background: var(--color-primary);
            color: var(--color-white);
            font-weight: 600;
            font-size: 1rem;
            padding: 0.85rem 2.25rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            box-shadow: 0 2px 8px rgba(44, 110, 73, 0.25);
        }

        .lp-cta:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
        }

        /* FEATURES */
        .lp-features {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
            padding: 3rem 2rem;
            max-width: 960px;
            margin: 0 auto;
            width: 100%;
        }

        .lp-feature {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 1.75rem 1.5rem;
            flex: 1 1 260px;
            max-width: 300px;
            text-align: center;
        }

        .lp-feature-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            display: block;
            color: var(--color-primary);
            font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 40;
        }

        .lp-feature-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--color-text);
        }

        .lp-feature-text {
            font-size: 0.875rem;
            color: var(--color-muted);
            line-height: 1.6;
        }

        /* FOOTER */
        .lp-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            font-size: 0.8rem;
            color: var(--color-muted);
            border-top: 1px solid var(--color-border);
            background: var(--color-white);
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
