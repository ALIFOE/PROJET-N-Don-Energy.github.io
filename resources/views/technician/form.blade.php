<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Technician Form</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }

        .form-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #3b82f6;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-button:hover {
            background-color: #2563eb;
        }
    </style>
</head>



<body>
    <div class="container bg-white shadow-md rounded-lg p-6">
        <h1 class="form-title">Formulaire de Recrutement</h1>
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" name="name" id="name" class="form-input" required>
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>
            <div>
                <label for="phone" class="form-label">Téléphone</label>
                <input type="text" name="phone" id="phone" class="form-input" required>
            </div>
            <div>
                <label for="position" class="form-label">Poste souhaité</label>
                <input type="text" name="position" id="position" class="form-input" required>
            </div>
            <div>
                <label for="cv" class="form-label">CV (format PDF)</label>
                <input type="file" name="cv" id="cv" class="form-input" accept=".pdf" required>
            </div>
            <div>
                <label for="cover_letter" class="form-label">Lettre de motivation</label>
                <textarea name="cover_letter" id="cover_letter" rows="4" class="form-input" required></textarea>
            </div>
            <div>
                <label for="experience" class="form-label">Expérience professionnelle</label>
                <textarea name="experience" id="experience" rows="4" class="form-input"></textarea>
            </div>
            <div>
                <label for="skills" class="form-label">Compétences</label>
                <textarea name="skills" id="skills" rows="4" class="form-input"></textarea>
            </div>
            <button type="submit" class="form-button">Soumettre</button>
        </form>
    </div>
</body>
</html>