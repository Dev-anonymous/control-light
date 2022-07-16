<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                    <i data-feather="align-justify" class="text-danger"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize" class="text-danger"></i>
                </a>
            </li>
        </ul>
    </div>

    <ul class="navbar-nav navbar-right">
        @if (auth()->user()->user_role == 'admin')
            @php
                $fs = facture_supprimee();
            @endphp
            @if ($fs)
                <a href="{{ route('facture-supprimee.admin') }}" data-toggle="tooltip"
                    title="Vous avez {{ $fs > 1 ? "$fs factures supprimées" : "$fs facture supprimée" }}"
                    class="nav-link nav-link-lg mr-3">
                    <i data-feather="bell" class="bell"></i>
                    <span class="badge headerBadge1 bg-danger text-white font-weight-bold"
                        style="position:absolute;top:15px;font-weight: 300;padding: 3px 6px;">{{ $fs }}</span>
                </a>
            @endif
            @if (magasinOk()->ok == false)
                <a href="{{ route('etat-magasin.admin') }}" data-toggle="tooltip"
                    title="Votre magasin n'est pas en bon état" class="nav-link nav-link-lg mr-3">
                    <i data-feather="bell" class="bell"></i>
                    <span class="badge headerBadge1 bg-danger text-white font-weight-bold"
                        style="position:absolute;top:15px;font-weight: 300;padding: 3px 6px;">alerte</span>
                </a>
            @endif
        @endif
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <span class="text-muted"><i class="fa fa-user"></i> {{ auth()->user()->name }} </span>
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">{{ auth()->user()->name . ' (' . auth()->user()->user_role . ')' }}
                </div>
                <a href="{{ auth()->user()->user_role == 'admin' ? route('compte.admin') : route('compte.caissier') }}"
                    class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Compte
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout.web') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </li>
    </ul>
</nav>
