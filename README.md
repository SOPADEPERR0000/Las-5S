# Página Web — Metodología 5S

Página web informativa sobre la metodología 5S con formulario de contacto conectado a **Supabase**.

## Estructura de archivos

```
├── index.html          # Página principal
├── supabase.js         # Configuración del cliente Supabase
├── app.js              # Lógica del formulario de contacto
├── supabase_setup.sql  # Script SQL para crear la tabla en Supabase
└── README.md           # Este archivo
```

---

## Configuración de Supabase

### Paso 1 — Crear proyecto en Supabase

1. Ve a [https://supabase.com](https://supabase.com) y crea una cuenta gratuita.
2. Crea un nuevo proyecto.
3. Anota la **URL del proyecto** y la **clave anon/public**.

### Paso 2 — Crear la tabla

1. En el dashboard de Supabase, ve a **SQL Editor → New query**.
2. Copia y pega el contenido de `supabase_setup.sql`.
3. Ejecuta el script.

### Paso 3 — Conectar la página

Abre `supabase.js` y reemplaza los valores:

```js
const SUPABASE_URL      = 'https://TU_PROYECTO.supabase.co';
const SUPABASE_ANON_KEY = 'TU_ANON_KEY_AQUI';
```

Puedes encontrar estos valores en:
**Supabase Dashboard → Settings → API**

---

## Cómo abrir la página

Simplemente abre `index.html` en tu navegador, o usa una extensión como **Live Server** en VS Code.

---

## Tecnologías usadas

| Tecnología | Uso |
|---|---|
| HTML5 | Estructura de la página |
| Tailwind CSS (CDN) | Estilos y diseño responsivo |
| JavaScript (Vanilla) | Lógica del formulario |
| Supabase JS v2 | Base de datos y API |
