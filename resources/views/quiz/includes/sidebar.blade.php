<!-- Main Sidebar Container -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{ route('backend.dashboard') }}" class="brand-link">
        <img src="{{ asset('backend/dist/img/logo.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">iQurius</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @php
        $email = "vishal@tingmail.in";
        $default = "https://www.somewhere.com/homestar.jpg";
        $size = 40;
        $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
        @endphp
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $grav_url }}" class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->email }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('backend.dashboard') }}" class="nav-link {{ strcmp(url()->current(), route('backend.dashboard')) == 0 ? 'active' : '' }}">--}}
{{--                        <i class="nav-icon fas fa-tachometer-alt"></i>--}}
{{--                        <p>Dashboard</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-item {{ strcmp(url()->current(), route('backend.board.index')) == 0 ? 'menu-is-opening menu-open' : '' }}
                {{ strcmp(url()->current(), route('backend.grade.index')) == 0 ? 'menu-is-opening menu-open' : '' }}
                {{ strcmp(url()->current(), route('backend.subject.index')) == 0 ? 'menu-is-opening menu-open' : '' }}
                {{ strcmp(url()->current(), route('backend.chapter.index')) == 0 ? 'menu-is-opening menu-open' : '' }}
                {{ strcmp(url()->current(), route('backend.concept.index')) == 0 ? 'menu-is-opening menu-open' : '' }}
                {{ strcmp(url()->current(), route('backend.instruction.index')) == 0 ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ strcmp(url()->current(), route('backend.board.index')) == 0 ? 'active' : '' }}
                    {{ strcmp(url()->current(), route('backend.grade.index')) == 0 ? 'active' : '' }}
                    {{ strcmp(url()->current(), route('backend.subject.index')) == 0 ? 'active' : '' }}
                    {{ strcmp(url()->current(), route('backend.chapter.index')) == 0 ? 'active' : '' }}
                    {{ strcmp(url()->current(), route('backend.concept.index')) == 0 ? 'active' : '' }}
                    {{ strcmp(url()->current(), route('backend.instruction.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ strcmp(url()->current(), route('backend.board.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.grade.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.subject.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.chapter.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.concept.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.instruction.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.taxonomy.index')) == 0 ? 'display: block' : 'display: none' }}
                    {{ strcmp(url()->current(), route('backend.learning_stage.index')) == 0 ? 'display: block' : 'display: none' }};">
                        <li class="nav-item">
                            <a href="{{ route('backend.board.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.board.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Board</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.grade.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.grade.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Grade</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.subject.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.subject.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subject</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.chapter.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.chapter.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Chapter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.concept.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.concept.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Concept</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.instruction.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.instruction.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Instruction</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.taxonomy.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.taxonomy.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Taxonomy</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.learning_stage.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.learning_stage.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Learning Stage</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.power.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.power.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Powers</p>
                            </a>
                        </li>
                        @if(Auth::user()->hasRole("super-admin"))
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.admin.index')) == 0 ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.user') }}" class="nav-link {{ strcmp(url()->current(), route('backend.user')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.paragraph.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.paragraph.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-paragraph"></i>
                        <p>Paragraph</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.question.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.question.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>Question Bank</p>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('backend.question.index-para') }}" class="nav-link {{ strcmp(url()->current(), route('backend.question.index-para')) == 0 ? 'active' : '' }}">--}}
{{--                        <i class="nav-icon fas fa-money-check"></i>--}}
{{--                        <p>Paragraph Question</p>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nav-item">
                    <a href="{{ route('backend.content_library.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.content_library.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Content Library</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('backend.module.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.module.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>Modules</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('backend.test.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.test.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Test</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.event.index') }}" class="nav-link {{ strcmp(url()->current(), route('backend.event.index')) == 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-week"></i>
                        <p>Event</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
