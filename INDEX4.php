<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de envíos">
    <title>EnvíaFácil | Gestión de envíos</title>
    <style>
        :root {
            --azul: #1358d1;
            --azul-oscuro: #102a65;
            --celeste: #eaf2ff;
            --texto: #19233a;
            --muted: #68748a;
            --borde: #dfe5ef;
            --fondo: #041c3c;
            --blanco: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--texto);
            background: var(--fondo);
        }

        .barra-superior {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px max(5%, 30px);
            color: white;
            background: var(--azul-oscuro);
        }

        .marca { display: flex; align-items: center; gap: 11px; font-size: 21px; font-weight: 800; }
        .logo { display: grid; place-items: center; width: 35px; height: 35px; border-radius: 10px; background: #2d77ff; font-size: 19px; }
        .usuario { font-size: 14px; color: #dce8ff; }

        main { width: min(1120px, 92%); margin: 36px auto 60px; }

        .encabezado { display: flex; justify-content: space-between; gap: 20px; align-items: end; margin-bottom: 25px; }
        h1 { margin: 0 0 7px; font-size: clamp(27px, 4vw, 36px); letter-spacing: -1px; }
        .encabezado p { margin: 0; color: var(--muted); }
        .fecha { padding: 9px 13px; border: 1px solid var(--borde); border-radius: 8px; color: var(--muted); background: white; font-size: 14px; white-space: nowrap; }

        .paneles { display: grid; grid-template-columns: minmax(300px, 0.95fr) minmax(360px, 1.45fr); gap: 24px; align-items: start; }
        .tarjeta { padding: 27px; border: 1px solid var(--borde); border-radius: 16px; background: var(--blanco); box-shadow: 0 8px 24px rgba(25, 47, 91, .06); }
        .tarjeta h2 { margin: 0 0 7px; font-size: 21px; }
        .tarjeta > p { margin: 0 0 23px; color: var(--muted); font-size: 14px; }

        label { display: block; margin: 0 0 7px; color: #34415a; font-size: 14px; font-weight: 650; }
        input {
            width: 100%; padding: 12px 13px; margin-bottom: 17px;
            border: 1px solid #ccd5e4; border-radius: 8px; color: var(--texto);
            background: #fff; font: inherit; outline: none;
        }
        input:focus { border-color: var(--azul); box-shadow: 0 0 0 3px rgba(19, 88, 209, .13); }
        input::placeholder { color: #9aa5b5; }

        button {
            width: 100%; padding: 13px 18px; border: 0; border-radius: 8px;
            color: rgb(29, 34, 167); background: var(--azul); cursor: pointer; font: 700 15px inherit;
            transition: background .2s, transform .2s;
        }
        button:hover { background: #0c48b5; transform: translateY(-1px); }

        .lista-cabecera { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
        .contador { padding: 5px 10px; border-radius: 20px; color: var(--azul); background: var(--celeste); font-size: 13px; font-weight: 700; }
        .tabla-contenedor { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 530px; font-size: 14px; }
        th { padding: 0 10px 12px; border-bottom: 1px solid var(--borde); color: var(--muted); text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        td { padding: 15px 10px; border-bottom: 1px solid #edf0f5; }
        tr:last-child td { border-bottom: 0; }
        .destinatario { display: flex; align-items: center; gap: 10px; font-weight: 650; }
        .avatar { display: grid; place-items: center; flex: 0 0 32px; width: 32px; height: 32px; border-radius: 50%; color: #1256cb; background: #dbe9ff; font-size: 12px; font-weight: 800; }
        .estado { padding: 5px 9px; border-radius: 20px; color: #08733e; background: #e1f8ed; font-size: 12px; font-weight: 700; }
        .vacio { padding: 32px 12px 12px; color: var(--muted); text-align: center; }

        .nota { margin-top: 23px; padding: 15px 17px; border-left: 4px solid #5a91ee; border-radius: 7px; color: #48617f; background: #eaf2ff; font-size: 13px; line-height: 1.5; }
        .nota code { color: #103a86; font-weight: 700; }

        @media (max-width: 780px) {
            .encabezado { align-items: start; flex-direction: column; }
            .paneles { grid-template-columns: 1fr; }
            .tarjeta { padding: 21px; }
            .usuario { display: none; }
        }
    </style>
</head>
<body>
    <header class="barra-superior">
        <div class="marca"><span class="logo">↗</span> EnvíaFácil</div>
        <span class="usuario">Panel de administración</span>
    </header>

    <main>
        <div id="mensaje" role="status" aria-live="polite"></div>
        <section class="encabezado">
            <div>
                <h1>Gestión de envíos</h1>
                <p>Registra y consulta la información de cada envío.</p>
            </div>
            <span class="fecha" id="fechaActual"></span>
        </section>

        <section class="paneles">
            <article class="tarjeta">
                <h2>Nuevo envío</h2>
                <p>Completa los datos para registrar un envío.</p>

                <form action="guardar_envio.php" method="post">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" placeholder="Ej. Juan Pérez" required maxlength="100">

                    <label for="correo">Correo electrónico</label>
                    <input id="correo" name="correo" type="email" placeholder="juan@correo.com" required maxlength="120">

                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" type="tel" placeholder="Ej. 300 123 4567" required maxlength="20">

                    <label for="destinatario">Destinatario</label>
                    <input id="destinatario" name="destinatario" type="text" placeholder="Nombre de quien recibe" required maxlength="100">

                    <button type="submit">Registrar envío</button>
                </form>
            </article>

            <article class="tarjeta">
                <div class="lista-cabecera">
                    <div>
                        <h2>Envíos recientes</h2>
                        <p>Los registros guardados aparecerán aquí.</p>
                    </div>
                    <span class="contador">0 envíos</span>
                </div>

                <div class="tabla-contenedor">
                    <table>
                        <thead>
                            <tr><th>Remitente</th><th>Destinatario</th><th>Contacto</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="vacio">Aún no hay envíos registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="nota">
                    Este formulario envía los datos por <code>POST</code> a <code>guardar_envio.php</code>. Allí debes conectar PHP con MySQL y guardar los campos en la tabla <code>envios</code>.
                </div>
            </article>
        </section>
    </main>

    <script>
        const fecha = new Intl.DateTimeFormat('es-CO', { dateStyle: 'full' }).format(new Date());
        document.getElementById('fechaActual').textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1);

        const estado = new URLSearchParams(window.location.search).get('estado');
        if (estado) {
            const mensaje = document.getElementById('mensaje');
            const correcto = estado === 'guardado';
            mensaje.textContent = correcto ? '✓ El envío fue registrado correctamente.' : 'No fue posible registrar el envío. Revisa los datos e inténtalo otra vez.';
            mensaje.style.cssText = `margin-bottom:20px;padding:13px 16px;border-radius:8px;font-weight:600;color:${correcto ? '#08733e' : '#a51d2d'};background:${correcto ? '#e1f8ed' : '#ffe8eb'};`;
            window.history.replaceState({}, '', window.location.pathname);
        }
    </script>
</body>
</html>
