<?php
$title = "Carya.tn";
$class = ""
    ?>

<?php ob_start();
session_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="home-container">
    <div class="hero">
        <div class="text-content">
            <h2>Find Your Perfect Ride</h2>
            <p class="block-text">
                Explore our wide range of rental cars at affordable prices.
                Discover our extensive selection of rental cars,
                each one offered at competitive prices.
                Whether you're looking for a compact car for city driving,
                a comfortable sedan for business trips,
                or a spacious SUV for family vacations,
                we have the perfect vehicle to meet your needs and fit your budget.
            </p>
        </div>
        <img src="/Mini-PHP-Project/carya.tn/Resources/car_images/m2.jpg" alt="car" id="home-image">
    </div>

    <div class="car-card">
        <?php

        echo '<a href="/Mini-PHP-Project/carya.tn/Templates/rent_car.php" class="btn">Rent Now</a>';

        ?>
    </div>

    <footer>
        <p> 2024 Rent-a-Car. All rights reserved.</p>
    </footer>
</div>


<?php $content = ob_get_clean(); ?>

<?php require ('layout.php') ?>