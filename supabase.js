// ============================================================
//  CONFIGURACIÓN DE SUPABASE
//  Reemplaza los valores de SUPABASE_URL y SUPABASE_ANON_KEY
//  con los de tu proyecto en https://supabase.com/dashboard
// ============================================================

const SUPABASE_URL     = 'https://nxkhofswokhywvyfjpbu.supabase.co';   // <-- cambia esto
const SUPABASE_ANON_KEY = 'sb_publishable_unR5YXxSVIqxe1n8UWYHJA_lzZYsZeN';                 // <-- cambia esto

// Inicializar cliente de Supabase
const { createClient } = supabase;
const supabaseClient   = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
