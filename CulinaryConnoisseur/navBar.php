<?php

/*
 * PHP Taskbar
   defines links in an array
  checks the current filename to add an 'active' class for styling.
 */

// Define your 6 links here: "Label" => "filename.php"
$navLinks = [
    "Home"      => "CullConn_index.php",
    "Book Events"     => "BookClientCateringEvent.php",
    "Cancel Events"  => "CancelClientCateringEvent.php",
    "Create Client Account"  => "CreateClientAccount.php",
    "Blog"      => "UpdateAdditionalCateringServices.php",
    "Contact"   => "RequestAdditionalServices.php"
];

// Get the current script name (e.g., 'index.php') to set active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="taskbar-container">
    <nav class="taskbar">
        <div class="logo">MyWebsite</div>
        <ul class="nav-links">
            <?php foreach ($navLinks as $name => $url): ?>
                <?php 
                    // Check if this link matches the current page
                    $activeClass = ($current_page == $url) ? 'active' : ''; 
                ?>
                <li>
                    <a href="<?php echo htmlspecialchars($url); ?>" class="<?php echo $activeClass; ?>">
                        <?php echo htmlspecialchars($name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>