<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a>
            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="dropdown">
            <a href="{{ route('accueil.admin') }}" class="nav-link">
                <i class="fa fa-home"></i><span>Accueil</span>
            </a>
        </li>
        <li class="dropdown">
            <a href="{{ route('etat-magasin.admin') }}" class="nav-link d-flex">
                <i class="fa fa-heartbeat"></i><span>Etat magasin</span>
                @if (magasinOk()->ok)
                    <span data-toggle="tooltip" title="Tout est en ordre" class="fa fa-check-circle text-success">
                    </span>
                @else
                    <span data-toggle="tooltip" title="Votre magasin n'est pas en bon état"
                        class="fa fa-exclamation-triangle text-danger">
                    </span>
                @endif
            </a>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-shopping-basket"></i><span>Vente</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('ventes-magasin.admin') }}">Afficher les ventes</a></li>
                <li><a class="nav-link" href="{{ route('ventes.admin') }}">Nouvelle vente</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-align-left"></i><span>Articles</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('articles.admin') }}">Afficher les articles</a></li>
                <li><a class="nav-link" href="{{ route('code-barre.admin') }}">Code barre</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-users"></i><span>Caissiers</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('cassier.admin') }}">Afficher les caissiers</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-cog"></i><span>Parametres</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('groupe-article.admin') }}">Groupes d'article</a></li>
                <li><a class="nav-link" href="{{ route('categore-article.admin') }}">Catégories d'articles</a>
                </li>
                <li><a class="nav-link" href="{{ route('unite-mesure.admin') }}">Unités de mesure</a></li>
                <li><a class="nav-link" href="{{ route('devise.admin') }}">Devise</a></li>
                <li><a class="nav-link" href="{{ route('compte.admin') }}">Compte</a></li>
            </ul>
        </li>
        @php
            $fs = facture_supprimee();
        @endphp
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fa fa-cogs"></i><span>Autres</span>
                @if ($fs)
                    <span data-toggle="tooltip"
                        title="Vous avez {{ $fs > 1 ? "$fs factures supprimées" : "$fs facture supprimée" }}"
                        class="fa fa-exclamation-triangle text-danger">
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('facture-supprimee.admin') }}">Factures supprimées</a>
                </li>
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
