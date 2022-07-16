<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a>
            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="dropdown">
            <a href="{{ route('accueil.caissier') }}" class="nav-link">
                <i class="fa fa-home"></i><span>Accueil</span>
            </a>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-shopping-basket"></i><span>Vente</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('ventes-magasin.caissier') }}">Afficher les ventes</a>
                </li>
                <li><a class="nav-link" href="{{ route('ventes.caissier') }}">Nouvelle vente</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-align-left"></i><span>Articles</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('articles.caissier') }}">Afficher les articles</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-users"></i><span>Caissiers</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('cassier.caissier') }}">Afficher les caissiers</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-cog"></i><span>Parametres</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('compte.caissier') }}">Compte</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-sign-out-alt"></i><span>Se déconnecter</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('logout.web') }}">déconnexion</a></li>
            </ul>
        </li>
    </ul>
</aside>
