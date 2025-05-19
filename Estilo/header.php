<!-- header.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CineGest</title>
<link rel="stylesheet" href="styles.css">
<script>
    function toggleMenu() {
        const menu = document.getElementById('menu');
        const contenido = document.getElementById('contenido');
        const hamburger = document.querySelector('.hamburger');
        
        menu.classList.toggle('active');
        if(contenido) contenido.classList.toggle('active');
        hamburger.classList.toggle('active');
        
        // Bloquear scroll cuando el menú está abierto
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }
</script>