<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Inclusion de la connexion à la base de données
// On utilise require_once pour s'assurer qu'elle n'est chargée qu'une fois
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Model - Premium Services</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* --- VARIABLES THEME CLAIR (Par défaut) --- */
        :root {
            --bg: #f9fafb;
            --card: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --header-bg: #ffffff;
            --badge-bg: #eff6ff;
            --input-bg: #ffffff;
        }

        /* --- VARIABLES THEME SOMBRE --- */
        body.dark-theme {
            --bg: #0f172a;
            --card: #1e293b;
            --primary: #3b82f6; 
            --primary-hover: #60a5fa;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
            --header-bg: rgba(15, 23, 42, 0.95);
            --badge-bg: rgba(59, 130, 246, 0.15);
            --input-bg: rgba(0, 0, 0, 0.2);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
            flex-grow: 1;
        }

        /* --- HEADER & NAV --- */
        header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            backdrop-filter: blur(10px); 
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .logo a {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        /* --- BOUTON THEME TOGGLE --- */
        .theme-toggle-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            color: var(--text);
            background: rgba(128, 128, 128, 0.1);
        }

        /* Masquer le soleil dans le thème clair et la lune dans le thème sombre */
        .icon-sun { display: none; }
        body.dark-theme .icon-moon { display: none; }
        body.dark-theme .icon-sun { display: block; color: #fbbf24; }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text);
            cursor: pointer;
            padding: 5px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        nav a:hover { color: var(--text); }

        .badge-solde {
            background: var(--badge-bg);
            color: var(--primary) !important;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid var(--border);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            background: var(--primary);
            color: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn:hover { background: var(--primary-hover); }

        /* --- FORMS & AUTH CARDS --- */
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        form {
            background: var(--card);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        form h2 { margin-bottom: 24px; text-align: center; font-size: 1.5rem; color: var(--text); }
        label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 0.9rem; color: var(--text); }
        
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], textarea {
            width: 100%; padding: 10px 12px; margin-bottom: 20px;
            border: 1px
