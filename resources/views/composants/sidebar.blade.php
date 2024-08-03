<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a>
            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
        </a>
    </div>
    <ul class="sidebar-menu">
        @if (in_array(auth()->user()->user_role, ['admin', 'gerant']))
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
                    <i class="fas fa-shopping-bag"></i><span>Gestion Production</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('ventes-magasin.admin') }}">Inventaire</a></li>
                    {{-- <li><a class="nav-link" href="{{ route('ventes.admin') }}">Nouvelle vente</a></li> --}}
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-dollar-sign"></i><span>Gestion Caisse</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('bonentree.common') }}">Bons d'entrée</a></li>
                    <li><a class="nav-link" href="{{ route('bonsortie.common') }}">Bons de sortie</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-align-left"></i><span>Articles</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('articles.admin') }}">Afficher les articles</a></li>
                    {{-- <li><a class="nav-link" href="{{ route('code-barre.admin') }}">Code barre</a></li> --}}
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-file"></i><span>Facturation</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('proforma') }}">Consulter factures</a></li>
                    <li><a class="nav-link" href="{{ route('proforma_default') }}">Editer facture</a></li>
                    <li><a class="nav-link" href="{{ route('proforma.modele') }}">Modèles des factures</a></li>
                </ul>
            </li>
            @if (in_array(auth()->user()->user_role, ['admin']))
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i class="fa fa-users"></i><span>Utilisateurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('cassier.admin') }}">Afficher les utilisateurs</a></li>
                    </ul>
                </li>
            @endif
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-user-check"></i><span>Clients</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('clients.admin') }}">Afficher les clients</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-cog"></i><span>Parametres</span>
                </a>
                <ul class="dropdown-menu">
                    @if (in_array(auth()->user()->user_role, ['admin']))
                        <li><a class="nav-link" href="{{ route('groupe-article.admin') }}">Groupes d'article</a></li>
                        <li><a class="nav-link" href="{{ route('categore-article.admin') }}">Catégories d'articles</a>
                        </li>
                        <li><a class="nav-link" href="{{ route('unite-mesure.admin') }}">Unités de mesure</a></li>
                        <li><a class="nav-link" href="{{ route('devise.admin') }}">Devise</a></li>
                    @endif
                    <li><a class="nav-link" href="{{ route('compte.admin') }}">Configuration</a></li>
                </ul>
            </li>
            @php
                // $fs = facture_supprimee();
            @endphp
            {{-- <li class="dropdown">
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
            </li> --}}
        @elseif (auth()->user()->user_role == 'caissier')
            {{-- <li class="dropdown">
                <a href="{{ route('accueil.caissier') }}" class="nav-link">
                    <i class="fa fa-home"></i><span>Accueil</span>
                </a>
            </li> --}}
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-shopping-basket"></i><span>Gestion Production</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('ventes-magasin.caissier') }}">Inventaire</a>
                    </li>
                    <li><a class="nav-link" href="{{ route('ventes.caissier') }}">Nouvelle vente</a></li>
                </ul>
            </li> --}}
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-file"></i><span>Facturation</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('proforma') }}">Consulter factures</a></li>
                    <li><a class="nav-link" href="{{ route('proforma_default') }}">Editer facture</a></li>
                    <li><a class="nav-link" href="{{ route('proforma.modele') }}">Modèles des factures</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-dollar-sign"></i><span>Gestion Caisse</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('bonentree.common') }}">Bons d'entrée</a></li>
                    <li><a class="nav-link" href="{{ route('bonsortie.common') }}">Bons de sortie</a></li>
                </ul>
            </li>
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-align-left"></i><span>Articles</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('articles.caissier') }}">Afficher les articles</a></li>
                </ul>
            </li> --}}
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-users"></i><span>Caissiers</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('cassier.caissier') }}">Afficher les caissiers</a></li>
                </ul>
            </li> --}}
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fa fa-cog"></i><span>Parametres</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('compte.caissier') }}">Configuration</a></li>
                </ul>
            </li>
        @endif
    </ul>
</aside>
