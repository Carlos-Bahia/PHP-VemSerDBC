<header class="header-container">
    <div class="logo header-content">
        <img style="cursor: pointer" onclick="redirectToMain()" src="https://cdn.discordapp.com/attachments/1172171935900258375/1287538339326595083/dbc.png?ex=66f1e905&is=66f09785&hm=4a6607e17ed651d89e6cef9fc711e0c21db196339b12f62e2eb894221c11a258&" alt="Logo da DBC">
    </div>
    <div class="header-content menu-toggle" id="mobile-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
    <nav class="header-content menu-classic">
        <ul>
            <li><a href="<?php $_SERVER['SERVER_NAME']; ?>/Curriculum_Project/pages/curriculum.php" class="header-options">Currículos</a></li>
            <li><a href="<?php $_SERVER['SERVER_NAME']; ?>/Curriculum_Project/pages/job.php" class="header-options">Vagas de Emprego</a></li>
<!--            <li><a href="--><?php //$_SERVER['SERVER_NAME']; ?><!--/Curriculum_Project/pages/dashboard.php" class="header-options">Dashboard</a></li>-->
        </ul>
    </nav>
</header>

<script>
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.menu-classic');

    mobileMenu.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    function redirectToMain() {
        window.location.href = "<?php $_SERVER['SERVER_NAME']; ?>/Curriculum_Project/index.php";
    }
</script>