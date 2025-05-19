    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">NARET Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="visitor_stats.php">Website Visitors</a>
                    </li>
                    <li><a class="nav-link" href="view_customers.php">View Customers</a></li>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Manage Content
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="edit_header_content.php">Edit Headings</a></li>
                            <li><a class="dropdown-item" href="manage_content.php">Vision & Mission</a></li>
                            <li><a class="dropdown-item" href="videos.php">Videos</a></li>

                        </ul>
                    </li>

                   
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Call-to-Action Content
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="manage_cta_image.php">Call-to-Action Images</a></li>
                            <li><a class="dropdown-item" href="manage_cta_content.php">Call-to-Action Content</a></li>
                        </ul>
                    </li>

                       <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                           Manage About Contents
                        </a>
                        <ul class="dropdown-menu">
                         <li><a class="dropdown-item" href="manage_about.php">About Contents </a></li>
                            <li><a class="dropdown-item" href="manage_about_image.php"> About Image</a></li>
                            <li><a class="dropdown-item" href="manage_about_points.php"> About Points</a></li>


                        </ul>
                    </li>

                     
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Manage Others
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="manage_client_images.php">Manage Clients Images</a></li>
                            <li><a class="dropdown-item" href="manage_services_category.php">Manage Category</a></li>
                            <li><a class="dropdown-item" href="manage_fumigation.php">Manage Fumigation</a></li>
                            <li><a class="dropdown-item" href="manage_deci.php">Manage Container Desiccants</a></li>
                            <li><a class="dropdown-item" href="edit_deci_content.php">Edit Desiccants Header </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>