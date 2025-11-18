document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login');
    const errorLabel = form.querySelector('.Error');
    const submitBtn = form.querySelector('input[type="submit"]'); 
    const email = document.getElementById("Email");
    const password =document.getElementById("password");
    const eye = document.getElementById("eye");

    const MAX_WARNING = 10;
    const MAX_BLOCK = 15;
    const BLOCK_TIME = 15 * 60 * 1000; // 15 minutos

    errorLabel.style.display = 'none';

    // Obtener info de localStorage
    function getLoginData() {
        return {
            attempts: parseInt(localStorage.getItem('loginAttempts')) || 0,
            blockUntil: parseInt(localStorage.getItem('blockUntil')) || 0
        };
    }

    function setLoginData(attempts, blockUntil = 0) {
        localStorage.setItem('loginAttempts', attempts);
        localStorage.setItem('blockUntil', blockUntil);
    }

    function checkBlockStatus() {
        const { blockUntil } = getLoginData();
        const now = Date.now();

        if (now < blockUntil) {
            const remaining = Math.ceil((blockUntil - now) / 60000);
            showError(`Has superado los intentos permitidos. Intenta en ${remaining} min.`);
            disableForm(true);
            return true;
        } else {
            disableForm(false);
            return false;
        }
    }

    function disableForm(state) {
        submitBtn.disabled = state;
    }
    function animateError() {
    errorLabel.classList.remove("shake");
    void errorLabel.offsetWidth;
    errorLabel.classList.add("shake");
   }

    function showError(msg) {
        errorLabel.textContent = msg;
        errorLabel.style.display = 'block';
        animateError();
    }

    form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (checkBlockStatus()) return;

    if (email.value.trim() === "" || password.value.trim() === "") {
        showError("Debes completar todos los campos.");
        return;
    }

    const { attempts } = getLoginData();

    const originalText = submitBtn.value;
    submitBtn.value = "Cargando...";
    submitBtn.disabled = true;

    try {
        const formData = new FormData(form);

        const response = await fetch('/login', {
            method: "POST",
            body: formData
        });

        if (!response.headers.get("content-type")?.includes("application/json")) {
            throw new Error("Respuesta del servidor inválida");
        }

        const result = await response.json();

if (result.success) {
    setLoginData(0);

    if (!result.tienePago && result.rol !== "administrador") {
        window.location.href = "/pagoInicial";
        return; // ✅ IMPORTANTE
    }

    window.location.href = (result.rol === "administrador")
        ? "/dashboard-admin"
        : "/dashboard-usuario";
    
    return; // ✅ ESTE return evita que siga al finally
}

 else {
    const newAttempts = attempts + 1;
    setLoginData(newAttempts);

    // Si el servidor indica que el usuario no está autorizado, mostrar ese mensaje exacto
    if (result.error === "Usuario no autorizado. Contacte al backoffice.") {
        showError(result.error);
        return;  
    }

    if (newAttempts >= MAX_BLOCK) {
        const blockUntil = Date.now() + BLOCK_TIME;
        setLoginData(newAttempts, blockUntil);
        showError("Demasiados intentos fallidos. Cuenta bloqueada por 15 min.");
        disableForm(true);
    } else if (newAttempts >= MAX_WARNING) {
        showError(`Advertencia: ${newAttempts}/${MAX_BLOCK} intentos.`);
    } else {
        showError(result.error || "Credenciales incorrectas. Inténtalo de nuevo.");
    }
}


    } catch (err) {
        console.error("Error en la petición:", err);
        showError("Error en el servidor");
    } finally {
        submitBtn.value = originalText;
        if (!checkBlockStatus()) submitBtn.disabled = false;
    }
});


eye.addEventListener("click", () => {
    if (password.type === "password") {
        password.type = "text";
        eye.src = "imagenes/ojo.png"; // ojo abierto
    } else {
        password.type = "password";
        eye.src = "imagenes/ojo-apagado.png"; // ojo apagado
    }
});

    // Al cargar la página verificar si está bloqueado
    checkBlockStatus();
});
