<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OLONA TALENTS — Customer Insights</title>
    <style>
        :root {
            --bg:#f5f7fb; --panel:#fff; --ink:#172033; --muted:#718096;
            --line:#e5eaf2; --brand:#5146e5; --brand-light:#eeedff;
            --green:#13845b; --red:#c0394b;
        }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font:14px/1.5 Inter,Segoe UI,Arial,sans-serif; }
        button,input,select { font:inherit; }
        button { cursor:pointer; }
        .layout { display:flex; min-height:100vh; }
        aside { width:235px; flex-shrink:0; background:#171d31; color:#d8deef; padding:26px 16px; }
        .logo { display:flex; gap:11px; align-items:center; padding:0 9px 32px; color:#fff; font-weight:750; font-size:17px; }
        .logo-mark { display:grid; place-items:center; width:38px; height:38px; background:var(--brand); border-radius:12px; font-size:19px; }
        .nav-label { color:#8791aa; font-size:10px; font-weight:700; letter-spacing:1.5px; padding:0 12px; margin:15px 0 10px; }
        .nav { display:flex; align-items:center; gap:12px; width:100%; padding:12px; margin:4px 0; border:0; border-radius:9px; color:#c6cde0; background:transparent; text-align:left; }
        .nav .ico { width:20px; text-align:center; font-size:17px; }
        .nav.active { color:#fff; background:#34345f; }
        .nav:hover { background:#292f49; }
        .side-note { margin:36px 5px 0; padding:14px; border:1px solid #343b53; border-radius:10px; color:#aeb7cc; font-size:12px; }
        main { min-width:0; flex:1; }
        header { background:#fff; border-bottom:1px solid var(--line); padding:22px 32px; display:flex; align-items:center; justify-content:space-between; gap:15px; }
        header h1 { font-size:21px; margin:0; letter-spacing:-.5px; }
        header p { margin:4px 0 0; color:var(--muted); font-size:12px; }
        .status { display:flex; gap:7px; align-items:center; color:var(--green); background:#e8f7ef; padding:8px 12px; border-radius:20px; font-size:12px; white-space:nowrap; }
        .dot { width:7px; height:7px; background:#21a675; border-radius:50%; }
        .content { padding:28px 32px; max-width:1500px; margin:auto; }
        .welcome { margin-bottom:23px; }
        .welcome h2 { margin:0 0 5px; font-size:23px; letter-spacing:-.5px; }
        .welcome p { margin:0; color:var(--muted); }
        .metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; margin-bottom:24px; }
        .metric,.panel { background:var(--panel); border:1px solid var(--line); border-radius:13px; }
        .metric { padding:20px; }
        .metric-label { color:var(--muted); font-size:12px; }
        .metric-value { font-size:28px; font-weight:750; margin-top:8px; letter-spacing:-1px; }
        .metric-foot { font-size:11px; color:var(--muted); margin-top:3px; }
        .panel { padding:22px; margin-bottom:20px; }
        .panel-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px; flex-wrap:wrap; }
        .panel-head h3 { font-size:16px; margin:0; }
        .panel-head p { margin:4px 0 0; color:var(--muted); font-size:12px; }
        .btn { border:1px solid var(--line); border-radius:8px; padding:9px 13px; background:#fff; color:var(--ink); font-weight:600; }
        .btn:hover { background:#f6f7fc; }
        .btn.primary { color:#fff; background:var(--brand); border-color:var(--brand); }
        .btn.primary:hover { background:#4035cf; }
        .btn:disabled { opacity:.45; cursor:not-allowed; }
        .toolbar { display:flex; gap:8px; align-items:center; }
        .table-wrap { width:100%; overflow-x:auto; }
        table { border-collapse:collapse; width:100%; text-align:left; }
        th { background:#f8f9fc; color:#69758a; text-transform:uppercase; font-size:10px; letter-spacing:.7px; font-weight:700; }
        th,td { border-bottom:1px solid var(--line); padding:13px 12px; white-space:nowrap; }
        td { font-size:12px; }
        tbody tr:last-child td { border-bottom:0; }
        tbody tr:hover { background:#fafbff; }
        .muted { color:var(--muted); }
        .pill { display:inline-block; background:var(--brand-light); color:var(--brand); border-radius:5px; padding:4px 8px; font-size:11px; font-weight:650; }
        .empty { text-align:center; padding:30px 12px; color:var(--muted); }
        .pagination { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:17px; color:var(--muted); font-size:12px; }
        .pagination-actions { display:flex; gap:7px; }
        .condition { display:grid; grid-template-columns:1.1fr 1.1fr .8fr 1fr 36px; gap:10px; align-items:end; padding:14px; background:#f8f9fc; border:1px solid var(--line); border-radius:10px; margin-bottom:10px; }
        .field label { display:block; font-size:11px; font-weight:650; margin-bottom:6px; color:#536076; }
        .field input,.field select { width:100%; min-width:0; padding:10px; border:1px solid #dce2ec; background:#fff; border-radius:7px; color:var(--ink); outline:none; }
        .field input:focus,.field select:focus { border-color:var(--brand); box-shadow:0 0 0 3px #5146e51a; }
        .remove { height:38px; border:1px solid #f0d6d9; color:var(--red); background:#fff; border-radius:7px; font-size:18px; }
        .segment-actions { display:flex; flex-wrap:wrap; gap:9px; align-items:center; margin-top:17px; }
        .limit-field { width:100px; }
        .message { display:none; padding:12px 14px; margin:12px 0; border-radius:8px; font-size:12px; overflow-wrap:anywhere; }
        .message.error { display:block; background:#fff0f0; color:#a82e3e; border:1px solid #f6d1d5; }
        .message.info { display:block; background:#eef4ff; color:#315a9c; border:1px solid #d9e5ff; }
        .hidden { display:none !important; }
        footer { padding:10px 32px 25px; color:#8a94a8; font-size:11px; }
        @media(max-width:900px) {
            aside { width:190px; }
            .content { padding:22px 18px; }
            header { padding:18px; }
            .condition { grid-template-columns:1fr 1fr; }
            .remove { width:38px; }
        }
        @media(max-width:600px) {
            .layout { display:block; }
            aside { width:100%; padding:12px; }
            .logo { padding:0 7px 12px; }
            .nav-label,.side-note { display:none; }
            .navs { display:flex; gap:5px; }
            .nav { justify-content:center; padding:10px 5px; font-size:12px; gap:5px; }
            .nav .ico { display:none; }
            header { align-items:flex-start; }
            header h1 { font-size:17px; }
            .status { font-size:10px; padding:7px; }
            .metrics { grid-template-columns:1fr; gap:10px; }
            .metric { padding:15px; }
            .metric-value { font-size:23px; }
            .panel { padding:15px; }
            .condition { grid-template-columns:1fr; }
            .remove { width:100%; }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside>
        <div class="logo"><span class="logo-mark">O</span><span>OLONA TALENTS</span></div>
        <div class="nav-label">ESPACE CLIENT</div>
        <nav class="navs" aria-label="Navigation principale">
            <button class="nav active" data-view="customers"><span class="ico">◉</span> Clients</button>
            <button class="nav" data-view="events"><span class="ico">◷</span> Événements</button>
            <button class="nav" data-view="segments"><span class="ico">⌘</span> Segmentation</button>
        </nav>
        <div class="side-note">Customer Insights<br><br>Exploration des clients, suivi des événements et ciblage par critères comportementaux.</div>
    </aside>

    <main>
        <header>
            <div>
                <h1 id="page-title">Clients</h1>
                <p>Plateforme de données clients et de segmentation</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <a href="/events/create" class="btn" style="text-decoration:none">+ Enregistrer un événement</a>
                <div class="status"><span class="dot"></span> Interface prête</div>
            </div>
        </header>

        <div class="content">
            <section class="welcome">
                <h2>Vue d'ensemble</h2>
                <p>Explorez les données et identifiez les clients selon leurs interactions.</p>
            </section>

            <section class="metrics">
                <article class="metric">
                    <div class="metric-label">Clients enregistrés</div>
                    <div class="metric-value" id="total-customers">—</div>
                    <div class="metric-foot">Clients présents dans la base</div>
                </article>
                <article class="metric">
                    <div class="metric-label">Événements enregistrés</div>
                    <div class="metric-value" id="total-events">—</div>
                    <div class="metric-foot">Interactions disponibles</div>
                </article>
                <article class="metric">
                    <div class="metric-label">Dernière segmentation</div>
                    <div class="metric-value" id="segment-count">—</div>
                    <div class="metric-foot">Clients correspondants</div>
                </article>
            </section>

            <section id="view-customers" class="panel">
                <div class="panel-head">
                    <div><h3>Répertoire des clients</h3><p>Consultez les profils et le nombre d'événements associés.</p></div>
                    <button class="btn" id="refresh-customers">↻ Actualiser</button>
                </div>
                <div id="customers-message" class="message"></div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Événements</th><th>Création</th></tr></thead>
                        <tbody id="customers-body"><tr><td colspan="5" class="empty">Chargement des clients…</td></tr></tbody>
                    </table>
                </div>
                <div class="pagination">
                    <span id="customers-page">Page —</span>
                    <div class="pagination-actions">
                        <button class="btn" id="customers-prev" disabled>← Précédent</button>
                        <button class="btn" id="customers-next" disabled>Suivant →</button>
                    </div>
                </div>
            </section>

            <section id="view-events" class="panel hidden">
                <div class="panel-head">
                    <div><h3>Journal des événements</h3><p>Dernières interactions reçues par l'API.</p></div>
                    <button class="btn" id="refresh-events">↻ Actualiser</button>
                </div>
                <div id="events-message" class="message"></div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Événement</th><th>Client</th><th>Propriétés</th><th>Date</th></tr></thead>
                        <tbody id="events-body"><tr><td colspan="5" class="empty">Chargement des événements…</td></tr></tbody>
                    </table>
                </div>
                <div class="pagination">
                    <span id="events-page">Page —</span>
                    <div class="pagination-actions">
                        <button class="btn" id="events-prev" disabled>← Précédent</button>
                        <button class="btn" id="events-next" disabled>Suivant →</button>
                    </div>
                </div>
            </section>

            <section id="view-segments" class="panel hidden">
                <div class="panel-head">
                    <div><h3>Moteur de segmentation</h3><p>Chaque condition doit être satisfaite par le client (logique ET).</p></div>
                    <span class="pill">API POST /api/segments/query</span>
                </div>
                <div id="conditions">
                    <div class="condition">
                        <div class="field"><label>Événement</label><input name="event" value="purchase" maxlength="100" required></div>
                        <div class="field"><label>Propriété JSON</label><input name="property" value="amount" maxlength="100" pattern="[a-zA-Z0-9_]+" required></div>
                        <div class="field"><label>Opérateur</label><select name="operator"><option>&gt;</option><option>=</option><option>!=</option><option>&gt;=</option><option>&lt;</option><option>&lt;=</option></select></div>
                        <div class="field"><label>Valeur</label><input name="value" type="number" value="100" step="any" required></div>
                        <button type="button" class="remove" title="Supprimer cette condition" aria-label="Supprimer cette condition">×</button>
                    </div>
                </div>
                <div id="segments-message" class="message"></div>
                <div class="segment-actions">
                    <button class="btn" id="add-condition">+ Ajouter une condition</button>
                    <div class="field limit-field"><label>Limite</label><select id="segment-limit"><option>10</option><option selected>50</option><option>100</option></select></div>
                    <button class="btn primary" id="run-segment">Exécuter la segmentation →</button>
                </div>
                <div style="margin-top:28px">
                    <div class="panel-head"><div><h3>Résultats</h3><p id="segment-summary">Configurez les conditions puis lancez la recherche.</p></div></div>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>ID</th><th>Nom</th><th>Email</th></tr></thead>
                            <tbody id="segments-body"><tr><td colspan="3" class="empty">Aucun résultat pour le moment.</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <footer>OLONA TALENTS · Customer Insights · API Laravel</footer>
    </main>
</div>
<script>
(() => {
    const $ = id => document.getElementById(id);
    const state = {
        customersPage: 1, eventsPage: 1,
        customersLast: 1, eventsLast: 1,
        customersLoaded: false, eventsLoaded: false
    };

    function cell(value) {
        const td = document.createElement('td');
        td.textContent = value === null || value === undefined || value === '' ? '—' : String(value);
        return td;
    }

    function row(values) {
        const tr = document.createElement('tr');
        values.forEach(value => tr.appendChild(cell(value)));
        return tr;
    }

    function showMessage(id, message, type = 'error') {
        const el = $(id);
        el.textContent = message;
        el.className = message ? `message ${type}` : 'message';
    }

    function date(value) {
        if (!value) return '—';
        const parsed = new Date(value);
        return Number.isNaN(parsed.getTime()) ? value : parsed.toLocaleString('fr-FR');
    }

    async function api(url, options = {}) {
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}) },
            ...options
        });
        const body = await response.json().catch(() => ({}));
        if (!response.ok) {
            const detail = body.message || Object.values(body.errors || {}).flat().join(' ');
            throw new Error(detail || `Erreur HTTP ${response.status}`);
        }
        return body;
    }

    function updatePagination(type, result) {
        const plural = type === 'customers' ? 'customers' : 'events';
        state[`${plural}Page`] = result.current_page || 1;
        state[`${plural}Last`] = result.last_page || 1;
        $(`${type}-page`).textContent = `Page ${result.current_page || 1} / ${result.last_page || 1} · ${result.total || 0} résultat(s)`;
        $(`${type}-prev`).disabled = !result.prev_page_url;
        $(`${type}-next`).disabled = !result.next_page_url;
        $(`total-${type}`).textContent = Number(result.total || 0).toLocaleString('fr-FR');
    }

    async function loadCustomers(page = 1) {
        const body = $('customers-body');
        body.replaceChildren(row(['Chargement…','','','','']));
        showMessage('customers-message','');
        try {
            const result = await api(`/api/customers?page=${page}`);
            body.replaceChildren();
            if (!result.data?.length) {
                body.appendChild(row(['Aucun client','','','','']));
                body.firstChild.firstChild.colSpan = 5;
                body.firstChild.firstChild.className = 'empty';
            } else {
                result.data.forEach(c => {
                    const tr = row([c.id, c.name || 'Sans nom', c.email, c.events_count ?? 0, date(c.created_at)]);
                    body.appendChild(tr);
                });
            }
            updatePagination('customers', result);
            state.customersLoaded = true;
        } catch (error) {
            body.replaceChildren();
            const tr = row([error.message,'','','','']);
            tr.firstChild.colSpan = 5; tr.firstChild.className = 'empty';
            body.appendChild(tr);
            showMessage('customers-message', `Impossible de charger les clients : ${error.message}`);
        }
    }

    async function loadEvents(page = 1) {
        const body = $('events-body');
        body.replaceChildren(row(['Chargement…','','','','']));
        showMessage('events-message','');
        try {
            const result = await api(`/api/events?page=${page}`);
            body.replaceChildren();
            if (!result.data?.length) {
                const tr = row(['Aucun événement','','','','']);
                tr.firstChild.colSpan = 5; tr.firstChild.className = 'empty'; body.appendChild(tr);
            } else {
                result.data.forEach(e => {
                    let properties = '—';
                    if (e.properties) {
                        try { properties = JSON.stringify(e.properties); } catch (_) {}
                        if (properties.length > 100) properties = properties.slice(0, 97) + '…';
                    }
                    body.appendChild(row([e.id, e.event_name, e.customer?.name || e.customer?.email || `Client #${e.customer_id}`, properties, date(e.occurred_at)]));
                });
            }
            updatePagination('events', result);
            state.eventsLoaded = true;
        } catch (error) {
            body.replaceChildren();
            const tr = row([error.message,'','','','']);
            tr.firstChild.colSpan = 5; tr.firstChild.className = 'empty'; body.appendChild(tr);
            showMessage('events-message', `Impossible de charger les événements : ${error.message}`);
        }
    }

    function showView(view) {
        ['customers','events','segments'].forEach(name => {
            $(`view-${name}`).classList.toggle('hidden', name !== view);
            document.querySelector(`[data-view="${name}"]`).classList.toggle('active', name === view);
        });
        $('page-title').textContent = {customers:'Clients',events:'Événements',segments:'Segmentation'}[view];
        if (view === 'customers' && !state.customersLoaded) loadCustomers();
        if (view === 'events' && !state.eventsLoaded) loadEvents();
    }

    document.querySelectorAll('[data-view]').forEach(button => {
        button.addEventListener('click', () => showView(button.dataset.view));
    });

    $('refresh-customers').addEventListener('click', () => loadCustomers(state.customersPage));
    $('refresh-events').addEventListener('click', () => loadEvents(state.eventsPage));
    $('customers-prev').addEventListener('click', () => loadCustomers(Math.max(1, state.customersPage - 1)));
    $('customers-next').addEventListener('click', () => loadCustomers(Math.min(state.customersLast, state.customersPage + 1)));
    $('events-prev').addEventListener('click', () => loadEvents(Math.max(1, state.eventsPage - 1)));
    $('events-next').addEventListener('click', () => loadEvents(Math.min(state.eventsLast, state.eventsPage + 1)));

    function addCondition() {
        const count = $('conditions').children.length;
        if (count >= 10) {
            showMessage('segments-message','Maximum : 10 conditions.');
            return;
        }
        const condition = document.createElement('div');
        condition.className = 'condition';
        const eventField = document.createElement('div');
        eventField.className = 'field';
        const eventLabel = document.createElement('label');
        eventLabel.textContent = 'Événement';
        const eventInput = document.createElement('input');
        eventInput.name = 'event'; eventInput.maxLength = 100; eventInput.required = true; eventInput.value = 'purchase';
        eventField.append(eventLabel,eventInput);

        const propertyField = document.createElement('div');
        propertyField.className = 'field';
        const propertyLabel = document.createElement('label');
        propertyLabel.textContent = 'Propriété JSON';
        const propertyInput = document.createElement('input');
        propertyInput.name = 'property'; propertyInput.maxLength = 100; propertyInput.pattern = '[a-zA-Z0-9_]+'; propertyInput.required = true; propertyInput.value = 'amount';
        propertyField.append(propertyLabel,propertyInput);

        const operatorField = document.createElement('div');
        operatorField.className = 'field';
        const operatorLabel = document.createElement('label');
        operatorLabel.textContent = 'Opérateur';
        const operatorSelect = document.createElement('select');
        operatorSelect.name = 'operator';
        ['>','=','!=','>=','<','<='].forEach(op => {
            const option = document.createElement('option'); option.value = op; option.textContent = op; operatorSelect.appendChild(option);
        });
        operatorField.append(operatorLabel,operatorSelect);

        const valueField = document.createElement('div');
        valueField.className = 'field';
        const valueLabel = document.createElement('label');
        valueLabel.textContent = 'Valeur';
        const valueInput = document.createElement('input');
        valueInput.name = 'value'; valueInput.required = true; valueInput.value = '100';
        valueField.append(valueLabel,valueInput);

        const remove = document.createElement('button');
        remove.type = 'button'; remove.className = 'remove'; remove.textContent = '×'; remove.setAttribute('aria-label','Supprimer cette condition');
        remove.addEventListener('click', () => { condition.remove(); showMessage('segments-message',''); });
        condition.append(eventField,propertyField,operatorField,valueField,remove);
        $('conditions').appendChild(condition);
        showMessage('segments-message','');
    }

    $('add-condition').addEventListener('click', addCondition);
    $('conditions').addEventListener('click', event => {
        if (event.target.closest('.remove')) {
            const condition = event.target.closest('.condition');
            if (condition && $('conditions').children.length > 1) condition.remove();
            else showMessage('segments-message','Conservez au moins une condition.');
        }
    });

    $('run-segment').addEventListener('click', async () => {
        const button = $('run-segment');
        const conditions = [];
        for (const element of $('conditions').querySelectorAll('.condition')) {
            const event = element.querySelector('[name="event"]').value.trim();
            const property = element.querySelector('[name="property"]').value.trim();
            const operator = element.querySelector('[name="operator"]').value;
            const rawValue = element.querySelector('[name="value"]').value.trim();
            if (!event || !property || !rawValue || !/^[a-zA-Z0-9_]+$/.test(property)) {
                showMessage('segments-message','Renseignez tous les champs. Le nom de propriété doit contenir uniquement des lettres, chiffres ou underscores.');
                return;
            }
            const valueInput = element.querySelector('[name="value"]');
            const value = valueInput.type === 'number' && rawValue !== '' ? Number(rawValue) : rawValue;
            conditions.push({event, property, operator, value});
        }

        button.disabled = true;
        button.textContent = 'Recherche en cours…';
        showMessage('segments-message','');
        $('segments-body').replaceChildren();
        try {
            const result = await api('/api/segments/query', {
                method: 'POST',
                body: JSON.stringify({conditions, limit: Number($('segment-limit').value)})
            });
            const body = $('segments-body');
            if (!result.data?.length) {
                const tr = row(['Aucun client ne correspond à ces conditions','','']);
                tr.firstChild.colSpan = 3; tr.firstChild.className = 'empty'; body.appendChild(tr);
            } else {
                result.data.forEach(c => body.appendChild(row([c.id, c.name || 'Sans nom', c.email])));
            }
            $('segment-count').textContent = Number(result.meta?.count ?? result.data?.length ?? 0).toLocaleString('fr-FR');
            $('segment-summary').textContent = `${result.meta?.count ?? result.data?.length ?? 0} client(s) trouvé(s) · ${conditions.length} condition(s) appliquée(s)`;
        } catch (error) {
            const tr = row([error.message,'','']);
            tr.firstChild.colSpan = 3; tr.firstChild.className = 'empty'; $('segments-body').appendChild(tr);
            showMessage('segments-message', `Échec de la segmentation : ${error.message}`);
        } finally {
            button.disabled = false;
            button.textContent = 'Exécuter la segmentation →';
        }
    });

    loadCustomers();
})();
</script>
</body>
</html>