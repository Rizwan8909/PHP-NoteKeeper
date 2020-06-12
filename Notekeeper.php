<?php
$insert = false;
$update = false;
$delete = false;
$servername = 'localhost';
$username = 'root';
$password = '';
$databasename = 'notekeeper';

$conn = mysqli_connect($servername, $username, $password, $databasename);

if (!$conn) {
  die("Some thing went wrong!" . mysqli_connect_error());
}


if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    //  Update the record
    $title = $_POST['titleEdit'];
    $desc = $_POST['descEdit'];
    $sno = $_POST['snoEdit'];

    // SQL QUERY to be executed and stored in the result variable
    $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$desc' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $update = true;
    } else {
      echo "We could not update the record successfully";
    }
  } else {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    // SQL QUERY to be executed and stored in the result variable
    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$desc')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $insert = true;
    } else {
      echo "Some thing gone wrong ->" . mysqli_error($conn);
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">
  <title>Note Keeper</title>
</head>

<body>


  <!-- Modal -->

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rounded-0">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit your note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="/PHPNoteKeepr/Notekeeper.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="titleEdit">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit">
            </div>

            <div class="form-group">
              <label for="descEdit">Your Note's Description</label>
              <textarea class="form-control" id="descEdit" name="descEdit" rows="3"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- NavBAr -->
  <!-- <nav class="navbar navbar-dark bg-dark">
    <span class="navbar-brand mb-0 h1">Note Keeping Application</span>
  </nav> -->

  <?php
  if ($insert) {
    echo "<div id='selectAlert'><div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been inserted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div></div>";
  }
  ?>

  <?php
  if ($update) {
    echo "<div id='selectAlert1'><div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been updated successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div></div>";
  }
  ?>

  <?php
  if ($delete) {
    echo "<div id='selectAlert2'><div class='alert alert-danger alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been deleted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div></div>";
  }
  ?>


  <!-- Form starts form here -->
  <div class="container col-sm-4 p-2 m-5 mx-auto bg-white shadow">
    <div class="container-sm my-3">
      <h3>Add your Note!!!</h3>
      <!-- E:\Installed Softwares\xampp\htdocs\PHPNoteKeepr\Notekeeper.php -->
      <form action="/PHPNoteKeepr/Notekeeper.php" method="POST">
        <div class="form-group">
          <label for="title">Note Title</label>
          <input type="text" class="form-control rounded-0" id="title" name="title">
        </div>

        <div class="form-group">
          <label for="desc">Your Note's Description</label>
          <textarea class="form-control rounded-0" id="desc" name="desc" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary rounded-0 btn-block">Add Note</button>
      </form>
    </div>
</div>
    <!-- Table start from here -->
    <div class="container col-sm-10 p-2 m-5 mx-auto">
    <div class="container my-5">
      <table class="table" id="myTable">
        <thead>
          <tr>
            <th scope="col">Sno.</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Displaying Notes from the Database
          $sql = "SELECT * FROM `notes`";
          $result = mysqli_query($conn, $sql);
          $sno = 0;
          while ($row = mysqli_fetch_assoc($result)) {

            // echo $row['sno'] . " " . $row['title'] . " " . $row['description'] . " " . $row['time'] . "<br>";
            $sno = $sno + 1;
            echo "<tr>
                    <th scope='row'>" . $sno . "</th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td id='forbtn'><button class='btn btn-success rounded-0 edit' id = " . $row['sno'] . ">Edit</button> <button id = d" . $row['sno'] . " class='btn btn-danger rounded-0 delete'>Delete</button></td>
                  </tr>";
          }
          ?>

        </tbody>
      </table>
      <hr>
    </div>

  </div>



  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>

  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener('click', (e) => {
        console.log("Edit", e.target.parentNode.parentNode);
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName('td')[0].innerText;
        description = tr.getElementsByTagName('td')[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id);
        $("#editModal").modal("toggle");
      });
    });

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `/PHPNoteKeepr/Notekeeper.php?delete=${sno}`;
          // TODO: Create a form and use post request to submit a form
        } else {
          console.log("no");
        }
      });
    });

    const alert = document.getElementById('selectAlert');
    setTimeout(() => {
      alert.innerHTML = "";
    }, 3000);

    const alert1 = document.getElementById('selectAlert1');
    setTimeout(() => {
      alert1.innerHTML = "";
    }, 3000);

    const alert2 = document.getElementById('selectAlert2');
    setTimeout(() => {
      alert2.innerHTML = "";
    }, 3000);
  </script>
</body>

</html>