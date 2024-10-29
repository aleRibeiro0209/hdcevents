$(document).ready(() => {

    // Constantes - Logout
    const btnLogout = $('#btn-logout');

    // Realizar logout ao clicar no botão
    btnLogout.on('click', (e) => {
        e.preventDefault();
        e.currentTarget.closest('form').submit();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Constantes - Tema
    const btnThemeMode = $('#toggle-theme button');
    const IconThemeMode = $('#icon-theme-mode');

    // Verifica o tema salvo no LocalStorage (se houver)
    let theme = localStorage.getItem("theme") || "light";
    setTheme(theme);

    // Alterna o tema ao clicar no botão
    btnThemeMode.on('click', (e) => {
        theme = $(e.currentTarget).data('theme');
        setTheme(theme);
    });

    // Constantes - Idioma
    const btnIdiom = $('#toggle-idiom button');

    // Alterna o idioma ao clicar no botão
    btnIdiom.on('click', (e) => {
        selecionarButton(e.currentTarget);
    });

    /**
     * Métodos utilitários
     */

    // Função para adicionar estilo no botão selecionado
    function selecionarButton(obj){
        $(obj).closest('ul').find('.active [name="checkmark"]').addClass('d-none');
        $(obj).closest('ul').find('.active').removeClass('active');
        $(obj).addClass('active');
        $(obj).find('[name="checkmark"]').removeClass('d-none');
    };

    // Função para setar o tema
    function setTheme(theme) {
        let btnTheme = $(`[data-theme="${theme}"]`);
        let iconCurrent = btnTheme.find('ion-icon').attr('name');
        IconThemeMode.attr('name', iconCurrent);

        // Define se o tema é escuro ou claro
        const isDark = theme === "dark" ||
                    (theme === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches);

        isDark ? $('body').attr('data-bs-theme', 'dark') : $('body').attr('data-bs-theme', 'light');
        $("#navbar a, h5, p, #navbar button").toggleClass("text-light", isDark);
        $(".msg, .msg-erro, #mensagem").removeClass("text-light");

        // Salva a preferência no LocalStorage
        localStorage.setItem("theme", theme);
        selecionarButton(btnTheme);
    }
});
