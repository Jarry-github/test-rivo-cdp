<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enregistrement d'un événement</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6fb; color: #172033; margin: 0; }
        header { background: white; padding: 20px 5%; border-bottom: 1px solid #ddd; }
        main { max-width: 760px; margin: 35px auto; padding: 0 20px; }
        .panel { background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e5ed; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field { margin-bottom: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 7px; }
        input, textarea { box-sizing: border-box; width: 100%; padding: 11px; border: 1px solid #ccd3df; border-radius: 7px; font: inherit; }
        textarea { min-height: 120px; font-family: Consolas, monospace; }
        button { padding: 12px 17px; border: 0; border-radius: 7px; background: #5146e5; color: white; font-weight: bold; cursor: pointer; }
        a { color: #5146e5; text-decoration: none; }
        #message { display: none; padding: 12px; margin-bottom: 16px; border-radius: 7px; overflow-wrap: anywhere; }
        .success { display: block !important; background: #e5f7ed; color: #116b49; }
        .error { display: block !important; background: #fff0f0; color: #a82e3e; }
        @media(max-width:600px) { .grid { grid-template-columns: 1fr; gap: 0; } }
    </style>
</head>
<body>
<header>
    <strong>OLONA TALENTS · Customer Insights</strong>
    &nbsp; | &nbsp;
    <a href="/">Retour au dashboard</a>
</header>

<main>
    <h1>Enregistrer un événement</h1>
    <p>Le client sera créé automatiquement si son adresse e-mail n'existe pas encore.</p>

    <section class="panel">
        <div id="message" role="status" aria-live="polite"></div>

        <form id="event-form">
            <h3>Informations du client</h3>
            <div class="grid">
                <div class="field">
                    <label for="email">Adresse e-mail *</label>
                    <input id="email" type="email" maxlength="255" required placeholder="client@exemple.com">
                </div>
                <div class="field">
                    <label for="name">Nom du client</label>
                    <input id="name" maxlength="255" placeholder="Nom et prénom">
                </div>
            </div>

            <h3>Informations de l'événement</h3>
            <div class="grid">
                <div class="field">
                    <label for="event_name">Type d'événement *</label>
                    <input id="event_name" maxlength="100" required value="purchase">
                </div>
                <div class="field">
                    <label for="occurred_at">Date et heure *</label>
                    <input id="occurred_at" type="datetime-local" required>
                </div>
            </div>

            <div class="field">
                <label for="properties">Propriétés JSON</label>
                <textarea id="properties" spellcheck="false">{
  "amount": 150,
  "currency": "EUR"
}</textarea>
            </div>

            <button id="submit-button" type="submit">Enregistrer l'événement</button>
        </form>
    </section>
</main>

<script>
(() => {
    const form = document.getElementById('event-form');
    const message = document.getElementById('message');
    const button = document.getElementById('submit-button');
    const dateInput = document.getElementById('occurred_at');

    function setDate() {
        const now = new Date();
        dateInput.value = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
            .toISOString().slice(0, 16);
    }

    function showMessage(text, type) {
        message.textContent = text;
        message.className = type;
    }

    setDate();

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        let properties;
        try {
            properties = JSON.parse(document.getElementById('properties').value || '{}');
            if (properties === null || Array.isArray(properties) || typeof properties !== 'object') {
                throw new Error('Les propriétés doivent être un objet JSON.');
            }
        } catch (error) {
            showMessage('JSON invalide : ' + error.message, 'error');
            return;
        }

        const payload = {
            email: document.getElementById('email').value.trim(),
            name: document.getElementById('name').value.trim() || null,
            event_name: document.getElementById('event_name').value.trim(),
            occurred_at: dateInput.value,
            properties: properties
        };

        button.disabled = true;
        button.textContent = 'Enregistrement...';

        try {
            const response = await fetch('/api/events', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                const errors = Object.values(result.errors || {}).flat();
                throw new Error(errors.join(' ') || result.message || 'Erreur HTTP ' + response.status);
            }

            showMessage(
                'Événement enregistré avec succès. ' +
                (result.event?.id ? 'ID : ' + result.event.id : ''),
                'success'
            );
            form.reset();
            setDate();
        } catch (error) {
            showMessage("Échec de l'enregistrement : " + error.message, 'error');
        } finally {
            button.disabled = false;
            button.textContent = "Enregistrer l'événement";
        }
    });
})();
</script>
</body>
</html>