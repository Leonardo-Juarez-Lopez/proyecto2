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
        contenido.classList.toggle('active');
        hamburger.classList.toggle('active');
    }
</script>