<?php
// Include necessary files
include_once $_SERVER['DOCUMENT_ROOT'] . '/Mini-PHP-Project/carya.tn/src/Lib/connect.php';

class Car {
    // Properties
    public $id;
    public $brand;
    public $model;
    public $color;
    public $image;
    public $km;
    public $price;
    public $owner_id;

    // Constructor
    public function __construct($id, $brand, $model, $color, $image, $km, $price, $owner_id) {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->image = $image;
        $this->km = $km;
        $this->price = $price;
        $this->owner_id = $owner_id;
    }

    // Get a car object from the sql result
    public static function getCarFromRow($row) {
        return new Car(
            $row['id'],
            $row['brand'],
            $row['model'],
            $row['color'],
            $row['image'],
            $row['km'],
            $row['price'],
            $row['owner_id']
        );
    }

    // Method to get all cars
    public static function getAllCars() {
        global $pdo;
        try {
            $sql = "SELECT * FROM cars";
            $stmt = $pdo->query($sql);
            $cars = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $car = Car::getCarFromRow($row);
                $cars[] = $car;
            }
            return $cars;
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error fetching cars: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Method to delete a car by ID
    public function deleteCarById() {
        $carId = $this->id;
        global $pdo;
        try {
            $sql = "DELETE FROM cars WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$carId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error deleting car: " . $e->getMessage());
            throw $e;
        }
    }

    // Method to get a car by ID
    public static function getCarById($car_id) {
        global $pdo;
        try {
            $sql = "SELECT * FROM cars WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$car_id]);
            $car_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($car_data) {
                $car = Car::getCarFromRow($car_data);
                return $car;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error fetching car details: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Method to update car details
    public function updateCar($brand, $model, $color, $image, $km, $price) {
        global $pdo;
        try {
            $sql = "UPDATE cars SET brand = ?, model = ?, color = ?, image = ?, km = ?, price = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$brand, $model, $color, $image, $km, $price, $this->id]);
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error updating car details: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Method to add a new car
    public static function addCar($brand, $model, $color, $image, $km, $price, $owner_id) {
        global $pdo;
        try {
            $sql = "INSERT INTO cars (brand, model, color, image, km, price, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$brand, $model, $color, $image, $km, $price, $owner_id]);
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error adding new car: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Method to check if the car is available
    public function isCarAvailable() {
        global $pdo;
        try {
            $current_date = date('Y-m-d');
            $car_id = $this->id;
            $car_commanded_sql = "SELECT * FROM command WHERE car_id=? AND start_date <= ? AND end_date >= ?";
            $car_commanded_stmt = $pdo->prepare($car_commanded_sql);
            $car_commanded_stmt->execute([$car_id, $current_date, $current_date]);
            $car_commanded = $car_commanded_stmt->fetchAll(PDO::FETCH_ASSOC);
            return count($car_commanded) == 0;
        } catch (PDOException $e) {
            // Log error and rethrow the exception
            error_log("Error checking car availability: " . $e->getMessage());
            throw $e;
        }
    }

    // Method to display car availability actions
    public function displayCarAvailabilityActions() {
        try {
            if ($this->isCarAvailable()) {
                echo "<a href='http://localhost/Mini-PHP-Project/carya.tn/src/controllers/delete_car.php?id={$this->id}'>Delete</a>";
                if ($this->owner_id == $_SESSION['user_id']) {
                    echo " | <a href='http://localhost/Mini-PHP-Project/carya.tn/templates/update_car_form.php?id={$this->id}'>Update</a>";
                }
            } else {
                echo "Car is in use";
            }
        } catch (Exception $e) {
            // Log error and rethrow the exception
            error_log("Error displaying car availability actions: " . $e->getMessage());
            throw $e;
        }
    }
}
?>
