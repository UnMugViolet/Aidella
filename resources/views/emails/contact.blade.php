<!DOCTYPE html>
<html lang="fr" xml:lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouveau message de contact</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">
  <div style="max-width: 480px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 32px;">
    <h2 style="color: #2563eb; margin-bottom: 24px;">Nouveau message de contact</h2>
    <p><strong>Nom:</strong> {{ $data['nom'] }}</p>
    <p><strong>Prénom:</strong> {{ $data['prenom'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <div style="margin-top: 20px;">
      <strong>Message:</strong>
      <div style="background: #f1f5f9; border-radius: 4px; padding: 12px; margin-top: 6px; white-space: pre-line;">
        {{ $data['message'] }}
      </div>
    </div>
  </div>
</body>
</html>
