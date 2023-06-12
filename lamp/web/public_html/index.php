<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docker LAMP STACK</title>
    <link rel="stylesheet" href="/assets/css/bulma.min.css">
</head>

<body>
    <section class="hero is-medium is-bold is-primary">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    Docker LAMP STACK
                </h1>
                <h2 class="subtitle">
                    Your local development environment
                </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <h3 class="title is-3 has-text-centered">Environment</h3>
                    <hr>
                    <div class="content">
                        <ul>
                            <li><?= $_SERVER['SERVER_SOFTWARE']; ?></li>
                            <li>PHP <?= phpversion(); ?></li>
                            <li>
                                <?php
                                $link = mysqli_connect("database", "root", "deep70", null);

                                /* check connection */
                                if (!$link) {
                                    printf("MySQL connecttion failed: %s", mysqli_connect_error());
                                } else {
                                    /* print server version */
                                    printf("MySQL Server %s", mysqli_get_server_info($link));
                                }
                                /* close connection */
                                mysqli_close($link);
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="column">
                    <h3 class="title is-3 has-text-centered">Quick Links</h3>
                    <hr>
                    <div class="content">
                        <ul>
                            <li><a href="/phpinfo.php" target="_blank">phpinfo()</a></li>
                            <li><a href="http://localhost:8081" target="_blank">phpMyAdmin</a></li>
                            <li><a href="/test_db.php" target="_blank">Test DB Connection with mysqli</a></li>
                            <li><a href="/test_db_pdo.php" target="_blank">Test DB Connection with PDO</a></li>
                        </ul>
                    </div>
                </div>
                <?php 
                    $dirs = glob('../*', GLOB_ONLYDIR); 
                    $currentProtocol = "http://";
                    if (isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "on") {
                        $currentProtocol = "https://";
                    }
                    if(!empty($dirs)) {
                        foreach($dirs as $projectName) {
                            if($projectName != '../public_html') {
                                $projects[] = str_replace('../', '', $projectName);
                            }
                        }
                    }
                    $projects = array_slice($projects, 0, 10);
                ?>
                <div class="column">
                    <h3 class="title is-3 has-text-centered">Your Projects (Top 10)</h3>
                    <hr>
                    <div class="content">
                        <?php if(!empty($projects)) { ?>
                            <ul>
                                <?php foreach($projects as $project) { ?>
                                    <li><a href="<?php echo $currentProtocol.$project?>.localhost" target="_blank"><?php echo $project?>.localhost</li></a>
                                <?php } ?>
                            </ul>
                        <?php } else {
                            echo "No Projects Available!";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>