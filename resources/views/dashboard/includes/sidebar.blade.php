<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a href="{{route('admin.dashboard')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard</span></a>
            </li>

            <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="nav.users.main">Users</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('admin.users')}}" data-i18n="nav.users.users_contacts">Users List</a>
                    </li>
                    <li><a class="menu-item" href="{{route('admin.user.create')}}" data-i18n="nav.users.user_cards">Add user</a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="la la-book"></i><span class="menu-title" data-i18n="nav.users.main">Books</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('admin.books')}}" data-i18n="nav.users.users_contacts">Books List</a>
                    </li>
                    <li><a class="menu-item" href="{{route('admin.book.create')}}" data-i18n="nav.users.user_cards">Add book</a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="la la-male"></i><span class="menu-title" data-i18n="nav.users.main">Authors</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('admin.authors')}}" data-i18n="nav.users.users_contacts">Authors List</a>
                    </li>
                    <li><a class="menu-item" href="{{route('admin.author.create')}}" data-i18n="nav.users.user_cards">Add Author</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
