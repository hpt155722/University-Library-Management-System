<?php
include("../../connection.php");

$response = [];

// Retrieve form data
$bookName = $_POST['bookName'];
$ISBN = $_POST['ISBN'];
$bookDescription = $_POST['bookDescription'];
$bookGenre = $_POST['bookGenre'];
$author = $_POST['author'];
$publicationCompany = $_POST['publicationCompany'];
$publishedDate = $_POST['publishedDate'];
$paperbackCopiesAvailable = $_POST['paperbackCopiesAvailable'];
$paperbackCopyValue = $_POST['paperbackCopyValue'];
$hardbackCopiesAvailable = $_POST['hardbackCopiesAvailable'];
$hardbackCopyValue = $_POST['hardbackCopyValue'];
$coverImage = $_FILES['coverImage']['name']; // For file uploads

// Verify ISBN
if (!preg_match('/^[0-9]{13}$/', $ISBN)) {
    echo "Invalid ISBN.";
} else if ($paperbackCopiesAvailable == 0 && $hardbackCopiesAvailable == 0) {
    echo "At least one book copy needs to be added.";
} else if (strtotime($publishedDate) > strtotime(date('Y-m-d'))) {
    echo "Publication date needs to have been in the past";
} else {
    try {
        // First, check if the author already exists
        $authorQuery = "SELECT * FROM authors WHERE authorName = '$author'";
        $authorResult = mysqli_query($conn, $authorQuery);
        if (!$authorResult) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($authorResult) == 0) {
            // Author doesn't exist, so add them to the authors table
            $addAuthorQuery = "INSERT INTO authors (authorName) VALUES ('$author')";
            $addAuthorResult = mysqli_query($conn, $addAuthorQuery);
            if (!$addAuthorResult) {
                die("Query failed: " . mysqli_error($conn));
            }
            // Get the auto-generated authorID
            $authorID = mysqli_insert_id($conn);
        } else {
            // Author already exists, retrieve their authorID
            $authorRow = mysqli_fetch_assoc($authorResult);
            $authorID = $authorRow['authorID'];
        }

        // Check if a file was uploaded
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
            $targetDir = "../../main resources/item covers/bookCovers/";
            $targetFile = $targetDir . basename($_FILES['coverImage']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Generate a unique image ID (you can use any method you prefer)
            $uniqueImageID = uniqid();

            // Construct a new file path with the unique image ID
            $newFilePath = $targetDir . $uniqueImageID . '.' . $imageFileType;
            // Move the uploaded file to the new location
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $newFilePath)) {
                // File was uploaded successfully, now insert the book record
                $coverImagePath = '/main resources/item covers/bookCovers/' . $uniqueImageID . '.' . $imageFileType;
                $insertBookQuery = "INSERT INTO books (bookName, ISBN, publicationCompany, coverFilePath, genre) 
                                    VALUES ('$bookName', '$ISBN', '$publicationCompany', '$coverImagePath', '$bookGenre')";
                //Run insert book query
                $insertBookResult = mysqli_query($conn, $insertBookQuery);
                if (!$insertBookResult) {
                    throw new Exception("Error adding book: " . mysqli_error($conn));
                }

                if ($insertBookResult) {
                    // Get the auto-generated bookID
                    $bookID = mysqli_insert_id($conn);

                    // Add written by author-book relationship
                    $addWrittenByRelationship = "INSERT INTO writtenby (authorID, bookID) VALUES ('$authorID ', '$bookID ')";
                    $writtenByRelationshipResult = mysqli_query($conn, $addWrittenByRelationship);

                    // Construct the item description SQL query
                    $escapedBookDescription = mysqli_real_escape_string($conn, $bookDescription);

                    $descriptionInsert = "INSERT INTO item_description (itemType, itemID, description) VALUES ('book', '$bookID', '$escapedBookDescription')";
                    // Execute the descriptionInsert query
                    $descriptionInsertResult = mysqli_query($conn, $descriptionInsert);

                    if (!$descriptionInsertResult) {
                        // If the query failed, output the error message
                        die("Query failed: " . mysqli_error($conn));
                    }

                    // Add paperback copies
                    for ($i = 0; $i < $paperbackCopiesAvailable; $i++) {
                        $addCopyQuery = "INSERT INTO bookcopy (bookID, publishedDate, addDate, available, value, coverType) 
                        VALUES ('$bookID', '$publishedDate', NOW(), 1, '$paperbackCopyValue', 'paperback')";
                        $addCopyResult = mysqli_query($conn, $addCopyQuery);
                        if (!$addCopyResult) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                    }

                    // Add hardback copies
                    for ($i = 0; $i < $hardbackCopiesAvailable; $i++) {
                        $addCopyQuery = "INSERT INTO bookcopy (bookID, publishedDate, addDate, available, value, coverType) 
                        VALUES ('$bookID', '$publishedDate', NOW(), 1, '$hardbackCopyValue', 'hardback')";
                        $addCopyResult = mysqli_query($conn, $addCopyQuery);
                        if (!$addCopyResult) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                    }
                    $response['success'] = true;
                    echo "Book(s) added successfully";
                }
            }
        }
    } catch (Exception $e) {
        $response['error'] = true;
        echo $e->getMessage();
    }
}
?>
