document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("cadastroForm");

    form.addEventListener("submit", function (event) {
        let isValid = true;

        const nome = document.getElementById("nome").value.trim();
        const sobrenome = document.getElementById("sobrenome").value.trim();
        const email = document.getElementById("email").value.trim();
        const senha = document.getElementById("senha").value;
        const data = document.getElementById("data").value;
        const telefone = document.getElementById("telefone").value.replace(/\D/g, "");
        const cep = document.getElementById("cep").value.replace(/\D/g, "");

        // Valida Nome
        if (nome.length < 2) {
            alert("Por favor, insira um nome válido.");
            isValid = false;
        }

        // Valida Sobrenome
        if (sobrenome.length < 2) {
            alert("Por favor, insira um sobrenome válido.");
            isValid = false;
        }

        // Valida E-mail
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Por favor, insira um e-mail válido.");
            isValid = false;
        }

        // Valida Senha
        if (senha.length < 6) {
            alert("A senha deve ter pelo menos 6 caracteres.");
            isValid = false;
        }

        // Valida Data
        const dataRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dataRegex.test(data)) {
            alert("Por favor, selecione uma data válida.");
            isValid = false;
        }

        // Valida Telefone (pelo menos 10 dígitos)
        if (telefone.length < 10) {
            alert("Por favor, insira um telefone válido.");
            isValid = false;
        }

        // Valida CEP (8 dígitos)
        if (cep.length !== 8) {
            alert("Por favor, insira um CEP válido (8 dígitos).");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});