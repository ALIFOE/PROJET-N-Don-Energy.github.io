<style>
    /* Navigation administrative */
    nav.admin-nav {        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 200px;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.3) !important;
        padding: 1rem 0;
        overflow-y: auto;
        z-index: 50;
    }

    /* Container des liens */
    .admin-nav .md\:flex {
        flex-direction: column;
        width: 100%;
    }    /* Liens du menu */
    .navbar-link {
        color: #1a1a1a;
        transition: all 0.3s ease;
        position: relative;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .navbar-link i {
        width: 20px;
        margin-right: 0.75rem;
        font-size: 1rem;
    }

    .navbar-link::after {
        content: '';
        position: absolute;
        width: 3px;
        height: 0;
        right: 0;
        background-color: var(--primary-color, #FFA500);
        transition: height 0.3s ease;
    }

    .navbar-link:hover {
        background-color: rgba(255, 165, 0, 0.1);
    }

    .navbar-link:hover::after {
        height: 100%;
    }

    .navbar-link.active {
        background-color: rgba(255, 165, 0, 0.15);
    }

    .navbar-link.active::after {
        height: 100%;
    }

    /* Menus déroulants */
    .dropdown-menu {
        position: relative !important;
        width: 100% !important;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border-left: 3px solid rgba(255, 165, 0, 0.5);
        box-shadow: none;
        margin-left: 1rem;
        margin-top: 0 !important;
    }    .dropdown-item {
        padding: 0.4rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.75rem;
    }

    .dropdown-item:hover {
        background: rgba(255, 165, 0, 0.1);
    }

    /* Ajustement du contenu principal */    main {
        margin-left: 200px;
        padding: 1rem;
    }
</style>
