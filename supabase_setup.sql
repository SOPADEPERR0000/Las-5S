-- ============================================================
--  SCRIPT SQL PARA SUPABASE
--  Ejecuta esto en el SQL Editor de tu proyecto Supabase:
--  https://supabase.com/dashboard → SQL Editor → New query
-- ============================================================

-- 1. Crear la tabla de mensajes
CREATE TABLE IF NOT EXISTS public.mensajes (
  id         BIGSERIAL PRIMARY KEY,
  nombre     TEXT        NOT NULL,
  apellido   TEXT,
  email      TEXT        NOT NULL,
  empresa    TEXT,
  asunto     TEXT        NOT NULL,
  mensaje    TEXT        NOT NULL,
  creado_en  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- 2. Habilitar Row Level Security (RLS)
ALTER TABLE public.mensajes ENABLE ROW LEVEL SECURITY;

-- 3. Política: cualquier usuario anónimo puede INSERTAR mensajes
--    (necesario para que el formulario público funcione)
CREATE POLICY "Permitir inserción pública"
  ON public.mensajes
  FOR INSERT
  TO anon
  WITH CHECK (true);

-- 4. Política: solo usuarios autenticados (admin) pueden LEER mensajes
CREATE POLICY "Solo admins pueden leer"
  ON public.mensajes
  FOR SELECT
  TO authenticated
  USING (true);

-- 5. Índice para búsquedas por email
CREATE INDEX IF NOT EXISTS idx_mensajes_email ON public.mensajes (email);

-- ============================================================
--  VERIFICAR QUE TODO ESTÁ CORRECTO
-- ============================================================
SELECT column_name, data_type, is_nullable
FROM information_schema.columns
WHERE table_name = 'mensajes'
ORDER BY ordinal_position;
