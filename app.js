// ============================================================
//  LÓGICA DEL FORMULARIO DE CONTACTO
//  Guarda los datos en Supabase y envía email via EmailJS
// ============================================================

const EMAILJS_SERVICE_ID  = 'service_kzf56rb';
const EMAILJS_TEMPLATE_ID = 'template_vaqupce';
const EMAILJS_PUBLIC_KEY  = 'WIc07wsaKP5j9HFhp';

const form       = document.getElementById('contactForm');
const alertEl    = document.getElementById('alert');
const submitBtn  = document.getElementById('submitBtn');
const btnText    = document.getElementById('btnText');
const btnSpinner = document.getElementById('btnSpinner');

/**
 * Muestra una alerta con estilo según el tipo.
 * @param {string} message
 * @param {'success'|'error'} type
 */
function showAlert(message, type) {
  alertEl.textContent = message;
  alertEl.className = 'mb-6 px-5 py-4 rounded text-sm font-medium';

  if (type === 'success') {
    alertEl.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
  } else {
    alertEl.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
  }

  alertEl.classList.remove('hidden');

  setTimeout(() => alertEl.classList.add('hidden'), 6000);
}

/**
 * Activa o desactiva el estado de carga del botón.
 * @param {boolean} loading
 */
function setLoading(loading) {
  submitBtn.disabled = loading;
  if (loading) {
    btnText.textContent = 'Enviando...';
    btnSpinner.classList.remove('hidden');
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
  } else {
    btnText.textContent = 'Enviar Mensaje';
    btnSpinner.classList.add('hidden');
    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
  }
}

/**
 * Maneja el envío del formulario.
 */
form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const nombre   = document.getElementById('nombre').value.trim();
  const apellido = document.getElementById('apellido').value.trim();
  const email    = document.getElementById('email').value.trim();
  const empresa  = document.getElementById('empresa').value.trim();
  const asunto   = document.getElementById('asunto').value;
  const mensaje  = document.getElementById('mensaje').value.trim();

  if (!nombre || !email || !asunto || !mensaje) {
    showAlert('Por favor completa todos los campos obligatorios.', 'error');
    return;
  }

  setLoading(true);

  try {
    // 1. Guardar en Supabase
    const { error: dbError } = await supabaseClient
      .from('mensajes')
      .insert([{
        nombre,
        apellido:  apellido || null,
        email,
        empresa:   empresa  || null,
        asunto,
        mensaje,
        creado_en: new Date().toISOString(),
      }]);

    if (dbError) {
      console.error('Error de Supabase:', dbError);
      showAlert(`Error al guardar el mensaje: ${dbError.message}`, 'error');
      return;
    }

    // 2. Enviar email via EmailJS
    await emailjs.send(
      EMAILJS_SERVICE_ID,
      EMAILJS_TEMPLATE_ID,
      {
        nombre,
        apellido:  apellido  || '—',
        email,
        empresa:   empresa   || '—',
        asunto,
        mensaje,
      },
      EMAILJS_PUBLIC_KEY
    );

    showAlert('✅ ¡Mensaje enviado correctamente! Te responderemos pronto.', 'success');
    form.reset();

  } catch (err) {
    console.error('Error inesperado:', err);
    showAlert('Ocurrió un error inesperado. Inténtalo de nuevo más tarde.', 'error');
  } finally {
    setLoading(false);
  }
});
